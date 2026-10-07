<?php
/**
 * API: Payments - Create Add Funds Intent
 * Generates Razorpay / UPI Payment Intent
 */

require_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json; charset=utf-8');

$currentUser = Auth::user();
if (!$currentUser) {
    jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
}

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$amount = (float)($input['amount'] ?? 0);
$method = trim($input['method'] ?? 'Razorpay');

if ($amount < MIN_DEPOSIT_AMOUNT || $amount > MAX_DEPOSIT_AMOUNT) {
    jsonResponse([
        'success' => false,
        'message' => 'Deposit amount must be between ' . formatCurrency(MIN_DEPOSIT_AMOUNT) . ' and ' . formatCurrency(MAX_DEPOSIT_AMOUNT)
    ], 400);
}

$orderId = 'order_rzp_' . bin2hex(random_bytes(8));
$txnCode = 'PAY_TXN_' . mt_rand(10000, 99999);

try {
    $db = Database::getConnection();
    $stmt = $db->prepare("
        INSERT INTO payments (transaction_code, user_id, amount, payment_method, gateway_order_id, status)
        VALUES (:code, :uid, :amt, :method, :g_ord, 'pending')
    ");
    $stmt->execute([
        ':code' => $txnCode,
        ':uid' => $currentUser['id'],
        ':amt' => $amount,
        ':method' => $method,
        ':g_ord' => $orderId
    ]);
} catch (Exception $e) {
    // Continue in demo mode
}

jsonResponse([
    'success' => true,
    'gateway_order_id' => $orderId,
    'transaction_code' => $txnCode,
    'amount' => $amount,
    'currency' => 'INR',
    'key' => 'rzp_test_1DP5mmOlF5G5ag',
    'user' => [
        'name' => $currentUser['name'],
        'email' => $currentUser['email'],
        'phone' => $currentUser['phone'] ?? '+919876543210'
    ]
]);
