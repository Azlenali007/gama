<?php
/**
 * SMM Panel - Admin Module: Orders Management
 * Protected Server-Side
 */
$adminPageTitle = 'Orders Management';
$activeAdminNav = 'orders';

require_once __DIR__ . '/../includes/admin-header.php';
require_once __DIR__ . '/../includes/admin-navbar.php';

$db = Database::getConnection();
$orders = $db->query("
    SELECT o.*, u.name as user_name, u.user_id_code, s.name as service_name
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    LEFT JOIN services s ON o.service_id = s.id
    ORDER BY o.id DESC
")->fetchAll();
?>

<div class="admin-layout">
  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
      <h1 style="font-size: 24px; font-weight: 900; color: #0f172a; margin: 0;">Orders Management</h1>
      <p style="font-size: 13px; color: var(--text-muted); margin: 4px 0 0 0;">Review orders, delivery statuses, and provider automation.</p>
    </div>
    <a href="/cron/cron.php?key=<?= urlencode(getSetting('cron_secret_key', 'smm_cron_secret_secure_key_2025')) ?>" target="_blank" class="btn-primary" style="width: auto; padding: 10px 18px;">
      <i data-lucide="refresh-cw"></i> Sync with Providers
    </a>
  </div>

  <div class="data-table-card">
    <div class="table-responsive">
      <table class="app-table">
        <thead>
          <tr>
            <th>Order Code</th>
            <th>Customer</th>
            <th>Service</th>
            <th>Target Link</th>
            <th>Quantity</th>
            <th>Charge</th>
            <th>Provider Ref</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($orders)): ?>
            <tr>
              <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 40px;">
                <i data-lucide="shopping-bag" style="width: 40px; height: 40px; margin-bottom: 8px; color: var(--text-light);"></i>
                <p style="font-weight: 700;">No customer orders yet</p>
                <span style="font-size: 13px;">Placed orders will display here with live provider synchronization.</span>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($orders as $o): ?>
              <tr>
                <td><strong><?= htmlspecialchars($o['order_code']) ?></strong></td>
                <td><?= htmlspecialchars($o['user_name'] ?: 'User') ?> (<?= htmlspecialchars($o['user_id_code'] ?: '#') ?>)</td>
                <td><strong><?= htmlspecialchars($o['service_name'] ?: 'Service') ?></strong></td>
                <td><a href="<?= htmlspecialchars($o['target_link']) ?>" target="_blank" style="color: var(--primary-blue); font-size: 13px; max-width: 180px; overflow: hidden; text-overflow: ellipsis; display: inline-block; white-space: nowrap; vertical-align: middle;">Link &nearr;</a></td>
                <td><?= number_format($o['quantity']) ?></td>
                <td><strong><?= formatCurrency((float)$o['charge']) ?></strong></td>
                <td><?= htmlspecialchars($o['provider_order_id'] ?: 'Direct') ?></td>
                <td><span class="badge <?= getStatusBadgeClass($o['status']) ?>"><?= htmlspecialchars($o['status']) ?></span></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
