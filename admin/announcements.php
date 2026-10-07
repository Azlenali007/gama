<?php
/**
 * SMM Panel - Admin Module: Announcements
 */
$adminPageTitle = 'Announcements & News';
$activeAdminNav = 'announcements';

require_once __DIR__ . '/../includes/admin-header.php';
require_once __DIR__ . '/../includes/admin-navbar.php';

$db = Database::getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_announcement'])) {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $type = $_POST['type'] ?? 'info';

    if (!empty($title) && !empty($content)) {
        $stmt = $db->prepare("INSERT INTO announcements (title, content, type, is_active) VALUES (:title, :content, :type, 1)");
        $stmt->execute([':title' => $title, ':content' => $content, ':type' => $type]);

        // Broadcast notification to active users
        try {
            $userRows = $db->query("SELECT id FROM users WHERE status = 'active'")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($userRows as $ur) {
                createNotification(
                    (int)$ur['id'],
                    $title,
                    substr($content, 0, 200),
                    'system',
                    '/user/dashboard.php'
                );
            }
        } catch (Exception $e) {}

        setFlash('success', 'Announcement published and broadcasted to users successfully!');
        header("Location: /admin/announcements.php");
        exit;
    }
}

$announcements = $db->query("SELECT * FROM announcements ORDER BY id DESC")->fetchAll();
?>

<div class="admin-layout">
  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
      <h1 style="font-size: 24px; font-weight: 900; color: #0f172a; margin: 0;">Platform Announcements</h1>
      <p style="font-size: 13px; color: var(--text-muted); margin: 4px 0 0 0;">Broadcast notices, service maintenance alerts, and offers to users.</p>
    </div>
    <button class="btn-primary" style="width: auto; padding: 10px 18px;" onclick="document.getElementById('modal-post-announce').classList.add('active')">
      <i data-lucide="plus"></i> Post Announcement
    </button>
  </div>

  <div class="data-table-card">
    <div class="table-responsive">
      <table class="app-table">
        <thead>
          <tr>
            <th>Title</th>
            <th>Type</th>
            <th>Content Snippet</th>
            <th>Created</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($announcements)): ?>
            <tr>
              <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 40px;">
                <i data-lucide="bell" style="width: 40px; height: 40px; margin-bottom: 8px; color: var(--text-light);"></i>
                <p style="font-weight: 700;">No announcements published yet</p>
                <span style="font-size: 13px;">Click "+ Post Announcement" to broadcast news to all customers.</span>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($announcements as $a): ?>
              <tr>
                <td><strong><?= htmlspecialchars($a['title']) ?></strong></td>
                <td><span class="badge badge-info"><?= htmlspecialchars(strtoupper($a['type'])) ?></span></td>
                <td><?= htmlspecialchars(substr($a['content'], 0, 75)) ?>...</td>
                <td><?= htmlspecialchars(getFormattedDate($a['created_at'])) ?></td>
                <td><span class="badge <?= $a['is_active'] ? 'badge-success' : 'badge-danger' ?>"><?= $a['is_active'] ? 'Live' : 'Hidden' ?></span></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal: Post Announcement -->
<div class="modal-overlay" id="modal-post-announce">
  <div class="modal-window">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <h3 style="font-size: 18px; font-weight: 900; margin: 0;">Broadcast Announcement</h3>
      <button style="background:none; border:none; color:#64748b; cursor:pointer;" onclick="document.getElementById('modal-post-announce').classList.remove('active')">
        <i data-lucide="x"></i>
      </button>
    </div>
    <form method="POST" action="/admin/announcements.php">
      <input type="hidden" name="post_announcement" value="1">
      <div style="margin-bottom: 14px;">
        <label class="form-field-label">Title</label>
        <input type="text" name="title" required placeholder="e.g. New YouTube Services Added" style="width: 100%;">
      </div>
      <div style="margin-bottom: 14px;">
        <label class="form-field-label">Type</label>
        <select name="type" style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid var(--border-light);">
          <option value="info">Information (Blue)</option>
          <option value="success">Success / New Feature (Green)</option>
          <option value="warning">Maintenance / Notice (Yellow)</option>
          <option value="promo">Promo / Discount (Purple)</option>
        </select>
      </div>
      <div style="margin-bottom: 20px;">
        <label class="form-field-label">Content Message</label>
        <textarea name="content" required rows="4" placeholder="Enter notice details for users" style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid var(--border-light);"></textarea>
      </div>
      <div style="display: flex; justify-content: flex-end; gap: 10px;">
        <button type="button" class="btn-secondary" onclick="document.getElementById('modal-post-announce').classList.remove('active')">Cancel</button>
        <button type="submit" class="btn-primary" style="width: auto; padding: 10px 24px;">Publish Now</button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
