<?php
/**
 * SMM Panel - User Module: Orders History
 * Protected Server-Side
 */
require_once __DIR__ . '/../includes/user-auth.php';
$user = Auth::user();
$db = Database::getConnection();

$statusFilter = trim($_GET['status'] ?? 'all');
$sql = "
    SELECT o.*, s.name as service_name, c.name as category_name, c.icon as category_icon
    FROM orders o
    LEFT JOIN services s ON o.service_id = s.id
    LEFT JOIN categories c ON s.category_id = c.id
    WHERE o.user_id = :uid
";
$params = [':uid' => $user['id']];
if ($statusFilter !== 'all' && !empty($statusFilter)) {
    $sql .= " AND o.status = :status";
    $params[':status'] = $statusFilter;
}
$sql .= " ORDER BY o.id DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Orders - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/user.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">
    <div class="page-back-header">
      <a href="/user/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>My Orders</span>
      </a>
      <a href="/user/new-order.php" class="btn-primary" style="width: auto; padding: 10px 18px;">
        + New Order
      </a>
    </div>

    <div class="filter-tabs-row">
      <a href="?status=all" class="filter-pill-btn <?= $statusFilter === 'all' ? 'active' : '' ?>">All</a>
      <a href="?status=processing" class="filter-pill-btn <?= $statusFilter === 'processing' ? 'active' : '' ?>">Processing</a>
      <a href="?status=completed" class="filter-pill-btn <?= $statusFilter === 'completed' ? 'active' : '' ?>">Completed</a>
      <a href="?status=cancelled" class="filter-pill-btn <?= $statusFilter === 'cancelled' ? 'active' : '' ?>">Cancelled</a>
    </div>

    <div class="orders-list-wrap">
      <?php if (empty($orders)): ?>
        <div style="text-align: center; padding: 40px 20px; background: #fff; border-radius: 20px; border: 1px dashed var(--border-light); color: var(--text-muted);">
          <i data-lucide="package-open" style="width: 44px; height: 44px; margin-bottom: 12px; color: var(--text-light);"></i>
          <p style="font-weight: 700; font-size: 15px;">No orders yet</p>
          <span style="font-size: 13px;">You have not placed any orders matching this filter.</span>
        </div>
      <?php else: ?>
        <?php foreach ($orders as $ord): ?>
          <div class="order-card-row">
            <div class="order-info-group">
              <div class="order-social-icon"><i data-lucide="<?= htmlspecialchars($ord['category_icon'] ?: 'package') ?>"></i></div>
              <div>
                <div class="order-meta-title"><?= htmlspecialchars($ord['service_name'] ?: 'Service') ?></div>
                <div class="order-meta-sub"><?= number_format($ord['quantity']) ?> &bull; <?= formatCurrency((float)$ord['charge']) ?></div>
              </div>
            </div>
            <div style="text-align: right;">
              <span class="badge <?= getStatusBadgeClass($ord['status']) ?>"><?= htmlspecialchars($ord['status']) ?></span>
              <div class="order-meta-id" style="margin-top: 4px;"><?= htmlspecialchars($ord['order_code']) ?></div>
              <div style="font-size: 11px; color: var(--text-light); margin-top: 2px;"><?= getFormattedDate($ord['created_at']) ?></div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
