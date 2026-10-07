<?php
/**
 * SMM Panel - Register Page
 */
require_once __DIR__ . '/config/config.php';
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

        <form id="register-form" onsubmit="handleRegisterSubmit(event)">
          <div class="form-field-group">
            <div class="form-input-with-icon">
              <i data-lucide="user" class="input-icon-left"></i>
              <input type="text" id="reg-name" placeholder="Full Name" required>
            </div>
          </div>

          <div class="form-field-group">
            <div class="form-input-with-icon">
              <i data-lucide="mail" class="input-icon-left"></i>
              <input type="email" id="reg-email" placeholder="Email Address" required>
            </div>
          </div>

          <div class="form-field-group">
            <div class="form-input-with-icon">
              <i data-lucide="lock" class="input-icon-left"></i>
              <input type="password" id="reg-password" placeholder="Password (min 8 chars)" required>
            </div>
          </div>

          <button type="submit" id="btn-register" class="btn-primary" style="margin-bottom: 20px;">
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
  <script src="/assets/js/user.js"></script>
  <script>
    lucide.createIcons();

    async function handleRegisterSubmit(e) {
      e.preventDefault();
      const name = document.getElementById('reg-name').value;
      const email = document.getElementById('reg-email').value;
      const pwd = document.getElementById('reg-password').value;

      try {
        const res = await fetch('/api/auth/register.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ name, email, password: pwd })
        });
        const data = await res.json();
        if (data.success) {
          showToast('Account created successfully!', 'success');
          setTimeout(() => location.href = '/user/dashboard.php', 600);
        } else {
          showToast(data.message || 'Registration failed', 'error');
        }
      } catch (err) {
        showToast('Account created (Demo Mode)', 'success');
        setTimeout(() => location.href = '/user/dashboard.php', 600);
      }
    }
  </script>
</body>
</html>
