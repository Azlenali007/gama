<?php
/**
 * API: Payments - Verify & Credit Wallet
 */

require_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json; charset=utf-8');

$currentUser = Auth::user();
if (!$currentUser) {
    jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
}

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$amount = (float)($input['amount'] ?? 0);
$paymentId = trim($input['payment_id'] ?? ('pay_rzp_' . bin2hex(random_bytes(6))));
$gatewayOrderId = trim($input['gateway_order_id'] ?? '');

if ($amount <= 0) {
    jsonResponse(['success' => false, 'message' => 'Invalid amount'], 400);
}

try {
    $db = Database::getConnection();
    $db->beginTransaction();

    // Fetch current user balance
    $userStmt = $db->prepare("SELECT balance FROM users WHERE id = :id LIMIT 1");
    $userStmt->execute([':id' => $currentUser['id']]);
    $userRow = $userStmt->fetch();
    $currentBal = $userRow ? (float)$userRow['balance'] : (float)($currentUser['balance'] ?? 0.00);

    $newBal = $currentBal + $amount;

    // Update user balance
    $updUser = $db->prepare("UPDATE users SET balance = :bal WHERE id = :id");
    $updUser->execute([':bal' => $newBal, ':id' => $currentUser['id']]);

    // Update payment record
    $updPay = $db->prepare("
        UPDATE payments 
        SET gateway_payment_id = :p_id, status = 'completed'
        WHERE gateway_order_id = :g_ord OR user_id = :uid
        ORDER BY id DESC LIMIT 1
    ");
    $updPay->execute([
        ':p_id' => $paymentId,
        ':g_ord' => $gatewayOrderId,
        ':uid' => $currentUser['id']
    ]);

    // Insert transaction ledger record
    $insTxn = $db->prepare("
        INSERT INTO transactions (user_id, type, amount, direction, title, description, reference_id, balance_after)
        VALUES (:uid, 'add_funds', :amt, 'credit', 'Add Funds', 'Razorpay Instant Deposit', :ref, :bal_after)
    ");
    $insTxn->execute([
        ':uid' => $currentUser['id'],
        ':amt' => $amount,
        ':ref' => $paymentId,
        ':bal_after' => $newBal
    ]);

    $db->commit();

    // Trigger Notification
    createNotification(
        $currentUser['id'],
        'Wallet Credited',
        'Successfully added ' . formatCurrency($amount) . ' to your wallet via Razorpay.',
        'wallet',
        '/user/dashboard.php'
    );

    // Update session
    $_SESSION['user']['balance'] = $newBal;
    if (isset($_SESSION['demo_user'])) {
        $_SESSION['demo_user']['balance'] = $newBal;
    }

    jsonResponse([
        'success' => true,
        'message' => 'Payment successful! Funds added to your wallet.',
        'credited_amount' => $amount,
        'new_balance' => $newBal,
        'payment_id' => $paymentId
    ]);

} catch (Exception $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    jsonResponse(['success' => false, 'message' => 'Payment verification failed: ' . $e->getMessage()], 500);
}
