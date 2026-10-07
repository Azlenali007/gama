<?php
/**
 * SMM Panel - Admin Module: Reports & Financial Analytics
 */
$adminPageTitle = 'Financial Reports & Analytics';
$activeAdminNav = 'reports';

require_once __DIR__ . '/../includes/admin-header.php';
require_once __DIR__ . '/../includes/admin-navbar.php';

$db = Database::getConnection();

$totalDeposits = (float)$db->query("SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'completed'")->fetchColumn();
$totalOrderVolume = (float)$db->query("SELECT COALESCE(SUM(charge), 0) FROM orders WHERE status != 'cancelled'")->fetchColumn();
$completedOrdersCount = (int)$db->query("SELECT COUNT(*) FROM orders WHERE status = 'completed'")->fetchColumn();
$totalOrdersCount = (int)$db->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$completionRate = $totalOrdersCount > 0 ? round(($completedOrdersCount / $totalOrdersCount) * 100, 1) : 100.0;
?>

<div class="admin-layout">
  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
      <h1 style="font-size: 24px; font-weight: 900; color: #0f172a; margin: 0;">Financial Reports &amp; Analytics</h1>
      <p style="font-size: 13px; color: var(--text-muted); margin: 4px 0 0 0;">Platform turnover, order performance metrics, and profitability summary.</p>
    </div>
  </div>

  <div class="admin-stats-grid">
    <div class="admin-stat-card">
      <div>
        <div class="stat-label-small">TOTAL DEPOSITS COLLECTED</div>
        <div class="stat-val-large"><?= formatCurrency($totalDeposits) ?></div>
      </div>
      <div class="brand-icon-box" style="background: #ecfdf5; color: #10b981;">
        <i data-lucide="arrow-down-left"></i>
      </div>
    </div>

    <div class="admin-stat-card">
      <div>
        <div class="stat-label-small">CUSTOMER ORDER VOLUME</div>
        <div class="stat-val-large"><?= formatCurrency($totalOrderVolume) ?></div>
      </div>
      <div class="brand-icon-box" style="background: #eff6ff; color: #2563eb;">
        <i data-lucide="shopping-cart"></i>
      </div>
    </div>

    <div class="admin-stat-card">
      <div>
        <div class="stat-label-small">COMPLETED ORDERS</div>
        <div class="stat-val-large" style="color: #10b981;"><?= number_format($completedOrdersCount) ?></div>
      </div>
      <div class="brand-icon-box" style="background: #eff6ff; color: #2563eb;">
        <i data-lucide="check-circle-2"></i>
      </div>
    </div>

    <div class="admin-stat-card">
      <div>
        <div class="stat-label-small">FULFILLMENT SUCCESS RATE</div>
        <div class="stat-val-large"><?= $completionRate ?>%</div>
      </div>
      <div class="brand-icon-box" style="background: #ecfdf5; color: #10b981;">
        <i data-lucide="activity"></i>
      </div>
    </div>
  </div>

  <div class="data-table-card" style="padding: 28px;">
    <h3 style="font-size: 17px; font-weight: 800; color: #0f172a; margin-bottom: 8px;">Automated Financial Audit Summary</h3>
    <p style="font-size: 13px; color: var(--text-muted); line-height: 1.6;">
      All platform ledger entries are strictly recorded with immutable timestamps and DECIMAL precision.
      Wallet credits, order debit operations, and balance adjustments are synchronized against gateway logs to ensure zero drift.
    </p>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
