<?php
/**
 * SMM Panel - Admin Module: Transactions Master Ledger
 */
require_once __DIR__ . '/../includes/admin-auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Transactions Ledger - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">
    <div class="page-back-header">
      <a href="/admin/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>Global Transactions Ledger</span>
      </a>
    </div>

    <div class="data-table-card">
      <table class="app-table">
        <thead>
          <tr>
            <th>Txn ID</th>
            <th>User</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Description</th>
            <th>Balance After</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>#1</td>
            <td>Aaris Ali (#1024)</td>
            <td><span class="badge badge-success">Credit</span></td>
            <td><strong style="color: #10b981;">+ ₹500.00</strong></td>
            <td>Add Funds - Razorpay</td>
            <td>₹850.50</td>
            <td>12 May 2025, 4:12 PM</td>
          </tr>
          <tr>
            <td>#2</td>
            <td>Aaris Ali (#1024)</td>
            <td><span class="badge badge-danger">Debit</span></td>
            <td><strong style="color: #ef4444;">- ₹35.00</strong></td>
            <td>Instagram Followers (#10254)</td>
            <td>₹350.50</td>
            <td>12 May 2025, 4:32 PM</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
