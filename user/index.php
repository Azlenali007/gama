<?php
/**
 * SMM Panel - User Root Router
 * Protected: Automatically enforces user authentication
 */
require_once __DIR__ . '/../includes/user-auth.php';

header("Location: /user/dashboard.php");
exit;
