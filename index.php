<?php
/**
 * SMM Panel - Application Index & Public Landing Page
 * Matches Reference Image Exactly
 */

// If not yet installed, route directly to the Web Installer
if (!file_exists(__DIR__ . '/install/installed.lock')) {
    header("Location: /install/index.php");
    exit;
}

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

// Route directly into user dashboard if user is authenticated
if (Auth::checkUser()) {
    header("Location: /user/dashboard.php");
    exit;
}

// Fetch categories and services dynamically from database
$categories = [];
$services = [];
$currencySymbol = getCurrencySymbol();
$currencyCode = getSetting('currency_code', 'INR');

try {
    $db = Database::getConnection();
    $catStmt = $db->query("SELECT * FROM categories WHERE status = 'active' ORDER BY sort_order ASC");
    if ($catStmt) $categories = $catStmt->fetchAll(PDO::FETCH_ASSOC);

    $servStmt = $db->query("
        SELECT s.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon 
        FROM services s 
        JOIN categories c ON s.category_id = c.id 
        WHERE s.status = 'active' 
        ORDER BY c.sort_order ASC, s.id ASC
    ");
    if ($servStmt) $services = $servStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Database connection fallback
}

// Fallback if database table is not yet seeded or empty
if (empty($services)) {
    $services = [
        ['id' => 2, 'category_id' => 2, 'category_name' => 'YouTube', 'category_slug' => 'youtube', 'name' => 'YouTube Views', 'rate_per_1k' => 12.00, 'min_quantity' => 1000, 'max_quantity' => 1000000, 'badges' => 'Real Views • High Retention', 'speed_tag' => 'Fast Delivery'],
        ['id' => 1, 'category_id' => 1, 'category_name' => 'Instagram', 'category_slug' => 'instagram', 'name' => 'Instagram Followers', 'rate_per_1k' => 35.00, 'min_quantity' => 1000, 'max_quantity' => 1010000, 'badges' => 'Real & Active Followers • High Quality • Fast Delivery', 'speed_tag' => 'Starts in 1-2 Hours'],
        ['id' => 3, 'category_id' => 3, 'category_name' => 'Telegram', 'category_slug' => 'telegram', 'name' => 'Telegram Members', 'rate_per_1k' => 45.00, 'min_quantity' => 500, 'max_quantity' => 200000, 'badges' => 'Real & Active Members • Instant Start', 'speed_tag' => 'Fast Delivery'],
        ['id' => 4, 'category_id' => 5, 'category_name' => 'TikTok', 'category_slug' => 'tiktok', 'name' => 'TikTok Likes & Views', 'rate_per_1k' => 25.00, 'min_quantity' => 500, 'max_quantity' => 500000, 'badges' => 'Instant For-You Reach • High Retention', 'speed_tag' => 'Instant Start'],
        ['id' => 5, 'category_id' => 6, 'category_name' => 'Twitter (X)', 'category_slug' => 'twitter-x', 'name' => 'Twitter (X) Retweets', 'rate_per_1k' => 40.00, 'min_quantity' => 100, 'max_quantity' => 100000, 'badges' => 'Worldwide Engagement • Fast Viral Boost', 'speed_tag' => 'Fast Delivery'],
        ['id' => 6, 'category_id' => 1, 'category_name' => 'Instagram', 'category_slug' => 'instagram', 'name' => 'Instagram Likes', 'rate_per_1k' => 20.00, 'min_quantity' => 100, 'max_quantity' => 500000, 'badges' => 'High Quality • Instant Start • Non-Drop', 'speed_tag' => 'Instant Start'],
        ['id' => 7, 'category_id' => 2, 'category_name' => 'YouTube', 'category_slug' => 'youtube', 'name' => 'YouTube Subscribers', 'rate_per_1k' => 180.00, 'min_quantity' => 100, 'max_quantity' => 100000, 'badges' => 'Monetizable • High Retention • Refill', 'speed_tag' => 'Fast Delivery']
    ];
}

// Order services so YouTube is left, Instagram is center (index 1), Telegram is right (index 2) matching reference image
usort($services, function($a, $b) {
    $orderMap = ['youtube' => 1, 'instagram' => 2, 'telegram' => 3, 'tiktok' => 4, 'twitter-x' => 5, 'facebook' => 6];
    $slugA = strtolower($a['category_slug'] ?? '');
    $slugB = strtolower($b['category_slug'] ?? '');
    $valA = $orderMap[$slugA] ?? 99;
    $valB = $orderMap[$slugB] ?? 99;
    return $valA <=> $valB;
});

function getLandingCardTheme($service) {
    $slug = strtolower($service['category_slug'] ?? $service['category_name'] ?? '');
    $name = strtolower($service['name'] ?? '');

    if (strpos($slug, 'youtube') !== false || strpos($name, 'youtube') !== false) {
        return [
            'tag' => 'YouTube',
            'icon' => 'youtube',
            'bg' => 'radial-gradient(circle at 50% 50%, #ff4b4b 0%, #dc2626 100%)',
            'icon_bg' => 'rgba(255,255,255,0.22)',
            'icon_color' => '#ffffff',
            'has_heart' => false,
            'badge_plus' => null
        ];
    } elseif (strpos($slug, 'instagram') !== false || strpos($name, 'instagram') !== false) {
        return [
            'tag' => 'Instagram',
            'icon' => 'instagram',
            'bg' => 'radial-gradient(circle at 30% 30%, #f58529 0%, #dd2a7b 50%, #8134af 100%)',
            'icon_bg' => 'rgba(255,255,255,0.25)',
            'icon_color' => '#ffffff',
            'has_heart' => true,
            'badge_plus' => '+1K'
        ];
    } elseif (strpos($slug, 'telegram') !== false || strpos($name, 'telegram') !== false) {
        return [
            'tag' => 'Telegram',
            'icon' => 'send',
            'bg' => 'radial-gradient(circle at 50% 50%, #38bdf8 0%, #0284c7 100%)',
            'icon_bg' => 'rgba(255,255,255,0.22)',
            'icon_color' => '#ffffff',
            'has_heart' => false,
            'badge_plus' => null
        ];
    } elseif (strpos($slug, 'tiktok') !== false || strpos($name, 'tiktok') !== false) {
        return [
            'tag' => 'TikTok',
            'icon' => 'music',
            'bg' => 'radial-gradient(circle at 50% 50%, #1e293b 0%, #000000 100%)',
            'icon_bg' => 'rgba(255,255,255,0.15)',
            'icon_color' => '#22d3ee',
            'has_heart' => false,
            'badge_plus' => null
        ];
    } elseif (strpos($slug, 'twitter') !== false || strpos($name, 'twitter') !== false) {
        return [
            'tag' => 'Twitter (X)',
            'icon' => 'twitter',
            'bg' => 'radial-gradient(circle at 50% 50%, #334155 0%, #0f172a 100%)',
            'icon_bg' => 'rgba(255,255,255,0.18)',
            'icon_color' => '#ffffff',
            'has_heart' => false,
            'badge_plus' => null
        ];
    } elseif (strpos($slug, 'facebook') !== false || strpos($name, 'facebook') !== false) {
        return [
            'tag' => 'Facebook',
            'icon' => 'facebook',
            'bg' => 'radial-gradient(circle at 50% 50%, #1877f2 0%, #0d5cb6 100%)',
            'icon_bg' => 'rgba(255,255,255,0.22)',
            'icon_color' => '#ffffff',
            'has_heart' => false,
            'badge_plus' => null
        ];
    } else {
        return [
            'tag' => htmlspecialchars($service['category_name'] ?? 'Premium'),
            'icon' => 'zap',
            'bg' => 'radial-gradient(circle at 50% 50%, #2563eb 0%, #1d4ed8 100%)',
            'icon_bg' => 'rgba(255,255,255,0.20)',
            'icon_color' => '#ffffff',
            'has_heart' => false,
            'badge_plus' => null
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
  <title><?= htmlspecialchars(getSetting('site_name', APP_NAME)) ?> - <?= htmlspecialchars(getSetting('site_tagline', APP_TAGLINE)) ?></title>
  <meta name="description" content="Boost Your Social Media With Our SMM Panel. Real followers, likes, views and more for all major platforms.">

  <!-- Google Fonts: Plus Jakarta Sans & Caveat (for exact headline brush cursive) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Swiper.js 3D Slider CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

  <!-- Application CSS -->
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/user.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">

  <style>
    body {
      background: radial-gradient(circle at 50% 0%, #edf5ff 0%, #f8fafc 45%, #ffffff 100%);
      min-height: 100vh;
      overflow-x: hidden;
      font-family: 'Plus Jakarta Sans', -apple-system, sans-serif;
    }
    .landing-card-zoom-target {
      transform-origin: center center;
      transition: transform 0.3s ease;
    }
  </style>
</head>
<body>

  <!-- 1. Top Navbar (Matches Reference Image) -->
  <header>
    <div class="landing-nav-container">
      <!-- Logo -->
      <a href="/" class="landing-logo">
        <div class="landing-logo-icon">
          <i data-lucide="bar-chart-2" style="width: 24px; height: 24px;"></i>
        </div>
        <div>
          <div class="landing-logo-text"><?= htmlspecialchars(getSetting('site_name', APP_NAME)) ?></div>
          <div class="landing-logo-sub"><?= htmlspecialchars(getSetting('site_tagline', APP_TAGLINE)) ?></div>
        </div>
      </a>

      <!-- Center Pill Navigation -->
      <nav class="landing-nav-pills">
        <a href="/" class="landing-nav-link active">Home</a>
        <a href="#services-section" class="landing-nav-link">Services</a>
        <a href="#how-it-works" class="landing-nav-link">How It Works</a>
        <a href="/login.php" class="landing-nav-link">Support</a>
      </nav>

      <!-- Auth Action Buttons -->
      <div class="landing-auth-btns">
        <a href="/login.php" class="landing-btn-login">Login</a>
        <a href="/register.php" class="landing-btn-register">Register</a>
      </div>
    </div>
  </header>

  <!-- 2. Hero Section (Matches Reference Image) -->
  <section class="landing-hero">
    <!-- Top Growth Badge -->
    <div class="landing-growth-badge">
      <i data-lucide="rocket" style="width: 14px; height: 14px;"></i>
      SOCIAL MEDIA GROWTH
    </div>

    <!-- Main Headline & Cursive Sub-headline -->
    <h1 class="landing-headline-main">Boost Your Social Media</h1>
    <span class="landing-headline-cursive">With Our SMM Panel</span>

    <!-- Description -->
    <p class="landing-hero-desc">
      Get real followers, likes, views and more. Fast, secure and affordable services for all major social media platforms.
    </p>

    <!-- 4 Feature Pills -->
    <div class="landing-feature-row">
      <div class="landing-feature-pill">
        <i data-lucide="zap" style="color: #2563eb; width: 16px; height: 16px;"></i>
        <span>Fast Delivery</span>
      </div>
      <div class="landing-feature-pill">
        <i data-lucide="shield-check" style="color: #2563eb; width: 16px; height: 16px;"></i>
        <span>100% Safe</span>
      </div>
      <div class="landing-feature-pill">
        <i data-lucide="star" style="color: #2563eb; width: 16px; height: 16px;"></i>
        <span>High Quality</span>
      </div>
      <div class="landing-feature-pill">
        <i data-lucide="headphones" style="color: #2563eb; width: 16px; height: 16px;"></i>
        <span>24/7 Support</span>
      </div>
    </div>
  </section>

  <!-- 3. 3D Card Carousel Section (Dynamically Powered by Database) -->
  <section class="landing-carousel-section" id="services-section">
    <div class="swiper landing-3d-swiper">
      <div class="swiper-wrapper">

        <?php foreach ($services as $srv): 
          $theme = getLandingCardTheme($srv);
          $srvRate = number_format((float)$srv['rate_per_1k'], 0);
          $srvName = htmlspecialchars($srv['name']);
          $srvBadges = htmlspecialchars($srv['badges'] ?: 'High Quality • Instant Start • Fast Delivery');
          $srvSpeed = htmlspecialchars($srv['speed_tag'] ?: 'Fast Delivery');
        ?>
        <div class="swiper-slide landing-card-slide landing-card-zoom-target" data-url="/register.php?service_id=<?= (int)$srv['id'] ?>">
          <div class="landing-card-banner" style="background: <?= $theme['bg'] ?>;">
            <span class="landing-tag-frosted"><?= $theme['tag'] ?></span>
            
            <div style="position: relative; display: flex; align-items: center; justify-content: center;">
              <div style="width: <?= $theme['has_heart'] ? '100px' : '90px' ?>; height: <?= $theme['has_heart'] ? '100px' : '90px' ?>; border-radius: <?= $theme['has_heart'] ? '30px' : '26px' ?>; background: <?= $theme['icon_bg'] ?>; backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; box-shadow: 0 14px 28px rgba(0,0,0,0.22); border: 2px solid rgba(255,255,255,0.38);">
                <i data-lucide="<?= $theme['icon'] ?>" style="width: <?= $theme['has_heart'] ? '54px' : '48px' ?>; height: <?= $theme['has_heart'] ? '54px' : '48px' ?>; color: <?= $theme['icon_color'] ?>;"></i>
              </div>
              <?php if ($theme['has_heart']): ?>
              <div style="position: absolute; top: -10px; right: -22px; background: #ffffff; color: #e11d48; padding: 6px 10px; border-radius: 9999px; box-shadow: 0 6px 16px rgba(0,0,0,0.15); display: flex; align-items: center; gap: 4px; font-weight: 900; font-size: 11px;">
                <i data-lucide="heart" style="width: 12px; height: 12px; fill: #e11d48;"></i>
              </div>
              <?php endif; ?>
              <?php if (!empty($theme['badge_plus'])): ?>
              <div style="position: absolute; bottom: -12px; right: -15px; background: #6366f1; color: #ffffff; padding: 5px 12px; border-radius: 9999px; font-weight: 900; font-size: 12px; box-shadow: 0 6px 16px rgba(99, 102, 241, 0.4);">
                <?= $theme['badge_plus'] ?>
              </div>
              <?php endif; ?>
            </div>
          </div>
          <div class="landing-card-body">
            <div class="landing-card-title"><?= $srvName ?></div>
            <div class="landing-card-subtitle"><?= $srvBadges ?></div>
            <div class="landing-price-bar">
              <div class="landing-price-val"><?= htmlspecialchars($currencySymbol) ?><?= $srvRate ?> <span>/ 1K</span></div>
              <span class="badge badge-success" style="padding: 6px 12px; font-size: 11px;">
                <i data-lucide="zap" style="width: 12px; height: 12px; margin-right: 4px;"></i> <?= $srvSpeed ?>
              </span>
            </div>
            <a href="/register.php?service_id=<?= (int)$srv['id'] ?>" class="landing-order-btn" onclick="handleCardOrderClick(event, this)">
              <i data-lucide="shopping-cart"></i> Order Now &rarr;
            </a>
          </div>
        </div>
        <?php endforeach; ?>

      </div>

      <!-- Pagination Dots -->
      <div class="swiper-pagination landing-swiper-pagination" style="margin-top: 24px;"></div>
    </div>
  </section>

  <!-- Live Database Service Catalog Table / Directory -->
  <section style="max-width: 1100px; margin: 40px auto; padding: 0 20px;">
    <div style="background: #ffffff; border-radius: 24px; border: 1px solid #e2e8f0; padding: 28px 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.03);">
      <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px; margin-bottom: 24px;">
        <div>
          <div style="display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 11px; font-weight: 800; background: #eff6ff; color: #2563eb; padding: 4px 10px; border-radius: 9999px; text-transform: uppercase;">Database Powered</span>
            <span style="font-size: 12px; color: #16a34a; font-weight: 700;">🟢 Live Service Pricing</span>
          </div>
          <h3 style="font-size: 22px; font-weight: 900; color: #0f172a; margin: 6px 0 0 0;">All Verified SMM Services</h3>
        </div>
        <div>
          <input type="text" id="live-catalog-search" placeholder="Search services..." oninput="filterCatalogTable()" style="padding: 10px 16px; border-radius: 12px; border: 1px solid #cbd5e1; font-size: 13px; width: 220px; outline: none; font-weight: 600;" />
        </div>
      </div>

      <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 13px; text-align: left;">
          <thead>
            <tr style="border-bottom: 2px solid #f1f5f9; color: #64748b; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">
              <th style="padding: 12px 14px;">Platform</th>
              <th style="padding: 12px 14px;">Service Name</th>
              <th style="padding: 12px 14px;">Rate / 1K</th>
              <th style="padding: 12px 14px;">Min / Max</th>
              <th style="padding: 12px 14px;">Delivery Speed</th>
              <th style="padding: 12px 14px; text-align: right;">Action</th>
            </tr>
          </thead>
          <tbody id="live-catalog-tbody">
            <?php foreach ($services as $srv): 
              $theme = getLandingCardTheme($srv);
            ?>
            <tr class="catalog-row" data-name="<?= strtolower(htmlspecialchars($srv['name'])) ?>" data-platform="<?= strtolower(htmlspecialchars($srv['category_name'] ?? '')) ?>" style="border-bottom: 1px solid #f1f5f9;">
              <td style="padding: 14px;">
                <span style="font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 6px;">
                  <i data-lucide="<?= $theme['icon'] ?>" style="width: 16px; height: 16px; color: #2563eb;"></i>
                  <?= htmlspecialchars($srv['category_name'] ?? $theme['tag']) ?>
                </span>
              </td>
              <td style="padding: 14px;">
                <strong style="color: #0f172a; display: block;"><?= htmlspecialchars($srv['name']) ?></strong>
                <span style="font-size: 11px; color: #64748b;"><?= htmlspecialchars($srv['badges'] ?: 'Non-Drop • High Retention') ?></span>
              </td>
              <td style="padding: 14px;">
                <span style="font-weight: 800; color: #2563eb; font-size: 14px;">
                  <?= htmlspecialchars($currencySymbol) ?><?= number_format((float)$srv['rate_per_1k'], 2) ?>
                </span>
              </td>
              <td style="padding: 14px; color: #64748b; font-size: 12px;">
                <?= number_format((int)$srv['min_quantity']) ?> / <?= number_format((int)$srv['max_quantity']) ?>
              </td>
              <td style="padding: 14px;">
                <span class="badge badge-success" style="font-size: 11px;">
                  <i data-lucide="zap" style="width: 11px; height: 11px; margin-right: 3px;"></i>
                  <?= htmlspecialchars($srv['speed_tag'] ?: 'Fast Delivery') ?>
                </span>
              </td>
              <td style="padding: 14px; text-align: right;">
                <a href="/register.php?service_id=<?= (int)$srv['id'] ?>" style="background: #2563eb; color: #ffffff; text-decoration: none; padding: 6px 14px; border-radius: 9999px; font-weight: 800; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                  Order &rarr;
                </a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </section>

  <script>
    function filterCatalogTable() {
      const q = document.getElementById('live-catalog-search').value.toLowerCase().trim();
      document.querySelectorAll('.catalog-row').forEach(row => {
        const text = (row.dataset.name + ' ' + row.dataset.platform).toLowerCase();
        row.style.display = text.includes(q) ? '' : 'none';
      });
    }
  </script>

  <!-- 4. Supported Platforms Section (Matches Reference Image) -->
  <section class="landing-platforms-section" id="how-it-works">
    <div class="landing-growth-badge" style="margin-bottom: 8px;">
      Supported Platforms
    </div>
    <h2 style="font-size: 26px; font-weight: 900; color: #0f172a; letter-spacing: -0.5px;">All Major Social Media Platforms</h2>

    <div class="landing-platforms-grid">
      <!-- 1. Instagram -->
      <a href="/register.php" class="platform-card" style="text-decoration: none;">
        <div class="platform-icon-box" style="background: linear-gradient(135deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);">
          <i data-lucide="instagram"></i>
        </div>
        <div class="platform-name">Instagram</div>
      </a>

      <!-- 2. YouTube -->
      <a href="/register.php" class="platform-card" style="text-decoration: none;">
        <div class="platform-icon-box" style="background: #ff0000;">
          <i data-lucide="youtube"></i>
        </div>
        <div class="platform-name">YouTube</div>
      </a>

      <!-- 3. Telegram -->
      <a href="/register.php" class="platform-card" style="text-decoration: none;">
        <div class="platform-icon-box" style="background: #0088cc;">
          <i data-lucide="send"></i>
        </div>
        <div class="platform-name">Telegram</div>
      </a>

      <!-- 4. Facebook -->
      <a href="/register.php" class="platform-card" style="text-decoration: none;">
        <div class="platform-icon-box" style="background: #1877f2;">
          <i data-lucide="facebook"></i>
        </div>
        <div class="platform-name">Facebook</div>
      </a>

      <!-- 5. TikTok -->
      <a href="/register.php" class="platform-card" style="text-decoration: none;">
        <div class="platform-icon-box" style="background: #000000;">
          <i data-lucide="music"></i>
        </div>
        <div class="platform-name">TikTok</div>
      </a>

      <!-- 6. Twitter (X) -->
      <a href="/register.php" class="platform-card" style="text-decoration: none;">
        <div class="platform-icon-box" style="background: #0f172a;">
          <i data-lucide="twitter"></i>
        </div>
        <div class="platform-name">Twitter (X)</div>
      </a>
    </div>
  </section>

  <!-- 5. Bottom Decorative Accents (Matches Reference Image) -->
  <section class="landing-bottom-strip">
    <div class="bottom-followers-badge">
      <i data-lucide="heart" style="width: 18px; height: 18px; fill: #ffffff;"></i>
      <span>+10K</span>
    </div>

    <div class="bottom-script-text">
      Grow Faster, Smarter!
    </div>

    <div class="bottom-chart-graphic">
      <div class="chart-bar" style="height: 18px;"></div>
      <div class="chart-bar" style="height: 28px;"></div>
      <div class="chart-bar" style="height: 38px;"></div>
      <div class="chart-bar" style="height: 48px; background: #1d4ed8;"></div>
      <i data-lucide="trending-up" style="color: #2563eb; width: 28px; height: 28px; margin-left: 6px; margin-bottom: 24px;"></i>
    </div>
  </section>

  <!-- Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    lucide.createIcons();

    // 3D Swiper Carousel Initialization
    const landingSwiper = new Swiper('.landing-3d-swiper', {
      effect: 'coverflow',
      grabCursor: true,
      centeredSlides: true,
      slidesPerView: 'auto',
      initialSlide: 1, // Start on Instagram Followers (center card)
      loop: false,
      speed: 600,
      coverflowEffect: {
        rotate: 15,
        stretch: 0,
        depth: 180,
        modifier: 1,
        slideShadows: false,
      },
      pagination: {
        el: '.landing-swiper-pagination',
        clickable: true,
      },
      breakpoints: {
        320: {
          slidesPerView: 1.15,
          spaceBetween: 10,
        },
        640: {
          slidesPerView: 1.6,
          spaceBetween: 20,
        },
        1024: {
          slidesPerView: 3,
          spaceBetween: 30,
        }
      }
    });

    // GSAP Card Tap Expand Zoom Transition (Requirement 11)
    function handleCardOrderClick(e, elem) {
      e.preventDefault();
      const card = elem.closest('.landing-card-slide');
      const targetUrl = card ? card.dataset.url : '/register.php';

      if (typeof gsap !== 'undefined' && card) {
        gsap.to(card, {
          scale: 1.15,
          zIndex: 9999,
          boxShadow: '0 30px 60px rgba(37, 99, 235, 0.4)',
          duration: 0.35,
          ease: 'power2.out',
          onComplete: () => {
            window.location.href = targetUrl;
          }
        });
      } else {
        window.location.href = targetUrl;
      }
    }
  </script>
</body>
</html>
