<?php
/**
 * SMM Panel - Admin Module: Global System Settings
 */
require_once __DIR__ . '/../includes/admin-auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Settings - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container" style="max-width: 720px;">
    <div class="page-back-header">
      <a href="/admin/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>Global Settings</span>
      </a>
    </div>

    <div class="data-table-card" style="padding: 28px;">
      <form onsubmit="event.preventDefault(); showToast('Settings saved successfully', 'success');">
        <div style="margin-bottom: 18px;">
          <label class="form-field-label">Platform Name</label>
          <input type="text" value="<?= htmlspecialchars(APP_NAME) ?>" style="width: 100%;">
        </div>

        <div style="margin-bottom: 18px;">
          <label class="form-field-label">Currency Symbol</label>
          <input type="text" value="<?= htmlspecialchars(CURRENCY_SYMBOL) ?>" style="width: 100%;">
        </div>

        <div style="margin-bottom: 18px;">
          <label class="form-field-label">Razorpay Key ID</label>
          <input type="text" value="rzp_test_1DP5mmOlF5G5ag" style="width: 100%;">
        </div>

        <div style="margin-bottom: 18px;">
          <label class="form-field-label">Razorpay Secret</label>
          <input type="password" value="sec_rzp_mock_live_secret" style="width: 100%;">
        </div>

        <div style="margin-bottom: 24px;">
          <label class="form-field-label">Scheduled Cron Secret Key</label>
          <input type="text" value="smm_cron_secret_secure_key_2025" style="width: 100%;">
        </div>

        <button type="submit" class="btn-primary">
          Save Configuration
        </button>
      </form>
    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="/assets/js/user.js"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
