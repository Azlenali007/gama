<?php
/**
 * SMM Panel - Admin Module: Transactions Master Ledger
 * Protected Server-Side
 */
$adminPageTitle = 'Transactions Ledger';
$activeAdminNav = 'transactions';

require_once __DIR__ . '/../includes/admin-header.php';
require_once __DIR__ . '/../includes/admin-navbar.php';

$db = Database::getConnection();
$transactions = $db->query("
    SELECT t.*, u.name as user_name, u.user_id_code 
    FROM transactions t 
    LEFT JOIN users u ON t.user_id = u.id 
    ORDER BY t.id DESC
")->fetchAll();
?>

<div class="admin-layout">
  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
      <h1 style="font-size: 24px; font-weight: 900; color: #0f172a; margin: 0;">Transactions Ledger</h1>
      <p style="font-size: 13px; color: var(--text-muted); margin: 4px 0 0 0;">Audit wallet balance debits, credits, and order deductions.</p>
    </div>
  </div>

  <div class="data-table-card">
    <div class="table-responsive">
      <table class="app-table">
        <thead>
          <tr>
            <th>Txn ID</th>
            <th>Customer</th>
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
                <td>
                  <span class="badge <?= $t['direction'] === 'credit' ? 'badge-success' : 'badge-danger' ?>">
                    <?= htmlspecialchars(strtoupper($t['direction'])) ?>
                  </span>
                </td>
                <td>
                  <strong style="color: <?= $t['direction'] === 'credit' ? '#16a34a' : '#dc2626' ?>;">
                    <?= $t['direction'] === 'credit' ? '+' : '-' ?><?= formatCurrency((float)$t['amount']) ?>
                  </strong>
                </td>
                <td><?= htmlspecialchars($t['description'] ?: $t['title']) ?></td>
                <td><strong><?= formatCurrency((float)$t['balance_after']) ?></strong></td>
                <td><?= htmlspecialchars(getFormattedDate($t['created_at'])) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
