<?php
/**
 * SMM Panel - User Module: Orders History
 */
require_once __DIR__ . '/../includes/user-auth.php';
$user = Auth::user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Orders - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/user.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">
    <div class="page-back-header">
      <a href="/user/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>My Orders</span>
      </a>
      <a href="/user/new-order.php" class="btn-primary" style="width: auto; padding: 10px 18px;">
        + New Order
      </a>
    </div>

    <!-- Filter tabs (Image 5) -->
    <div class="filter-tabs-row">
      <button class="filter-pill-btn active order-filter-pill" data-filter="all">All</button>
      <button class="filter-pill-btn order-filter-pill" data-filter="processing">Processing</button>
      <button class="filter-pill-btn order-filter-pill" data-filter="completed">Completed</button>
      <button class="filter-pill-btn order-filter-pill" data-filter="cancelled">Cancelled</button>
    </div>

    <!-- Orders List (Matches Image 5) -->
    <div class="orders-list-wrap" id="my-orders-list-container">
      <div class="order-card-row" data-status="processing">
        <div class="order-info-group">
          <div class="order-social-icon"><i data-lucide="instagram"></i></div>
          <div>
            <div class="order-meta-title">Instagram Followers</div>
            <div class="order-meta-sub">1K &bull; ₹35</div>
          </div>
        </div>
        <div style="text-align: right;">
          <span class="badge badge-warning">Processing</span>
          <div class="order-meta-id" style="margin-top: 4px;">#10254</div>
          <div style="font-size: 11px; color: var(--text-light); margin-top: 2px;">12 May 2025, 4:32 PM</div>
        </div>
      </div>

      <div class="order-card-row" data-status="completed">
        <div class="order-info-group">
          <div class="order-social-icon" style="color: #ef4444; background: #fee2e2;"><i data-lucide="youtube"></i></div>
          <div>
            <div class="order-meta-title">YouTube Views</div>
            <div class="order-meta-sub">5K &bull; ₹120</div>
          </div>
        </div>
        <div style="text-align: right;">
          <span class="badge badge-success">Completed</span>
          <div class="order-meta-id" style="margin-top: 4px;">#10253</div>
          <div style="font-size: 11px; color: var(--text-light); margin-top: 2px;">11 May 2025, 6:10 PM</div>
        </div>
      </div>

      <div class="order-card-row" data-status="processing">
        <div class="order-info-group">
          <div class="order-social-icon" style="color: #0284c7; background: #e0f2fe;"><i data-lucide="send"></i></div>
          <div>
            <div class="order-meta-title">Telegram Members</div>
            <div class="order-meta-sub">2K &bull; ₹90</div>
          </div>
        </div>
        <div style="text-align: right;">
          <span class="badge badge-warning">Processing</span>
          <div class="order-meta-id" style="margin-top: 4px;">#10252</div>
          <div style="font-size: 11px; color: var(--text-light); margin-top: 2px;">10 May 2025, 1:45 PM</div>
        </div>
      </div>

      <div class="order-card-row" data-status="completed">
        <div class="order-info-group">
          <div class="order-social-icon"><i data-lucide="instagram"></i></div>
          <div>
            <div class="order-meta-title">Instagram Likes</div>
            <div class="order-meta-sub">1K &bull; ₹20</div>
          </div>
        </div>
        <div style="text-align: right;">
          <span class="badge badge-success">Completed</span>
          <div class="order-meta-id" style="margin-top: 4px;">#10251</div>
          <div style="font-size: 11px; color: var(--text-light); margin-top: 2px;">9 May 2025, 7:20 PM</div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="/assets/js/user.js"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
