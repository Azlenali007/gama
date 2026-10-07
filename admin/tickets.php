<?php
/**
 * SMM Panel - Admin Module: Support Desk & Tickets
 */
require_once __DIR__ . '/../includes/admin-auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Support Tickets - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">
    <div class="page-back-header">
      <a href="/admin/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>Support Tickets</span>
      </a>
      <span class="badge badge-warning">4 Open Tickets</span>
    </div>

    <div class="data-table-card">
      <table class="app-table">
        <thead>
          <tr>
            <th>Ticket Code</th>
            <th>User</th>
            <th>Subject</th>
            <th>Department</th>
            <th>Status</th>
            <th>Created</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>#T1024</strong></td>
            <td>Aaris Ali (#1024)</td>
            <td>Order not started yet</td>
            <td>Orders</td>
            <td><span class="badge badge-warning">Open</span></td>
            <td>12 May 2025, 11:20 AM</td>
            <td>
              <button class="btn-secondary" onclick="showToast('Replying to #T1024', 'info')" style="padding: 6px 12px; font-size: 12px;">Reply</button>
            </td>
          </tr>
          <tr>
            <td><strong>#T1023</strong></td>
            <td>Aaris Ali (#1024)</td>
            <td>Payment issue</td>
            <td>Payment</td>
            <td><span class="badge badge-info">In Progress</span></td>
            <td>10 May 2025, 6:15 PM</td>
            <td>
              <button class="btn-secondary" onclick="showToast('Replying to #T1023', 'info')" style="padding: 6px 12px; font-size: 12px;">Reply</button>
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
