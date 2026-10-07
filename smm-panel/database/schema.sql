-- ===================================================
-- SMM Panel - Database Schema (MySQL)
-- ===================================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `ticket_messages`;
DROP TABLE IF EXISTS `tickets`;
DROP TABLE IF EXISTS `transactions`;
DROP TABLE IF EXISTS `payments`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `provider_services`;
DROP TABLE IF EXISTS `services`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `providers`;
DROP TABLE IF EXISTS `announcements`;
DROP TABLE IF EXISTS `settings`;
DROP TABLE IF EXISTS `admins`;
DROP TABLE IF EXISTS `users`;

-- 1. Users Table
CREATE TABLE `users` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id_code` VARCHAR(20) NOT NULL UNIQUE,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `phone` VARCHAR(25) DEFAULT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `balance` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
  `currency` VARCHAR(5) NOT NULL DEFAULT 'INR',
  `status` ENUM('active', 'suspended', 'pending') NOT NULL DEFAULT 'active',
  `email_verified` TINYINT(1) NOT NULL DEFAULT 1,
  `avatar_url` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Admins Table
CREATE TABLE `admins` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` ENUM('super_admin', 'manager', 'support') NOT NULL DEFAULT 'super_admin',
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `last_login` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Providers Table (API Integration)
CREATE TABLE `providers` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `api_url` VARCHAR(255) NOT NULL,
  `api_key` VARCHAR(255) NOT NULL,
  `balance` DECIMAL(12, 2) NOT NULL DEFAULT 0.00,
  `currency` VARCHAR(5) NOT NULL DEFAULT 'USD',
  `status` ENUM('active', 'disabled') NOT NULL DEFAULT 'active',
  `api_status` ENUM('connected', 'error', 'pending') NOT NULL DEFAULT 'connected',
  `last_sync` TIMESTAMP NULL DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Categories Table
CREATE TABLE `categories` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `icon` VARCHAR(50) NOT NULL DEFAULT 'share-2',
  `sort_order` INT NOT NULL DEFAULT 0,
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Services Table
CREATE TABLE `services` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  `rate_per_1k` DECIMAL(10, 2) NOT NULL,
  `min_quantity` INT UNSIGNED NOT NULL DEFAULT 100,
  `max_quantity` INT UNSIGNED NOT NULL DEFAULT 1000000,
  `description` TEXT DEFAULT NULL,
  `badges` VARCHAR(255) DEFAULT 'High Quality | Instant Start | No Drop',
  `speed_tag` VARCHAR(50) DEFAULT 'Fast Delivery',
  `status` ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
  `provider_id` INT UNSIGNED DEFAULT NULL,
  `provider_service_id` INT UNSIGNED DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`provider_id`) REFERENCES `providers`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Provider Service Mappings
CREATE TABLE `provider_services` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `provider_id` INT UNSIGNED NOT NULL,
  `remote_service_id` VARCHAR(50) NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  `rate` DECIMAL(10, 4) NOT NULL,
  `min` INT UNSIGNED NOT NULL,
  `max` INT UNSIGNED NOT NULL,
  `category` VARCHAR(100) DEFAULT NULL,
  `sync_status` ENUM('synced', 'unmapped') NOT NULL DEFAULT 'unmapped',
  `last_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`provider_id`) REFERENCES `providers`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Orders Table
CREATE TABLE `orders` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `order_code` VARCHAR(30) NOT NULL UNIQUE,
  `user_id` INT UNSIGNED NOT NULL,
  `service_id` INT UNSIGNED NOT NULL,
  `target_link` VARCHAR(500) NOT NULL,
  `quantity` INT UNSIGNED NOT NULL,
  `charge` DECIMAL(10, 2) NOT NULL,
  `start_count` INT UNSIGNED DEFAULT 0,
  `remains` INT UNSIGNED DEFAULT 0,
  `status` ENUM('pending', 'processing', 'in_progress', 'completed', 'partial', 'cancelled', 'refunded') NOT NULL DEFAULT 'processing',
  `provider_id` INT UNSIGNED DEFAULT NULL,
  `provider_order_id` VARCHAR(100) DEFAULT NULL,
  `provider_status` VARCHAR(50) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`provider_id`) REFERENCES `providers`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Payments Table
CREATE TABLE `payments` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `transaction_code` VARCHAR(50) NOT NULL UNIQUE,
  `user_id` INT UNSIGNED NOT NULL,
  `amount` DECIMAL(10, 2) NOT NULL,
  `fee` DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
  `payment_method` VARCHAR(50) NOT NULL DEFAULT 'Razorpay',
  `gateway_order_id` VARCHAR(100) DEFAULT NULL,
  `gateway_payment_id` VARCHAR(100) DEFAULT NULL,
  `status` ENUM('pending', 'completed', 'failed', 'refunded') NOT NULL DEFAULT 'completed',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Transactions Ledger Table
CREATE TABLE `transactions` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT UNSIGNED NOT NULL,
  `type` ENUM('add_funds', 'order_payment', 'refund', 'bonus', 'manual_adjustment') NOT NULL,
  `amount` DECIMAL(10, 2) NOT NULL,
  `direction` ENUM('credit', 'debit') NOT NULL,
  `title` VARCHAR(100) NOT NULL,
  `description` VARCHAR(255) DEFAULT NULL,
  `reference_id` VARCHAR(50) DEFAULT NULL,
  `balance_after` DECIMAL(12, 2) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Support Tickets Table
CREATE TABLE `tickets` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `ticket_code` VARCHAR(20) NOT NULL UNIQUE,
  `user_id` INT UNSIGNED NOT NULL,
  `subject` VARCHAR(200) NOT NULL,
  `department` ENUM('orders', 'payment', 'service', 'other') NOT NULL DEFAULT 'orders',
  `order_code` VARCHAR(30) DEFAULT NULL,
  `status` ENUM('open', 'in_progress', 'closed') NOT NULL DEFAULT 'open',
  `priority` ENUM('low', 'medium', 'high') NOT NULL DEFAULT 'medium',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Ticket Messages Table
CREATE TABLE `ticket_messages` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `ticket_id` INT UNSIGNED NOT NULL,
  `sender_type` ENUM('user', 'admin') NOT NULL,
  `sender_id` INT UNSIGNED NOT NULL,
  `message` TEXT NOT NULL,
  `attachment_path` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`ticket_id`) REFERENCES `tickets`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Announcements Table
CREATE TABLE `announcements` (
  `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(200) NOT NULL,
  `content` TEXT NOT NULL,
  `type` ENUM('info', 'warning', 'success', 'promo') NOT NULL DEFAULT 'info',
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. System Settings Table
CREATE TABLE `settings` (
  `setting_key` VARCHAR(100) PRIMARY KEY,
  `setting_value` TEXT NOT NULL,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
