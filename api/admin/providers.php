<?php
/**
 * API: Admin - Provider Management
 * Full Provider CRUD, Balance Sync, Status Toggle
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../includes/admin-auth.php';
require_once __DIR__ . '/../../provider/ProviderManager.php';

header('Content-Type: application/json; charset=utf-8');

$action = $_GET['action'] ?? $_POST['action'] ?? 'list';

try {
    $db = Database::getConnection();

    if ($action === 'list') {
        $stmt = $db->query("SELECT * FROM providers ORDER BY id DESC");
        $providers = $stmt->fetchAll();
        jsonResponse(['success' => true, 'providers' => $providers]);
    }

    if ($action === 'create') {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $name = trim($input['name'] ?? '');
        $url = trim($input['api_url'] ?? '');
        $key = trim($input['api_key'] ?? '');

        if (empty($name) || empty($url) || empty($key)) {
            jsonResponse(['success' => false, 'message' => 'Provider name, API URL, and API key are required'], 400);
        }

        // Test connection
        $adapter = new StandardProviderAdapter($url, $key);
        $balRes = $adapter->getBalance();
        $balance = isset($balRes['balance']) ? (float)$balRes['balance'] : 0.00;
        $currency = $balRes['currency'] ?? 'USD';
        $apiStatus = isset($balRes['balance']) ? 'connected' : 'error';

        $stmt = $db->prepare("
            INSERT INTO providers (name, api_url, api_key, balance, currency, status, api_status, last_sync)
            VALUES (:name, :url, :key, :bal, :curr, 'active', :api_status, NOW())
        ");
        $stmt->execute([
            ':name' => $name,
            ':url' => $url,
            ':key' => $key,
            ':bal' => $balance,
            ':curr' => $currency,
            ':api_status' => $apiStatus
        ]);

        jsonResponse([
            'success' => true,
            'message' => 'Provider added successfully',
            'id' => $db->lastInsertId(),
            'balance' => $balance
        ]);
    }

    if ($action === 'toggle_status') {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $id = (int)($input['id'] ?? 0);
        $status = ($input['status'] ?? 'active') === 'active' ? 'active' : 'disabled';

        $stmt = $db->prepare("UPDATE providers SET status = :status WHERE id = :id");
        $stmt->execute([':status' => $status, ':id' => $id]);

        jsonResponse(['success' => true, 'message' => "Provider marked as {$status}"]);
    }

    if ($action === 'sync_balance') {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $id = (int)($input['id'] ?? 0);

        $stmt = $db->prepare("SELECT * FROM providers WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $provider = $stmt->fetch();

        if (!$provider) {
            jsonResponse(['success' => false, 'message' => 'Provider not found'], 404);
        }

        $adapter = new StandardProviderAdapter($provider['api_url'], $provider['api_key']);
        $balRes = $adapter->getBalance();

        if (isset($balRes['balance'])) {
            $upd = $db->prepare("UPDATE providers SET balance = :bal, api_status = 'connected', last_sync = NOW() WHERE id = :id");
            $upd->execute([':bal' => (float)$balRes['balance'], ':id' => $id]);

            jsonResponse([
                'success' => true,
                'message' => "Synced successfully: {$balRes['balance']} {$balRes['currency']}",
                'balance' => (float)$balRes['balance'],
                'currency' => $balRes['currency']
            ]);
        }

        jsonResponse(['success' => false, 'message' => $balRes['error'] ?? 'Sync failed'], 500);
    }

    if ($action === 'delete') {
        $input = json_decode(file_get_contents('php://input'), true) ?? $_POST;
        $id = (int)($input['id'] ?? 0);

        $stmt = $db->prepare("DELETE FROM providers WHERE id = :id");
        $stmt->execute([':id' => $id]);

        jsonResponse(['success' => true, 'message' => 'Provider deleted']);
    }

} catch (Exception $e) {
    jsonResponse(['success' => false, 'message' => $e->getMessage()], 500);
}
