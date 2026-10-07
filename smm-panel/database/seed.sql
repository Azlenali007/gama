-- ===================================================
-- SMM Panel - Seed Data (Matches Reference Images)
-- ===================================================

-- 1. Insert Default User (Matches Image 2 & 9: Aaris Ali, #1024, ₹850.50)
INSERT INTO `users` (`id`, `user_id_code`, `name`, `email`, `phone`, `password_hash`, `balance`, `currency`, `status`, `email_verified`, `avatar_url`, `created_at`) VALUES
(1, '#1024', 'Aaris Ali', 'aarisali@gmail.com', '+91 98765 43210', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 850.50, 'INR', 'active', 1, '/assets/images/avatar.png', '2025-05-10 10:00:00');

-- 2. Insert Default Admin
INSERT INTO `admins` (`id`, `name`, `email`, `password_hash`, `role`, `status`, `created_at`) VALUES
(1, 'Super Admin', 'admin@smmpanel.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', 'active', '2025-01-01 00:00:00');

-- 3. Insert SMM API Providers
INSERT INTO `providers` (`id`, `name`, `api_url`, `api_key`, `balance`, `currency`, `status`, `api_status`, `last_sync`, `created_at`) VALUES
(1, 'GlobalSMM Prime API', 'https://api.globalsmm.pro/v2', 'sec_live_99f8d1720a4b89e31d45', 428.60, 'USD', 'active', 'connected', NOW(), NOW()),
(2, 'TurboPanel Provider', 'https://turbopanel.net/api/v2', 'sec_live_55ab41198e0cc198302f', 1250.00, 'USD', 'active', 'connected', NOW(), NOW()),
(3, 'FastSocial Nexus', 'https://nexus-smm.com/api/v2', 'sec_live_839cbb105e1a44c9b20e', 84.15, 'USD', 'disabled', 'pending', NOW(), NOW());

-- 4. Categories
INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `sort_order`, `status`) VALUES
(1, 'Instagram', 'instagram', 'instagram', 1, 'active'),
(2, 'YouTube', 'youtube', 'youtube', 2, 'active'),
(3, 'Telegram', 'telegram', 'send', 3, 'active'),
(4, 'Facebook', 'facebook', 'facebook', 4, 'active'),
(5, 'TikTok', 'tiktok', 'video', 5, 'active'),
(6, 'Twitter (X)', 'twitter-x', 'twitter', 6, 'active');

-- 5. Services (Matches Image 3 & 6)
INSERT INTO `services` (`id`, `category_id`, `name`, `rate_per_1k`, `min_quantity`, `max_quantity`, `description`, `badges`, `speed_tag`, `status`, `provider_id`, `provider_service_id`) VALUES
(1, 1, 'Instagram Followers', 35.00, 1000, 1010000, 'Real & Active looking followers. Instant start with 0-1 hour completion time. Lifetime guarantee option.', 'High quality followers | Instant Start | No Drop', 'Fast Delivery', 'active', 1, 101),
(2, 1, 'Instagram Likes', 20.00, 100, 500000, 'Super fast real likes from active accounts. Start time 5-15 mins. Non-drop high retention quality.', 'High Quality | Instant Start | Non-Drop', 'Fast Delivery', 'active', 1, 102),
(3, 1, 'Instagram Views', 15.00, 500, 2000000, 'Ultra fast reel and video views. High impression booster for explore algorithm.', 'Instant Start | High Retention | 100% Safe', 'Ultra Fast', 'active', 1, 103),
(4, 1, 'Instagram Comments', 50.00, 10, 50000, 'Custom positive emoji + relevant comments from verified lookalike accounts.', 'Custom Comments | Verified Look | HQ', 'Speed 1K/day', 'active', 1, 104),
(5, 2, 'YouTube Views', 120.00, 1000, 1000000, 'Monetizable organic high retention views with lifetime refill guarantee.', 'Monetizable | High Retention | Refill', 'Fast Delivery', 'active', 2, 201),
(6, 2, 'YouTube Subscribers', 450.00, 100, 50000, 'Permanent real YouTube channel subscribers. Non-drop natural pacing.', 'Real Accounts | Non Drop | 30d Refill', 'Natural Pace', 'active', 2, 202),
(7, 3, 'Telegram Members', 90.00, 500, 200000, 'Channel & Group members with active avatars and natural usernames.', '0% Drop | Real Profiles | Fast Add', 'Instant Start', 'active', 1, 301),
(8, 4, 'Facebook Page Likes', 65.00, 250, 100000, 'Worldwide organic looking page followers and profile page likes.', 'Global Reach | High Quality | Safe', 'Fast Delivery', 'active', 2, 401),
(9, 5, 'TikTok Followers', 80.00, 500, 500000, 'Targeted TikTok followers to boost FYP engagement and live eligibility.', 'Instant Push | HQ Profiles | Safe', 'Fast Delivery', 'active', 1, 501),
(10, 6, 'Twitter (X) Followers', 110.00, 200, 250000, 'High authority X profiles, boost impression count and algorithmic visibility.', 'HQ Accounts | Fast Start | Refill', 'Fast Delivery', 'active', 2, 601);

-- 6. Provider Service Mappings
INSERT INTO `provider_services` (`id`, `provider_id`, `remote_service_id`, `name`, `rate`, `min`, `max`, `category`, `sync_status`) VALUES
(1, 1, '101', 'Instagram Followers HQ Instant', 0.2800, 1000, 1000000, 'Instagram', 'synced'),
(2, 1, '102', 'Instagram Likes Fast Real', 0.1400, 100, 500000, 'Instagram', 'synced'),
(3, 1, '103', 'Instagram Video Views Super Fast', 0.0900, 500, 2000000, 'Instagram', 'synced'),
(4, 1, '104', 'Instagram Custom Comments HQ', 0.4200, 10, 50000, 'Instagram', 'synced'),
(5, 2, '201', 'YouTube Views Retention 3-5m', 0.9500, 1000, 1000000, 'YouTube', 'synced');

-- 7. Orders (Matches Image 5: #10254, #10253, #10252, #10251)
INSERT INTO `orders` (`id`, `order_code`, `user_id`, `service_id`, `target_link`, `quantity`, `charge`, `start_count`, `remains`, `status`, `provider_id`, `provider_order_id`, `provider_status`, `created_at`) VALUES
(1, '#10254', 1, 1, 'https://instagram.com/aarisali_official', 1000, 35.00, 4820, 320, 'processing', 1, 'EXT_ORD_88921', 'In progress', '2025-05-12 16:32:00'),
(2, '#10253', 1, 5, 'https://youtube.com/watch?v=smmDemoVideo', 5000, 120.00, 1250, 0, 'completed', 2, 'EXT_ORD_88710', 'Completed', '2025-05-11 18:10:00'),
(3, '#10252', 1, 7, 'https://t.me/techcommunity_in', 2000, 90.00, 8500, 900, 'processing', 1, 'EXT_ORD_88540', 'In progress', '2025-05-10 13:45:00'),
(4, '#10251', 1, 2, 'https://instagram.com/p/C_demoPhoto99', 1000, 20.00, 340, 0, 'completed', 1, 'EXT_ORD_88412', 'Completed', '2025-05-09 19:20:00');

-- 8. Payments (Matches Image 4 & 7)
INSERT INTO `payments` (`id`, `transaction_code`, `user_id`, `amount`, `fee`, `payment_method`, `gateway_order_id`, `gateway_payment_id`, `status`, `created_at`) VALUES
(1, 'PAY_TXN_99182', 1, 500.00, 0.00, 'Razorpay', 'order_Rzp_00192837', 'pay_Rzp_99182a', 'completed', '2025-05-12 16:12:00'),
(2, 'PAY_TXN_98711', 1, 200.00, 0.00, 'Razorpay', 'order_Rzp_00181273', 'pay_Rzp_98711b', 'completed', '2025-05-10 11:20:00');

-- 9. Transactions (Matches Image 7)
INSERT INTO `transactions` (`id`, `user_id`, `type`, `amount`, `direction`, `title`, `description`, `reference_id`, `balance_after`, `created_at`) VALUES
(1, 1, 'add_funds', 500.00, 'credit', 'Add Funds', 'Razorpay Instant Deposit', 'PAY_TXN_99182', 850.50, '2025-05-12 16:12:00'),
(2, 1, 'order_payment', 35.00, 'debit', 'Order Payment', 'Instagram Followers (#10254)', '#10254', 350.50, '2025-05-12 16:32:00'),
(3, 1, 'add_funds', 200.00, 'credit', 'Add Funds', 'Razorpay Instant Deposit', 'PAY_TXN_98711', 385.50, '2025-05-10 11:20:00'),
(4, 1, 'order_payment', 120.00, 'debit', 'Order Payment', 'YouTube Views (#10253)', '#10253', 185.50, '2025-05-10 18:15:00');

-- 10. Support Tickets (Matches Image 8: #T1024, #T1023, #T1022, #T1021)
INSERT INTO `tickets` (`id`, `ticket_code`, `user_id`, `subject`, `department`, `order_code`, `status`, `priority`, `created_at`) VALUES
(1, '#T1024', 1, 'Order not started yet', 'orders', '#10254', 'open', 'high', '2025-05-12 11:20:00'),
(2, '#T1023', 1, 'Payment issue', 'payment', NULL, 'in_progress', 'medium', '2025-05-10 18:15:00'),
(3, '#T1022', 1, 'Service delay', 'service', '#10252', 'closed', 'low', '2025-05-08 15:40:00'),
(4, '#T1021', 1, 'Wrong quantity', 'orders', '#10251', 'closed', 'medium', '2025-05-06 13:10:00');

-- 11. Ticket Messages
INSERT INTO `ticket_messages` (`id`, `ticket_id`, `sender_type`, `sender_id`, `message`, `created_at`) VALUES
(1, 1, 'user', 1, 'Hello, my order #10254 for Instagram Followers was placed over 2 hours ago but status is still pending start. Could you please check with provider?', '2025-05-12 11:20:00'),
(2, 2, 'user', 1, 'Payment was deducted from my bank but the wallet balance took 10 minutes to update. Need confirmation if future payments are instant.', '2025-05-10 18:15:00'),
(3, 2, 'admin', 1, 'Hi Aaris! Yes, our Razorpay webhook had a brief 2-minute gateway delay. Your balance of ₹200 was successfully credited and future payments will be instant.', '2025-05-10 18:25:00'),
(4, 3, 'user', 1, 'Service took 4 hours instead of 1 hour.', '2025-05-08 15:40:00'),
(5, 3, 'admin', 1, 'Issue resolved. System queue cleared and bonus balance credited.', '2025-05-08 16:00:00');

-- 12. Announcements
INSERT INTO `announcements` (`id`, `title`, `content`, `type`, `is_active`, `created_at`) VALUES
(1, 'Instagram Algorithm Update 2025', 'All our Instagram followers and reel views services have been upgraded with high-retention AI nodes. Fast delivery with 0% drop guaranteed!', 'promo', 1, NOW()),
(2, 'Razorpay Instant UPI Activated', 'Enjoy 0% gateway transaction fees on all UPI wallet recharges above ₹500.', 'success', 1, NOW());

-- 13. System Settings
INSERT INTO `settings` (`setting_key`, `setting_value`) VALUES
('site_name', 'SMM Panel'),
('site_tagline', 'Grow Your Social Media'),
('currency_symbol', '₹'),
('currency_code', 'INR'),
('min_deposit', '100'),
('razorpay_key_id', 'rzp_test_1DP5mmOlF5G5ag'),
('razorpay_key_secret', 'mock_rzp_secret_key_889922'),
('auto_provider_sync', '1'),
('ticket_email_notifications', '1');
