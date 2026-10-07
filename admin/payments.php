<?php
/**
 * SMM Panel - Admin Module: Payments
 */
require_once __DIR__ . '/../includes/admin-auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payments - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">
    <div class="page-back-header">
      <a href="/admin/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>Payments Gateway Logs</span>
      </a>
    </div>

    <div class="data-table-card">
      <table class="app-table">
        <thead>
          <tr>
            <th>Txn Code</th>
            <th>User</th>
            <th>Amount</th>
            <th>Gateway</th>
            <th>Payment ID</th>
            <th>Status</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><code>PAY_TXN_99182</code></td>
            <td>Aaris Ali (#1024)</td>
            <td><strong style="color: #10b981;">₹500.00</strong></td>
            <td>Razorpay</td>
            <td><code>pay_Rzp_99182a</code></td>
            <td><span class="badge badge-success">Completed</span></td>
            <td>12 May 2025, 4:12 PM</td>
          </tr>
          <tr>
            <td><code>PAY_TXN_98711</code></td>
            <td>Aaris Ali (#1024)</td>
            <td><strong style="color: #10b981;">₹200.00</strong></td>
            <td>Razorpay</td>
            <td><code>pay_Rzp_98711b</code></td>
            <td><span class="badge badge-success">Completed</span></td>
            <td>10 May 2025, 11:20 AM</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
