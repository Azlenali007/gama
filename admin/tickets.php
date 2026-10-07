<?php
/**
 * SMM Panel - Admin Module: Support Desk & Tickets
 * Protected Server-Side
 */
require_once __DIR__ . '/../includes/admin-auth.php';
$db = Database::getConnection();

$tickets = $db->query("
    SELECT t.*, u.name as user_name, u.user_id_code 
    FROM tickets t 
    LEFT JOIN users u ON t.user_id = u.id 
    ORDER BY t.id DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Support Tickets - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">
    <div class="page-back-header">
      <a href="/admin/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>Support Desk</span>
      </a>
      <span class="badge badge-info"><?= count($tickets) ?> Tickets</span>
    </div>

    <div class="data-table-card">
      <table class="app-table">
        <thead>
          <tr>
            <th>Ticket Code</th>
            <th>User</th>
            <th>Subject</th>
            <th>Department</th>
            <th>Status</th>
            <th>Created</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($tickets)): ?>
            <tr>
              <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px;">
                <i data-lucide="life-buoy" style="width: 40px; height: 40px; margin-bottom: 8px; color: var(--text-light);"></i>
                <p style="font-weight: 700;">No support tickets open</p>
                <span style="font-size: 13px;">Customer support requests will show up here.</span>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($tickets as $t): ?>
              <tr>
                <td><strong><?= htmlspecialchars($t['ticket_code']) ?></strong></td>
                <td><?= htmlspecialchars($t['user_name'] ?: 'User') ?> (<?= htmlspecialchars($t['user_id_code'] ?: '#') ?>)</td>
                <td><?= htmlspecialchars($t['subject']) ?></td>
                <td><?= htmlspecialchars($t['department']) ?></td>
                <td><span class="badge <?= getStatusBadgeClass($t['status']) ?>"><?= htmlspecialchars($t['status']) ?></span></td>
                <td><?= getFormattedDate($t['created_at']) ?></td>
                <td>
                  <button class="btn-secondary" onclick="showToast('Ticket reply opened', 'info')" style="padding: 6px 12px; font-size: 12px;">Reply</button>
                </td>
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
