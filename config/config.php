<?php
/**
 * SMM Panel - Global System Config
 */

if (session_status() === PHP_SESSION_NONE) {
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

// Auto initialize demo session if unauthenticated for preview convenience
if (!isset($_SESSION['user_id']) && !isset($_SESSION['admin_id'])) {
    // Default demo session aligned with reference images: Aaris Ali #1024
    $_SESSION['demo_user'] = [
        'id' => 1,
        'user_id_code' => '#1024',
        'name' => 'Aaris Ali',
        'email' => 'aarisali@gmail.com',
        'phone' => '+91 98765 43210',
        'balance' => 850.50,
        'currency' => 'INR',
        'email_verified' => true,
        'role' => 'user'
    ];
}
