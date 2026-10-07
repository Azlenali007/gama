<?php
/**
 * SMM Panel - User Auth Protection Middleware
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/auth.php';

if (!Auth::check()) {
    header("Location: /login.php");
    exit;
}

$currentUser = Auth::user();
