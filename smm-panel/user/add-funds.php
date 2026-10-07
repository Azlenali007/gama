<?php
/**
 * SMM Panel - User Module: Add Funds Page
 */
require_once __DIR__ . '/../includes/user-auth.php';
$user = Auth::user();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Funds - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/user.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container" style="max-width: 600px;">
    <div class="page-back-header">
      <a href="/user/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>Add Funds</span>
      </a>
    </div>

    <!-- Current Balance Card (Image 4) -->
    <div class="funds-current-card">
      <div>
        <div class="balance-label" style="text-align: left;">Current Balance</div>
        <div class="funds-current-val live-user-balance">
          <?= formatCurrency((float)($user['balance'] ?? 850.50)) ?>
        </div>
      </div>
      <div class="brand-icon-box" style="width: 56px; height: 56px; border-radius: 18px;">
        <i data-lucide="wallet" style="width: 28px; height: 28px;"></i>
      </div>
    </div>

    <!-- Select Amount (Image 4) -->
    <label class="form-field-label" style="font-size: 15px; margin-bottom: 12px;">Select Amount</label>
    <div class="amount-chips-grid">
      <div class="amount-chip" data-amount="100">₹100</div>
      <div class="amount-chip selected" data-amount="200">₹200</div>
      <div class="amount-chip" data-amount="500">₹500</div>
      <div class="amount-chip" data-amount="1000">₹1,000</div>
      <div class="amount-chip" data-amount="2000">₹2,000</div>
      <div class="amount-chip" data-amount="5000">Other</div>
    </div>

    <!-- Payment Method (Image 4) -->
    <label class="form-field-label" style="font-size: 15px; margin-bottom: 12px;">Payment Method</label>
    <div class="payment-method-selector">
      <div style="display: flex; align-items: center; gap: 14px;">
        <div class="razorpay-badge-brand">Razorpay</div>
        <span style="font-size: 12px; color: var(--text-muted); font-weight: 600;">Secure & Fast UPI / Cards</span>
      </div>
      <i data-lucide="chevron-right" style="color: var(--text-light);"></i>
    </div>

    <button type="button" class="btn-primary" id="btn-pay-now-funds" onclick="triggerAddFundsPayment()">
      Pay Now ₹200 &rarr;
    </button>

    <div class="secure-encryption-note">
      <i data-lucide="lock" style="width: 14px; height: 14px;"></i>
      <span>Your payment is secure and encrypted</span>
    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="/assets/js/user.js"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
