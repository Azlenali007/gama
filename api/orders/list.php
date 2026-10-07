<?php
/**
 * API: Orders - List User Orders
 */

require_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json; charset=utf-8');

$currentUser = Auth::user();
if (!$currentUser) {
    jsonResponse(['success' => false, 'message' => 'Unauthorized'], 401);
}

$statusFilter = trim($_GET['status'] ?? 'all');

try {
    $db = Database::getConnection();
    $sql = "
        SELECT o.*, s.name as service_name, s.rate_per_1k, c.name as category_name, c.icon as category_icon
        FROM orders o
        JOIN services s ON o.service_id = s.id
        JOIN categories c ON s.category_id = c.id
        WHERE o.user_id = :uid
    ";
    $params = [':uid' => $currentUser['id']];

    if ($statusFilter !== 'all' && !empty($statusFilter)) {
        $sql .= " AND o.status = :status";
        $params[':status'] = $statusFilter;
    }

    $sql .= " ORDER BY o.id DESC LIMIT 100";
    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    $orders = $stmt->fetchAll();

    jsonResponse([
        'success' => true,
        'orders' => $orders
    ]);
} catch (Exception $e) {
    jsonResponse([
        'success' => true,
        'orders' => []
    ]);
}
