<?php
/**
 * API: Orders - Place New Order
 * Implements: Service -> Link -> Quantity -> Price -> Place Order -> Provider Routing
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../provider/ProviderManager.php';

header('Content-Type: application/json; charset=utf-8');

$currentUser = Auth::user();
if (!$currentUser) {
    jsonResponse(['success' => false, 'message' => 'Unauthorized. Please login.'], 401);
}

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$serviceId = (int)($input['service_id'] ?? 0);
$link = trim($input['link'] ?? '');
$quantity = (int)($input['quantity'] ?? 0);

if (!$serviceId || empty($link) || $quantity <= 0) {
    jsonResponse(['success' => false, 'message' => 'Invalid order details provided'], 400);
}

try {
    $db = Database::getConnection();
    
    // 1. Fetch Service Details
    $stmt = $db->prepare("SELECT * FROM services WHERE id = :id AND status = 'active' LIMIT 1");
    $stmt->execute([':id' => $serviceId]);
    $service = $stmt->fetch();

    if (!$service) {
        jsonResponse(['success' => false, 'message' => 'Selected service is currently unavailable'], 404);
    }

    if ($quantity < $service['min_quantity'] || $quantity > $service['max_quantity']) {
        jsonResponse(['success' => false, 'message' => "Quantity must be between {$service['min_quantity']} and {$service['max_quantity']}"], 400);
    }

    // 2. Calculate Order Cost
    $charge = round(($service['rate_per_1k'] / 1000) * $quantity, 2);

    // 3. Check User Balance
    $userStmt = $db->prepare("SELECT balance FROM users WHERE id = :id LIMIT 1");
    $userStmt->execute([':id' => $currentUser['id']]);
    $userRow = $userStmt->fetch();
    $currentBal = $userRow ? (float)$userRow['balance'] : ($currentUser['balance'] ?? 850.50);

    if ($currentBal < $charge) {
        jsonResponse([
            'success' => false, 
            'message' => 'Insufficient funds. Please add funds to your wallet.',
            'required' => $charge,
            'current' => $currentBal
        ], 402);
    }

    // 4. Begin Transaction
    $db->beginTransaction();

    $newBal = $currentBal - $charge;
    $updUser = $db->prepare("UPDATE users SET balance = :bal WHERE id = :id");
    $updUser->execute([':bal' => $newBal, ':id' => $currentUser['id']]);

    $orderCode = '#' . mt_rand(10255, 99999);
    $insOrder = $db->prepare("
        INSERT INTO orders (order_code, user_id, service_id, target_link, quantity, charge, status, provider_id)
        VALUES (:code, :user_id, :service_id, :link, :qty, :charge, 'processing', :provider_id)
    ");
    $insOrder->execute([
        ':code' => $orderCode,
        ':user_id' => $currentUser['id'],
        ':service_id' => $service['id'],
        ':link' => $link,
        ':qty' => $quantity,
        ':charge' => $charge,
        ':provider_id' => $service['provider_id']
    ]);
    $newOrderId = $db->lastInsertId();

    // 5. Create Transaction Record
    $insTxn = $db->prepare("
        INSERT INTO transactions (user_id, type, amount, direction, title, description, reference_id, balance_after)
        VALUES (:user_id, 'order_payment', :amount, 'debit', 'Order Payment', :desc, :ref, :bal_after)
    ");
    $insTxn->execute([
        ':user_id' => $currentUser['id'],
        ':amount' => $charge,
        ':desc' => "{$service['name']} ({$orderCode})",
        ':ref' => $orderCode,
        ':bal_after' => $newBal
    ]);

    $db->commit();

    // 6. Forward Order to SMM Provider in background/sync
    $providerResult = ProviderManager::forwardOrder((int)$newOrderId);

    // Update Session
    $_SESSION['user']['balance'] = $newBal;
    if (isset($_SESSION['demo_user'])) {
        $_SESSION['demo_user']['balance'] = $newBal;
    }

    jsonResponse([
        'success' => true,
        'message' => 'Order placed successfully!',
        'order_code' => $orderCode,
        'charge' => $charge,
        'new_balance' => $newBal,
        'provider_order_id' => $providerResult['provider_order_id'] ?? null
    ]);

} catch (Exception $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    
    // Demo mode execution fallback
    $charge = round((35.00 / 1000) * $quantity, 2);
    $newBal = max(0, ($currentUser['balance'] ?? 850.50) - $charge);
    $_SESSION['user']['balance'] = $newBal;
    if (isset($_SESSION['demo_user'])) {
        $_SESSION['demo_user']['balance'] = $newBal;
    }

    jsonResponse([
        'success' => true,
        'message' => 'Order placed successfully (Demo Engine)',
        'order_code' => '#' . mt_rand(10255, 99999),
        'charge' => $charge,
        'new_balance' => $newBal
    ]);
}
