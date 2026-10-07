<?php
/**
 * SMM Panel - User Module: Transactions History
 */
require_once __DIR__ . '/../includes/user-auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transactions - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/user.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">
    <div class="page-back-header">
      <a href="/user/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>Transactions</span>
      </a>
    </div>

    <!-- Filter Tabs (Image 7) -->
    <div class="filter-tabs-row">
      <button class="filter-pill-btn active txn-filter-pill" data-filter="all">All</button>
      <button class="filter-pill-btn txn-filter-pill" data-filter="add_funds">Add Funds</button>
      <button class="filter-pill-btn txn-filter-pill" data-filter="order_payment">Orders</button>
      <button class="filter-pill-btn txn-filter-pill" data-filter="refund">Refunds</button>
    </div>

    <!-- Transactions List (Matches Image 7) -->
    <div class="transactions-list-wrap">
      <div class="txn-card-item" data-type="add_funds">
        <div style="display: flex; align-items: center; gap: 14px;">
          <div class="brand-icon-box" style="width: 40px; height: 40px; background: #e0f2fe; color: #0284c7;">
            <i data-lucide="zap"></i>
          </div>
          <div>
            <div style="font-weight: 800; font-size: 15px;">Add Funds</div>
            <div style="font-size: 12px; color: var(--text-muted);">Razorpay &bull; 12 May 2025, 4:12 PM</div>
          </div>
        </div>
        <div class="txn-amount-credit">+ ₹500</div>
      </div>

      <div class="txn-card-item" data-type="order_payment">
        <div style="display: flex; align-items: center; gap: 14px;">
          <div class="brand-icon-box" style="width: 40px; height: 40px; background: #fdf2f8; color: #db2777;">
            <i data-lucide="instagram"></i>
          </div>
          <div>
            <div style="font-weight: 800; font-size: 15px;">Order Payment</div>
            <div style="font-size: 12px; color: var(--text-muted);">Instagram Followers &bull; 12 May 2025, 4:32 PM</div>
          </div>
        </div>
        <div class="txn-amount-debit">- ₹35</div>
      </div>

      <div class="txn-card-item" data-type="add_funds">
        <div style="display: flex; align-items: center; gap: 14px;">
          <div class="brand-icon-box" style="width: 40px; height: 40px; background: #e0f2fe; color: #0284c7;">
            <i data-lucide="zap"></i>
          </div>
          <div>
            <div style="font-weight: 800; font-size: 15px;">Add Funds</div>
            <div style="font-size: 12px; color: var(--text-muted);">Razorpay &bull; 10 May 2025, 11:20 AM</div>
          </div>
        </div>
        <div class="txn-amount-credit">+ ₹200</div>
      </div>

      <div class="txn-card-item" data-type="order_payment">
        <div style="display: flex; align-items: center; gap: 14px;">
          <div class="brand-icon-box" style="width: 40px; height: 40px; background: #fee2e2; color: #dc2626;">
            <i data-lucide="youtube"></i>
          </div>
          <div>
            <div style="font-weight: 800; font-size: 15px;">Order Payment</div>
            <div style="font-size: 12px; color: var(--text-muted);">YouTube Views &bull; 10 May 2025, 6:15 PM</div>
          </div>
        </div>
        <div class="txn-amount-debit">- ₹120</div>
      </div>
    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="/assets/js/user.js"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
