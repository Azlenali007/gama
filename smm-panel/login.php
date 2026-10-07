<?php
/**
 * SMM Panel - User Login Page (Matches Reference Image 1 Exactly)
 * Strict Authentication: password_verify(), CSRF Protection, Rate Limiting
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth.php';

// If already logged in as normal user, redirect to user dashboard
if (Auth::checkUser()) {
    header("Location: /user/dashboard.php");
    exit;
}

$errorMessage = '';
$isRateLimited = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_login'])) {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!CSRF::validateToken($csrfToken)) {
        $errorMessage = 'Session expired. Please try again.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            $errorMessage = 'Please provide both email and password.';
        } elseif (Auth::isRateLimited('user_' . $email)) {
            $errorMessage = 'Too many failed login attempts. Please wait 15 minutes before trying again.';
            $isRateLimited = true;
        } else {
            try {
                $db = Database::getConnection();
                $stmt = $db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
                $stmt->execute([':email' => $email]);
                $user = $stmt->fetch();

                if ($user && password_verify($password, $user['password_hash'])) {
                    if ($user['status'] !== 'active') {
                        $errorMessage = 'Your account has been suspended or is pending review.';
                    } else {
                        Auth::loginUser($user);
                        $redirect = $_GET['redirect'] ?? '/user/dashboard.php';
                        header("Location: " . filter_var($redirect, FILTER_SANITIZE_URL));
                        exit;
                    }
                } else {
                    Auth::recordFailedAttempt('user_' . $email);
                    $errorMessage = 'Invalid email address or password.';
                }
            } catch (Exception $e) {
                $errorMessage = 'Database connection error: ' . htmlspecialchars($e->getMessage());
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
  <title>Login - <?= htmlspecialchars(APP_NAME) ?></title>
  
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/user.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px;">

  <div class="auth-split-wrapper">
    <div class="auth-dual-card">

      <!-- Left Hero Side (Image 1) -->
      <div class="auth-hero-side">
        <div class="brand-badge">
          <div class="brand-icon-box" style="background: rgba(255,255,255,0.2); backdrop-filter: blur(8px);">
            <i data-lucide="bar-chart-2" style="color: #ffffff;"></i>
          </div>
          <div>
            <div class="brand-title" style="color: #ffffff;"><?= htmlspecialchars(APP_NAME) ?></div>
            <div class="brand-subtitle" style="color: rgba(255,255,255,0.8);"><?= htmlspecialchars(APP_TAGLINE) ?></div>
          </div>
        </div>

        <div>
          <h2 class="auth-hero-headline">
            More Followers<br>
            More Engagement<br>
            More Success
          </h2>

          <!-- Floating 3D Social Icons (Image 1) -->
          <div class="social-icons-cloud">
            <div class="floating-social-bubble"><i data-lucide="instagram"></i></div>
            <div class="floating-social-bubble"><i data-lucide="youtube"></i></div>
            <div class="floating-social-bubble"><i data-lucide="send"></i></div>
            <div class="floating-social-bubble"><i data-lucide="video"></i></div>
            <div class="floating-social-bubble"><i data-lucide="facebook"></i></div>
            <div class="floating-social-bubble"><i data-lucide="twitter"></i></div>
          </div>
        </div>

        <div style="font-size: 12px; color: rgba(255,255,255,0.7); display: flex; justify-content: space-between; align-items: center;">
          <span>&copy; <?= date('Y') ?> <?= htmlspecialchars(APP_NAME) ?>.</span>
          <a href="/admin/index.php" style="color: rgba(255,255,255,0.85); font-weight: 700; text-decoration: underline;">Admin Portal</a>
        </div>
      </div>

      <!-- Right Form Side (Image 1) -->
      <div class="auth-form-side">
        <!-- Switch Tabs [Login] [Register] -->
        <div class="auth-tab-switch">
          <button class="auth-switch-btn active" onclick="location.href='/login.php'">Login</button>
          <button class="auth-switch-btn" onclick="location.href='/register.php'">Register</button>
        </div>

        <div style="margin-bottom: 24px;">
          <h3 style="font-size: 24px; font-weight: 900; color: #0f172a; margin-bottom: 6px;">Welcome Back!</h3>
          <p style="font-size: 13px; color: var(--text-muted);">Sign in to your account to continue.</p>
        </div>

        <?php if (!empty($errorMessage)): ?>
          <div style="background: #fee2e2; border: 1px solid #f87171; color: #b91c1c; padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <i data-lucide="alert-circle" style="width: 16px; height: 16px; flex-shrink: 0;"></i>
            <span><?= htmlspecialchars($errorMessage) ?></span>
          </div>
        <?php endif; ?>

        <form method="POST" action="/login.php<?= isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : '' ?>">
          <?= CSRF::inputField() ?>

          <!-- Email Input -->
          <div class="form-field-group">
            <div class="form-input-with-icon">
              <i data-lucide="mail" class="input-icon-left"></i>
              <input type="email" name="email" id="login-email" placeholder="Email Address" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
          </div>

          <!-- Password Input -->
          <div class="form-field-group">
            <div class="form-input-with-icon">
              <i data-lucide="lock" class="input-icon-left"></i>
              <input type="password" name="password" id="login-password" placeholder="Password" required>
              <i data-lucide="eye" class="input-icon-right" onclick="togglePasswordVisibility()"></i>
            </div>
          </div>

          <!-- Remember Me & Forgot Password -->
          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; font-size: 13px;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; color: #334155;">
              <input type="checkbox" name="remember_me" id="remember-me" checked style="width: 16px; height: 16px;">
              <span>Remember Me</span>
            </label>
            <a href="/forgot-password.php" style="color: var(--primary-blue); font-weight: 600;">Forgot Password?</a>
          </div>

          <!-- Submit CTA Button (Image 1: Login ->) -->
          <button type="submit" name="user_login" id="btn-login" class="btn-primary" style="margin-bottom: 20px;">
            Login &rarr;
          </button>

          <!-- Social Login Buttons -->
          <div style="text-align: center; margin-bottom: 18px; font-size: 12px; color: var(--text-light); font-weight: 600;">
            Or continue with
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 24px;">
            <button type="button" class="btn-secondary" onclick="alert('Social OAuth requires OAuth credentials configured in settings.')" style="padding: 10px;">
              <i data-lucide="globe" style="width: 16px; height: 16px; color: #ea4335;"></i> Google
            </button>
            <button type="button" class="btn-secondary" onclick="alert('Telegram authentication requires bot widget configuration.')" style="padding: 10px;">
              <i data-lucide="send" style="width: 16px; height: 16px; color: #0284c7;"></i> Telegram
            </button>
          </div>

          <div style="text-align: center; font-size: 13px; color: var(--text-muted);">
            Don't have an account? <a href="/register.php" style="font-weight: 700; color: var(--primary-blue);">Register</a>
          </div>
        </form>
      </div>

    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script>
    lucide.createIcons();

    function togglePasswordVisibility() {
      const input = document.getElementById('login-password');
      if (input.type === 'password') {
        input.type = 'text';
      } else {
        input.type = 'password';
      }
    }
  </script>
</body>
</html>
