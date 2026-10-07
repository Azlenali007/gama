<?php
/**
 * API: Authentication - Login
 */

require_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(['success' => false, 'message' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
$email = trim($input['email'] ?? '');
$password = trim($input['password'] ?? '');

if (empty($email) || empty($password)) {
    jsonResponse(['success' => false, 'message' => 'Email and password are required'], 400);
}

try {
    $db = Database::getConnection();
    $stmt = $db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
    $stmt->execute([':email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        if ($user['status'] !== 'active') {
            jsonResponse(['success' => false, 'message' => 'Your account is suspended or inactive'], 403);
        }

        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['auth_role'] = 'user';
        $_SESSION['user'] = [
            'id' => (int)$user['id'],
            'user_id_code' => $user['user_id_code'],
            'name' => $user['name'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'balance' => (float)$user['balance'],
            'currency' => $user['currency'],
            'role' => 'user'
        ];
        jsonResponse([
            'success' => true,
            'message' => 'Logged in successfully',
            'user' => $_SESSION['user'],
            'redirect' => '/user/dashboard.php'
        ]);
    }

    jsonResponse(['success' => false, 'message' => 'Invalid email or password'], 401);
} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => 'Login error: ' . $e->getMessage()], 500);
}
