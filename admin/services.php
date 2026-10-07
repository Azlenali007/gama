<?php
/**
 * SMM Panel - Admin Module: Services Catalog & Pricing
 */
require_once __DIR__ . '/../includes/admin-auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Services Management - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">
    <div class="page-back-header">
      <a href="/admin/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>Services Management</span>
      </a>
      <button class="btn-primary" style="width: auto; padding: 10px 18px;" onclick="showToast('Add service modal', 'info')">
        + Add Service
      </button>
    </div>

    <div class="data-table-card">
      <table class="app-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Service Name</th>
            <th>Category</th>
            <th>Rate / 1K</th>
            <th>Min / Max</th>
            <th>Mapped Provider</th>
            <th>Provider Service ID</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>1</td>
            <td><strong>Instagram Followers</strong></td>
            <td>Instagram</td>
            <td><strong>₹35.00</strong></td>
            <td>1K / 1.01M</td>
            <td>GlobalSMM Prime API</td>
            <td><code>#101</code></td>
            <td><span class="badge badge-success">Active</span></td>
          </tr>
          <tr>
            <td>2</td>
            <td><strong>Instagram Likes</strong></td>
            <td>Instagram</td>
            <td><strong>₹20.00</strong></td>
            <td>100 / 500K</td>
            <td>GlobalSMM Prime API</td>
            <td><code>#102</code></td>
            <td><span class="badge badge-success">Active</span></td>
          </tr>
          <tr>
            <td>3</td>
            <td><strong>Instagram Views</strong></td>
            <td>Instagram</td>
            <td><strong>₹15.00</strong></td>
            <td>500 / 2M</td>
            <td>GlobalSMM Prime API</td>
            <td><code>#103</code></td>
            <td><span class="badge badge-success">Active</span></td>
          </tr>
          <tr>
            <td>4</td>
            <td><strong>Instagram Comments</strong></td>
            <td>Instagram</td>
            <td><strong>₹50.00</strong></td>
            <td>10 / 50K</td>
            <td>GlobalSMM Prime API</td>
            <td><code>#104</code></td>
            <td><span class="badge badge-success">Active</span></td>
          </tr>
          <tr>
            <td>5</td>
            <td><strong>YouTube Views</strong></td>
            <td>YouTube</td>
            <td><strong>₹120.00</strong></td>
            <td>1K / 1M</td>
            <td>TurboPanel Provider</td>
            <td><code>#201</code></td>
            <td><span class="badge badge-success">Active</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="/assets/js/user.js"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
