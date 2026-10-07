<?php
/**
 * SMM Panel - Authentication & Session Security Service
 * Strict Server-Side Verification: No Demo, No Bypass, No Fallbacks
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

class Auth {
    const MAX_LOGIN_ATTEMPTS = 5;
    const LOCKOUT_MINUTES = 15;

    /**
     * Check if a normal user is authenticated
     */
    public static function checkUser(): bool {
        return isset($_SESSION['user_id']) && 
               !empty($_SESSION['user_id']) && 
               (isset($_SESSION['auth_role']) && $_SESSION['auth_role'] === 'user');
    }

    /**
     * Check if an admin is authenticated
     */
    public static function checkAdmin(): bool {
        return isset($_SESSION['admin_id']) && 
               !empty($_SESSION['admin_id']) && 
               (isset($_SESSION['auth_role']) && $_SESSION['auth_role'] === 'admin');
    }

    /**
     * Get authenticated user data from database
     */
    public static function user(): ?array {
        if (!self::checkUser()) {
            return null;
        }

        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT id, user_id_code, name, email, phone, balance, currency, status, email_verified, created_at FROM users WHERE id = :id AND status = 'active' LIMIT 1");
            $stmt->execute([':id' => (int)$_SESSION['user_id']]);
            $user = $stmt->fetch();
            return $user ?: null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Get authenticated admin data from database
     */
    public static function admin(): ?array {
        if (!self::checkAdmin()) {
            return null;
        }

        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT id, name, email, role, status, last_login, created_at FROM admins WHERE id = :id AND status = 'active' LIMIT 1");
            $stmt->execute([':id' => (int)$_SESSION['admin_id']]);
            $admin = $stmt->fetch();
            return $admin ?: null;
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Log in a user securely with session regeneration
     */
    public static function loginUser(array $user): void {
        session_regenerate_id(true);
        // Clear any previous role data
        unset($_SESSION['admin_id']);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['auth_role'] = 'user';
        $_SESSION['last_activity'] = time();
        self::resetAttempts('user_' . $user['email']);
    }

    /**
     * Log in an admin securely with session regeneration
     */
    public static function loginAdmin(array $admin): void {
        session_regenerate_id(true);
        // Clear any previous normal user data
        unset($_SESSION['user_id']);
        $_SESSION['admin_id'] = (int)$admin['id'];
        $_SESSION['auth_role'] = 'admin';
        $_SESSION['admin_role'] = $admin['role'] ?? 'super_admin';
        $_SESSION['last_activity'] = time();
        self::resetAttempts('admin_' . $admin['email']);

        // Update last_login in database
        try {
            $db = Database::getConnection();
            $stmt = $db->prepare("UPDATE admins SET last_login = NOW() WHERE id = :id");
            $stmt->execute([':id' => (int)$admin['id']]);
        } catch (Exception $e) {}
    }

    /**
     * Terminate user session completely
     */
    public static function logout(): void {
        unset($_SESSION['user_id']);
        if (isset($_SESSION['auth_role']) && $_SESSION['auth_role'] === 'user') {
            unset($_SESSION['auth_role']);
        }
        session_regenerate_id(true);
    }

    /**
     * Terminate admin session completely
     */
    public static function adminLogout(): void {
        unset($_SESSION['admin_id'], $_SESSION['admin_role']);
        if (isset($_SESSION['auth_role']) && $_SESSION['auth_role'] === 'admin') {
            unset($_SESSION['auth_role']);
        }
        session_regenerate_id(true);
    }

    /**
     * Brute-Force Rate Limiting Check
     */
    public static function isRateLimited(string $identifier): bool {
        $key = 'attempts_' . md5($identifier);
        if (isset($_SESSION[$key])) {
            $data = $_SESSION[$key];
            if ($data['count'] >= self::MAX_LOGIN_ATTEMPTS) {
                if (time() - $data['time'] < (self::LOCKOUT_MINUTES * 60)) {
                    return true;
                } else {
                    unset($_SESSION[$key]);
                }
            }
        }
        return false;
    }

    /**
     * Record a failed login attempt
     */
    public static function recordFailedAttempt(string $identifier): void {
        $key = 'attempts_' . md5($identifier);
        if (!isset($_SESSION[$key])) {
            $_SESSION[$key] = ['count' => 1, 'time' => time()];
        } else {
            $_SESSION[$key]['count']++;
            $_SESSION[$key]['time'] = time();
        }
    }

    /**
     * Reset failed attempts after successful authentication
     */
    public static function resetAttempts(string $identifier): void {
        $key = 'attempts_' . md5($identifier);
        unset($_SESSION[$key]);
    }
}
