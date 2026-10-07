<?php
/**
 * SMM Panel - Admin Module: Services Catalog & Pricing
 * Protected Server-Side
 */
$adminPageTitle = 'Services Management';
$activeAdminNav = 'services';

require_once __DIR__ . '/../includes/admin-header.php';
require_once __DIR__ . '/../includes/admin-navbar.php';

$db = Database::getConnection();

// Handle Service Creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_service'])) {
    $name = trim($_POST['name'] ?? '');
    $catId = (int)($_POST['category_id'] ?? 1);
    $rate = (float)($_POST['rate_per_1k'] ?? 35.00);
    $min = (int)($_POST['min_quantity'] ?? 100);
    $max = (int)($_POST['max_quantity'] ?? 100000);
    $badges = trim($_POST['badges'] ?? 'High Quality | Instant Start');
    $speed = trim($_POST['speed_tag'] ?? 'Fast Delivery');

    if (!empty($name) && $rate > 0) {
        $stmt = $db->prepare("
            INSERT INTO services (category_id, name, rate_per_1k, min_quantity, max_quantity, badges, speed_tag, status)
            VALUES (:cid, :name, :rate, :min, :max, :badges, :speed, 'active')
        ");
        $stmt->execute([
            ':cid' => $catId,
            ':name' => $name,
            ':rate' => $rate,
            ':min' => $min,
            ':max' => $max,
            ':badges' => $badges,
            ':speed' => $speed
        ]);
        setFlash('success', 'Service package created successfully!');
        header("Location: /admin/services.php");
        exit;
    }
}

$services = $db->query("
    SELECT s.*, c.name as category_name, p.name as provider_name 
    FROM services s 
    LEFT JOIN categories c ON s.category_id = c.id 
    LEFT JOIN providers p ON s.provider_id = p.id 
    ORDER BY s.id DESC
")->fetchAll();

$categories = $db->query("SELECT * FROM categories ORDER BY sort_order ASC")->fetchAll();
?>

<div class="admin-layout">
  <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; flex-wrap: wrap; gap: 12px;">
    <div>
      <h1 style="font-size: 24px; font-weight: 900; color: #0f172a; margin: 0;">Services Catalog</h1>
      <p style="font-size: 13px; color: var(--text-muted); margin: 4px 0 0 0;">Configure your public SMM services, rates per 1,000, and provider routing.</p>
    </div>
    <button class="btn-primary" style="width: auto; padding: 10px 20px;" onclick="document.getElementById('add-service-modal').classList.add('active')">
      <i data-lucide="plus"></i> Add Service
    </button>
  </div>

  <div class="data-table-card">
    <div class="table-responsive">
      <table class="app-table">
        <thead>
          <tr>
            <th>ID</th>
            <th>Service Name</th>
            <th>Category</th>
            <th>Rate / 1K</th>
            <th>Min / Max</th>
            <th>Mapped Provider</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($services)): ?>
            <tr>
              <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px;">
                <i data-lucide="layers" style="width: 40px; height: 40px; margin-bottom: 8px; color: var(--text-light);"></i>
                <p style="font-weight: 700;">No services created yet</p>
                <span style="font-size: 13px;">Click "+ Add Service" to add social media packages to your catalog.</span>
              </td>
            </tr>
          <?php else: ?>
            <?php foreach ($services as $srv): ?>
              <tr>
                <td>#<?= (int)$srv['id'] ?></td>
                <td><strong><?= htmlspecialchars($srv['name']) ?></strong></td>
                <td><?= htmlspecialchars($srv['category_name'] ?: 'General') ?></td>
                <td><strong style="color: #2563eb;"><?= formatCurrency((float)$srv['rate_per_1k']) ?></strong></td>
                <td><?= number_format($srv['min_quantity']) ?> - <?= number_format($srv['max_quantity']) ?></td>
                <td><?= htmlspecialchars($srv['provider_name'] ?: 'Direct Node') ?></td>
                <td><span class="badge badge-success"><?= htmlspecialchars($srv['status']) ?></span></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal: Add Service -->
<div class="modal-overlay" id="add-service-modal">
  <div class="modal-window">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
      <h3 style="font-size: 18px; font-weight: 900; margin: 0;">Add New Service</h3>
      <button style="background:none; border:none; color:#64748b; cursor:pointer;" onclick="document.getElementById('add-service-modal').classList.remove('active')">
        <i data-lucide="x"></i>
      </button>
    </div>
    <form method="POST" action="/admin/services.php">
      <input type="hidden" name="create_service" value="1">
      <div style="margin-bottom: 14px;">
        <label class="form-field-label">Service Name</label>
        <input type="text" name="name" required placeholder="e.g. Instagram Followers - Premium" style="width: 100%;">
      </div>
      <div style="margin-bottom: 14px;">
        <label class="form-field-label">Category</label>
        <select name="category_id" style="width: 100%; padding: 12px; border-radius: 12px; border: 1px solid var(--border-light);">
          <?php foreach ($categories as $cat): ?>
            <option value="<?= (int)$cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 14px;">
        <div>
          <label class="form-field-label">Rate / 1K (<?= htmlspecialchars(getCurrencySymbol()) ?>)</label>
          <input type="number" step="0.01" name="rate_per_1k" required value="35.00" style="width: 100%;">
        </div>
        <div>
          <label class="form-field-label">Speed Tag</label>
          <input type="text" name="speed_tag" value="Fast Delivery" style="width: 100%;">
        </div>
      </div>
      <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 20px;">
        <div>
          <label class="form-field-label">Min Quantity</label>
          <input type="number" name="min_quantity" value="100" style="width: 100%;">
        </div>
        <div>
          <label class="form-field-label">Max Quantity</label>
          <input type="number" name="max_quantity" value="1000000" style="width: 100%;">
        </div>
      </div>
      <div style="display: flex; justify-content: flex-end; gap: 10px;">
        <button type="button" class="btn-secondary" onclick="document.getElementById('add-service-modal').classList.remove('active')">Cancel</button>
        <button type="submit" class="btn-primary" style="width: auto; padding: 10px 24px;">Create Service</button>
      </div>
    </form>
  </div>
</div>

<?php require_once __DIR__ . '/../includes/admin-footer.php'; ?>
