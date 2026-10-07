<?php
/**
 * SMM Panel - Application Index Entry Point
 */

// If not yet installed, route directly to the Web Installer
if (!file_exists(__DIR__ . '/install/installed.lock')) {
    header("Location: /install/index.php");
    exit;
}

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth.php';

// Route directly into user dashboard or login based on session
if (Auth::checkUser()) {
    header("Location: /user/dashboard.php");
    exit;
} else {
    header("Location: /login.php");
    exit;
}
