<?php
/**
 * SMM Panel - Admin Module: Provider Services Mapping
 */
$adminPageTitle = 'Provider Services Mapping';
$activeAdminNav = 'provider-services';

require_once __DIR__ . '/../includes/admin-header.php';
require_once __DIR__ . '/../includes/admin-navbar.php';

$db = Database::getConnection();
$providerId = (int)($_GET['provider_id'] ?? 1);

$provider = $db->prepare("SELECT * FROM providers WHERE id = :id LIMIT 1");
$provider->execute([':id' => $providerId]);
$prov = $provider->fetch();

$mappings = $db->query("
    SELECT ps.*, p.name as provider_name 
    FROM provider_services ps 
    JOIN providers p ON ps.provider_id = p.id 
    ORDER BY ps.id DESC LIMIT 50
")->fetchAll();
?>

<div class="admin-layout">
  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
      <h1 style="font-size: 24px; font-weight: 900; color: #0f172a; margin: 0;">Provider Services Catalog</h1>
      <p style="font-size: 13px; color: var(--text-muted); margin: 4px 0 0 0;">Map remote provider service IDs to internal customer packages.</p>
    </div>
    <span class="badge badge-primary" style="font-size: 13px; padding: 6px 14px;">
      <?= htmlspecialchars($prov['name'] ?? 'All Providers') ?>
    </span>
  </div>

  <div class="data-table-card">
    <div class="table-responsive">
      <table class="app-table">
        <thead>
          <tr>
            <th>Provider</th>
            <th>Remote Service ID</th>
            <th>Remote Service Name</th>
            <th>Provider Rate</th>
            <th>Min / Max</th>
            <th>Sync Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($mappings)): ?>
            <tr>
              <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 40px;">
                <i data-lucide="refresh-cw" style="width: 40px; height: 40px; margin-bottom: 8px; color: var(--text-light);"></i>
                <p style="font-weight: 700;">No services imported yet</p>
                <span style="font-size: 13px;">Add an API provider with a valid API key, then run service synchronization.</span>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($mappings as $m): ?>
              <tr>
                <td><?= htmlspecialchars($m['provider_name']) ?></td>
                <td><code><?= htmlspecialchars($m['remote_service_id']) ?></code></td>
                <td><strong><?= htmlspecialchars($m['name']) ?></strong></td>
                <td>$<?= number_format((float)$m['rate'], 4) ?></td>
                <td><?= number_format($m['min']) ?> - <?= number_format($m['max']) ?></td>
                <td><span class="badge badge-success"><?= htmlspecialchars($m['sync_status']) ?></span></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
