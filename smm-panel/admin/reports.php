<?php
/**
 * SMM Panel - Admin Module: Reports & Financial Analytics
 */
require_once __DIR__ . '/../includes/admin-auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Analytics & Reports - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">
    <div class="page-back-header">
      <a href="/admin/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>Financial Reports & Analytics</span>
      </a>
    </div>

    <div class="admin-stats-grid">
      <div class="admin-stat-card">
        <div>
          <div class="stat-label-small">TOTAL DEPOSITS</div>
          <div class="stat-val-large">₹2,48,900</div>
        </div>
        <div class="brand-icon-box" style="background: #ecfdf5; color: #10b981;">
          <i data-lucide="arrow-down-left"></i>
        </div>
      </div>

      <div class="admin-stat-card">
        <div>
          <div class="stat-label-small">PROVIDER EXPENSES</div>
          <div class="stat-val-large">$1,180.20</div>
        </div>
        <div class="brand-icon-box" style="background: #fef2f2; color: #ef4444;">
          <i data-lucide="arrow-up-right"></i>
        </div>
      </div>

      <div class="admin-stat-card">
        <div>
          <div class="stat-label-small">ESTIMATED NET MARGIN</div>
          <div class="stat-val-large" style="color: #10b981;">+ 62.4%</div>
        </div>
        <div class="brand-icon-box" style="background: #eff6ff; color: #2563eb;">
          <i data-lucide="trending-up"></i>
        </div>
      </div>

      <div class="admin-stat-card">
        <div>
          <div class="stat-label-small">ORDER COMPLETION RATE</div>
          <div class="stat-val-large">99.2%</div>
        </div>
        <div class="brand-icon-box" style="background: #f5f3ff; color: #8b5cf6;">
          <i data-lucide="check-check"></i>
        </div>
      </div>
    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
