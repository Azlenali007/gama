<?php
/**
 * SMM Panel - User Module: Support Tickets
 */
require_once __DIR__ . '/../includes/user-auth.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Support - <?= htmlspecialchars(APP_NAME) ?></title>
  <link rel="stylesheet" href="/assets/css/style.css">
  <link rel="stylesheet" href="/assets/css/user.css">
  <link rel="stylesheet" href="/assets/css/responsive.css">
</head>
<body>
  <div class="app-container">
    <div class="page-back-header">
      <a href="/user/dashboard.php" class="back-btn-link">
        <span class="back-icon-circle"><i data-lucide="arrow-left"></i></span>
        <span>Support</span>
      </a>
      <button class="btn-primary" style="width: auto; padding: 10px 18px;" onclick="showToast('Create Ticket Modal Opened', 'info')">
        + Create Ticket
      </button>
    </div>

    <!-- Filter Tabs (Image 8) -->
    <div class="filter-tabs-row">
      <button class="filter-pill-btn active ticket-filter-pill" data-filter="all">All</button>
      <button class="filter-pill-btn ticket-filter-pill" data-filter="open">Open</button>
      <button class="filter-pill-btn ticket-filter-pill" data-filter="in_progress">In Progress</button>
      <button class="filter-pill-btn ticket-filter-pill" data-filter="closed">Closed</button>
    </div>

    <!-- Tickets List (Matches Image 8: #T1024, #T1023, #T1022, #T1021) -->
    <div>
      <div class="ticket-row-card" data-status="open">
        <div>
          <div style="font-weight: 800; font-size: 15px;"><span style="color: var(--text-light);">#T1024</span> Order not started yet</div>
          <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">12 May 2025, 11:20 AM</div>
        </div>
        <span class="badge badge-warning">Open</span>
      </div>

      <div class="ticket-row-card" data-status="in_progress">
        <div>
          <div style="font-weight: 800; font-size: 15px;"><span style="color: var(--text-light);">#T1023</span> Payment issue</div>
          <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">10 May 2025, 6:15 PM</div>
        </div>
        <span class="badge badge-info">In Progress</span>
      </div>

      <div class="ticket-row-card" data-status="closed">
        <div>
          <div style="font-weight: 800; font-size: 15px;"><span style="color: var(--text-light);">#T1022</span> Service delay</div>
          <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">8 May 2025, 3:40 PM</div>
        </div>
        <span class="badge badge-success">Closed</span>
      </div>

      <div class="ticket-row-card" data-status="closed">
        <div>
          <div style="font-weight: 800; font-size: 15px;"><span style="color: var(--text-light);">#T1021</span> Wrong quantity</div>
          <div style="font-size: 12px; color: var(--text-muted); margin-top: 4px;">6 May 2025, 1:10 PM</div>
        </div>
        <span class="badge badge-success">Closed</span>
      </div>
    </div>
  </div>

  <script src="https://unpkg.com/lucide@latest"></script>
  <script src="/assets/js/user.js"></script>
  <script>lucide.createIcons();</script>
</body>
</html>
