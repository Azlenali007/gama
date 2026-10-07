<?php
/**
 * API: Authentication - Register
 */

require_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$name = trim($input['name'] ?? '');
$email = trim($input['email'] ?? '');
$password = trim($input['password'] ?? '');

if (empty($name) || empty($email) || empty($password)) {
    jsonResponse(['success' => false, 'message' => 'All fields are required'], 400);
}

try {
    $db = Database::getConnection();
    $check = $db->prepare("SELECT id FROM users WHERE email = :email");
    $check->execute([':email' => $email]);
    if ($check->fetch()) {
        jsonResponse(['success' => false, 'message' => 'Email is already registered'], 400);
    }

    $userIdCode = '#' . mt_rand(1000, 9999);
    $hash = password_hash($password, PASSWORD_DEFAULT);

    $currencyCode = getSetting('currency_code', 'INR');
    $stmt = $db->prepare("INSERT INTO users (user_id_code, name, email, password_hash, balance, currency, status, email_verified) VALUES (:code, :name, :email, :pwd, 0.00, :curr, 'active', 1)");
    $stmt->execute([
        ':code' => $userIdCode,
        ':name' => $name,
        ':email' => $email,
        ':pwd' => $hash,
        ':curr' => $currencyCode
    ]);

    $newId = (int)$db->lastInsertId();

    createNotification(
        $newId,
        'Welcome to ' . getSetting('site_name', APP_NAME) . '!',
        'Your account has been successfully created. Explore our premium SMM services and add funds to place your first order.',
        'system',
        '/user/dashboard.php'
    );

    $_SESSION['user_id'] = $newId;
    $_SESSION['auth_role'] = 'user';
    $_SESSION['user'] = [
        'id' => $newId,
        'user_id_code' => $userIdCode,
        'name' => $name,
        'email' => $email,
        'balance' => 0.00,
        'currency' => $currencyCode,
        'role' => 'user'
    ];

    jsonResponse([
        'success' => true,
        'message' => 'Account created successfully',
        'user' => $_SESSION['user'],
        'redirect' => '/user/dashboard.php'
    ]);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()], 500);
}
