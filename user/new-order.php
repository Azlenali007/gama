<?php
/**
 * SMM Panel - User Module: New Order Page
 */
require_once __DIR__ . '/../includes/user-auth.php';
$user = Auth::user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>New Order - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/user.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">
    <div class="page-back-header">
      <a href="/user/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>New Order</span>
      </a>
      <div class="balance-pill-card">
        <div class="balance-label">Balance:</div>
        <div class="balance-value live-user-balance"><?= formatCurrency((float)($user['balance'] ?? 0.00)) ?></div>
      </div>
    </div>

    <!-- Step Indicator (Image 3) -->
    <div class="order-step-indicator">
      <div class="step-item active">
        <span class="step-number-circle">1</span>
        <span>Select Service</span>
      </div>
      <div class="step-item active">
        <span class="step-number-circle">2</span>
        <span>Contact Info</span>
      </div>
      <div class="step-item">
        <span class="step-number-circle">3</span>
        <span>Payment</span>
      </div>
    </div>

    <div class="order-layout-grid">
      <!-- Category Sidebar -->
      <div class="category-sidebar-list">
        <button class="category-tab-btn active" data-category="instagram"><i data-lucide="instagram"></i> Instagram</button>
        <button class="category-tab-btn" data-category="youtube"><i data-lucide="youtube"></i> YouTube</button>
        <button class="category-tab-btn" data-category="telegram"><i data-lucide="send"></i> Telegram</button>
        <button class="category-tab-btn" data-category="facebook"><i data-lucide="facebook"></i> Facebook</button>
        <button class="category-tab-btn" data-category="tiktok"><i data-lucide="video"></i> TikTok</button>
        <button class="category-tab-btn" data-category="twitter"><i data-lucide="twitter"></i> Twitter (X)</button>
      </div>

      <!-- Service Selection & Order Form -->
      <div>
        <div class="service-list-header">
          <div class="brand-icon-box" style="width: 36px; height: 36px; border-radius: 10px;">
            <i data-lucide="instagram"></i>
          </div>
          <div>
            <h3 id="current-category-title">Instagram</h3>
            <p>Choose a service from the list below</p>
          </div>
        </div>

        <div class="service-card-item selected" data-category="instagram" data-rate="35" data-name="Instagram Followers" data-min="1000" data-max="1010000" data-badges="High quality followers | Instant Start | No Drop">
          <div style="display: flex; align-items: center; gap: 12px;">
            <i data-lucide="instagram" style="color: #ec4899;"></i>
            <span class="service-item-title">Instagram Followers</span>
          </div>
          <span class="service-item-rate"><?= htmlspecialchars(getCurrencySymbol()) ?>35 / 1K</span>
        </div>

        <div class="service-card-item" data-category="instagram" data-rate="20" data-name="Instagram Likes" data-min="100" data-max="500000" data-badges="High Quality | Instant Start | Non-Drop">
          <div style="display: flex; align-items: center; gap: 12px;">
            <i data-lucide="heart" style="color: #ec4899;"></i>
            <span class="service-item-title">Instagram Likes</span>
          </div>
          <span class="service-item-rate"><?= htmlspecialchars(getCurrencySymbol()) ?>20 / 1K</span>
        </div>

        <div class="service-card-item" data-category="instagram" data-rate="15" data-name="Instagram Views" data-min="500" data-max="2000000" data-badges="Instant Start | High Retention | 100% Safe">
          <div style="display: flex; align-items: center; gap: 12px;">
            <i data-lucide="play-circle" style="color: #ec4899;"></i>
            <span class="service-item-title">Instagram Views</span>
          </div>
          <span class="service-item-rate"><?= htmlspecialchars(getCurrencySymbol()) ?>15 / 1K</span>
        </div>

        <div class="service-card-item" data-category="instagram" data-rate="50" data-name="Instagram Comments" data-min="10" data-max="50000" data-badges="Custom Comments | Verified Look | HQ">
          <div style="display: flex; align-items: center; gap: 12px;">
            <i data-lucide="message-circle" style="color: #ec4899;"></i>
            <span class="service-item-title">Instagram Comments</span>
          </div>
          <span class="service-item-rate"><?= htmlspecialchars(getCurrencySymbol()) ?>50 / 1K</span>
        </div>

        <div class="service-details-card" style="margin-top: 24px;">
          <div class="service-details-top">
            <div>
              <h4 id="selected-service-name" style="font-size: 18px; font-weight: 800;">Instagram Followers</h4>
              <div class="badges-wrap">
                <span id="selected-service-badges" class="badge-feature">High quality followers | Instant Start | No Drop</span>
              </div>
            </div>
            <div style="text-align: right;">
              <div id="selected-service-rate" style="font-size: 20px; font-weight: 900; color: #2563eb;"><?= htmlspecialchars(getCurrencySymbol()) ?>35 / 1K</div>
              <span class="badge-speed" style="display: inline-block; margin-top: 4px;">Fast Delivery</span>
            </div>
          </div>

          <div style="margin-top: 20px;">
            <label class="form-field-label">Quantity</label>
            <div class="quantity-stepper-box">
              <button type="button" class="step-calc-btn" id="qty-minus-btn">&minus;</button>
              <input type="number" id="order-quantity-input" class="quantity-input" value="1000" min="1000" max="1010000" step="100">
              <button type="button" class="step-calc-btn" id="qty-plus-btn">&plus;</button>
            </div>
            <div id="selected-service-minmax" style="font-size: 12px; color: var(--text-light); margin-top: 6px; font-weight: 600;">
              Min: 1000 | Max: 1010000
            </div>
          </div>

          <div style="margin-top: 18px;">
            <label class="form-field-label">Instagram Link / Profile URL</label>
            <input type="url" id="order-link-input" placeholder="https://instagram.com/yourusername" style="width: 100%;">
          </div>

          <div class="total-price-bar">
            <span class="total-price-label">Total Price</span>
            <span class="total-price-val" id="calculated-order-price"><?= htmlspecialchars(getCurrencySymbol()) ?>35.00</span>
          </div>

          <button type="button" class="btn-primary" id="btn-place-order" onclick="placeNewOrder()">
            Place Order &rarr;
          </button>
        </div>
      </div>
    </div>
  </div>

  <script>
    window.APP_CURRENCY_SYMBOL = <?= json_encode(getCurrencySymbol()) ?>;
    window.APP_CURRENCY_CODE = <?= json_encode(getCurrencyCode()) ?>;
    window.APP_CURRENCY_NAME = <?= json_encode(getCurrencyName()) ?>;
  </script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="/assets/js/user.js"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
