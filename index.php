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

  <!-- 3. 3D Card Carousel Section (Matches Reference Image) -->
  <section class="landing-carousel-section" id="services-section">
    <div class="swiper landing-3d-swiper">
      <div class="swiper-wrapper">

        <!-- Card 1: YouTube Views (Left Card in reference image) -->
        <div class="swiper-slide landing-card-slide landing-card-zoom-target" data-url="/register.php">
          <div class="landing-card-banner" style="background: radial-gradient(circle at 50% 50%, #ff4b4b 0%, #dc2626 100%);">
            <span class="landing-tag-frosted">YouTube</span>
            <div style="width: 90px; height: 90px; border-radius: 26px; background: rgba(255,255,255,0.22); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; box-shadow: 0 14px 28px rgba(0,0,0,0.2); border: 2px solid rgba(255,255,255,0.35);">
              <i data-lucide="youtube" style="width: 48px; height: 48px; color: #ffffff;"></i>
            </div>
          </div>
          <div class="landing-card-body">
            <div class="landing-card-title">YouTube Views</div>
            <div class="landing-card-subtitle">Real Views • High Retention</div>
            <div class="landing-price-bar">
              <div class="landing-price-val"><?= htmlspecialchars(getCurrencySymbol()) ?>12 <span>/ 1K</span></div>
              <span class="badge badge-success" style="padding: 6px 12px; font-size: 11px;">
                <i data-lucide="zap" style="width: 12px; height: 12px; margin-right: 4px;"></i> Fast Delivery
              </span>
            </div>
            <a href="/register.php" class="landing-order-btn" onclick="handleCardOrderClick(event, this)">
              <i data-lucide="shopping-cart"></i> Order Now &rarr;
            </a>
          </div>
        </div>

        <!-- Card 2: Instagram Followers (Active Center Card in reference image) -->
        <div class="swiper-slide landing-card-slide landing-card-zoom-target" data-url="/register.php">
          <div class="landing-card-banner" style="background: radial-gradient(circle at 30% 30%, #f58529 0%, #dd2a7b 50%, #8134af 100%);">
            <span class="landing-tag-frosted">Instagram</span>
            <!-- 3D Heart Speech Bubbles & +1K Badge -->
            <div style="position: relative; display: flex; align-items: center; justify-content: center;">
              <div style="width: 100px; height: 100px; border-radius: 30px; background: rgba(255,255,255,0.25); backdrop-filter: blur(12px); display: flex; align-items: center; justify-content: center; box-shadow: 0 16px 32px rgba(0,0,0,0.25); border: 2px solid rgba(255,255,255,0.45);">
                <i data-lucide="instagram" style="width: 54px; height: 54px; color: #ffffff;"></i>
              </div>
              <div style="position: absolute; top: -10px; right: -22px; background: #ffffff; color: #e11d48; padding: 6px 10px; border-radius: 9999px; box-shadow: 0 6px 16px rgba(0,0,0,0.15); display: flex; align-items: center; gap: 4px; font-weight: 900; font-size: 11px;">
                <i data-lucide="heart" style="width: 12px; height: 12px; fill: #e11d48;"></i>
              </div>
              <div style="position: absolute; bottom: -12px; right: -15px; background: #6366f1; color: #ffffff; padding: 5px 12px; border-radius: 9999px; font-weight: 900; font-size: 12px; box-shadow: 0 6px 16px rgba(99, 102, 241, 0.4);">
                +1K
              </div>
            </div>
          </div>
          <div class="landing-card-body">
            <div class="landing-card-title">Instagram Followers</div>
            <div class="landing-card-subtitle">Real &amp; Active Followers • High Quality • Fast Delivery</div>
            <div class="landing-price-bar">
              <div class="landing-price-val"><?= htmlspecialchars(getCurrencySymbol()) ?>35 <span>/ 1K</span></div>
              <span class="badge badge-success" style="padding: 6px 12px; font-size: 11px;">
                <i data-lucide="zap" style="width: 12px; height: 12px; margin-right: 4px;"></i> Starts in 1-2 Hours
              </span>
            </div>
            <a href="/register.php" class="landing-order-btn" onclick="handleCardOrderClick(event, this)">
              <i data-lucide="shopping-cart"></i> Order Now &rarr;
            </a>
          </div>
        </div>

        <!-- Card 3: Telegram Members (Right Card in reference image) -->
        <div class="swiper-slide landing-card-slide landing-card-zoom-target" data-url="/register.php">
          <div class="landing-card-banner" style="background: radial-gradient(circle at 50% 50%, #38bdf8 0%, #0284c7 100%);">
            <span class="landing-tag-frosted">Telegram</span>
            <div style="width: 90px; height: 90px; border-radius: 26px; background: rgba(255,255,255,0.22); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; box-shadow: 0 14px 28px rgba(0,0,0,0.2); border: 2px solid rgba(255,255,255,0.35);">
              <i data-lucide="send" style="width: 44px; height: 44px; color: #ffffff;"></i>
            </div>
          </div>
          <div class="landing-card-body">
            <div class="landing-card-title">Telegram Members</div>
            <div class="landing-card-subtitle">Real &amp; Active Members • Instant Start</div>
            <div class="landing-price-bar">
              <div class="landing-price-val"><?= htmlspecialchars(getCurrencySymbol()) ?>45 <span>/ 1K</span></div>
              <span class="badge badge-success" style="padding: 6px 12px; font-size: 11px;">
                <i data-lucide="zap" style="width: 12px; height: 12px; margin-right: 4px;"></i> Fast Delivery
              </span>
            </div>
            <a href="/register.php" class="landing-order-btn" onclick="handleCardOrderClick(event, this)">
              <i data-lucide="shopping-cart"></i> Order Now &rarr;
            </a>
          </div>
        </div>

        <!-- Card 4: TikTok Likes -->
        <div class="swiper-slide landing-card-slide landing-card-zoom-target" data-url="/register.php">
          <div class="landing-card-banner" style="background: radial-gradient(circle at 50% 50%, #1e293b 0%, #000000 100%);">
            <span class="landing-tag-frosted">TikTok</span>
            <div style="width: 90px; height: 90px; border-radius: 26px; background: rgba(255,255,255,0.15); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; box-shadow: 0 14px 28px rgba(0,0,0,0.3); border: 2px solid rgba(255,255,255,0.25);">
              <i data-lucide="music" style="width: 44px; height: 44px; color: #22d3ee;"></i>
            </div>
          </div>
          <div class="landing-card-body">
            <div class="landing-card-title">TikTok Likes &amp; Views</div>
            <div class="landing-card-subtitle">Instant For-You Reach • High Retention</div>
            <div class="landing-price-bar">
              <div class="landing-price-val"><?= htmlspecialchars(getCurrencySymbol()) ?>25 <span>/ 1K</span></div>
              <span class="badge badge-success" style="padding: 6px 12px; font-size: 11px;">
                <i data-lucide="zap" style="width: 12px; height: 12px; margin-right: 4px;"></i> Instant Start
              </span>
            </div>
            <a href="/register.php" class="landing-order-btn" onclick="handleCardOrderClick(event, this)">
              <i data-lucide="shopping-cart"></i> Order Now &rarr;
            </a>
          </div>
        </div>

        <!-- Card 5: Twitter (X) Retweets -->
        <div class="swiper-slide landing-card-slide landing-card-zoom-target" data-url="/register.php">
          <div class="landing-card-banner" style="background: radial-gradient(circle at 50% 50%, #334155 0%, #0f172a 100%);">
            <span class="landing-tag-frosted">Twitter (X)</span>
            <div style="width: 90px; height: 90px; border-radius: 26px; background: rgba(255,255,255,0.18); backdrop-filter: blur(8px); display: flex; align-items: center; justify-content: center; box-shadow: 0 14px 28px rgba(0,0,0,0.25); border: 2px solid rgba(255,255,255,0.3);">
              <i data-lucide="twitter" style="width: 44px; height: 44px; color: #ffffff;"></i>
            </div>
          </div>
          <div class="landing-card-body">
            <div class="landing-card-title">Twitter (X) Retweets</div>
            <div class="landing-card-subtitle">Worldwide Engagement • Fast Viral Boost</div>
            <div class="landing-price-bar">
              <div class="landing-price-val"><?= htmlspecialchars(getCurrencySymbol()) ?>40 <span>/ 1K</span></div>
              <span class="badge badge-success" style="padding: 6px 12px; font-size: 11px;">
                <i data-lucide="zap" style="width: 12px; height: 12px; margin-right: 4px;"></i> Fast Delivery
              </span>
            </div>
            <a href="/register.php" class="landing-order-btn" onclick="handleCardOrderClick(event, this)">
              <i data-lucide="shopping-cart"></i> Order Now &rarr;
            </a>
          </div>
        </div>

      </div>

      <!-- Pagination Dots (5 dots as in image) -->
      <div class="swiper-pagination landing-swiper-pagination" style="margin-top: 24px;"></div>
    </div>
  </section>

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
