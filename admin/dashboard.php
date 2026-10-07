<?php
/**
 * SMM Panel - Admin Master Dashboard
 * Protected Server-Side: Requires Authenticated Admin Session
 * Clean: Only displays real database records with proper empty states
 */
require_once __DIR__ . '/../includes/admin-auth.php';
$admin = Auth::admin();
$db = Database::getConnection();

// Real Database Analytics
$totalUsers = (int)$db->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalOrders = (int)$db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalRevenue = (float)$db->query("SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'completed'")->fetchColumn();
$activeProvidersCount = (int)$db->query("SELECT COUNT(*) FROM providers WHERE status = 'active'")->fetchColumn();

// Fetch Real Providers
$providers = $db->query("SELECT * FROM providers ORDER BY id DESC LIMIT 10")->fetchAll();

// Fetch Real Recent Orders
$orders = $db->query("
    SELECT o.*, u.name as user_name, u.user_id_code, s.name as service_name
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    LEFT JOIN services s ON o.service_id = s.id
    ORDER BY o.id DESC LIMIT 10
")->fetchAll();

// Fetch Real Services
$services = $db->query("
    SELECT s.*, c.name as category_name, p.name as provider_name
    FROM services s
    LEFT JOIN categories c ON s.category_id = c.id
    LEFT JOIN providers p ON s.provider_id = p.id
    ORDER BY s.id DESC LIMIT 10
")->fetchAll();
?>
$adminPageTitle = 'Dashboard Overview';
$activeAdminNav = 'dashboard';

require_once __DIR__ . '/../includes/admin-header.php';
require_once __DIR__ . '/../includes/admin-navbar.php';
?>

<div class="admin-layout">

    <!-- Admin Stats Overview -->
    <div class="admin-stats-grid">
      <div class="admin-stat-card">
        <div>
          <div class="stat-label-small">TOTAL USERS</div>
          <div class="stat-val-large"><?= number_format($totalUsers) ?></div>
        </div>
        <div class="brand-icon-box" style="width: 44px; height: 44px; background: #eff6ff; color: #2563eb;">
          <i data-lucide="users"></i>
        </div>
      </div>

      <div class="admin-stat-card">
        <div>
          <div class="stat-label-small">TOTAL ORDERS</div>
          <div class="stat-val-large"><?= number_format($totalOrders) ?></div>
        </div>
        <div class="brand-icon-box" style="width: 44px; height: 44px; background: #ecfdf5; color: #10b981;">
          <i data-lucide="shopping-cart"></i>
        </div>
      </div>

      <div class="admin-stat-card">
        <div>
          <div class="stat-label-small">TOTAL REVENUE</div>
          <div class="stat-val-large"><?= formatCurrency($totalRevenue) ?></div>
        </div>
        <div class="brand-icon-box" style="width: 44px; height: 44px; background: #fef3c7; color: #f59e0b;">
          <i data-lucide="dollar-sign"></i>
        </div>
      </div>

      <div class="admin-stat-card">
        <div>
          <div class="stat-label-small">ACTIVE PROVIDERS</div>
          <div class="stat-val-large"><?= number_format($activeProvidersCount) ?> <span style="font-size: 13px; font-weight: 500; color: #10b981;">Active</span></div>
        </div>
        <div class="brand-icon-box" style="width: 44px; height: 44px; background: #f5f3ff; color: #8b5cf6;">
          <i data-lucide="server"></i>
        </div>
      </div>
    </div>

    <!-- 3D Card Navigation for Admin Modules -->
    <section class="carousel-hub-section">
      <div class="swiper swiper-3d-hub">
        <div class="swiper-wrapper">

          <div class="swiper-slide">
            <div class="hub-card card-theme-purple" data-target-page="admin-page-providers">
              <div class="hub-card-icon-wrap"><i data-lucide="server"></i></div>
              <div>
                <h2 class="hub-card-title">Providers</h2>
                <p class="hub-card-desc">SMM API integrations, balance sync &amp; endpoints</p>
              </div>
              <button class="hub-card-btn">Manage &rarr;</button>
            </div>
          </div>

          <div class="swiper-slide">
            <div class="hub-card card-theme-blue" data-target-page="admin-page-orders">
              <div class="hub-card-icon-wrap"><i data-lucide="package-check"></i></div>
              <div>
                <h2 class="hub-card-title">Orders</h2>
                <p class="hub-card-desc">Customer orders, status synchronization &amp; logs</p>
              </div>
              <button class="hub-card-btn">Inspect &rarr;</button>
            </div>
          </div>

          <div class="swiper-slide">
            <div class="hub-card card-theme-emerald" data-target-page="admin-page-services">
              <div class="hub-card-icon-wrap"><i data-lucide="layers"></i></div>
              <div>
                <h2 class="hub-card-title">Services</h2>
                <p class="hub-card-desc">Configure rates, categories &amp; provider mapping</p>
              </div>
              <button class="hub-card-btn">Configure &rarr;</button>
            </div>
          </div>

        </div>
        <div class="hub-carousel-pagination"></div>
      </div>
    </section>

    <!-- Admin Quick Actions Grid (Exactly 4 Options) -->
    <section class="quick-actions-container">
      <div class="section-label"><i data-lucide="command"></i> Admin Quick Actions</div>
      <div class="quick-actions-grid">
        <div class="quick-action-item" onclick="location.href='/admin/providers.php'">
          <div class="action-icon-circle action-icon-support"><i data-lucide="server"></i></div>
          <div><div class="action-meta-title">Providers</div><div class="action-meta-desc">API integrations</div></div>
        </div>

        <div class="quick-action-item" onclick="location.href='/admin/users.php'">
          <div class="action-icon-circle action-icon-profile"><i data-lucide="users"></i></div>
          <div><div class="action-meta-title">Users</div><div class="action-meta-desc">Manage accounts</div></div>
        </div>

        <div class="quick-action-item" onclick="location.href='/admin/tickets.php'">
          <div class="action-icon-circle action-icon-services"><i data-lucide="life-buoy"></i></div>
          <div><div class="action-meta-title">Tickets</div><div class="action-meta-desc">Support queries</div></div>
        </div>

        <div class="quick-action-item" onclick="location.href='/admin/settings.php'">
          <div class="action-icon-circle action-icon-transactions"><i data-lucide="settings"></i></div>
          <div><div class="action-meta-title">Settings</div><div class="action-meta-desc">System config</div></div>
        </div>
      </div>
    </section>

    <!-- Separate Second Section (Another 4 Options) -->
    <section class="quick-access-section">
      <div class="quick-access-dock">
        <button class="dock-item-btn active" onclick="location.href='/admin/dashboard.php'">
          <i data-lucide="layout-dashboard"></i><span>Dashboard</span>
        </button>
        <button class="dock-item-btn" onclick="location.href='/admin/orders.php'">
          <i data-lucide="shopping-bag"></i><span>Orders</span>
        </button>
        <button class="dock-item-btn" onclick="location.href='/admin/providers.php'">
          <i data-lucide="server"></i><span>Providers</span>
        </button>
        <button class="dock-item-btn" onclick="location.href='/admin/reports.php'">
          <i data-lucide="bar-chart"></i><span>Reports</span>
        </button>
      </div>
    </section>

    <!-- EXPANDED ADMIN PAGE: PROVIDERS -->
    <div id="admin-page-providers" class="page-view-container">
      <div class="page-back-header">
        <button class="back-btn-link"><span class="back-icon-circle"><i data-lucide="arrow-left"></i></span><span>Provider Management</span></button>
        <a href="/admin/providers.php" class="btn-primary" style="width: auto; padding: 10px 20px;">+ Add Provider</a>
      </div>

      <div class="data-table-card">
        <div class="table-responsive">
          <table class="app-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Provider Name</th>
                <th>API Endpoint</th>
                <th>Balance</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($providers)): ?>
                <tr><td colspan="5" style="text-align: center; color: var(--text-muted); padding: 30px;">No providers configured yet. Click "+ Add Provider" to integrate your first SMM API.</td></tr>
              <?php else: ?>
                <?php foreach ($providers as $prov): ?>
                  <tr>
                    <td>#<?= (int)$prov['id'] ?></td>
                    <td><strong><?= htmlspecialchars($prov['name']) ?></strong></td>
                    <td><code><?= htmlspecialchars($prov['api_url']) ?></code></td>
                    <td><strong style="color: #10b981;">$<?= number_format((float)$prov['balance'], 2) ?> USD</strong></td>
                    <td><span class="badge <?= $prov['status'] === 'active' ? 'badge-success' : 'badge-danger' ?>"><?= htmlspecialchars($prov['status']) ?></span></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- EXPANDED ADMIN PAGE: ORDERS -->
    <div id="admin-page-orders" class="page-view-container">
      <div class="page-back-header">
        <button class="back-btn-link"><span class="back-icon-circle"><i data-lucide="arrow-left"></i></span><span>Order Management &amp; Sync</span></button>
        <a href="/admin/orders.php" class="btn-secondary" style="padding: 10px 18px;">View All Orders</a>
      </div>

      <div class="data-table-card">
        <div class="table-responsive">
          <table class="app-table">
            <thead>
              <tr>
                <th>Order Code</th>
                <th>User</th>
                <th>Service</th>
                <th>Quantity</th>
                <th>Charge</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($orders)): ?>
                <tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">No customer orders placed yet.</td></tr>
              <?php else: ?>
                <?php foreach ($orders as $o): ?>
                  <tr>
                    <td><strong><?= htmlspecialchars($o['order_code']) ?></strong></td>
                    <td><?= htmlspecialchars($o['user_name'] ?: 'User') ?> (<?= htmlspecialchars($o['user_id_code'] ?: '#') ?>)</td>
                    <td><?= htmlspecialchars($o['service_name'] ?: 'Service') ?></td>
                    <td><?= number_format($o['quantity']) ?></td>
                    <td><?= formatCurrency((float)$o['charge']) ?></td>
                    <td><span class="badge <?= getStatusBadgeClass($o['status']) ?>"><?= htmlspecialchars($o['status']) ?></span></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- EXPANDED ADMIN PAGE: SERVICES -->
    <div id="admin-page-services" class="page-view-container">
      <div class="page-back-header">
        <button class="back-btn-link"><span class="back-icon-circle"><i data-lucide="arrow-left"></i></span><span>Services Catalog</span></button>
        <a href="/admin/services.php" class="btn-primary" style="width: auto; padding: 10px 20px;">+ Add Service</a>
      </div>

      <div class="data-table-card">
        <div class="table-responsive">
          <table class="app-table">
            <thead>
              <tr>
                <th>ID</th>
                <th>Service Name</th>
                <th>Category</th>
                <th>Rate / 1K</th>
                <th>Mapped Provider</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($services)): ?>
                <tr><td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">No services in catalog yet.</td></tr>
              <?php else: ?>
                <?php foreach ($services as $srv): ?>
                  <tr>
                    <td><?= (int)$srv['id'] ?></td>
                    <td><strong><?= htmlspecialchars($srv['name']) ?></strong></td>
                    <td><?= htmlspecialchars($srv['category_name'] ?: 'General') ?></td>
                    <td><strong><?= formatCurrency((float)$srv['rate_per_1k']) ?></strong></td>
                    <td><?= htmlspecialchars($srv['provider_name'] ?: 'Manual') ?></td>
                    <td><span class="badge badge-success"><?= htmlspecialchars($srv['status']) ?></span></td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="/assets/js/swiper-init.js"></script>
<script src="/assets/js/animations.js"></script>
<script src="/assets/js/admin.js"></script>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
