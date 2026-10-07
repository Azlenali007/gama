<?php
/**
 * SMM Panel - User Module: Notifications Page
 * Secure: Strictly filters by authenticated user_id
 */
require_once __DIR__ . '/../includes/user-auth.php';
require_once __DIR__ . '/../includes/functions.php';

$user = Auth::user();
$userId = (int)$user['id'];

// Handle Actions: Mark As Read / Mark All As Read
$action = $_POST['action'] ?? ($_GET['action'] ?? '');
$targetId = (int)($_POST['id'] ?? ($_GET['id'] ?? 0));

if ($action === 'read' && $targetId > 0) {
    markNotificationAsRead($targetId, $userId);
    header("Location: /user/notifications.php");
    exit;
} elseif ($action === 'read_all') {
    markAllNotificationsAsRead($userId);
    header("Location: /user/notifications.php");
    exit;
}

$unreadCount = getUnreadNotificationCount($userId);
$notifications = getUserNotifications($userId, 50);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notifications - <?= htmlspecialchars(getSetting('site_name', APP_NAME)) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/user.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
  <style>
    .notif-card-item {
      background: #ffffff;
      border: 1px solid var(--border-light);
      border-radius: 20px;
      padding: 18px 20px;
      margin-bottom: 12px;
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 16px;
      transition: var(--transition-smooth);
      box-shadow: var(--shadow-sm);
    }
    .notif-card-item:hover {
      box-shadow: var(--shadow-md);
      transform: translateY(-2px);
    }
    .notif-card-item.unread {
      border-left: 4px solid var(--primary-blue);
      background: #f8fbff;
    }
    .notif-icon-box {
      width: 44px;
      height: 44px;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }
    .notif-icon-box.info { background: #e0f2fe; color: #0284c7; }
    .notif-icon-box.order { background: #fef3c7; color: #d97706; }
    .notif-icon-box.wallet { background: #dcfce7; color: #16a34a; }
    .notif-icon-box.ticket { background: #f3e8ff; color: #9333ea; }
    .notif-icon-box.system { background: #fee2e2; color: #dc2626; }
    .notif-title {
      font-weight: 800;
      font-size: 15px;
      color: #0f172a;
      margin-bottom: 4px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .unread-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: var(--primary-blue);
      display: inline-block;
    }
    .notif-message {
      font-size: 13px;
      color: var(--text-muted);
      line-height: 1.5;
    }
    .notif-meta {
      font-size: 11px;
      color: var(--text-light);
      margin-top: 6px;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .notif-action-btn {
      background: none;
      border: none;
      color: var(--primary-blue);
      font-size: 11px;
      font-weight: 700;
      cursor: pointer;
      text-decoration: none;
      padding: 0;
    }
    .notif-action-btn:hover {
      text-decoration: underline;
    }
    .header-bar-actions {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .mark-all-btn {
      background: #f1f5f9;
      color: #334155;
      font-size: 12px;
      font-weight: 700;
      padding: 8px 14px;
      border-radius: 9999px;
      border: 1px solid var(--border-light);
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 6px;
      transition: var(--transition-smooth);
    }
    .mark-all-btn:hover {
      background: #e2e8f0;
      color: #0f172a;
    }
  </style>
</head>
<body>
  <div class="app-container" style="max-width: 800px;">
    <div class="page-back-header">
      <a href="/user/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>Notifications</span>
      </a>
      <div class="header-bar-actions">
        <?php if ($unreadCount > 0): ?>
          <a href="/user/notifications.php?action=read_all" class="mark-all-btn">
            <i data-lucide="check-check" style="width: 14px; height: 14px;"></i> Mark All Read
          </a>
        <?php endif; ?>
        <span class="badge <?= $unreadCount > 0 ? 'badge-primary' : 'badge-secondary' ?>">
          <?= $unreadCount ?> Unread
        </span>
      </div>
    </div>

    <div class="notifications-container">
      <?php if (empty($notifications)): ?>
        <div class="empty-state-box">
          <i data-lucide="bell-off"></i>
          <p>No Notifications Yet</p>
          <span>Your order updates, wallet transactions, and system messages will appear here.</span>
        </div>
      <?php else: ?>
        <?php foreach ($notifications as $n): ?>
          <?php
            $typeClass = $n['type'] ?? 'info';
            $icon = match($n['type']) {
                'order' => 'shopping-cart',
                'wallet' => 'wallet',
                'ticket' => 'message-square',
                'system' => 'shield-check',
                default => 'bell'
            };
          ?>
          <div class="notif-card-item <?= $n['is_read'] ? '' : 'unread' ?>">
            <div style="display: flex; gap: 14px; align-items: flex-start; flex: 1;">
              <div class="notif-icon-box <?= htmlspecialchars($typeClass) ?>">
                <i data-lucide="<?= htmlspecialchars($icon) ?>"></i>
              </div>
              <div style="flex: 1;">
                <div class="notif-title">
                  <span><?= htmlspecialchars($n['title']) ?></span>
                  <?php if (!$n['is_read']): ?>
                    <span class="unread-dot" title="Unread"></span>
                  <?php endif; ?>
                </div>
                <div class="notif-message"><?= htmlspecialchars($n['message']) ?></div>
                <div class="notif-meta">
                  <span><?= htmlspecialchars(getFormattedDate($n['created_at'])) ?></span>
                  <?php if (!$n['is_read']): ?>
                    <a href="/user/notifications.php?action=read&id=<?= (int)$n['id'] ?>" class="notif-action-btn">
                      Mark as read
                    </a>
                  <?php endif; ?>
                  <?php if (!empty($n['link'])): ?>
                    <a href="<?= htmlspecialchars($n['link']) ?>" class="notif-action-btn">
                      View details &rarr;
                    </a>
                  <?php endif; ?>
                </div>
              </div>
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
