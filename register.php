<?php
/**
 * SMM Panel - User Registration Page
 * Strict Authentication: password_hash(), Server-Side Validation, CSRF
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/auth.php';

if (Auth::checkUser()) {
    header("Location: /user/dashboard.php");
    exit;
}

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_register'])) {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!CSRF::validateToken($csrfToken)) {
        $errorMessage = 'Security session expired. Please refresh and try again.';
    } else {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $confirmPassword = trim($_POST['confirm_password'] ?? '');

        if (empty($name) || empty($email) || empty($password)) {
            $errorMessage = 'Please fill in all required fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMessage = 'Please enter a valid email address.';
        } elseif (strlen($password) < 8) {
            $errorMessage = 'Password must be at least 8 characters long.';
        } elseif ($password !== $confirmPassword) {
            $errorMessage = 'Passwords do not match.';
        } else {
            try {
                $db = Database::getConnection();
                $check = $db->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
                $check->execute([':email' => $email]);
                if ($check->fetch()) {
                    $errorMessage = 'An account with this email address already exists.';
                } else {
                    $userCode = '#' . mt_rand(1000, 9999);
                    $hash = password_hash($password, PASSWORD_DEFAULT);

                    $currencyCode = getSetting('currency_code', 'INR');
                    $stmt = $db->prepare("
                        INSERT INTO users (user_id_code, name, email, phone, password_hash, balance, currency, status, email_verified)
                        VALUES (:code, :name, :email, :phone, :pwd, 0.00, :curr, 'active', 1)
                    ");
                    $stmt->execute([
                        ':code' => $userCode,
                        ':name' => $name,
                        ':email' => $email,
                        ':phone' => $phone ?: null,
                        ':pwd' => $hash,
                        ':curr' => $currencyCode
                    ]);

                    $newId = (int)$db->lastInsertId();

                    // Create Welcome Notification in database
                    createNotification(
                        $newId,
                        'Welcome to ' . getSetting('site_name', APP_NAME) . '!',
                        'Your account has been successfully created. Explore our premium SMM services and add funds to place your first order.',
                        'system',
                        '/user/dashboard.php'
                    );

                    $newUser = [
                        'id' => $newId,
                        'user_id_code' => $userCode,
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'balance' => 0.00,
                        'currency' => $currencyCode,
                        'status' => 'active'
                    ];

                    Auth::loginUser($newUser);
                    header("Location: /user/dashboard.php?registered=1");
                    exit;
                }
            } catch (Exception $e) {
                $errorMessage = 'Database error: ' . htmlspecialchars($e->getMessage());
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
  <title>Register - <?= htmlspecialchars(APP_NAME) ?></title>
  
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/user.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh; padding: 20px;">

  <div class="auth-split-wrapper">
    <div class="auth-dual-card">

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
            Start Growing<br>
            Your Presence<br>
            Instantly
          </h2>

          <div class="social-icons-cloud">
            <div class="floating-social-bubble"><i data-lucide="instagram"></i></div>
            <div class="floating-social-bubble"><i data-lucide="youtube"></i></div>
            <div class="floating-social-bubble"><i data-lucide="send"></i></div>
            <div class="floating-social-bubble"><i data-lucide="video"></i></div>
            <div class="floating-social-bubble"><i data-lucide="facebook"></i></div>
            <div class="floating-social-bubble"><i data-lucide="twitter"></i></div>
          </div>
        </div>

        <div style="font-size: 12px; color: rgba(255,255,255,0.7);">
          &copy; <?= date('Y') ?> <?= htmlspecialchars(APP_NAME) ?>.
        </div>
      </div>

      <div class="auth-form-side">
        <div class="auth-tab-switch">
          <button class="auth-switch-btn" onclick="location.href='/login.php'">Login</button>
          <button class="auth-switch-btn active" onclick="location.href='/register.php'">Register</button>
        </div>

        <div style="margin-bottom: 24px;">
          <h3 style="font-size: 24px; font-weight: 900; color: #0f172a; margin-bottom: 6px;">Create Account</h3>
          <p style="font-size: 13px; color: var(--text-muted);">Join thousands of creators and agencies</p>
        </div>

        <?php if (!empty($errorMessage)): ?>
          <div style="background: #fee2e2; border: 1px solid #f87171; color: #b91c1c; padding: 12px 16px; border-radius: 12px; font-size: 13px; font-weight: 700; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
            <i data-lucide="alert-circle" style="width: 16px; height: 16px; flex-shrink: 0;"></i>
            <span><?= htmlspecialchars($errorMessage) ?></span>
          </div>
        <?php endif; ?>

        <form method="POST" action="/register.php">
          <?= CSRF::inputField() ?>

          <div class="form-field-group">
            <div class="form-input-with-icon">
              <i data-lucide="user" class="input-icon-left"></i>
              <input type="text" name="name" placeholder="Full Name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
            </div>
          </div>

          <div class="form-field-group">
            <div class="form-input-with-icon">
              <i data-lucide="mail" class="input-icon-left"></i>
              <input type="email" name="email" placeholder="Email Address" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>
          </div>

          <div class="form-field-group">
            <div class="form-input-with-icon">
              <i data-lucide="phone" class="input-icon-left"></i>
              <input type="tel" name="phone" placeholder="Phone Number (Optional)" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
            </div>
          </div>

          <div class="form-field-group">
            <div class="form-input-with-icon">
              <i data-lucide="lock" class="input-icon-left"></i>
              <input type="password" name="password" placeholder="Password (min 8 chars)" required>
            </div>
          </div>

          <div class="form-field-group">
            <div class="form-input-with-icon">
              <i data-lucide="check-circle" class="input-icon-left"></i>
              <input type="password" name="confirm_password" placeholder="Confirm Password" required>
            </div>
          </div>

          <button type="submit" name="user_register" class="btn-primary" style="margin-bottom: 20px;">
            Create Free Account &rarr;
          </button>

          <div style="text-align: center; font-size: 13px; color: var(--text-muted);">
            Already have an account? <a href="/login.php" style="font-weight: 700; color: var(--primary-blue);">Login</a>
          </div>
        </form>
      </div>

    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
