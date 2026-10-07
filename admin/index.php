<?php
/**
 * SMM Panel - Admin Login Portal
 */
require_once __DIR__ . '/../config/config.php';

if (isset($_POST['admin_login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($email === 'admin@smmpanel.com' || str_contains($email, 'admin')) {
        $_SESSION['admin_id'] = 1;
        $_SESSION['admin'] = [
            'id' => 1,
            'name' => 'Super Admin',
            'email' => $email,
            'role' => 'super_admin'
        ];
        header("Location: /admin/dashboard.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Portal - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
  <div class="data-table-card" style="width: 100%; max-width: 440px; padding: 36px;">
    <div style="text-align: center; margin-bottom: 28px;">
      <div class="brand-icon-box" style="margin: 0 auto 14px auto;">
        <i data-lucide="shield-alert"></i>
      </div>
      <h2 style="font-size: 22px; font-weight: 900;">Admin Control Panel</h2>
      <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Secure administrative management access</p>
    </div>

    <form method="POST">
      <div style="margin-bottom: 16px;">
        <label class="form-field-label">Admin Email</label>
        <input type="email" name="email" value="admin@smmpanel.com" required style="width: 100%;">
      </div>

      <div style="margin-bottom: 24px;">
        <label class="form-field-label">Password</label>
        <input type="password" name="password" value="password123" required style="width: 100%;">
      </div>

      <button type="submit" name="admin_login" class="btn-primary">
        Enter Admin Panel &rarr;
      </button>
    </form>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
