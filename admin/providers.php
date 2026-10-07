<?php
/**
 * SMM Panel - Admin Module: Provider Management
 * Protected Server-Side
 */
require_once __DIR__ . '/../includes/admin-auth.php';
$db = Database::getConnection();

$providers = $db->query("SELECT * FROM providers ORDER BY id DESC")->fetchAll();
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
          <?php if (empty($providers)): ?>
            <tr>
              <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px;">
                <i data-lucide="server" style="width: 40px; height: 40px; margin-bottom: 8px; color: var(--text-light);"></i>
                <p style="font-weight: 700;">No providers connected yet</p>
                <span style="font-size: 13px;">Click "+ Add Provider" above to integrate your first SMM API v2 provider.</span>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($providers as $prov): ?>
              <tr>
                <td>#<?= (int)$prov['id'] ?></td>
                <td><strong><?= htmlspecialchars($prov['name']) ?></strong></td>
                <td><code><?= htmlspecialchars($prov['api_url']) ?></code></td>
                <td><strong id="prov-bal-<?= (int)$prov['id'] ?>" style="color: #10b981;">$<?= number_format((float)$prov['balance'], 2) ?> USD</strong></td>
                <td><span class="badge <?= $prov['api_status'] === 'connected' ? 'badge-success' : 'badge-warning' ?>"><?= htmlspecialchars($prov['api_status']) ?></span></td>
                <td><span class="badge <?= $prov['status'] === 'active' ? 'badge-success' : 'badge-danger' ?>"><?= htmlspecialchars($prov['status']) ?></span></td>
                <td>
                  <div style="display: flex; gap: 8px;">
                    <button class="btn-secondary" onclick="syncProviderBalance(<?= (int)$prov['id'] ?>, this)" style="padding: 6px 10px; font-size: 12px;">
                      <i data-lucide="refresh-cw"></i> Sync
                    </button>
                    <a href="/admin/provider-services.php?id=<?= (int)$prov['id'] ?>" class="btn-secondary" style="padding: 6px 10px; font-size: 12px;">
                      Services
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
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
          <input type="text" id="prov-name" placeholder="e.g. PeakPanel API" required style="width: 100%;">
        </div>

        <div style="margin-bottom: 16px;">
          <label class="form-field-label">API URL (Endpoint)</label>
          <input type="url" id="prov-url" placeholder="https://api.provider.com/v2" required style="width: 100%;">
        </div>

        <div style="margin-bottom: 24px;">
          <label class="form-field-label">API Key (Stored Server-Side)</label>
          <input type="password" id="prov-key" placeholder="sec_live_..." required style="width: 100%;">
        </div>

        <button type="submit" class="btn-primary">
          Connect &amp; Save Provider
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
