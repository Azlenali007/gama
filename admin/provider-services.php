<?php
/**
 * SMM Panel - Admin Module: Provider Services Mapping
 */
require_once __DIR__ . '/../includes/admin-auth.php';
$providerId = (int)($_GET['id'] ?? 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Provider Service Mapping - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">
    <div class="page-back-header">
      <a href="/admin/providers.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>Provider Services Mapping</span>
      </a>
      <span class="badge badge-success">Provider #<?= $providerId ?> Synced</span>
    </div>

    <div class="data-table-card">
      <div class="data-table-header">
        <div>
          <h3 style="font-size: 17px; font-weight: 800;">Remote Services on GlobalSMM API</h3>
          <p style="font-size: 12px; color: var(--text-muted);">Map remote service IDs to our customer catalog</p>
        </div>
      </div>

      <table class="app-table">
        <thead>
          <tr>
            <th>Remote ID</th>
            <th>Remote Service Name</th>
            <th>Category</th>
            <th>API Cost / 1K</th>
            <th>Min / Max</th>
            <th>Mapped Our Service</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><code>101</code></td>
            <td><strong>Instagram Followers HQ Instant</strong></td>
            <td>Instagram</td>
            <td>$0.28 USD</td>
            <td>1K / 1M</td>
            <td><strong style="color: #2563eb;">Instagram Followers (₹35)</strong></td>
            <td><span class="badge badge-success">Mapped</span></td>
          </tr>
          <tr>
            <td><code>102</code></td>
            <td><strong>Instagram Likes Fast Real</strong></td>
            <td>Instagram</td>
            <td>$0.14 USD</td>
            <td>100 / 500K</td>
            <td><strong style="color: #2563eb;">Instagram Likes (₹20)</strong></td>
            <td><span class="badge badge-success">Mapped</span></td>
          </tr>
          <tr>
            <td><code>103</code></td>
            <td><strong>Instagram Video Views Super Fast</strong></td>
            <td>Instagram</td>
            <td>$0.09 USD</td>
            <td>500 / 2M</td>
            <td><strong style="color: #2563eb;">Instagram Views (₹15)</strong></td>
            <td><span class="badge badge-success">Mapped</span></td>
          </tr>
          <tr>
            <td><code>104</code></td>
            <td><strong>Instagram Custom Comments HQ</strong></td>
            <td>Instagram</td>
            <td>$0.42 USD</td>
            <td>10 / 50K</td>
            <td><strong style="color: #2563eb;">Instagram Comments (₹50)</strong></td>
            <td><span class="badge badge-success">Mapped</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
