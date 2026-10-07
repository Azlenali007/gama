<?php
/**
 * SMM Panel - Admin Module: Services Catalog & Pricing
 * Protected Server-Side
 */
require_once __DIR__ . '/../includes/admin-auth.php';
$db = Database::getConnection();

$services = $db->query("
    SELECT s.*, c.name as category_name, p.name as provider_name 
    FROM services s 
    LEFT JOIN categories c ON s.category_id = c.id 
    LEFT JOIN providers p ON s.provider_id = p.id 
    ORDER BY s.id DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Services Management - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">
    <div class="page-back-header">
      <a href="/admin/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>Services Management</span>
      </a>
      <button class="btn-primary" style="width: auto; padding: 10px 18px;" onclick="showToast('Add service modal ready', 'info')">
        + Add Service
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
            <th>Min / Max</th>
            <th>Mapped Provider</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($services)): ?>
            <tr>
              <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px;">
                <i data-lucide="layers" style="width: 40px; height: 40px; margin-bottom: 8px; color: var(--text-light);"></i>
                <p style="font-weight: 700;">No services created yet</p>
                <span style="font-size: 13px;">Click "+ Add Service" to add social media packages to your catalog.</span>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($services as $s): ?>
              <tr>
                <td><?= (int)$s['id'] ?></td>
                <td><strong><?= htmlspecialchars($s['name']) ?></strong></td>
                <td><?= htmlspecialchars($s['category_name'] ?: 'General') ?></td>
                <td><strong><?= formatCurrency((float)$s['rate_per_1k']) ?></strong></td>
                <td><?= number_format($s['min_quantity']) ?> / <?= number_format($s['max_quantity']) ?></td>
                <td><?= htmlspecialchars($s['provider_name'] ?: 'Manual') ?></td>
                <td><span class="badge badge-success"><?= htmlspecialchars($s['status']) ?></span></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="/assets/js/user.js"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
