<?php
/**
 * SMM Panel - User Module: Notifications Page
 */
require_once __DIR__ . '/../includes/user-auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notifications - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/user.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">
    <div class="page-back-header">
      <a href="/user/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>Notifications</span>
      </a>
    </div>

    <div class="orders-list-wrap">
      <div class="order-card-row">
        <div style="display: flex; gap: 14px; align-items: center;">
          <div class="brand-icon-box" style="width: 42px; height: 42px; background: #e0f2fe; color: #0284c7;">
            <i data-lucide="bell"></i>
          </div>
          <div>
            <div style="font-weight: 800; font-size: 15px;">Order #10254 is in processing</div>
            <div style="font-size: 12px; color: var(--text-muted);">Your Instagram followers are now being delivered by the server node.</div>
          </div>
        </div>
        <span style="font-size: 11px; color: var(--text-light);">2h ago</span>
      </div>

      <div class="order-card-row">
        <div style="display: flex; gap: 14px; align-items: center;">
          <div class="brand-icon-box" style="width: 42px; height: 42px; background: #dcfce7; color: #16a34a;">
            <i data-lucide="check-circle"></i>
          </div>
          <div>
            <div style="font-weight: 800; font-size: 15px;">Wallet Credited: +₹500.00</div>
            <div style="font-size: 12px; color: var(--text-muted);">Your Razorpay payment #PAY_TXN_99182 was verified and credited successfully.</div>
          </div>
        </div>
        <span style="font-size: 11px; color: var(--text-light);">12 May</span>
      </div>
    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
