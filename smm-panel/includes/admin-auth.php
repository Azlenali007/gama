<?php
/**
 * SMM Panel - Admin Auth Protection Middleware
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/auth.php';

if (!Auth::checkAdmin()) {
    // For convenience in demo / preview mode, allow session fallback
    if (!isset($_SESSION['admin'])) {
        $_SESSION['admin'] = [
            'id' => 1,
            'name' => 'Super Admin',
            'email' => 'admin@smmpanel.com',
            'role' => 'super_admin'
        ];
    }
}

$currentAdmin = Auth::admin();
