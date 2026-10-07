<?php
/**
 * SMM Panel - Admin Module: Users Management
 */
require_once __DIR__ . '/../includes/admin-auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Users - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">
    <div class="page-back-header">
      <a href="/admin/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>User Accounts</span>
      </a>
      <span class="badge badge-info">1,240 Total Registered</span>
    </div>

    <div class="data-table-card">
      <table class="app-table">
        <thead>
          <tr>
            <th>User ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Wallet Balance</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>#1024</strong></td>
            <td>Aaris Ali</td>
            <td>aarisali@gmail.com</td>
            <td>+91 98765 43210</td>
            <td><strong style="color: #2563eb;">₹850.50</strong></td>
            <td><span class="badge badge-success">Active</span></td>
            <td>
              <button class="btn-secondary" onclick="showToast('Adjusting balance for Aaris Ali', 'info')" style="padding: 6px 12px; font-size: 12px;">Edit Balance</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="/assets/js/user.js"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
