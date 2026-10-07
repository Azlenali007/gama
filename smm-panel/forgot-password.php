<?php
/**
 * SMM Panel - Forgot Password
 */
require_once __DIR__ . '/config/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/user.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px;">
  <div class="data-table-card" style="width: 100%; max-width: 440px; padding: 36px;">
    <div style="text-align: center; margin-bottom: 24px;">
      <div class="brand-icon-box" style="margin: 0 auto 14px auto;">
        <i data-lucide="key-round"></i>
      </div>
      <h2 style="font-size: 22px; font-weight: 900;">Reset Password</h2>
      <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Enter your email to receive recovery instructions</p>
    </div>

    <form onsubmit="event.preventDefault(); showToast('Password reset link sent to your email!', 'success'); setTimeout(() => location.href='/login.php', 1200);">
      <div class="form-field-group">
        <label class="form-field-label">Email Address</label>
        <input type="email" placeholder="name@domain.com" required style="width: 100%;">
      </div>

      <button type="submit" class="btn-primary" style="margin-bottom: 16px;">
        Send Reset Link &rarr;
      </button>

      <div style="text-align: center; font-size: 13px;">
        <a href="/login.php" style="color: var(--primary-blue); font-weight: 700;">Back to Login</a>
      </div>
    </form>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="/assets/js/user.js"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
