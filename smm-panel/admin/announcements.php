<?php
/**
 * SMM Panel - Admin Module: Announcements
 */
require_once __DIR__ . '/../includes/admin-auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Announcements - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">
    <div class="page-back-header">
      <a href="/admin/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>Announcements & News</span>
      </a>
      <button class="btn-primary" style="width: auto; padding: 10px 18px;" onclick="showToast('Create announcement modal', 'info')">
        + Post Announcement
      </button>
    </div>

    <div class="data-table-card">
      <table class="app-table">
        <thead>
          <tr>
            <th>Title</th>
            <th>Type</th>
            <th>Content Snippet</th>
            <th>Active</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><strong>Instagram Algorithm Update 2025</strong></td>
            <td><span class="badge badge-info">Promo</span></td>
            <td>All Instagram services upgraded with high-retention AI nodes...</td>
            <td><span class="badge badge-success">Live</span></td>
            <td>
              <button class="btn-secondary" onclick="showToast('Editing announcement', 'info')" style="padding: 6px 12px; font-size: 12px;">Edit</button>
            </td>
          </tr>
          <tr>
            <td><strong>Razorpay Instant UPI Activated</strong></td>
            <td><span class="badge badge-success">Success</span></td>
            <td>Enjoy 0% gateway transaction fees on all UPI wallet recharges...</td>
            <td><span class="badge badge-success">Live</span></td>
            <td>
              <button class="btn-secondary" onclick="showToast('Editing announcement', 'info')" style="padding: 6px 12px; font-size: 12px;">Edit</button>
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
