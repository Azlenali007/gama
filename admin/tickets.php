<?php
/**
 * SMM Panel - Admin Module: Support Desk & Tickets
 * Protected Server-Side
 */
$adminPageTitle = 'Support Tickets';
$activeAdminNav = 'tickets';

require_once __DIR__ . '/../includes/admin-header.php';
require_once __DIR__ . '/../includes/admin-navbar.php';

$db = Database::getConnection();

// Handle Status Update
if (isset($_GET['action']) && $_GET['action'] === 'close' && isset($_GET['id'])) {
    $tid = (int)$_GET['id'];
    $stmt = $db->prepare("SELECT user_id, ticket_code, subject FROM tickets WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $tid]);
    $t = $stmt->fetch();
    if ($t) {
        $upd = $db->prepare("UPDATE tickets SET status = 'closed', updated_at = NOW() WHERE id = :id");
        $upd->execute([':id' => $tid]);
        createNotification(
            (int)$t['user_id'],
            "Support Ticket Resolved",
            "Your ticket {$t['ticket_code']} ({$t['subject']}) has been marked as resolved.",
            'ticket',
            '/user/tickets.php'
        );
    }
    header("Location: /admin/tickets.php");
    exit;
}

$tickets = $db->query("
    SELECT t.*, u.name as user_name, u.user_id_code 
    FROM tickets t 
    LEFT JOIN users u ON t.user_id = u.id 
    ORDER BY t.id DESC
")->fetchAll();
?>

<div class="admin-layout">
  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
      <h1 style="font-size: 24px; font-weight: 900; color: #0f172a; margin: 0;">Support Helpdesk</h1>
      <p style="font-size: 13px; color: var(--text-muted); margin: 4px 0 0 0;">Customer inquiries, order issues, and ticket conversations.</p>
    </div>
    <span class="badge badge-info" style="font-size: 13px; padding: 6px 14px;"><?= count($tickets) ?> Tickets</span>
  </div>

  <div class="data-table-card">
    <div class="table-responsive">
      <table class="app-table">
        <thead>
          <tr>
            <th>Ticket Code</th>
            <th>Customer</th>
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
                <td><strong><?= htmlspecialchars($t['subject']) ?></strong></td>
                <td><span class="badge badge-secondary"><?= htmlspecialchars(strtoupper($t['department'])) ?></span></td>
                <td><span class="badge <?= getStatusBadgeClass($t['status']) ?>"><?= htmlspecialchars($t['status']) ?></span></td>
                <td><?= htmlspecialchars(getFormattedDate($t['created_at'])) ?></td>
                <td>
                  <?php if ($t['status'] !== 'closed'): ?>
                    <a href="/admin/tickets.php?action=close&id=<?= (int)$t['id'] ?>" class="btn-secondary" style="padding: 6px 12px; font-size: 12px;">
                      Close Ticket
                    </a>
                  <?php else: ?>
                    <span style="font-size: 12px; color: var(--text-light);">Resolved</span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
