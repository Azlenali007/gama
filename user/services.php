<?php
/**
 * SMM Panel - User Module: All Services
 * Protected Server-Side
 */
require_once __DIR__ . '/../includes/user-auth.php';
$db = Database::getConnection();

$services = $db->query("
    SELECT s.*, c.name as category_name 
    FROM services s 
    LEFT JOIN categories c ON s.category_id = c.id 
    WHERE s.status = 'active'
    ORDER BY c.sort_order ASC, s.id ASC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>All Services - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/user.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">
    <div class="page-back-header">
      <a href="/user/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>All Services</span>
      </a>
    </div>

    <div class="services-search-bar">
      <i data-lucide="search" class="services-search-icon"></i>
      <input type="text" class="services-search-input" placeholder="Search services...">
    </div>

    <div class="services-grid-cards">
      <?php if (empty($services)): ?>
        <div style="grid-column: 1 / -1; text-align: center; padding: 40px 20px; background: #fff; border-radius: 20px; border: 1px dashed var(--border-light); color: var(--text-muted);">
          <i data-lucide="layers" style="width: 44px; height: 44px; margin-bottom: 12px; color: var(--text-light);"></i>
          <p style="font-weight: 700; font-size: 15px;">No services available</p>
          <span style="font-size: 13px;">No active services currently in catalog.</span>
        </div>
      <?php else: ?>
        <?php foreach ($services as $srv): ?>
          <a href="/user/new-order.php?service=<?= (int)$srv['id'] ?>" class="service-box-card">
            <div class="service-box-name"><?= htmlspecialchars($srv['name']) ?></div>
            <div class="service-box-price"><?= formatCurrency((float)$srv['rate_per_1k']) ?> <span style="font-size: 12px; color: var(--text-muted); font-weight: 500;">/ 1K</span></div>
            <div style="font-size: 11px; color: var(--text-muted); margin-top: 6px;">Min: <?= (int)$srv['min_quantity'] ?> | Max: <?= (int)$srv['max_quantity'] ?></div>
          </a>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
