<?php
/**
 * API: Authentication - Logout
 */

require_once __DIR__ . '/../../config/config.php';

Auth::logout();
session_destroy();

if (isset($_GET['format']) && $_GET['format'] === 'json') {
    jsonResponse(['success' => true, 'redirect' => '/login.php']);
}

header("Location: /login.php");
exit;
