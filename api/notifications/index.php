<?php
/**
 * API: Notifications - List, Count, Mark Read
 * Strictly scoped to authenticated user session
 */

require_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json; charset=utf-8');

$currentUser = Auth::user();
if (!$currentUser) {
    jsonResponse(['success' => false, 'message' => 'Unauthorized. Please login.'], 401);
}

$userId = (int)$currentUser['id'];
$action = $_GET['action'] ?? ($_POST['action'] ?? 'list');

if ($action === 'list') {
    $notifications = getUserNotifications($userId, 50);
    $unreadCount = getUnreadNotificationCount($userId);
    jsonResponse([
        'success' => true,
        'notifications' => $notifications,
        'unread_count' => $unreadCount
    ]);
}

if ($action === 'read') {
    $notifId = (int)($_POST['id'] ?? ($_GET['id'] ?? 0));
    if ($notifId <= 0) {
        jsonResponse(['success' => false, 'message' => 'Notification ID required'], 400);
    }
    $done = markNotificationAsRead($notifId, $userId);
    $unreadCount = getUnreadNotificationCount($userId);
    jsonResponse([
        'success' => $done,
        'message' => $done ? 'Marked as read' : 'Notification not found',
        'unread_count' => $unreadCount
    ]);
}

if ($action === 'read_all') {
    $done = markAllNotificationsAsRead($userId);
    jsonResponse([
        'success' => true,
        'message' => 'All notifications marked as read',
        'unread_count' => 0
    ]);
}

jsonResponse(['success' => false, 'message' => 'Invalid action'], 400);
