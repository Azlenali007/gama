<?php
/**
 * SMM Panel - User Module: Transactions History
 * Protected Server-Side
 */
require_once __DIR__ . '/../includes/user-auth.php';
$user = Auth::user();
$db = Database::getConnection();

$typeFilter = trim($_GET['type'] ?? 'all');
$sql = "SELECT * FROM transactions WHERE user_id = :uid";
$params = [':uid' => $user['id']];
if ($typeFilter !== 'all' && !empty($typeFilter)) {
    $sql .= " AND type = :type";
    $params[':type'] = $typeFilter;
}
$sql .= " ORDER BY id DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$transactions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transactions - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/user.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">
    <div class="page-back-header">
      <a href="/user/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>Transactions</span>
      </a>
    </div>

    <div class="filter-tabs-row">
      <a href="?type=all" class="filter-pill-btn <?= $typeFilter === 'all' ? 'active' : '' ?>">All</a>
      <a href="?type=add_funds" class="filter-pill-btn <?= $typeFilter === 'add_funds' ? 'active' : '' ?>">Add Funds</a>
      <a href="?type=order_payment" class="filter-pill-btn <?= $typeFilter === 'order_payment' ? 'active' : '' ?>">Orders</a>
      <a href="?type=refund" class="filter-pill-btn <?= $typeFilter === 'refund' ? 'active' : '' ?>">Refunds</a>
    </div>

    <div class="transactions-list-wrap">
      <?php if (empty($transactions)): ?>
        <div style="text-align: center; padding: 40px 20px; background: #fff; border-radius: 20px; border: 1px dashed var(--border-light); color: var(--text-muted);">
          <i data-lucide="receipt" style="width: 44px; height: 44px; margin-bottom: 12px; color: var(--text-light);"></i>
          <p style="font-weight: 700; font-size: 15px;">No transactions yet</p>
          <span style="font-size: 13px;">Wallet balance adjustments and purchases will appear here.</span>
        </div>
      <?php else: ?>
        <?php foreach ($transactions as $txn): ?>
          <div class="txn-card-item">
            <div>
              <div style="font-weight: 800; font-size: 15px;"><?= htmlspecialchars($txn['title']) ?></div>
              <div style="font-size: 12px; color: var(--text-muted);"><?= htmlspecialchars($txn['description'] ?: 'Transaction') ?> &bull; <?= getFormattedDate($txn['created_at']) ?></div>
            </div>
            <div class="<?= $txn['direction'] === 'credit' ? 'txn-amount-credit' : 'txn-amount-debit' ?>">
              <?= $txn['direction'] === 'credit' ? '+' : '-' ?> <?= formatCurrency((float)$txn['amount']) ?>
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
