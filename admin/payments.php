<?php
/**
 * SMM Panel - Admin Module: Payments
 * Protected Server-Side
 */
$adminPageTitle = 'Payments Logs';
$activeAdminNav = 'payments';

require_once __DIR__ . '/../includes/admin-header.php';
require_once __DIR__ . '/../includes/admin-navbar.php';

$db = Database::getConnection();
$payments = $db->query("
    SELECT p.*, u.name as user_name, u.user_id_code 
    FROM payments p 
    LEFT JOIN users u ON p.user_id = u.id 
    ORDER BY p.id DESC
")->fetchAll();
?>

<div class="admin-layout">
  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
      <h1 style="font-size: 24px; font-weight: 900; color: #0f172a; margin: 0;">Payment Transactions</h1>
      <p style="font-size: 13px; color: var(--text-muted); margin: 4px 0 0 0;">View deposits, Razorpay gateway verifications, and payment statuses.</p>
    </div>
  </div>

  <div class="data-table-card">
    <div class="table-responsive">
      <table class="app-table">
        <thead>
          <tr>
            <th>Txn Code</th>
            <th>Customer</th>
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
                <td><strong style="color: #16a34a;"><?= formatCurrency((float)$p['amount']) ?></strong></td>
                <td><?= htmlspecialchars($p['payment_method']) ?></td>
                <td><code style="font-size: 12px;"><?= htmlspecialchars($p['gateway_payment_id'] ?: 'Pending') ?></code></td>
                <td><span class="badge <?= $p['status'] === 'completed' ? 'badge-success' : 'badge-warning' ?>"><?= htmlspecialchars($p['status']) ?></span></td>
                <td><?= htmlspecialchars(getFormattedDate($p['created_at'])) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
