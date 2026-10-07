<?php
/**
 * SMM Panel - Authentication Base Helper
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class Auth {
    public static function check(): bool {
        return isset($_SESSION['user_id']) || isset($_SESSION['demo_user']);
    }

    public static function checkAdmin(): bool {
        return isset($_SESSION['admin_id']);
    }

    public static function user(): ?array {
        if (isset($_SESSION['user'])) {
            return $_SESSION['user'];
        }
        if (isset($_SESSION['demo_user'])) {
            return $_SESSION['demo_user'];
        }
        return null;
    }

    public static function admin(): ?array {
        return $_SESSION['admin'] ?? [
            'id' => 1,
            'name' => 'Super Admin',
            'email' => 'admin@smmpanel.com',
            'role' => 'super_admin'
        ];
    }

    public static function logout(): void {
        unset($_SESSION['user_id'], $_SESSION['user'], $_SESSION['demo_user']);
    }

    public static function adminLogout(): void {
        unset($_SESSION['admin_id'], $_SESSION['admin']);
    }
}
