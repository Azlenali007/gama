<?php
/**
 * SMM Panel - Provider Manager
 * Factory and orchestration for third-party SMM API providers
 */

require_once __DIR__ . '/ProviderInterface.php';
require_once __DIR__ . '/adapters/StandardProviderAdapter.php';
require_once __DIR__ . '/../config/database.php';

class ProviderManager {
    /**
     * Get Provider adapter by ID from database
     */
    public static function getAdapter(int $providerId): ?ProviderInterface {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM providers WHERE id = :id AND status = 'active' LIMIT 1");
            $stmt->execute([':id' => $providerId]);
            $provider = $stmt->fetch();

            if (!$provider) {
                return null;
            }

            return new StandardProviderAdapter($provider['api_url'], $provider['api_key']);
        } catch (Exception $e) {
            // Fallback default adapter
            return new StandardProviderAdapter('https://api.globalsmm.pro/v2', 'sec_live_demo_key');
        }
    }

    /**
     * Route and forward a user order to its connected provider
     */
    public static function forwardOrder(int $orderId): array {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                SELECT o.*, s.provider_id, s.provider_service_id, p.api_url, p.api_key
                FROM orders o
                JOIN services s ON o.service_id = s.id
                LEFT JOIN providers p ON s.provider_id = p.id
                WHERE o.id = :order_id
            ");
            $stmt->execute([':order_id' => $orderId]);
            $order = $stmt->fetch();

            if (!$order || !$order['provider_id'] || !$order['provider_service_id']) {
                return ['success' => false, 'message' => 'No provider mapped for this service'];
            }

            $adapter = new StandardProviderAdapter($order['api_url'], $order['api_key']);
            $apiResult = $adapter->addOrder($order['provider_service_id'], $order['target_link'], (int)$order['quantity']);

            if (isset($apiResult['order'])) {
                $update = $db->prepare("
                    UPDATE orders
                    SET provider_order_id = :p_ord_id, provider_status = 'In progress', status = 'processing', updated_at = NOW()
                    WHERE id = :id
                ");
                $update->execute([
                    ':p_ord_id' => $apiResult['order'],
                    ':id' => $orderId
                ]);

                return ['success' => true, 'provider_order_id' => $apiResult['order']];
            } else {
                $errorMsg = $apiResult['error'] ?? 'Unknown provider error';
                return ['success' => false, 'message' => $errorMsg];
            }
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Sync order statuses from provider API
     */
    public static function syncOrderStatus(int $orderId): array {
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("
                SELECT o.*, p.api_url, p.api_key
                FROM orders o
                JOIN providers p ON o.provider_id = p.id
                WHERE o.id = :order_id AND o.provider_order_id IS NOT NULL
            ");
            $stmt->execute([':order_id' => $orderId]);
            $order = $stmt->fetch();

            if (!$order) {
                return ['success' => false, 'message' => 'Order not eligible for sync'];
            }

            $adapter = new StandardProviderAdapter($order['api_url'], $order['api_key']);
            $statusResult = $adapter->getOrderStatus($order['provider_order_id']);

            if (isset($statusResult['status'])) {
                $mappedStatus = self::mapProviderStatus($statusResult['status']);
                $update = $db->prepare("
                    UPDATE orders
                    SET status = :status,
                        provider_status = :p_status,
                        remains = :remains,
                        start_count = :start_count,
                        updated_at = NOW()
                    WHERE id = :id
                ");
                $update->execute([
                    ':status' => $mappedStatus,
                    ':p_status' => $statusResult['status'],
                    ':remains' => $statusResult['remains'] ?? 0,
                    ':start_count' => $statusResult['start_count'] ?? 0,
                    ':id' => $orderId
                ]);

                return ['success' => true, 'status' => $mappedStatus, 'raw' => $statusResult];
            }

            return ['success' => false, 'message' => $statusResult['error'] ?? 'Sync failed'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Map provider status string to local internal order status
     */
    public static function mapProviderStatus(string $providerStatus): string {
        $clean = strtolower(trim($providerStatus));
        return match ($clean) {
            'completed' => ORDER_STATUS_COMPLETED,
            'processing', 'in progress', 'inprogress', 'pending' => ORDER_STATUS_PROCESSING,
            'partial' => ORDER_STATUS_PARTIAL,
            'canceled', 'cancelled' => ORDER_STATUS_CANCELLED,
            default => ORDER_STATUS_PROCESSING
        };
    }
}
