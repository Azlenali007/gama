<?php
/**
 * SMM Panel - Admin Module: Orders Management
 * Protected Server-Side
 */
require_once __DIR__ . '/../includes/admin-auth.php';
$db = Database::getConnection();

$orders = $db->query("
    SELECT o.*, u.name as user_name, u.user_id_code, s.name as service_name
    FROM orders o
    LEFT JOIN users u ON o.user_id = u.id
    LEFT JOIN services s ON o.service_id = s.id
    ORDER BY o.id DESC
")->fetchAll();
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
                <td><?= htmlspecialchars($o['service_name'] ?: 'Service') ?></td>
                <td><span style="color: var(--primary-blue); font-size: 12px;"><?= htmlspecialchars(substr($o['target_link'], 0, 35)) ?>...</span></td>
                <td><?= number_format($o['quantity']) ?></td>
                <td><?= formatCurrency((float)$o['charge']) ?></td>
                <td><code><?= htmlspecialchars($o['provider_order_id'] ?: 'Pending') ?></code></td>
                <td><span class="badge <?= getStatusBadgeClass($o['status']) ?>"><?= htmlspecialchars($o['status']) ?></span></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
