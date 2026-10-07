<?php
/**
 * SMM Panel - User Module: Settings Page
 */
require_once __DIR__ . '/../includes/user-auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Settings - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/user.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container" style="max-width: 640px;">
    <div class="page-back-header">
      <a href="/user/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>Settings</span>
      </a>
    </div>

    <div class="data-table-card" style="padding: 24px;">
      <h3 style="font-size: 18px; font-weight: 800; margin-bottom: 20px;">Preferences</h3>

      <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px 0; border-bottom: 1px solid var(--border-light);">
        <div>
          <div style="font-weight: 700; font-size: 15px;">Email Order Updates</div>
          <div style="font-size: 12px; color: var(--text-muted);">Receive receipt and status updates via email</div>
        </div>
        <input type="checkbox" checked style="width: 20px; height: 20px;">
      </div>

      <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px 0; border-bottom: 1px solid var(--border-light);">
        <div>
          <div style="font-weight: 700; font-size: 15px;">2-Factor Authentication</div>
          <div style="font-size: 12px; color: var(--text-muted);">Enhance wallet protection with SMS or Authenticator</div>
        </div>
        <button class="btn-secondary" onclick="showToast('2FA setup initiated', 'info')">Enable</button>
      </div>

      <div style="display: flex; justify-content: space-between; align-items: center; padding: 14px 0;">
        <div>
          <div style="font-weight: 700; font-size: 15px; color: #dc2626;">Sign Out</div>
          <div style="font-size: 12px; color: var(--text-muted);">Log out from current session</div>
        </div>
        <a href="/api/auth/logout.php" class="btn-secondary" style="color: #dc2626;">Log Out</a>
      </div>
    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="/assets/js/user.js"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
