<?php
/**
 * SMM Panel - Core Utility Functions
 */

require_once __DIR__ . '/../config/constants.php';
require_once __DIR__ . '/../config/database.php';

// In-memory cache for settings
global $SYSTEM_SETTINGS_CACHE;
$SYSTEM_SETTINGS_CACHE = null;

/**
 * Retrieve a setting from database settings table
 */
function getSetting(string $key, string $default = ''): string {
    global $SYSTEM_SETTINGS_CACHE;

    if ($SYSTEM_SETTINGS_CACHE === null) {
        $SYSTEM_SETTINGS_CACHE = [];
        
        // 1. Load from file backup if available
        $jsonFile = __DIR__ . '/../config/settings.json';
        if (file_exists($jsonFile)) {
            $fileSettings = json_decode(file_get_contents($jsonFile), true);
            if (is_array($fileSettings)) {
                $SYSTEM_SETTINGS_CACHE = $fileSettings;
            }
        }

        // 2. Load from database table
        try {
            $db = Database::getConnection();
            $stmt = $db->query("SELECT setting_key, setting_value FROM settings");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $SYSTEM_SETTINGS_CACHE[$row['setting_key']] = $row['setting_value'];
            }
        } catch (Exception $e) {
            // Database may be offline or initializing
        }
    }

    if (isset($SYSTEM_SETTINGS_CACHE[$key]) && $SYSTEM_SETTINGS_CACHE[$key] !== '') {
        return (string)$SYSTEM_SETTINGS_CACHE[$key];
    }

    // Default fallbacks based on constants if key not present in database
    return match ($key) {
        'site_name' => APP_NAME,
        'site_tagline' => APP_TAGLINE,
        'site_url' => APP_URL,
        'currency_symbol' => defined('CURRENCY_SYMBOL') ? CURRENCY_SYMBOL : '₹',
        'currency_code' => defined('CURRENCY_CODE') ? CURRENCY_CODE : 'INR',
        'currency_name' => 'Indian Rupee',
        'min_deposit' => '100',
        'max_deposit' => '100000',
        default => $default,
    };
}

/**
 * Set or update a system setting in database and cache file
 */
function setSetting(string $key, string $value): bool {
    global $SYSTEM_SETTINGS_CACHE;
    $updated = false;

    if ($SYSTEM_SETTINGS_CACHE === null) {
        $SYSTEM_SETTINGS_CACHE = [];
    }
    $SYSTEM_SETTINGS_CACHE[$key] = $value;

    // 1. Update database table
    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO settings (setting_key, setting_value)
            VALUES (:k, :v)
            ON DUPLICATE KEY UPDATE setting_value = :v2
        ");
        $stmt->execute([':k' => $key, ':v' => $value, ':v2' => $value]);
        $updated = true;
    } catch (Exception $e) {
        // Fall back to file cache
    }

    // 2. Persist to config/settings.json
    $jsonFile = __DIR__ . '/../config/settings.json';
    try {
        $all = [];
        if (file_exists($jsonFile)) {
            $all = json_decode(file_get_contents($jsonFile), true) ?: [];
        }
        $all[$key] = $value;
        file_put_contents($jsonFile, json_encode($all, JSON_PRETTY_PRINT));
        $updated = true;
    } catch (Exception $e) {}

    return $updated;
}

/**
 * Get all system settings
 */
function getAllSettings(): array {
    global $SYSTEM_SETTINGS_CACHE;
    if ($SYSTEM_SETTINGS_CACHE === null) {
        getSetting('site_name'); // Triggers bulk load
    }
    return $SYSTEM_SETTINGS_CACHE ?? [];
}

/**
 * Get current configured currency symbol
 */
function getCurrencySymbol(): string {
    return getSetting('currency_symbol', '₹');
}

/**
 * Get current configured currency code (e.g., INR, USD)
 */
function getCurrencyCode(): string {
    return getSetting('currency_code', 'INR');
}

/**
 * Get current configured currency name
 */
function getCurrencyName(): string {
    return getSetting('currency_name', 'Indian Rupee');
}

/**
 * Dynamic monetary formatter using the database-configured currency symbol
 */
function formatCurrency($amount): string {
    $symbol = getCurrencySymbol();
    $numeric = is_numeric($amount) ? (float)$amount : 0.00;
    return $symbol . number_format($numeric, 2);
}

/**
 * Retrieve user's verified current balance directly from database
 */
function getUserBalance(int $userId): float {
    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT balance FROM users WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $userId]);
        $val = $stmt->fetchColumn();
        return $val !== false ? (float)$val : 0.00;
    } catch (Exception $e) {
        return 0.00;
    }
}

/**
 * Create a user-specific notification
 */
function createNotification(int $userId, string $title, string $message, string $type = 'info', ?string $link = null): bool {
    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("
            INSERT INTO notifications (user_id, title, message, type, is_read, link, created_at)
            VALUES (:uid, :title, :msg, :type, 0, :link, NOW())
        ");
        return $stmt->execute([
            ':uid' => $userId,
            ':title' => $title,
            ':msg' => $message,
            ':type' => $type,
            ':link' => $link
        ]);
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Fetch notifications for a specific authenticated user
 */
function getUserNotifications(int $userId, int $limit = 50): array {
    try {
        $db = Database::getConnection();
        $safeLimit = max(1, min(100, (int)$limit));
        $stmt = $db->prepare("
            SELECT * FROM notifications
            WHERE user_id = :uid
            ORDER BY id DESC
            LIMIT {$safeLimit}
        ");
        $stmt->execute([':uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    } catch (Exception $e) {
        return [];
    }
}

/**
 * Count unread notifications for a specific user
 */
function getUnreadNotificationCount(int $userId): int {
    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = :uid AND is_read = 0");
        $stmt->execute([':uid' => $userId]);
        return (int)$stmt->fetchColumn();
    } catch (Exception $e) {
        return 0;
    }
}

/**
 * Mark a single notification as read (scoped to owning user for security)
 */
function markNotificationAsRead(int $notificationId, int $userId): bool {
    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE notifications SET is_read = 1 WHERE id = :id AND user_id = :uid");
        return $stmt->execute([':id' => $notificationId, ':uid' => $userId]);
    } catch (Exception $e) {
        return false;
    }
}

/**
 * Mark all notifications as read for a user
 */
function markAllNotificationsAsRead(int $userId): bool {
    try {
        $db = Database::getConnection();
        $stmt = $db->prepare("UPDATE notifications SET is_read = 1 WHERE user_id = :uid");
        return $stmt->execute([':uid' => $userId]);
    } catch (Exception $e) {
        return false;
    }
}

function sanitizeInput(string $data): string {
    $data = trim($data);
    $data = stripslashes($data);
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}

function jsonResponse(array $data, int $statusCode = 200): void {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

function getStatusBadgeClass(string $status): string {
    return match (strtolower($status)) {
        'completed', 'active', 'connected', 'success' => 'badge-success',
        'processing', 'in_progress', 'open', 'pending' => 'badge-warning',
        'cancelled', 'failed', 'error', 'suspended' => 'badge-danger',
        'partial', 'refunded' => 'badge-info',
        default => 'badge-secondary',
    };
}

function getFormattedDate(string $timestamp): string {
    $date = new DateTime($timestamp);
    return $date->format('j M Y, g:i A');
}

function setFlash(string $type, string $message): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function getFlash(): ?array {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

