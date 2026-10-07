<?php
/**
 * SMM Panel - Admin Module: Transactions Master Ledger
 * Protected Server-Side
 */
require_once __DIR__ . '/../includes/admin-auth.php';
$db = Database::getConnection();

$transactions = $db->query("
    SELECT t.*, u.name as user_name, u.user_id_code 
    FROM transactions t 
    LEFT JOIN users u ON t.user_id = u.id 
    ORDER BY t.id DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transactions Ledger - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">
    <div class="page-back-header">
      <a href="/admin/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>Global Transactions Ledger</span>
      </a>
    </div>

    <div class="data-table-card">
      <table class="app-table">
        <thead>
          <tr>
            <th>Txn ID</th>
            <th>User</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Description</th>
            <th>Balance After</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($transactions)): ?>
            <tr>
              <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px;">
                <i data-lucide="receipt" style="width: 40px; height: 40px; margin-bottom: 8px; color: var(--text-light);"></i>
                <p style="font-weight: 700;">No transactions recorded yet</p>
                <span style="font-size: 13px;">Platform credit/debit movements will show here.</span>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($transactions as $t): ?>
              <tr>
                <td>#<?= (int)$t['id'] ?></td>
                <td><?= htmlspecialchars($t['user_name'] ?: 'User') ?> (<?= htmlspecialchars($t['user_id_code'] ?: '#') ?>)</td>
                <td><span class="badge <?= $t['direction'] === 'credit' ? 'badge-success' : 'badge-danger' ?>"><?= htmlspecialchars($t['type']) ?></span></td>
                <td><strong class="<?= $t['direction'] === 'credit' ? 'txn-amount-credit' : 'txn-amount-debit' ?>"><?= $t['direction'] === 'credit' ? '+' : '-' ?> <?= formatCurrency((float)$t['amount']) ?></strong></td>
                <td><?= htmlspecialchars($t['title']) ?> (<?= htmlspecialchars($t['description'] ?: '') ?>)</td>
                <td><?= formatCurrency((float)$t['balance_after']) ?></td>
                <td><?= getFormattedDate($t['created_at']) ?></td>
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
