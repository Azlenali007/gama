<?php
/**
 * SMM Panel - Admin Module: Provider Management
 * Full Provider Features:
 * - Add, Edit, Delete, Enable/Disable
 * - API URL, API Key, Provider Balance, API Status
 * - Provider Service Mapping, Order Status Sync
 */
require_once __DIR__ . '/../includes/admin-auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Provider Management - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">
    <div class="page-back-header">
      <a href="/admin/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>Provider Management</span>
      </a>
      <div style="display: flex; gap: 10px;">
        <button class="btn-primary" id="btn-open-add-provider" style="width: auto; padding: 10px 18px;">
          + Add Provider
        </button>
        <a href="/cron/cron.php?cron_key=smm_cron_secret_secure_key_2025" target="_blank" class="btn-secondary" style="padding: 10px 14px;">
          <i data-lucide="play"></i> Run Sync Cron
        </a>
      </div>
    </div>

    <div class="data-table-card">
      <div class="data-table-header">
        <div>
          <h3 style="font-size: 18px; font-weight: 800;">Integrated SMM API Providers</h3>
          <p style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">Credentials stored securely server-side. Synchronized via <code>cron/cron.php</code></p>
        </div>
      </div>

      <table class="app-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Provider Name</th>
            <th>API Endpoint</th>
            <th>Live Balance</th>
            <th>API Status</th>
            <th>Status</th>
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
              <div style="display: flex; gap: 8px;">
                <button class="btn-secondary" onclick="syncProviderBalance(1, this)" style="padding: 6px 10px; font-size: 12px;">
                  <i data-lucide="refresh-cw"></i> Sync
                </button>
                <a href="/admin/provider-services.php?id=1" class="btn-secondary" style="padding: 6px 10px; font-size: 12px;">
                  Services
                </a>
              </div>
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
              <div style="display: flex; gap: 8px;">
                <button class="btn-secondary" onclick="syncProviderBalance(2, this)" style="padding: 6px 10px; font-size: 12px;">
                  <i data-lucide="refresh-cw"></i> Sync
                </button>
                <a href="/admin/provider-services.php?id=2" class="btn-secondary" style="padding: 6px 10px; font-size: 12px;">
                  Services
                </a>
              </div>
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
              <button class="btn-secondary" onclick="toggleProviderStatus(3, 'disabled')" style="padding: 6px 10px; font-size: 12px;">
                Enable
              </button>
            </td>
          </tr>
        </tbody>
      </table>
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
          <input type="text" id="prov-name" placeholder="e.g. BulkSMM Official" required style="width: 100%;">
        </div>

        <div style="margin-bottom: 16px;">
          <label class="form-field-label">API URL</label>
          <input type="url" id="prov-url" placeholder="https://api.bulksmm.net/api/v2" required style="width: 100%;">
        </div>

        <div style="margin-bottom: 24px;">
          <label class="form-field-label">API Key (Stored Server-Side)</label>
          <input type="text" id="prov-key" placeholder="sec_live_..." required style="width: 100%;">
        </div>

        <button type="submit" class="btn-primary">
          Connect & Save Provider
        </button>
      </form>
    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="/assets/js/user.js"></script>
  <script src="/assets/js/admin.js"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
