<?php
/**
 * SMM Panel - System Constants
 */

if (!defined('APP_INIT')) {
    define('APP_INIT', true);
}

define('APP_NAME', 'SMM Panel');
define('APP_TAGLINE', 'Grow Your Social Media');
define('APP_VERSION', '2.5.0');
define('APP_URL', getenv('APP_URL') ?: 'http://localhost:3000');

// Currency Configuration (Matches Reference Images: Indian Rupee ₹)
define('CURRENCY_SYMBOL', '₹');
define('CURRENCY_CODE', 'INR');
define('MIN_DEPOSIT_AMOUNT', 100);
define('MAX_DEPOSIT_AMOUNT', 100000);

// Order Status Constants
define('ORDER_STATUS_PENDING', 'pending');
define('ORDER_STATUS_PROCESSING', 'processing');
define('ORDER_STATUS_IN_PROGRESS', 'in_progress');
define('ORDER_STATUS_COMPLETED', 'completed');
define('ORDER_STATUS_PARTIAL', 'partial');
define('ORDER_STATUS_CANCELLED', 'cancelled');
define('ORDER_STATUS_REFUNDED', 'refunded');

// Ticket Status Constants
define('TICKET_STATUS_OPEN', 'open');
define('TICKET_STATUS_IN_PROGRESS', 'in_progress');
define('TICKET_STATUS_CLOSED', 'closed');

// Provider API Timeouts
define('PROVIDER_TIMEOUT_SECONDS', 15);
define('PROVIDER_USER_AGENT', 'SMMPanel-CoreEngine/2.5');
