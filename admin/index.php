<?php
/**
 * SMM Panel - Dedicated Admin Login Portal
 * Secure Authentication: password_verify(), CSRF, Rate Limiting, No Bypass
 */
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/auth.php';

// If already authenticated as Admin, redirect directly to admin dashboard
if (Auth::checkAdmin()) {
    header("Location: /admin/dashboard.php");
    exit;
}

$errorMessage = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_login'])) {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!CSRF::validateToken($csrfToken)) {
        $errorMessage = 'Security session expired. Please refresh and try again.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            $errorMessage = 'Please enter both your admin email and password.';
        } elseif (Auth::isRateLimited('admin_' . $email)) {
            $errorMessage = 'Too many failed login attempts. Please wait 15 minutes before trying again.';
        } else {
            try {
                $db = Database::getConnection();
                $stmt = $db->prepare("SELECT * FROM admins WHERE (email = :email OR name = :name) AND status = 'active' LIMIT 1");
                $stmt->execute([':email' => $email, ':name' => $email]);
                $admin = $stmt->fetch();

                if ($admin && password_verify($password, $admin['password_hash'])) {
                    Auth::loginAdmin($admin);
                    $redirect = $_GET['redirect'] ?? '/admin/dashboard.php';
                    header("Location: " . filter_var($redirect, FILTER_SANITIZE_URL));
                    exit;
                } else {
                    Auth::recordFailedAttempt('admin_' . $email);
                    $errorMessage = 'Invalid administrative credentials provided.';
                }
            } catch (Exception $e) {
                $errorMessage = 'Database authentication error: ' . htmlspecialchars($e->getMessage());
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Portal Login - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px;">
  <div class="data-table-card" style="width: 100%; max-width: 440px; padding: 36px;">
    <div style="text-align: center; margin-bottom: 28px;">
      <div class="brand-icon-box" style="margin: 0 auto 14px auto; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
        <i data-lucide="shield-alert" style="color: #ffffff;"></i>
      </div>
      <h2 style="font-size: 22px; font-weight: 900;">Admin Control Panel</h2>
      <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">Authorized Administrative Access Only</p>
    </div>

    <?php if (!empty($errorMessage)): ?>
      <div style="background: #fee2e2; border: 1px solid #f87171; color: #b91c1c; padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
        <i data-lucide="alert-circle" style="width: 16px; height: 16px; flex-shrink: 0;"></i>
        <span><?= htmlspecialchars($errorMessage) ?></span>
      </div>
    <?php endif; ?>

    <form method="POST" action="/admin/index.php<?= isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : '' ?>">
      <?= CSRF::inputField() ?>

      <div style="margin-bottom: 16px;">
        <label class="form-field-label">Admin Email / Username</label>
        <div class="form-input-with-icon">
          <i data-lucide="user-check" class="input-icon-left"></i>
          <input type="text" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" placeholder="admin@domain.com" required style="width: 100%;">
        </div>
      </div>

      <div style="margin-bottom: 24px;">
        <label class="form-field-label">Password</label>
        <div class="form-input-with-icon">
          <i data-lucide="lock" class="input-icon-left"></i>
          <input type="password" name="password" placeholder="Enter secure password" required style="width: 100%;">
        </div>
      </div>

      <button type="submit" name="admin_login" class="btn-primary" style="margin-bottom: 16px;">
        Sign In as Administrator &rarr;
      </button>

      <div style="text-align: center; font-size: 13px;">
        <a href="/login.php" style="color: var(--text-muted); font-weight: 600;">Switch to Customer Login</a>
      </div>
    </form>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
