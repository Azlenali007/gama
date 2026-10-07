<?php
/**
 * SMM Panel - Admin Module: Global System Settings
 * Full Centralized Settings Management & Dynamic Currency
 */
$adminPageTitle = 'Platform Settings';
$activeAdminNav = 'settings';

require_once __DIR__ . '/../includes/admin-header.php';
require_once __DIR__ . '/../includes/admin-navbar.php';

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_settings'])) {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!CSRF::validateToken($csrfToken)) {
        $errorMessage = 'Security token expired. Please refresh and try again.';
    } else {
        $siteName = trim($_POST['site_name'] ?? '');
        $siteTagline = trim($_POST['site_tagline'] ?? '');
        $siteUrl = trim($_POST['site_url'] ?? '');
        $currencySymbol = trim($_POST['currency_symbol'] ?? '');
        $currencyCode = strtoupper(trim($_POST['currency_code'] ?? ''));
        $currencyName = trim($_POST['currency_name'] ?? '');
        $minDeposit = trim($_POST['min_deposit'] ?? '100');
        $maxDeposit = trim($_POST['max_deposit'] ?? '100000');
        $razorpayKey = trim($_POST['razorpay_key_id'] ?? '');
        $razorpaySecret = trim($_POST['razorpay_key_secret'] ?? '');
        $cronKey = trim($_POST['cron_secret_key'] ?? '');

        if (empty($siteName) || empty($currencySymbol) || empty($currencyCode)) {
            $errorMessage = 'Site name, currency symbol, and currency code are required.';
        } else {
            setSetting('site_name', $siteName);
            setSetting('site_tagline', $siteTagline);
            setSetting('site_url', $siteUrl);
            setSetting('currency_symbol', $currencySymbol);
            setSetting('currency_code', $currencyCode);
            setSetting('currency_name', $currencyName);
            setSetting('min_deposit', $minDeposit);
            setSetting('max_deposit', $maxDeposit);
            setSetting('razorpay_key_id', $razorpayKey);
            setSetting('razorpay_key_secret', $razorpaySecret);
            setSetting('cron_secret_key', $cronKey);

            setFlash('success', 'Settings updated successfully! Currency and platform configuration applied.');
            header("Location: /admin/settings.php?saved=1");
            exit;
        }
    }
}

$flash = getFlash();
if ($flash && $flash['type'] === 'success') {
    $successMessage = $flash['message'];
} elseif (isset($_GET['saved'])) {
    $successMessage = 'Settings saved successfully! Currency changes are now active across user and admin portals.';
}
?>

<div class="admin-layout" style="max-width: 900px;">

  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
      <h1 style="font-size: 24px; font-weight: 900; color: #0f172a; margin: 0;">Platform Settings</h1>
      <p style="font-size: 13px; color: var(--text-muted); margin: 4px 0 0 0;">Manage platform identity, currency symbols, payment keys, and scheduled tasks.</p>
    </div>
    <div class="badge badge-primary" style="padding: 6px 14px; font-size: 12px;">
      Active Currency: <?= htmlspecialchars(getCurrencySymbol()) ?> (<?= htmlspecialchars(getCurrencyCode()) ?>)
    </div>
  </div>

  <?php if (!empty($successMessage)): ?>
    <div style="background: #dcfce7; border: 1px solid #86efac; color: #15803d; padding: 14px 18px; border-radius: 16px; margin-bottom: 24px; font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
      <i data-lucide="check-circle" style="width: 20px; height: 20px; flex-shrink: 0;"></i>
      <span><?= htmlspecialchars($successMessage) ?></span>
    </div>
  <?php endif; ?>

  <?php if (!empty($errorMessage)): ?>
    <div style="background: #fee2e2; border: 1px solid #f87171; color: #b91c1c; padding: 14px 18px; border-radius: 16px; margin-bottom: 24px; font-size: 14px; font-weight: 700; display: flex; align-items: center; gap: 10px;">
      <i data-lucide="alert-circle" style="width: 20px; height: 20px; flex-shrink: 0;"></i>
      <span><?= htmlspecialchars($errorMessage) ?></span>
    </div>
  <?php endif; ?>

  <div class="data-table-card" style="padding: 32px 28px;">
    <form method="POST" action="/admin/settings.php">
      <input type="hidden" name="csrf_token" value="<?= CSRF::generateToken() ?>">
      <input type="hidden" name="save_settings" value="1">

      <!-- Section 1: General Platform Branding -->
      <div style="margin-bottom: 28px;">
        <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
          <i data-lucide="globe" style="width: 18px; height: 18px; color: var(--primary-blue);"></i> General Branding
        </h3>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
          <div>
            <label class="form-field-label">Website Name</label>
            <input type="text" name="site_name" value="<?= htmlspecialchars(getSetting('site_name', APP_NAME)) ?>" required style="width: 100%;">
          </div>
          <div>
            <label class="form-field-label">Website Tagline</label>
            <input type="text" name="site_tagline" value="<?= htmlspecialchars(getSetting('site_tagline', APP_TAGLINE)) ?>" style="width: 100%;">
          </div>
        </div>

        <div>
          <label class="form-field-label">Website URL</label>
          <input type="url" name="site_url" value="<?= htmlspecialchars(getSetting('site_url', APP_URL)) ?>" required style="width: 100%;">
        </div>
      </div>

      <hr style="border: 0; border-top: 1px solid var(--border-light); margin: 24px 0;">

      <!-- Section 2: Currency & Monetary Configuration -->
      <div style="margin-bottom: 28px;">
        <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">
          <i data-lucide="coins" style="width: 18px; height: 18px; color: #16a34a;"></i> Currency &amp; Pricing Configuration
        </h3>
        <p style="font-size: 12px; color: var(--text-muted); margin-bottom: 16px;">
          Changes here update all monetary displays, order charges, deposits, and balances across the entire platform.
        </p>

        <div style="display: grid; grid-template-columns: 1fr 1fr 1.5fr; gap: 16px; margin-bottom: 16px;">
          <div>
            <label class="form-field-label">Currency Symbol</label>
            <input type="text" name="currency_symbol" value="<?= htmlspecialchars(getSetting('currency_symbol', '₹')) ?>" required placeholder="₹, $, €, £" style="width: 100%;">
            <span style="font-size: 11px; color: var(--text-muted);">Displayed on cards &amp; balances</span>
          </div>
          <div>
            <label class="form-field-label">Currency Code</label>
            <input type="text" name="currency_code" value="<?= htmlspecialchars(getSetting('currency_code', 'INR')) ?>" required placeholder="INR, USD, EUR" style="width: 100%;">
            <span style="font-size: 11px; color: var(--text-muted);">Standard 3-letter code</span>
          </div>
          <div>
            <label class="form-field-label">Currency Name</label>
            <input type="text" name="currency_name" value="<?= htmlspecialchars(getSetting('currency_name', 'Indian Rupee')) ?>" placeholder="e.g. US Dollar" style="width: 100%;">
            <span style="font-size: 11px; color: var(--text-muted);">Human-friendly currency name</span>
          </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
          <div>
            <label class="form-field-label">Min Deposit Amount</label>
            <input type="number" step="1" name="min_deposit" value="<?= htmlspecialchars(getSetting('min_deposit', '100')) ?>" style="width: 100%;">
          </div>
          <div>
            <label class="form-field-label">Max Deposit Amount</label>
            <input type="number" step="1" name="max_deposit" value="<?= htmlspecialchars(getSetting('max_deposit', '100000')) ?>" style="width: 100%;">
          </div>
        </div>
      </div>

      <hr style="border: 0; border-top: 1px solid var(--border-light); margin: 24px 0;">

      <!-- Section 3: Payment Gateway & Security Keys -->
      <div style="margin-bottom: 28px;">
        <h3 style="font-size: 16px; font-weight: 800; color: #0f172a; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
          <i data-lucide="key" style="width: 18px; height: 18px; color: #f59e0b;"></i> Payment Gateway &amp; Automation Keys
        </h3>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
          <div>
            <label class="form-field-label">Razorpay Key ID</label>
            <input type="text" name="razorpay_key_id" value="<?= htmlspecialchars(getSetting('razorpay_key_id', 'rzp_test_1DP5mmOlF5G5ag')) ?>" style="width: 100%;">
          </div>
          <div>
            <label class="form-field-label">Razorpay Key Secret</label>
            <input type="password" name="razorpay_key_secret" value="<?= htmlspecialchars(getSetting('razorpay_key_secret', 'sec_rzp_mock_live_secret')) ?>" style="width: 100%;">
          </div>
        </div>

        <div>
          <label class="form-field-label">Scheduled Cron Secret Key</label>
          <input type="text" name="cron_secret_key" value="<?= htmlspecialchars(getSetting('cron_secret_key', 'smm_cron_secret_secure_key_2025')) ?>" style="width: 100%;">
          <span style="font-size: 11px; color: var(--text-muted);">Used to authenticate automated requests to <code>/cron/cron.php?key=YOUR_KEY</code></span>
        </div>
      </div>

      <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 32px;">
        <button type="submit" class="btn-primary" style="width: auto; padding: 12px 32px;">
          <i data-lucide="save"></i> Save Settings
        </button>
      </div>
    </form>
  </div>

</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
