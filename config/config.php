<?php
/**
 * SMM Panel - Global System Configuration & Security Enforcement
 */

define('INSTALL_LOCK_FILE', __DIR__ . '/../install/installed.lock');
define('IS_INSTALLED', file_exists(INSTALL_LOCK_FILE));

// Secure Session Configuration
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_samesite', 'Lax');
    session_start();
}

require_once __DIR__ . '/constants.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';

// Security Headers
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
header("X-XSS-Protection: 1; mode=block");

// Set default timezone
date_default_timezone_set('Asia/Kolkata');

// Verify installation lock
$currentScript = $_SERVER['PHP_SELF'] ?? '';
$isInstallRoute = str_contains($currentScript, '/install/');

if (!IS_INSTALLED && !$isInstallRoute) {
    header("Location: /install/index.php");
    exit;
}
