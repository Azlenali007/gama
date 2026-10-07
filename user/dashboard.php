<?php
/**
 * SMM Panel - User App Dashboard
 * Protected Server-Side: Requires Authenticated User Session
 * Clean: Only displays real database records with proper empty states
 */

require_once __DIR__ . '/../includes/user-auth.php';
$user = Auth::user();

// Fetch live user statistics and records from database
$db = Database::getConnection();

// 1. Fetch user orders
$ordersStmt = $db->prepare("
    SELECT o.*, s.name as service_name, c.icon as category_icon, c.name as category_name
    FROM orders o
    LEFT JOIN services s ON o.service_id = s.id
    LEFT JOIN categories c ON s.category_id = c.id
    WHERE o.user_id = :uid
    ORDER BY o.id DESC
    LIMIT 20
");
$ordersStmt->execute([':uid' => $user['id']]);
$userOrders = $ordersStmt->fetchAll();

// 2. Fetch categories and active services
$categoriesStmt = $db->query("SELECT * FROM categories WHERE status = 'active' ORDER BY sort_order ASC");
$categories = $categoriesStmt->fetchAll();

$servicesStmt = $db->query("
    SELECT s.*, c.slug as category_slug, c.name as category_name
    FROM services s
    JOIN categories c ON s.category_id = c.id
    WHERE s.status = 'active'
    ORDER BY c.sort_order ASC, s.id ASC
");
$services = $servicesStmt->fetchAll();

// 3. Fetch user transactions
$txnStmt = $db->prepare("SELECT * FROM transactions WHERE user_id = :uid ORDER BY id DESC LIMIT 20");
$txnStmt->execute([':uid' => $user['id']]);
$userTransactions = $txnStmt->fetchAll();

// 4. Fetch user tickets
$ticketStmt = $db->prepare("SELECT * FROM tickets WHERE user_id = :uid ORDER BY id DESC LIMIT 20");
$ticketStmt->execute([':uid' => $user['id']]);
$userTickets = $ticketStmt->fetchAll();

// 5. Fetch announcements / notifications
$notifStmt = $db->query("SELECT * FROM announcements WHERE is_active = 1 ORDER BY id DESC LIMIT 10");
$notifications = $notifStmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - <?= htmlspecialchars(APP_NAME) ?></title>
  
  <!-- CSS Assets -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/user.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">

  <style>
    .empty-state-box {
      text-align: center;
      padding: 36px 20px;
      background: #f8fafc;
      border: 1px dashed var(--border-light);
      border-radius: 20px;
      color: var(--text-muted);
    }
    .empty-state-box i {
      width: 40px;
      height: 40px;
      margin-bottom: 10px;
      color: var(--text-light);
    }
    .empty-state-box p {
      font-size: 14px;
      font-weight: 700;
    }
  </style>
</head>
<body>

  <div class="app-container">

    <!-- 1. Top App Header -->
    <header class="app-header">
      <div class="brand-badge">
        <div class="brand-icon-box">
          <i data-lucide="bar-chart-2"></i>
        </div>
        <div>
          <div class="brand-title"><?= htmlspecialchars(APP_NAME) ?></div>
          <div class="brand-subtitle"><?= htmlspecialchars(APP_TAGLINE) ?></div>
        </div>
      </div>

      <div class="header-actions">
        <button class="icon-btn" onclick="expandCardToPage(this, 'page-notifications')" title="Notifications">
          <i data-lucide="bell"></i>
          <?php if (!empty($notifications)): ?><span class="icon-badge-dot"></span><?php endif; ?>
        </button>
        <button class="icon-btn" onclick="expandCardToPage(this, 'page-tickets')" title="Support Tickets">
          <i data-lucide="message-square"></i>
        </button>
        <button class="user-avatar-btn" onclick="expandCardToPage(this, 'page-profile')" title="Profile">
          <span><?= strtoupper(substr($user['name'] ?? 'U', 0, 2)) ?></span>
        </button>
        <a href="/api/auth/logout.php" class="icon-btn" title="Sign Out" style="color: #ef4444;">
          <i data-lucide="log-out"></i>
        </a>
      </div>
    </header>

    <!-- 2. User Welcome Bar -->
    <div class="user-welcome-bar">
      <div class="welcome-user-info">
        <div class="welcome-avatar">
          <i data-lucide="user"></i>
        </div>
        <div>
          <div class="welcome-user-title">Welcome back,</div>
          <div class="welcome-user-name"><?= htmlspecialchars($user['name']) ?></div>
          <div class="welcome-user-id">User ID: <?= htmlspecialchars($user['user_id_code']) ?></div>
        </div>
      </div>

      <div class="balance-pill-card">
        <div>
          <div class="balance-label">Your Balance</div>
          <div class="balance-value live-user-balance" id="header-balance-display">
            <?= formatCurrency((float)$user['balance']) ?>
          </div>
        </div>
        <button class="add-funds-pill-btn" onclick="expandCardToPage(this, 'page-add-funds')">
          <i data-lucide="plus"></i> Add Funds
        </button>
      </div>
    </div>

    <!-- 3. MAIN 3D CARD CAROUSEL -->
    <section class="carousel-hub-section">
      <div class="swiper swiper-3d-hub">
        <div class="swiper-wrapper">

          <!-- Card 1 (Left): Add Funds -->
          <div class="swiper-slide">
            <div class="hub-card card-theme-emerald" data-target-page="page-add-funds">
              <div class="hub-card-icon-wrap">
                <i data-lucide="wallet"></i>
              </div>
              <div>
                <h2 class="hub-card-title">Add Funds</h2>
                <p class="hub-card-desc">Top up your wallet with instant payment</p>
              </div>
              <button class="hub-card-btn">Open &rarr;</button>
            </div>
          </div>

          <!-- Card 2 (Center Active): New Order -->
          <div class="swiper-slide">
            <div class="hub-card card-theme-blue" data-target-page="page-new-order">
              <div class="hub-card-icon-wrap">
                <i data-lucide="zap"></i>
              </div>
              <div>
                <h2 class="hub-card-title">New Order</h2>
                <p class="hub-card-desc">Place a new order for your social media</p>
              </div>
              <button class="hub-card-btn">Open &rarr;</button>
            </div>
          </div>

          <!-- Card 3 (Right): My Orders -->
          <div class="swiper-slide">
            <div class="hub-card card-theme-amber" data-target-page="page-my-orders">
              <div class="hub-card-icon-wrap">
                <i data-lucide="package"></i>
              </div>
              <div>
                <h2 class="hub-card-title">My Orders</h2>
                <p class="hub-card-desc">Track and monitor your live order progress</p>
              </div>
              <button class="hub-card-btn">Open &rarr;</button>
            </div>
          </div>

        </div>
        <div class="hub-carousel-pagination"></div>
      </div>
    </section>

    <!-- 4. QUICK ACTIONS: EXACTLY 4 OPTIONS -->
    <section class="quick-actions-container">
      <div class="quick-actions-grid">
        <div class="quick-action-item" data-target-page="page-all-services">
          <div class="action-icon-circle action-icon-services"><i data-lucide="layers"></i></div>
          <div><div class="action-meta-title">Services</div><div class="action-meta-desc">Browse all services</div></div>
        </div>

        <div class="quick-action-item" data-target-page="page-transactions">
          <div class="action-icon-circle action-icon-transactions"><i data-lucide="receipt"></i></div>
          <div><div class="action-meta-title">Transactions</div><div class="action-meta-desc">Wallet & history</div></div>
        </div>

        <div class="quick-action-item" data-target-page="page-tickets">
          <div class="action-icon-circle action-icon-support"><i data-lucide="life-buoy"></i></div>
          <div><div class="action-meta-title">Support</div><div class="action-meta-desc">Get instant help</div></div>
        </div>

        <div class="quick-action-item" data-target-page="page-profile">
          <div class="action-icon-circle action-icon-profile"><i data-lucide="user-check"></i></div>
          <div><div class="action-meta-title">Profile</div><div class="action-meta-desc">Account settings</div></div>
        </div>
      </div>
    </section>

    <!-- 5. SEPARATE SECOND SECTION: EXACTLY 4 OPTIONS -->
    <section class="quick-access-section">
      <div class="quick-access-dock">
        <button class="dock-item-btn active" data-target="home">
          <i data-lucide="home"></i><span>Home</span>
        </button>
        <button class="dock-item-btn" data-target="page-my-orders">
          <i data-lucide="shopping-bag"></i><span>Orders</span>
        </button>
        <button class="dock-item-btn" data-target="page-add-funds">
          <i data-lucide="wallet"></i><span>Wallet</span>
        </button>
        <button class="dock-item-btn" data-target="page-profile">
          <i data-lucide="user"></i><span>Profile</span>
        </button>
      </div>
    </section>

    <!-- ===================================================
         EXPANDED PAGES (GSAP Animated Views)
         =================================================== -->

    <!-- PAGE 1: NEW ORDER -->
    <div id="page-new-order" class="page-view-container">
      <div class="page-back-header">
        <button class="back-btn-link">
          <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
          <span>New Order</span>
        </button>
      </div>

      <div class="order-step-indicator">
        <div class="step-item active"><span class="step-number-circle">1</span><span>Select Service</span></div>
        <div class="step-item active"><span class="step-number-circle">2</span><span>Contact Info</span></div>
        <div class="step-item"><span class="step-number-circle">3</span><span>Payment</span></div>
      </div>

      <?php if (empty($services)): ?>
        <div class="empty-state-box">
          <i data-lucide="layers"></i>
          <p>No services available</p>
          <span style="font-size: 12px;">The administrator has not added any services yet.</span>
        </div>
      <?php else: ?>
        <div class="order-layout-grid">
          <!-- Category Sidebar -->
          <div class="category-sidebar-list">
            <?php foreach ($categories as $idx => $cat): ?>
              <button class="category-tab-btn <?= $idx === 0 ? 'active' : '' ?>" data-category="<?= htmlspecialchars($cat['slug']) ?>">
                <i data-lucide="<?= htmlspecialchars($cat['icon'] ?: 'share-2') ?>"></i>
                <?= htmlspecialchars($cat['name']) ?>
              </button>
            <?php endforeach; ?>
          </div>

          <!-- Services List & Form -->
          <div>
            <div class="service-list-header">
              <h3 id="current-category-title"><?= htmlspecialchars($categories[0]['name'] ?? 'Services') ?></h3>
              <p>Choose a service from the list below</p>
            </div>

            <?php foreach ($services as $idx => $s): ?>
              <div class="service-card-item <?= $idx === 0 ? 'selected' : '' ?>"
                   data-category="<?= htmlspecialchars($s['category_slug']) ?>"
                   data-id="<?= (int)$s['id'] ?>"
                   data-rate="<?= htmlspecialchars($s['rate_per_1k']) ?>"
                   data-name="<?= htmlspecialchars($s['name']) ?>"
                   data-min="<?= (int)$s['min_quantity'] ?>"
                   data-max="<?= (int)$s['max_quantity'] ?>"
                   data-badges="<?= htmlspecialchars($s['badges']) ?>">
                <div style="display: flex; align-items: center; gap: 12px;">
                  <span class="service-item-title"><?= htmlspecialchars($s['name']) ?></span>
                </div>
                <span class="service-item-rate"><?= formatCurrency((float)$s['rate_per_1k']) ?> / 1K</span>
              </div>
            <?php endforeach; ?>

            <div class="service-details-card" style="margin-top: 24px;">
              <div class="service-details-top">
                <div>
                  <h4 id="selected-service-name" style="font-size: 18px; font-weight: 800;"><?= htmlspecialchars($services[0]['name'] ?? 'Service') ?></h4>
                  <div class="badges-wrap">
                    <span id="selected-service-badges" class="badge-feature"><?= htmlspecialchars($services[0]['badges'] ?? 'High Quality') ?></span>
                  </div>
                </div>
                <div style="text-align: right;">
                  <div id="selected-service-rate" style="font-size: 20px; font-weight: 900; color: #2563eb;">
                    <?= formatCurrency((float)($services[0]['rate_per_1k'] ?? 0)) ?> / 1K
                  </div>
                </div>
              </div>

              <div style="margin-top: 20px;">
                <label class="form-field-label">Quantity</label>
                <div class="quantity-stepper-box">
                  <button type="button" class="step-calc-btn" id="qty-minus-btn">&minus;</button>
                  <input type="number" id="order-quantity-input" class="quantity-input" value="<?= (int)($services[0]['min_quantity'] ?? 100) ?>" min="<?= (int)($services[0]['min_quantity'] ?? 100) ?>" max="<?= (int)($services[0]['max_quantity'] ?? 100000) ?>" step="100">
                  <button type="button" class="step-calc-btn" id="qty-plus-btn">&plus;</button>
                </div>
              </div>

              <div style="margin-top: 18px;">
                <label class="form-field-label">Target Link / URL</label>
                <input type="url" id="order-link-input" placeholder="https://..." required style="width: 100%;">
              </div>

              <div class="total-price-bar">
                <span class="total-price-label">Total Price</span>
                <span class="total-price-val" id="calculated-order-price">₹0.00</span>
              </div>

              <button type="button" class="btn-primary" id="btn-place-order" onclick="placeNewOrder()">
                Place Order &rarr;
              </button>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>

    <!-- PAGE 2: ADD FUNDS -->
    <div id="page-add-funds" class="page-view-container">
      <div class="page-back-header">
        <button class="back-btn-link">
          <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
          <span>Add Funds</span>
        </button>
      </div>

      <div class="funds-current-card">
        <div>
          <div class="balance-label" style="text-align: left;">Current Balance</div>
          <div class="funds-current-val live-user-balance">
            <?= formatCurrency((float)$user['balance']) ?>
          </div>
        </div>
        <div class="brand-icon-box" style="width: 56px; height: 56px; border-radius: 18px;">
          <i data-lucide="wallet" style="width: 28px; height: 28px;"></i>
        </div>
      </div>

      <label class="form-field-label" style="font-size: 15px; margin-bottom: 12px;">Select Amount</label>
      <div class="amount-chips-grid">
        <div class="amount-chip" data-amount="100">₹100</div>
        <div class="amount-chip selected" data-amount="200">₹200</div>
        <div class="amount-chip" data-amount="500">₹500</div>
        <div class="amount-chip" data-amount="1000">₹1,000</div>
        <div class="amount-chip" data-amount="2000">₹2,000</div>
        <div class="amount-chip" data-amount="5000">₹5,000</div>
      </div>

      <label class="form-field-label" style="font-size: 15px; margin-bottom: 12px;">Payment Method</label>
      <div class="payment-method-selector">
        <div style="display: flex; align-items: center; gap: 14px;">
          <div class="razorpay-badge-brand">Razorpay</div>
          <span style="font-size: 12px; color: var(--text-muted); font-weight: 600;">Secure UPI &amp; Cards</span>
        </div>
        <i data-lucide="chevron-right" style="color: var(--text-light);"></i>
      </div>

      <button type="button" class="btn-primary" id="btn-pay-now-funds" onclick="triggerAddFundsPayment()">
        Pay Now ₹200 &rarr;
      </button>

      <div class="secure-encryption-note">
        <i data-lucide="lock" style="width: 14px; height: 14px;"></i>
        <span>Your payment is secure and encrypted</span>
      </div>
    </div>

    <!-- PAGE 3: MY ORDERS -->
    <div id="page-my-orders" class="page-view-container">
      <div class="page-back-header">
        <button class="back-btn-link">
          <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
          <span>My Orders</span>
        </button>
      </div>

      <div class="filter-tabs-row">
        <button class="filter-pill-btn active order-filter-pill" data-filter="all">All</button>
        <button class="filter-pill-btn order-filter-pill" data-filter="processing">Processing</button>
        <button class="filter-pill-btn order-filter-pill" data-filter="completed">Completed</button>
        <button class="filter-pill-btn order-filter-pill" data-filter="cancelled">Cancelled</button>
      </div>

      <div class="orders-list-wrap" id="my-orders-list-container">
        <?php if (empty($userOrders)): ?>
          <div class="empty-state-box">
            <i data-lucide="package"></i>
            <p>No orders yet</p>
            <span style="font-size: 12px;">Your placed orders will show up here.</span>
          </div>
        <?php else: ?>
          <?php foreach ($userOrders as $ord): ?>
            <div class="order-card-row" data-status="<?= htmlspecialchars($ord['status']) ?>">
              <div class="order-info-group">
                <div class="order-social-icon"><i data-lucide="<?= htmlspecialchars($ord['category_icon'] ?: 'package') ?>"></i></div>
                <div>
                  <div class="order-meta-title"><?= htmlspecialchars($ord['service_name'] ?: 'Service') ?></div>
                  <div class="order-meta-sub"><?= number_format($ord['quantity']) ?> &bull; <?= formatCurrency((float)$ord['charge']) ?></div>
                </div>
              </div>
              <div style="text-align: right;">
                <span class="badge <?= getStatusBadgeClass($ord['status']) ?>"><?= htmlspecialchars($ord['status']) ?></span>
                <div class="order-meta-id" style="margin-top: 4px;"><?= htmlspecialchars($ord['order_code']) ?></div>
                <div style="font-size: 11px; color: var(--text-light); margin-top: 2px;"><?= getFormattedDate($ord['created_at']) ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- PAGE 4: ALL SERVICES -->
    <div id="page-all-services" class="page-view-container">
      <div class="page-back-header">
        <button class="back-btn-link">
          <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
          <span>All Services</span>
        </button>
      </div>

      <div class="services-search-bar">
        <i data-lucide="search" class="services-search-icon"></i>
        <input type="text" class="services-search-input" placeholder="Search services...">
      </div>

      <div class="services-grid-cards">
        <?php if (empty($services)): ?>
          <div class="empty-state-box" style="grid-column: 1 / -1;">
            <i data-lucide="layers"></i>
            <p>No services available</p>
          </div>
        <?php else: ?>
          <?php foreach ($services as $srv): ?>
            <div class="service-box-card" onclick="expandCardToPage(this, 'page-new-order')">
              <div class="service-box-name"><?= htmlspecialchars($srv['name']) ?></div>
              <div class="service-box-price"><?= formatCurrency((float)$srv['rate_per_1k']) ?> <span style="font-size: 12px; color: var(--text-muted); font-weight: 500;">/ 1K</span></div>
              <div style="font-size: 11px; color: var(--text-muted); margin-top: 6px;">Min: <?= (int)$srv['min_quantity'] ?> | Max: <?= (int)$srv['max_quantity'] ?></div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- PAGE 5: TRANSACTIONS -->
    <div id="page-transactions" class="page-view-container">
      <div class="page-back-header">
        <button class="back-btn-link">
          <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
          <span>Transactions</span>
        </button>
      </div>

      <div class="transactions-list-wrap">
        <?php if (empty($userTransactions)): ?>
          <div class="empty-state-box">
            <i data-lucide="receipt"></i>
            <p>No transactions yet</p>
          </div>
        <?php else: ?>
          <?php foreach ($userTransactions as $txn): ?>
            <div class="txn-card-item">
              <div>
                <div style="font-weight: 800; font-size: 15px;"><?= htmlspecialchars($txn['title']) ?></div>
                <div style="font-size: 12px; color: var(--text-muted);"><?= htmlspecialchars($txn['description']) ?> &bull; <?= getFormattedDate($txn['created_at']) ?></div>
              </div>
              <div class="<?= $txn['direction'] === 'credit' ? 'txn-amount-credit' : 'txn-amount-debit' ?>">
                <?= $txn['direction'] === 'credit' ? '+' : '-' ?> <?= formatCurrency((float)$txn['amount']) ?>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- PAGE 6: SUPPORT TICKETS -->
    <div id="page-tickets" class="page-view-container">
      <div class="page-back-header">
        <button class="back-btn-link">
          <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
          <span>Support</span>
        </button>
        <button class="btn-primary" style="width: auto; padding: 10px 18px;" onclick="showToast('Create Ticket feature ready', 'info')">
          + Create Ticket
        </button>
      </div>

      <div>
        <?php if (empty($userTickets)): ?>
          <div class="empty-state-box">
            <i data-lucide="life-buoy"></i>
            <p>No tickets yet</p>
            <span style="font-size: 12px;">Need help? Create a support ticket.</span>
          </div>
        <?php else: ?>
          <?php foreach ($userTickets as $t): ?>
            <div class="ticket-row-card">
              <div>
                <div style="font-weight: 800; font-size: 15px;">
                  <span style="color: var(--text-light);"><?= htmlspecialchars($t['ticket_code']) ?></span> <?= htmlspecialchars($t['subject']) ?>
                </div>
                <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;"><?= getFormattedDate($t['created_at']) ?></div>
              </div>
              <span class="badge <?= getStatusBadgeClass($t['status']) ?>"><?= htmlspecialchars($t['status']) ?></span>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <!-- PAGE 7: PROFILE -->
    <div id="page-profile" class="page-view-container">
      <div class="page-back-header">
        <button class="back-btn-link">
          <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
          <span>Profile</span>
        </button>
      </div>

      <div class="order-layout-grid">
        <div class="profile-tabs-sidebar">
          <button class="profile-nav-btn active"><i data-lucide="user"></i> Account Info</button>
        </div>

        <div>
          <h4 style="font-size: 17px; font-weight: 800; margin-bottom: 16px;">Account Information</h4>
          <div class="profile-avatar-banner">
            <div class="welcome-avatar" style="width: 54px; height: 54px;"><i data-lucide="user"></i></div>
            <div>
              <div style="font-size: 18px; font-weight: 800;"><?= htmlspecialchars($user['name']) ?></div>
              <div style="font-size: 13px; color: var(--text-muted);">User ID: <?= htmlspecialchars($user['user_id_code']) ?></div>
            </div>
          </div>

          <form onsubmit="event.preventDefault(); showToast('Profile updated', 'success');">
            <div class="form-field-group">
              <label class="form-field-label">Email</label>
              <input type="email" value="<?= htmlspecialchars($user['email']) ?>" readonly style="width: 100%; background: #f8fafc;">
            </div>
            <div class="form-field-group">
              <label class="form-field-label">Phone</label>
              <input type="tel" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="Phone number" style="width: 100%;">
            </div>
            <div class="form-field-group">
              <label class="form-field-label">Joined</label>
              <input type="text" value="<?= getFormattedDate($user['created_at'] ?? date('Y-m-d')) ?>" readonly style="width: 100%; background: #f8fafc;">
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- PAGE 8: NOTIFICATIONS -->
    <div id="page-notifications" class="page-view-container">
      <div class="page-back-header">
        <button class="back-btn-link">
          <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
          <span>Notifications</span>
        </button>
      </div>

      <div class="notifications-list-wrap">
        <?php if (empty($notifications)): ?>
          <div class="empty-state-box">
            <i data-lucide="bell"></i>
            <p>No notifications yet</p>
          </div>
        <?php else: ?>
          <?php foreach ($notifications as $n): ?>
            <div class="order-card-row">
              <div>
                <div style="font-weight: 800; font-size: 15px;"><?= htmlspecialchars($n['title']) ?></div>
                <div style="font-size: 12px; color: var(--text-muted);"><?= htmlspecialchars($n['content']) ?></div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="/assets/js/swiper-init.js"></script>
  <script src="/assets/js/animations.js"></script>
  <script src="/assets/js/user.js"></script>
  <script src="/assets/js/app.js"></script>
</body>
</html>
