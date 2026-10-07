<?php
/**
 * Reusable Admin Navigation Bar
 */
$navItems = [
    'dashboard' => ['label' => 'Dashboard', 'icon' => 'layout-dashboard', 'url' => '/admin/dashboard.php'],
    'users' => ['label' => 'Users', 'icon' => 'users', 'url' => '/admin/users.php'],
    'orders' => ['label' => 'Orders', 'icon' => 'shopping-cart', 'url' => '/admin/orders.php'],
    'services' => ['label' => 'Services', 'icon' => 'layers', 'url' => '/admin/services.php'],
    'providers' => ['label' => 'Providers', 'icon' => 'cpu', 'url' => '/admin/providers.php'],
    'provider-services' => ['label' => 'Sync Services', 'icon' => 'refresh-cw', 'url' => '/admin/provider-services.php'],
    'payments' => ['label' => 'Payments', 'icon' => 'credit-card', 'url' => '/admin/payments.php'],
    'transactions' => ['label' => 'Transactions', 'icon' => 'file-text', 'url' => '/admin/transactions.php'],
    'tickets' => ['label' => 'Tickets', 'icon' => 'message-square', 'url' => '/admin/tickets.php'],
    'announcements' => ['label' => 'Announce', 'icon' => 'bell', 'url' => '/admin/announcements.php'],
    'reports' => ['label' => 'Reports', 'icon' => 'bar-chart-2', 'url' => '/admin/reports.php'],
    'settings' => ['label' => 'Settings', 'icon' => 'settings', 'url' => '/admin/settings.php'],
];
?>
<header class="admin-nav-header">
  <div class="admin-nav-inner">
    <!-- Brand -->
    <a href="/admin/dashboard.php" class="admin-brand">
      <div class="admin-brand-logo">
        <i data-lucide="shield-check" style="width: 22px; height: 22px;"></i>
      </div>
      <div>
        <div class="admin-brand-title"><?= htmlspecialchars(getSetting('site_name', APP_NAME)) ?></div>
        <div class="admin-brand-tagline">Admin Management</div>
      </div>
    </a>

    <!-- Desktop Navigation Menu -->
    <nav class="admin-nav-menu">
      <?php foreach ($navItems as $key => $item): ?>
        <a href="<?= htmlspecialchars($item['url']) ?>" 
           class="admin-nav-item <?= ($activeAdminNav === $key) ? 'active' : '' ?>">
          <i data-lucide="<?= htmlspecialchars($item['icon']) ?>"></i>
          <span><?= htmlspecialchars($item['label']) ?></span>
        </a>
      <?php endforeach; ?>
    </nav>

    <!-- Admin Account Badge & Actions -->
    <div class="admin-user-badge">
      <div class="admin-profile-pill">
        <i data-lucide="user-check" style="width: 14px; height: 14px; color: var(--primary-blue);"></i>
        <span class="name-text"><?= htmlspecialchars($currentAdmin['name'] ?? 'Super Admin') ?></span>
      </div>
      <a href="/api/auth/logout.php?format=json" class="icon-btn" title="Sign Out Admin" style="color: #ef4444;" onclick="event.preventDefault(); window.location.href='/login.php?logout=1';">
        <i data-lucide="log-out"></i>
      </a>
      <button class="admin-hamburger" id="admin-hamburger-btn" aria-label="Toggle menu">
        <i data-lucide="menu" style="width: 24px; height: 24px;"></i>
      </button>
    </div>
  </div>
</header>

<!-- Mobile Drawer Navigation -->
<div class="admin-mobile-drawer" id="admin-mobile-drawer">
  <div class="admin-drawer-content">
    <div class="admin-drawer-header">
      <div style="display: flex; align-items: center; gap: 10px;">
        <div class="admin-brand-logo" style="width: 32px; height: 32px;">
          <i data-lucide="shield-check" style="width: 18px; height: 18px;"></i>
        </div>
        <div style="font-weight: 800; font-size: 15px;">Admin Menu</div>
      </div>
      <button id="admin-drawer-close" style="background:none; border:none; color:#64748b; cursor:pointer;">
        <i data-lucide="x" style="width: 22px; height: 22px;"></i>
      </button>
    </div>

    <div style="display: flex; flex-direction: column; gap: 6px; flex: 1;">
      <?php foreach ($navItems as $key => $item): ?>
        <a href="<?= htmlspecialchars($item['url']) ?>" 
           class="admin-nav-item <?= ($activeAdminNav === $key) ? 'active' : '' ?>"
           style="padding: 10px 14px; border-radius: 14px; font-size: 14px;">
          <i data-lucide="<?= htmlspecialchars($item['icon']) ?>" style="width: 18px; height: 18px;"></i>
          <span><?= htmlspecialchars($item['label']) ?></span>
        </a>
      <?php endforeach; ?>
    </div>

    <div style="padding-top: 16px; border-top: 1px solid var(--border-light); margin-top: auto;">
      <a href="/login.php?logout=1" class="btn-secondary" style="width: 100%; justify-content: center; color: #ef4444;">
        <i data-lucide="log-out"></i> Sign Out
      </a>
    </div>
  </div>
</div>
