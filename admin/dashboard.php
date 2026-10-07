<?php
/**
 * SMM Panel - Admin Master Dashboard
 * 3D Card Navigation & Admin Management Hub
 */
require_once __DIR__ . '/../includes/admin-auth.php';
$admin = Auth::admin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard - <?= htmlspecialchars(APP_NAME) ?></title>
  
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/user.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">

    <!-- Header -->
    <header class="app-header">
      <div class="brand-badge">
        <div class="brand-icon-box" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
          <i data-lucide="shield-check"></i>
        </div>
        <div>
          <div class="brand-title"><?= htmlspecialchars(APP_NAME) ?> <span class="admin-header-badge">Admin</span></div>
          <div class="brand-subtitle">Master Administration Control</div>
        </div>
      </div>

      <div class="header-actions">
        <a href="/user/dashboard.php" class="btn-secondary" style="font-size: 13px;">
          <i data-lucide="external-link"></i> User App
        </a>
        <a href="/admin/index.php" class="btn-secondary" style="font-size: 13px; color: #dc2626;">
          <i data-lucide="log-out"></i> Logout
        </a>
      </div>
    </header>

    <!-- Admin Stats Overview -->
    <div class="admin-stats-grid">
      <div class="admin-stat-card">
        <div>
          <div class="stat-label-small">TOTAL USERS</div>
          <div class="stat-val-large">1,240</div>
        </div>
        <div class="brand-icon-box" style="width: 44px; height: 44px; background: #eff6ff; color: #2563eb;">
          <i data-lucide="users"></i>
        </div>
      </div>

      <div class="admin-stat-card">
        <div>
          <div class="stat-label-small">TOTAL ORDERS</div>
          <div class="stat-val-large">10,254</div>
        </div>
        <div class="brand-icon-box" style="width: 44px; height: 44px; background: #ecfdf5; color: #10b981;">
          <i data-lucide="shopping-cart"></i>
        </div>
      </div>

      <div class="admin-stat-card">
        <div>
          <div class="stat-label-small">TOTAL REVENUE</div>
          <div class="stat-val-large">₹2,48,900</div>
        </div>
        <div class="brand-icon-box" style="width: 44px; height: 44px; background: #fef3c7; color: #f59e0b;">
          <i data-lucide="dollar-sign"></i>
        </div>
      </div>

      <div class="admin-stat-card">
        <div>
          <div class="stat-label-small">ACTIVE PROVIDERS</div>
          <div class="stat-val-large">3 <span style="font-size: 13px; font-weight: 500; color: #10b981;">Connected</span></div>
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

          <!-- Card 1: Provider Management -->
          <div class="swiper-slide">
            <div class="hub-card card-theme-purple" data-target-page="admin-page-providers">
              <div class="hub-card-icon-wrap">
                <i data-lucide="server"></i>
              </div>
              <div>
                <h2 class="hub-card-title">Providers</h2>
                <p class="hub-card-desc">SMM API integrations, balance sync & endpoints</p>
              </div>
              <button class="hub-card-btn">Manage &rarr;</button>
            </div>
          </div>

          <!-- Card 2: Orders & Synchronization -->
          <div class="swiper-slide">
            <div class="hub-card card-theme-blue" data-target-page="admin-page-orders">
              <div class="hub-card-icon-wrap">
                <i data-lucide="package-check"></i>
              </div>
              <div>
                <h2 class="hub-card-title">Orders</h2>
                <p class="hub-card-desc">User orders, status synchronization & logs</p>
              </div>
              <button class="hub-card-btn">Inspect &rarr;</button>
            </div>
          </div>

          <!-- Card 3: Services & Pricing -->
          <div class="swiper-slide">
            <div class="hub-card card-theme-emerald" data-target-page="admin-page-services">
              <div class="hub-card-icon-wrap">
                <i data-lucide="layers"></i>
              </div>
              <div>
                <h2 class="hub-card-title">Services</h2>
                <p class="hub-card-desc">Configure rates, categories & provider mapping</p>
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
          <div class="action-icon-circle action-icon-support">
            <i data-lucide="server"></i>
          </div>
          <div>
            <div class="action-meta-title">Providers</div>
            <div class="action-meta-desc">API integrations</div>
          </div>
        </div>

        <div class="quick-action-item" onclick="location.href='/admin/users.php'">
          <div class="action-icon-circle action-icon-profile">
            <i data-lucide="users"></i>
          </div>
          <div>
            <div class="action-meta-title">Users</div>
            <div class="action-meta-desc">Manage accounts</div>
          </div>
        </div>

        <div class="quick-action-item" onclick="location.href='/admin/tickets.php'">
          <div class="action-icon-circle action-icon-services">
            <i data-lucide="life-buoy"></i>
          </div>
          <div>
            <div class="action-meta-title">Tickets</div>
            <div class="action-meta-desc">Support queries</div>
          </div>
        </div>

        <div class="quick-action-item" onclick="location.href='/admin/settings.php'">
          <div class="action-icon-circle action-icon-transactions">
            <i data-lucide="settings"></i>
          </div>
          <div>
            <div class="action-meta-title">Settings</div>
            <div class="action-meta-desc">System config</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Separate Second Section (Another 4 Options) -->
    <section class="quick-access-section">
      <div class="quick-access-dock">
        <button class="dock-item-btn active" onclick="location.href='/admin/dashboard.php'">
          <i data-lucide="layout-dashboard"></i>
          <span>Dashboard</span>
        </button>
        <button class="dock-item-btn" onclick="location.href='/admin/orders.php'">
          <i data-lucide="shopping-bag"></i>
          <span>Orders</span>
        </button>
        <button class="dock-item-btn" onclick="location.href='/admin/providers.php'">
          <i data-lucide="server"></i>
          <span>Providers</span>
        </button>
        <button class="dock-item-btn" onclick="location.href='/admin/reports.php'">
          <i data-lucide="bar-chart"></i>
          <span>Reports</span>
        </button>
      </div>
    </section>

    <!-- EXPANDED ADMIN PAGE: PROVIDERS (Image/Workflow Target) -->
    <div id="admin-page-providers" class="page-view-container">
      <div class="page-back-header">
        <button class="back-btn-link">
          <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
          <span>Provider Management</span>
        </button>
        <button class="btn-primary" id="btn-open-add-provider" style="width: auto; padding: 10px 20px;">
          + Add Provider
        </button>
      </div>

      <div class="data-table-card">
        <div class="data-table-header">
          <h3 style="font-size: 17px; font-weight: 800;">Connected SMM API Providers</h3>
          <span style="font-size: 13px; color: var(--text-muted);">Auto-synchronization via <code>cron/cron.php</code></span>
        </div>

        <table class="app-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Provider Name</th>
              <th>API Endpoint</th>
              <th>Balance</th>
              <th>API Status</th>
              <th>Provider Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>#1</td>
              <td><strong>GlobalSMM Prime API</strong></td>
              <td><code>https://api.globalsmm.pro/v2</code></td>
              <td><strong id="prov-bal-1" style="color: #10b981;">$428.60 USD</strong></td>
              <td><span class="badge badge-success">Connected</span></td>
              <td><span class="badge badge-success">Active</span></td>
              <td>
                <button class="btn-secondary" onclick="syncProviderBalance(1, this)" style="padding: 6px 12px; font-size: 12px;">
                  <i data-lucide="refresh-cw"></i> Sync
                </button>
              </td>
            </tr>
            <tr>
              <td>#2</td>
              <td><strong>TurboPanel Provider</strong></td>
              <td><code>https://turbopanel.net/api/v2</code></td>
              <td><strong id="prov-bal-2" style="color: #10b981;">$1,250.00 USD</strong></td>
              <td><span class="badge badge-success">Connected</span></td>
              <td><span class="badge badge-success">Active</span></td>
              <td>
                <button class="btn-secondary" onclick="syncProviderBalance(2, this)" style="padding: 6px 12px; font-size: 12px;">
                  <i data-lucide="refresh-cw"></i> Sync
                </button>
              </td>
            </tr>
            <tr>
              <td>#3</td>
              <td><strong>FastSocial Nexus</strong></td>
              <td><code>https://nexus-smm.com/api/v2</code></td>
              <td><strong id="prov-bal-3" style="color: #64748b;">$84.15 USD</strong></td>
              <td><span class="badge badge-warning">Pending</span></td>
              <td><span class="badge badge-danger">Disabled</span></td>
              <td>
                <button class="btn-secondary" onclick="toggleProviderStatus(3, 'disabled')" style="padding: 6px 12px; font-size: 12px;">
                  Enable
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- EXPANDED ADMIN PAGE: ORDERS -->
    <div id="admin-page-orders" class="page-view-container">
      <div class="page-back-header">
        <button class="back-btn-link">
          <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
          <span>Order Management & Sync</span>
        </button>
      </div>

      <div class="data-table-card">
        <table class="app-table">
          <thead>
            <tr>
              <th>Order Code</th>
              <th>User</th>
              <th>Service</th>
              <th>Target Link</th>
              <th>Quantity</th>
              <th>Charge</th>
              <th>Provider Order ID</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>#10254</strong></td>
              <td>Aaris Ali (#1024)</td>
              <td>Instagram Followers</td>
              <td><span style="color: var(--primary-blue);">instagram.com/aarisali_official</span></td>
              <td>1,000</td>
              <td>₹35.00</td>
              <td><code>EXT_ORD_88921</code></td>
              <td><span class="badge badge-warning">Processing</span></td>
            </tr>
            <tr>
              <td><strong>#10253</strong></td>
              <td>Aaris Ali (#1024)</td>
              <td>YouTube Views</td>
              <td><span style="color: var(--primary-blue);">youtube.com/watch?v=smmDemo</span></td>
              <td>5,000</td>
              <td>₹120.00</td>
              <td><code>EXT_ORD_88710</code></td>
              <td><span class="badge badge-success">Completed</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- EXPANDED ADMIN PAGE: SERVICES -->
    <div id="admin-page-services" class="page-view-container">
      <div class="page-back-header">
        <button class="back-btn-link">
          <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
          <span>Services & Provider Mapping</span>
        </button>
      </div>

      <div class="data-table-card">
        <table class="app-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Service Name</th>
              <th>Category</th>
              <th>Rate / 1K</th>
              <th>Mapped Provider</th>
              <th>Provider Service ID</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td><strong>Instagram Followers</strong></td>
              <td>Instagram</td>
              <td>₹35.00</td>
              <td>GlobalSMM Prime API</td>
              <td><code>#101</code></td>
              <td><span class="badge badge-success">Active</span></td>
            </tr>
            <tr>
              <td>2</td>
              <td><strong>Instagram Likes</strong></td>
              <td>Instagram</td>
              <td>₹20.00</td>
              <td>GlobalSMM Prime API</td>
              <td><code>#102</code></td>
              <td><span class="badge badge-success">Active</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

  </div>

  <!-- Add Provider Modal -->
  <div id="modal-add-provider" class="modal-overlay">
    <div class="modal-window">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="font-size: 19px; font-weight: 800;">Add New Provider API</h3>
        <button id="btn-close-provider-modal" style="font-size: 20px; color: var(--text-muted);">&times;</button>
      </div>

      <form id="form-add-provider">
        <div style="margin-bottom: 16px;">
          <label class="form-field-label">Provider Name</label>
          <input type="text" id="prov-name" placeholder="e.g. PeakPanel API" required style="width: 100%;">
        </div>

        <div style="margin-bottom: 16px;">
          <label class="form-field-label">API URL</label>
          <input type="url" id="prov-url" placeholder="https://api.provider.com/v2" required style="width: 100%;">
        </div>

        <div style="margin-bottom: 24px;">
          <label class="form-field-label">API Key</label>
          <input type="text" id="prov-key" placeholder="sec_live_..." required style="width: 100%;">
        </div>

        <button type="submit" class="btn-primary">
          Connect & Save Provider
        </button>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="/assets/js/swiper-init.js"></script>
  <script src="/assets/js/animations.js"></script>
  <script src="/assets/js/admin.js"></script>
  <script src="/assets/js/app.js"></script>
</body>
</html>
