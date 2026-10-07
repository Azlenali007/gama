<?php
/**
 * SMM Panel - Admin Module: Users Management
 * Protected Server-Side
 */
$adminPageTitle = 'User Accounts';
$activeAdminNav = 'users';

require_once __DIR__ . '/../includes/admin-header.php';
require_once __DIR__ . '/../includes/admin-navbar.php';

$db = Database::getConnection();
$users = $db->query("SELECT * FROM users ORDER BY id DESC")->fetchAll();
?>

<div class="admin-layout">
  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
      <h1 style="font-size: 24px; font-weight: 900; color: #0f172a; margin: 0;">User Accounts</h1>
      <p style="font-size: 13px; color: var(--text-muted); margin: 4px 0 0 0;">View registered users, wallet balances, and account statuses.</p>
    </div>
    <span class="badge badge-info" style="font-size: 13px; padding: 6px 14px;"><?= count($users) ?> Total Registered</span>
  </div>

  <div class="data-table-card">
    <div class="table-responsive">
      <table class="app-table">
        <thead>
          <tr>
            <th>User ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Wallet Balance</th>
            <th>Status</th>
            <th>Registered</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($users)): ?>
            <tr>
              <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px;">
                <i data-lucide="users" style="width: 40px; height: 40px; margin-bottom: 8px; color: var(--text-light);"></i>
                <p style="font-weight: 700;">No registered users yet</p>
                <span style="font-size: 13px;">Customer registrations will appear here.</span>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($users as $u): ?>
              <tr>
                <td><strong><?= htmlspecialchars($u['user_id_code']) ?></strong></td>
                <td><strong><?= htmlspecialchars($u['name']) ?></strong></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['phone'] ?: 'N/A') ?></td>
                <td><strong style="color: #2563eb;"><?= formatCurrency((float)$u['balance']) ?></strong></td>
                <td><span class="badge badge-success"><?= htmlspecialchars($u['status']) ?></span></td>
                <td><?= htmlspecialchars(getFormattedDate($u['created_at'])) ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
