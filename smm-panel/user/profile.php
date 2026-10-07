<?php
/**
 * SMM Panel - User Module: Profile & Account Settings
 */
require_once __DIR__ . '/../includes/user-auth.php';
$user = Auth::user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/user.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">
    <div class="page-back-header">
      <a href="/user/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>Profile</span>
      </a>
    </div>

    <!-- Profile Grid (Matches Image 9) -->
    <div class="order-layout-grid">
      <div class="profile-tabs-sidebar">
        <button class="profile-nav-btn active"><i data-lucide="user"></i> Account Info</button>
        <button class="profile-nav-btn"><i data-lucide="lock"></i> Change Password</button>
        <button class="profile-nav-btn"><i data-lucide="mail"></i> Email Verification</button>
        <button class="profile-nav-btn"><i data-lucide="bell"></i> Notifications</button>
      </div>

      <div>
        <h4 style="font-size: 17px; font-weight: 800; margin-bottom: 16px;">Account Information</h4>

        <div class="profile-avatar-banner">
          <div class="welcome-avatar" style="width: 54px; height: 54px;">
            <i data-lucide="user" style="width: 26px; height: 26px;"></i>
          </div>
          <div>
            <div style="font-size: 18px; font-weight: 800;"><?= htmlspecialchars($user['name'] ?? 'Aaris Ali') ?></div>
            <div style="font-size: 13px; color: var(--text-muted); font-weight: 600;">User ID: <?= htmlspecialchars($user['user_id_code'] ?? '#1024') ?></div>
          </div>
        </div>

        <form onsubmit="event.preventDefault(); showToast('Profile changes saved successfully!', 'success');">
          <div class="form-field-group">
            <label class="form-field-label">Email</label>
            <div style="display: flex; align-items: center; gap: 10px;">
              <input type="email" value="<?= htmlspecialchars($user['email'] ?? 'aarisali@gmail.com') ?>" style="flex: 1;">
              <span class="verified-tag">Verified</span>
            </div>
          </div>

          <div class="form-field-group">
            <label class="form-field-label">Phone</label>
            <input type="tel" value="<?= htmlspecialchars($user['phone'] ?? '+91 98765 43210') ?>" style="width: 100%;">
          </div>

          <div class="form-field-group">
            <label class="form-field-label">Joined</label>
            <input type="text" value="10 May 2025" readonly style="width: 100%; background: #f8fafc; color: var(--text-muted);">
          </div>

          <button type="submit" class="btn-primary" style="margin-top: 14px;">
            Save Changes
          </button>
        </form>
      </div>
    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="/assets/js/user.js"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
