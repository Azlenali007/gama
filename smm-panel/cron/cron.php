<?php
/**
 * SMM Panel - Master Scheduled Automation Cron
 * File: cron/cron.php
 * Handles:
 * 1. Provider order status synchronization (polling provider APIs)
 * 2. Provider balance checks & health monitoring
 * 3. Expired payment cleanup & pending order routing
 * 4. Automated logs and execution summary
 */

// Allow execution from CLI or authenticated web request
if (php_sapi_name() !== 'cli') {
    // If called via web, require token
    $secret = $_GET['cron_key'] ?? '';
    if ($secret !== 'smm_cron_secret_secure_key_2025') {
        http_response_code(403);
        die("Unauthorized cron execution.");
    }
}

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../provider/ProviderManager.php';

header('Content-Type: text/plain; charset=utf-8');

$startTime = microtime(true);
echo "====================================================\n";
echo "SMM PANEL MASTER CRON RUNNER - " . date('Y-m-d H:i:s') . "\n";
echo "====================================================\n\n";

$syncedOrders = 0;
$routedOrders = 0;
$syncedProviders = 0;

try {
    $db = Database::getConnection();

    // ----------------------------------------------------
    // TASK 1: Forward Unrouted Orders to Providers
    // ----------------------------------------------------
    echo "[TASK 1] Checking pending unrouted orders...\n";
    $pendingStmt = $db->query("
        SELECT o.id, o.order_code 
        FROM orders o 
        JOIN services s ON o.service_id = s.id 
        WHERE o.provider_order_id IS NULL 
          AND s.provider_id IS NOT NULL 
          AND o.status IN ('pending', 'processing')
        LIMIT 50
    ");
    $unrouted = $pendingStmt->fetchAll();

    foreach ($unrouted as $ord) {
        $forwardRes = ProviderManager::forwardOrder($ord['id']);
        if ($forwardRes['success']) {
            echo " -> Routed {$ord['order_code']} => Provider Order #{$forwardRes['provider_order_id']}\n";
            $routedOrders++;
        } else {
            echo " -> Failed to route {$ord['order_code']}: {$forwardRes['message']}\n";
        }
    }

    // ----------------------------------------------------
    // TASK 2: Sync Active Orders with Provider Status
    // ----------------------------------------------------
    echo "\n[TASK 2] Polling provider API order statuses...\n";
    $activeOrdersStmt = $db->query("
        SELECT o.id, o.order_code, o.provider_order_id, o.provider_id
        FROM orders o
        WHERE o.provider_order_id IS NOT NULL
          AND o.status IN ('pending', 'processing', 'in_progress')
        ORDER BY o.updated_at ASC
        LIMIT 100
    ");
    $activeOrders = $activeOrdersStmt->fetchAll();

    foreach ($activeOrders as $ord) {
        $syncRes = ProviderManager::syncOrderStatus($ord['id']);
        if ($syncRes['success']) {
            echo " -> Synced {$ord['order_code']}: Status is now '{$syncRes['status']}'\n";
            $syncedOrders++;
        } else {
            echo " -> Notice for {$ord['order_code']}: {$syncRes['message']}\n";
        }
    }

    // ----------------------------------------------------
    // TASK 3: Update Provider Balances
    // ----------------------------------------------------
    echo "\n[TASK 3] Checking provider API account balances...\n";
    $providers = $db->query("SELECT id, name, api_url, api_key FROM providers WHERE status = 'active'")->fetchAll();
    foreach ($providers as $prov) {
        $adapter = new StandardProviderAdapter($prov['api_url'], $prov['api_key']);
        $balRes = $adapter->getBalance();
        if (isset($balRes['balance'])) {
            $updBal = $db->prepare("UPDATE providers SET balance = :bal, api_status = 'connected', last_sync = NOW() WHERE id = :id");
            $updBal->execute([
                ':bal' => (float)$balRes['balance'],
                ':id' => $prov['id']
            ]);
            echo " -> Provider '{$prov['name']}': Balance updated to {$balRes['balance']} {$balRes['currency']}\n";
            $syncedProviders++;
        }
    }

} catch (Exception $e) {
    echo "\n[ERROR] Cron execution exception: " . $e->getMessage() . "\n";
}

$elapsed = round(microtime(true) - $startTime, 3);
echo "\n====================================================\n";
echo "Cron Complete in {$elapsed}s\n";
echo "Summary: Routed {$routedOrders} orders | Synced {$syncedOrders} orders | Updated {$syncedProviders} providers\n";
echo "====================================================\n";
