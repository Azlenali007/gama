<?php
/**
 * SMM Panel - Admin Auth Protection Middleware
 * Strict Enforcement: Blocks unauthenticated access.
 * Normal users can NEVER access admin pages.
 * NO content is rendered before authentication.
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/auth.php';

if (!Auth::checkAdmin()) {
    $redirect = urlencode($_SERVER['REQUEST_URI'] ?? '/admin/dashboard.php');
    header("Location: /admin/index.php?redirect=" . $redirect);
    exit;
}

$currentAdmin = Auth::admin();
if (!$currentAdmin) {
    Auth::adminLogout();
    header("Location: /admin/index.php?error=session_expired");
    exit;
}
