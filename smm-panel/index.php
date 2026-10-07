<?php
/**
 * SMM Panel - Application Index Entry Point
 */
require_once __DIR__ . '/config/config.php';

// Route directly into user dashboard or login based on session
if (Auth::check()) {
    header("Location: /user/dashboard.php");
    exit;
} else {
    header("Location: /login.php");
    exit;
}
