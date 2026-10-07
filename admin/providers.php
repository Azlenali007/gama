<?php
/**
 * SMM Panel - Admin Module: Provider Management
 * Protected Server-Side
 */
$adminPageTitle = 'Provider Management';
$activeAdminNav = 'providers';

require_once __DIR__ . '/../includes/admin-header.php';
require_once __DIR__ . '/../includes/admin-navbar.php';

$db = Database::getConnection();
$providers = $db->query("SELECT * FROM providers ORDER BY id DESC")->fetchAll();
?>

<div class="admin-layout">
  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
      <h1 style="font-size: 24px; font-weight: 900; color: #0f172a; margin: 0;">API Providers</h1>
      <p style="font-size: 13px; color: var(--text-muted); margin: 4px 0 0 0;">Manage external SMM API connections (Standard API v2 protocol).</p>
    </div>
    <div style="display: flex; gap: 10px; flex-wrap: wrap;">
      <button class="btn-primary" id="btn-open-add-provider" style="width: auto; padding: 10px 18px;" onclick="document.getElementById('modal-add-provider').classList.add('active')">
        <i data-lucide="plus"></i> Add Provider
      </button>
      <a href="/cron/cron.php?key=<?= urlencode(getSetting('cron_secret_key', 'smm_cron_secret_secure_key_2025')) ?>" target="_blank" class="btn-secondary" style="padding: 10px 14px;">
        <i data-lucide="play"></i> Run Sync Cron
      </a>
    </div>
  </div>

  <div class="data-table-card">
    <div class="table-responsive">
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
            <?php foreach ($providers as $p): ?>
              <tr>
                <td>#<?= (int)$p['id'] ?></td>
                <td><strong><?= htmlspecialchars($p['name']) ?></strong></td>
                <td><code style="font-size: 12px;"><?= htmlspecialchars($p['api_url']) ?></code></td>
                <td><strong style="color: #10b981;">$<?= number_format((float)$p['balance'], 2) ?> USD</strong></td>
                <td><span class="badge <?= $p['api_status'] === 'connected' ? 'badge-success' : 'badge-danger' ?>"><?= htmlspecialchars($p['api_status']) ?></span></td>
                <td><span class="badge <?= $p['status'] === 'active' ? 'badge-success' : 'badge-danger' ?>"><?= htmlspecialchars($p['status']) ?></span></td>
                <td>
                  <div style="display: flex; gap: 8px;">
                    <button class="btn-secondary" style="padding: 6px 12px; font-size: 12px;" onclick="syncProviderBalance(<?= (int)$p['id'] ?>)">
                      <i data-lucide="refresh-cw" style="width: 12px; height: 12px;"></i> Sync
                    </button>
                    <a href="/admin/provider-services.php?provider_id=<?= (int)$p['id'] ?>" class="btn-secondary" style="padding: 6px 12px; font-size: 12px;">
                      Services &rarr;
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
</div>

<!-- Modal: Add Provider -->
<div class="modal-overlay" id="modal-add-provider">
  <div class="modal-window">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <h3 style="font-size: 18px; font-weight: 900; margin: 0;">Add SMM API Provider</h3>
      <button style="background:none; border:none; color:#64748b; cursor:pointer;" onclick="document.getElementById('modal-add-provider').classList.remove('active')">
        <i data-lucide="x"></i>
      </button>
    </div>
    <form id="form-add-provider" onsubmit="event.preventDefault(); submitNewProvider();">
      <div style="margin-bottom: 14px;">
        <label class="form-field-label">Provider Name</label>
        <input type="text" id="prov-name" placeholder="e.g. SMMKings / JustAnotherPanel" required style="width: 100%;">
      </div>
      <div style="margin-bottom: 14px;">
        <label class="form-field-label">API URL Endpoint</label>
        <input type="url" id="prov-url" placeholder="https://provider.com/api/v2" required style="width: 100%;">
      </div>
      <div style="margin-bottom: 20px;">
        <label class="form-field-label">API Key</label>
        <input type="password" id="prov-key" placeholder="Enter API Key from Provider" required style="width: 100%;">
      </div>
      <div style="display: flex; justify-content: flex-end; gap: 10px;">
        <button type="button" class="btn-secondary" onclick="document.getElementById('modal-add-provider').classList.remove('active')">Cancel</button>
        <button type="submit" id="btn-submit-provider" class="btn-primary" style="width: auto; padding: 10px 24px;">Test &amp; Add</button>
      </div>
    </form>
  </div>
</div>

<script src="/assets/js/admin.js"></script>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
