<?php
/**
 * SMM Panel - User Auth Protection Middleware
 * Strict Enforcement: Redirects directly to login if not authenticated.
 * NO content is ever output before authentication.
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/auth.php';

if (!Auth::checkUser()) {
    $redirect = urlencode($_SERVER['REQUEST_URI'] ?? '/user/dashboard.php');
    header("Location: /login.php?type=user&redirect=" . $redirect);
    exit;
}

$currentUser = Auth::user();
if (!$currentUser) {
    Auth::logout();
    header("Location: /login.php?type=user&error=session_expired");
    exit;
}
