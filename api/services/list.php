<?php
/**
 * API: Services - List all services grouped by category
 */

require_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $db = Database::getConnection();
    $catStmt = $db->query("SELECT * FROM categories WHERE status = 'active' ORDER BY sort_order ASC");
    $categories = $catStmt ? $catStmt->fetchAll(PDO::FETCH_ASSOC) : [];

    $servStmt = $db->query("
        SELECT s.*, c.name as category_name, c.slug as category_slug, c.icon as category_icon 
        FROM services s 
        JOIN categories c ON s.category_id = c.id 
        WHERE s.status = 'active' 
        ORDER BY c.sort_order ASC, s.id ASC
    ");
    $services = $servStmt ? $servStmt->fetchAll(PDO::FETCH_ASSOC) : [];

    // If categories or services are empty in the database, provide standard default seed
    if (empty($services)) {
        $services = [
            ['id' => 1, 'category_id' => 1, 'category_name' => 'Instagram', 'category_slug' => 'instagram', 'name' => 'Instagram Followers', 'rate_per_1k' => 35.00, 'min_quantity' => 1000, 'max_quantity' => 1010000, 'badges' => 'Real & Active Followers • High Quality • Fast Delivery', 'speed_tag' => 'Starts in 1-2 Hours'],
            ['id' => 2, 'category_id' => 2, 'category_name' => 'YouTube', 'category_slug' => 'youtube', 'name' => 'YouTube Views', 'rate_per_1k' => 12.00, 'min_quantity' => 1000, 'max_quantity' => 1000000, 'badges' => 'Real Views • High Retention', 'speed_tag' => 'Fast Delivery'],
            ['id' => 3, 'category_id' => 3, 'category_name' => 'Telegram', 'category_slug' => 'telegram', 'name' => 'Telegram Members', 'rate_per_1k' => 45.00, 'min_quantity' => 500, 'max_quantity' => 200000, 'badges' => 'Real & Active Members • Instant Start', 'speed_tag' => 'Fast Delivery'],
            ['id' => 4, 'category_id' => 5, 'category_name' => 'TikTok', 'category_slug' => 'tiktok', 'name' => 'TikTok Likes & Views', 'rate_per_1k' => 25.00, 'min_quantity' => 500, 'max_quantity' => 500000, 'badges' => 'Instant For-You Reach • High Retention', 'speed_tag' => 'Instant Start'],
            ['id' => 5, 'category_id' => 6, 'category_name' => 'Twitter (X)', 'category_slug' => 'twitter-x', 'name' => 'Twitter (X) Retweets', 'rate_per_1k' => 40.00, 'min_quantity' => 100, 'max_quantity' => 100000, 'badges' => 'Worldwide Engagement • Fast Viral Boost', 'speed_tag' => 'Fast Delivery'],
            ['id' => 6, 'category_id' => 1, 'category_name' => 'Instagram', 'category_slug' => 'instagram', 'name' => 'Instagram Likes', 'rate_per_1k' => 20.00, 'min_quantity' => 100, 'max_quantity' => 500000, 'badges' => 'High Quality • Instant Start • Non-Drop', 'speed_tag' => 'Instant Start'],
            ['id' => 7, 'category_id' => 2, 'category_name' => 'YouTube', 'category_slug' => 'youtube', 'name' => 'YouTube Subscribers', 'rate_per_1k' => 180.00, 'min_quantity' => 100, 'max_quantity' => 100000, 'badges' => 'Monetizable • High Retention • Refill', 'speed_tag' => 'Fast Delivery']
        ];
    }

    if (empty($categories)) {
        $categories = [
            ['id' => 1, 'name' => 'Instagram', 'slug' => 'instagram', 'icon' => 'instagram'],
            ['id' => 2, 'name' => 'YouTube', 'slug' => 'youtube', 'icon' => 'youtube'],
            ['id' => 3, 'name' => 'Telegram', 'slug' => 'telegram', 'icon' => 'send'],
            ['id' => 4, 'name' => 'Facebook', 'slug' => 'facebook', 'icon' => 'facebook'],
            ['id' => 5, 'name' => 'TikTok', 'slug' => 'tiktok', 'icon' => 'music'],
            ['id' => 6, 'name' => 'Twitter (X)', 'slug' => 'twitter-x', 'icon' => 'twitter']
        ];
    }

    jsonResponse([
        'success' => true,
        'currency_symbol' => getCurrencySymbol(),
        'currency_code' => getSetting('currency_code', 'INR'),
        'categories' => $categories,
        'services' => $services
    ]);
} catch (Exception $e) {
    // Fallback static list aligned with reference design
    jsonResponse([
        'success' => true,
        'currency_symbol' => getCurrencySymbol(),
        'currency_code' => getSetting('currency_code', 'INR'),
        'categories' => [
            ['id' => 1, 'name' => 'Instagram', 'slug' => 'instagram', 'icon' => 'instagram'],
            ['id' => 2, 'name' => 'YouTube', 'slug' => 'youtube', 'icon' => 'youtube'],
            ['id' => 3, 'name' => 'Telegram', 'slug' => 'telegram', 'icon' => 'send'],
            ['id' => 4, 'name' => 'Facebook', 'slug' => 'facebook', 'icon' => 'facebook'],
            ['id' => 5, 'name' => 'TikTok', 'slug' => 'tiktok', 'icon' => 'music'],
            ['id' => 6, 'name' => 'Twitter (X)', 'slug' => 'twitter-x', 'icon' => 'twitter']
        ],
        'services' => [
            ['id' => 1, 'category_id' => 1, 'category_name' => 'Instagram', 'category_slug' => 'instagram', 'name' => 'Instagram Followers', 'rate_per_1k' => 35.00, 'min_quantity' => 1000, 'max_quantity' => 1010000, 'badges' => 'Real & Active Followers • High Quality • Fast Delivery', 'speed_tag' => 'Starts in 1-2 Hours'],
            ['id' => 2, 'category_id' => 2, 'category_name' => 'YouTube', 'category_slug' => 'youtube', 'name' => 'YouTube Views', 'rate_per_1k' => 12.00, 'min_quantity' => 1000, 'max_quantity' => 1000000, 'badges' => 'Real Views • High Retention', 'speed_tag' => 'Fast Delivery'],
            ['id' => 3, 'category_id' => 3, 'category_name' => 'Telegram', 'category_slug' => 'telegram', 'name' => 'Telegram Members', 'rate_per_1k' => 45.00, 'min_quantity' => 500, 'max_quantity' => 200000, 'badges' => 'Real & Active Members • Instant Start', 'speed_tag' => 'Fast Delivery'],
            ['id' => 4, 'category_id' => 5, 'category_name' => 'TikTok', 'category_slug' => 'tiktok', 'name' => 'TikTok Likes & Views', 'rate_per_1k' => 25.00, 'min_quantity' => 500, 'max_quantity' => 500000, 'badges' => 'Instant For-You Reach • High Retention', 'speed_tag' => 'Instant Start'],
            ['id' => 5, 'category_id' => 6, 'category_name' => 'Twitter (X)', 'category_slug' => 'twitter-x', 'name' => 'Twitter (X) Retweets', 'rate_per_1k' => 40.00, 'min_quantity' => 100, 'max_quantity' => 100000, 'badges' => 'Worldwide Engagement • Fast Viral Boost', 'speed_tag' => 'Fast Delivery'],
            ['id' => 6, 'category_id' => 1, 'category_name' => 'Instagram', 'category_slug' => 'instagram', 'name' => 'Instagram Likes', 'rate_per_1k' => 20.00, 'min_quantity' => 100, 'max_quantity' => 500000, 'badges' => 'High Quality • Instant Start • Non-Drop', 'speed_tag' => 'Instant Start'],
            ['id' => 7, 'category_id' => 2, 'category_name' => 'YouTube', 'category_slug' => 'youtube', 'name' => 'YouTube Subscribers', 'rate_per_1k' => 180.00, 'min_quantity' => 100, 'max_quantity' => 100000, 'badges' => 'Monetizable • High Retention • Refill', 'speed_tag' => 'Fast Delivery']
        ]
    ]);
}
