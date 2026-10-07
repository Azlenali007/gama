<?php
/**
 * Reusable Admin Header Layout
 */
require_once __DIR__ . '/admin-auth.php';
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/csrf.php';

$currentAdmin = Auth::admin();
$adminPageTitle = $adminPageTitle ?? 'Admin Portal';
$activeAdminNav = $activeAdminNav ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
  <title><?= htmlspecialchars($adminPageTitle) ?> - <?= htmlspecialchars(getSetting('site_name', APP_NAME)) ?> Admin</title>
  
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body style="background-color: #f8fafc; min-height: 100vh; overflow-x: hidden;">
