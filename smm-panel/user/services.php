<?php
/**
 * SMM Panel - User Module: All Services
 */
require_once __DIR__ . '/../includes/user-auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Services - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/user.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">
    <div class="page-back-header">
      <a href="/user/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>All Services</span>
      </a>
    </div>

    <!-- Search Bar -->
    <div class="services-search-bar">
      <i data-lucide="search" class="services-search-icon"></i>
      <input type="text" class="services-search-input" placeholder="Search services...">
    </div>

    <!-- Services Grid (Image 6) -->
    <div class="services-grid-cards">
      <a href="/user/new-order.php" class="service-box-card">
        <div class="service-box-icon" style="background: #fdf2f8; color: #db2777;">
          <i data-lucide="instagram"></i>
        </div>
        <div class="service-box-name">Followers</div>
        <div class="service-box-price">₹35 <span style="font-size: 13px; color: var(--text-muted); font-weight: 500;">/ 1K</span></div>
      </a>

      <a href="/user/new-order.php" class="service-box-card">
        <div class="service-box-icon" style="background: #fff1f2; color: #e11d48;">
          <i data-lucide="heart"></i>
        </div>
        <div class="service-box-name">Likes</div>
        <div class="service-box-price">₹20 <span style="font-size: 13px; color: var(--text-muted); font-weight: 500;">/ 1K</span></div>
      </a>

      <a href="/user/new-order.php" class="service-box-card">
        <div class="service-box-icon" style="background: #f5f3ff; color: #7c3aed;">
          <i data-lucide="play-circle"></i>
        </div>
        <div class="service-box-name">Views</div>
        <div class="service-box-price">₹15 <span style="font-size: 13px; color: var(--text-muted); font-weight: 500;">/ 1K</span></div>
      </a>

      <a href="/user/new-order.php" class="service-box-card">
        <div class="service-box-icon" style="background: #fffbeb; color: #d97706;">
          <i data-lucide="message-square"></i>
        </div>
        <div class="service-box-name">Comments</div>
        <div class="service-box-price">₹50 <span style="font-size: 13px; color: var(--text-muted); font-weight: 500;">/ 1K</span></div>
      </a>
    </div>

    <!-- Promo Quality Banner (Image 6) -->
    <a href="/user/new-order.php" class="promo-quality-card">
      <div class="promo-info-group">
        <div class="promo-icon-badge">
          <i data-lucide="shield-check"></i>
        </div>
        <div>
          <div class="promo-title">Premium Quality Services</div>
          <div class="promo-subtitle">Boost your social media presence today!</div>
        </div>
      </div>
      <i data-lucide="chevron-right"></i>
    </a>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
