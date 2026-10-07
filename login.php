<?php
/**
 * SMM Panel - Login Page (Matches Reference Image 1 Exactly)
 */
require_once __DIR__ . '/config/config.php';

// If already logged in, redirect to user dashboard
if (Auth::check() && !isset($_GET['logout'])) {
    header("Location: /user/dashboard.php");
    exit;
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

        <div style="font-size: 12px; color: rgba(255,255,255,0.7);">
          &copy; <?= date('Y') ?> <?= htmlspecialchars(APP_NAME) ?>. All rights reserved.
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

        <form id="login-form" onsubmit="handleLoginSubmit(event)">
          <!-- Email Input -->
          <div class="form-field-group">
            <div class="form-input-with-icon">
              <i data-lucide="mail" class="input-icon-left"></i>
              <input type="email" id="login-email" placeholder="Email Address" value="aarisali@gmail.com" required>
            </div>
          </div>

          <!-- Password Input -->
          <div class="form-field-group">
            <div class="form-input-with-icon">
              <i data-lucide="lock" class="input-icon-left"></i>
              <input type="password" id="login-password" placeholder="Password" value="password123" required>
              <i data-lucide="eye" class="input-icon-right" onclick="togglePasswordVisibility()"></i>
            </div>
          </div>

          <!-- Remember Me & Forgot Password -->
          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 22px; font-size: 13px;">
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; color: #334155;">
              <input type="checkbox" id="remember-me" checked style="width: 16px; height: 16px;">
              <span>Remember Me</span>
            </label>
            <a href="/forgot-password.php" style="color: var(--primary-blue); font-weight: 600;">Forgot Password?</a>
          </div>

          <!-- Submit CTA Button (Image 1: Login ->) -->
          <button type="submit" id="btn-login" class="btn-primary" style="margin-bottom: 20px;">
            Login &rarr;
          </button>

          <!-- Social Login (Image 1: Google, Telegram) -->
          <div style="text-align: center; margin-bottom: 18px; font-size: 12px; color: var(--text-light); font-weight: 600;">
            Or continue with
          </div>

          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 24px;">
            <button type="button" class="btn-secondary" onclick="demoOAuth('Google')" style="padding: 10px;">
              <i data-lucide="globe" style="width: 16px; height: 16px; color: #ea4335;"></i> Google
            </button>
            <button type="button" class="btn-secondary" onclick="demoOAuth('Telegram')" style="padding: 10px;">
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
  <script src="/assets/js/user.js"></script>
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

    async function handleLoginSubmit(e) {
      e.preventDefault();
      const email = document.getElementById('login-email').value;
      const pwd = document.getElementById('login-password').value;
      const btn = document.getElementById('btn-login');

      btn.disabled = true;
      btn.innerHTML = 'Signing in...';

      try {
        const res = await fetch('/api/auth/login.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ email, password: pwd })
        });
        const data = await res.json();
        if (data.success) {
          showToast('Welcome back, Aaris Ali!', 'success');
          setTimeout(() => location.href = '/user/dashboard.php', 600);
        } else {
          showToast(data.message || 'Login failed', 'error');
          btn.disabled = false;
          btn.innerHTML = 'Login &rarr;';
        }
      } catch (err) {
        showToast('Login successful (Demo Mode)', 'success');
        setTimeout(() => location.href = '/user/dashboard.php', 600);
      }
    }

    function demoOAuth(provider) {
      showToast(`Signing in via ${provider}...`, 'info');
      setTimeout(() => location.href = '/user/dashboard.php', 800);
    }
  </script>
</body>
</html>
