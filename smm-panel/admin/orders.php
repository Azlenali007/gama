<?php
/**
 * SMM Panel - Admin Module: Orders Management
 */
require_once __DIR__ . '/../includes/admin-auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orders Management - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">
    <div class="page-back-header">
      <a href="/admin/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>Orders Management</span>
      </a>
      <a href="/cron/cron.php?cron_key=smm_cron_secret_secure_key_2025" target="_blank" class="btn-primary" style="width: auto; padding: 10px 18px;">
        <i data-lucide="refresh-cw"></i> Sync with Providers
      </a>
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
            <th>Provider ID</th>
            <th>Status</th>
            <th>Actions</th>
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
            <td>
              <button class="btn-secondary" onclick="showToast('Synced order #10254 with provider', 'success')" style="padding: 6px 10px; font-size: 12px;">Sync</button>
            </td>
          </tr>
          <tr>
            <td><strong>#10253</strong></td>
            <td>Aaris Ali (#1024)</td>
            <td>YouTube Views</td>
            <td><span style="color: var(--primary-blue);">youtube.com/watch?v=smmDemoVideo</span></td>
            <td>5,000</td>
            <td>₹120.00</td>
            <td><code>EXT_ORD_88710</code></td>
            <td><span class="badge badge-success">Completed</span></td>
            <td>
              <button class="btn-secondary" onclick="showToast('Order #10253 is completed', 'info')" style="padding: 6px 10px; font-size: 12px;">Details</button>
            </td>
          </tr>
          <tr>
            <td><strong>#10252</strong></td>
            <td>Aaris Ali (#1024)</td>
            <td>Telegram Members</td>
            <td><span style="color: var(--primary-blue);">t.me/techcommunity_in</span></td>
            <td>2,000</td>
            <td>₹90.00</td>
            <td><code>EXT_ORD_88540</code></td>
            <td><span class="badge badge-warning">Processing</span></td>
            <td>
              <button class="btn-secondary" onclick="showToast('Synced order #10252', 'success')" style="padding: 6px 10px; font-size: 12px;">Sync</button>
            </td>
          </tr>
          <tr>
            <td><strong>#10251</strong></td>
            <td>Aaris Ali (#1024)</td>
            <td>Instagram Likes</td>
            <td><span style="color: var(--primary-blue);">instagram.com/p/C_demoPhoto99</span></td>
            <td>1,000</td>
            <td>₹20.00</td>
            <td><code>EXT_ORD_88412</code></td>
            <td><span class="badge badge-success">Completed</span></td>
            <td>
              <button class="btn-secondary" onclick="showToast('Order #10251 is completed', 'info')" style="padding: 6px 10px; font-size: 12px;">Details</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="/assets/js/user.js"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
