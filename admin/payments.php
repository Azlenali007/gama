<?php
/**
 * SMM Panel - Admin Module: Payments
 * Protected Server-Side
 */
require_once __DIR__ . '/../includes/admin-auth.php';
$db = Database::getConnection();

$payments = $db->query("
    SELECT p.*, u.name as user_name, u.user_id_code 
    FROM payments p 
    LEFT JOIN users u ON p.user_id = u.id 
    ORDER BY p.id DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payments - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">
    <div class="page-back-header">
      <a href="/admin/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>Payments Gateway Logs</span>
      </a>
    </div>

    <div class="data-table-card">
      <table class="app-table">
        <thead>
          <tr>
            <th>Txn Code</th>
            <th>User</th>
            <th>Amount</th>
            <th>Gateway</th>
            <th>Gateway ID</th>
            <th>Status</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($payments)): ?>
            <tr>
              <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px;">
                <i data-lucide="credit-card" style="width: 40px; height: 40px; margin-bottom: 8px; color: var(--text-light);"></i>
                <p style="font-weight: 700;">No payment gateway transactions yet</p>
                <span style="font-size: 13px;">Customer wallet top-ups will be recorded here.</span>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($payments as $p): ?>
              <tr>
                <td><code><?= htmlspecialchars($p['transaction_code']) ?></code></td>
                <td><?= htmlspecialchars($p['user_name'] ?: 'User') ?> (<?= htmlspecialchars($p['user_id_code'] ?: '#') ?>)</td>
                <td><strong style="color: #10b981;"><?= formatCurrency((float)$p['amount']) ?></strong></td>
                <td><?= htmlspecialchars($p['payment_method']) ?></td>
                <td><code><?= htmlspecialchars($p['gateway_payment_id'] ?: 'Pending') ?></code></td>
                <td><span class="badge <?= getStatusBadgeClass($p['status']) ?>"><?= htmlspecialchars($p['status']) ?></span></td>
                <td><?= getFormattedDate($p['created_at']) ?></td>
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
