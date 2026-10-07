<?php
/**
 * SMM Panel - Core Utility Functions
 */

require_once __DIR__ . '/../config/constants.php';

function formatCurrency(float $amount): string {
    return CURRENCY_SYMBOL . number_format($amount, 2);
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
