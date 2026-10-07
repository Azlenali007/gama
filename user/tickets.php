<?php
/**
 * SMM Panel - User Module: Support Tickets
 * Protected Server-Side
 */
require_once __DIR__ . '/../includes/user-auth.php';
$user = Auth::user();
$db = Database::getConnection();

$statusFilter = trim($_GET['status'] ?? 'all');
$sql = "SELECT * FROM tickets WHERE user_id = :uid";
$params = [':uid' => $user['id']];
if ($statusFilter !== 'all' && !empty($statusFilter)) {
    $sql .= " AND status = :status";
    $params[':status'] = $statusFilter;
}
$sql .= " ORDER BY id DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$tickets = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Support - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/user.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">
    <div class="page-back-header">
      <a href="/user/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>Support Tickets</span>
      </a>
    </div>

    <div class="filter-tabs-row">
      <a href="?status=all" class="filter-pill-btn <?= $statusFilter === 'all' ? 'active' : '' ?>">All</a>
      <a href="?status=open" class="filter-pill-btn <?= $statusFilter === 'open' ? 'active' : '' ?>">Open</a>
      <a href="?status=in_progress" class="filter-pill-btn <?= $statusFilter === 'in_progress' ? 'active' : '' ?>">In Progress</a>
      <a href="?status=closed" class="filter-pill-btn <?= $statusFilter === 'closed' ? 'active' : '' ?>">Closed</a>
    </div>

    <div>
      <?php if (empty($tickets)): ?>
        <div style="text-align: center; padding: 40px 20px; background: #fff; border-radius: 20px; border: 1px dashed var(--border-light); color: var(--text-muted);">
          <i data-lucide="life-buoy" style="width: 44px; height: 44px; margin-bottom: 12px; color: var(--text-light);"></i>
          <p style="font-weight: 700; font-size: 15px;">No tickets yet</p>
          <span style="font-size: 13px;">You have no customer support requests open.</span>
        </div>
      <?php else: ?>
        <?php foreach ($tickets as $t): ?>
          <div class="ticket-row-card">
            <div>
              <div style="font-weight: 800; font-size: 15px;">
                <span style="color: var(--text-light);"><?= htmlspecialchars($t['ticket_code']) ?></span> <?= htmlspecialchars($t['subject']) ?>
              </div>
              <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;"><?= getFormattedDate($t['created_at']) ?></div>
            </div>
            <span class="badge <?= getStatusBadgeClass($t['status']) ?>"><?= htmlspecialchars($t['status']) ?></span>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
