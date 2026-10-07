<?php
/**
 * API: Services - List all services grouped by category
 */

require_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json; charset=utf-8');

try {
    $db = Database::getConnection();
    $catStmt = $db->query("SELECT * FROM categories WHERE status = 'active' ORDER BY sort_order ASC");
    $categories = $catStmt->fetchAll();

    $servStmt = $db->query("
        SELECT s.*, c.name as category_name, c.slug as category_slug 
        FROM services s 
        JOIN categories c ON s.category_id = c.id 
        WHERE s.status = 'active' 
        ORDER BY c.sort_order ASC, s.id ASC
    ");
    $services = $servStmt->fetchAll();

    jsonResponse([
        'success' => true,
        'categories' => $categories,
        'services' => $services
    ]);
} catch (Exception $e) {
    // Fallback static list aligned with reference design
    jsonResponse([
        'success' => true,
        'categories' => [
            ['id' => 1, 'name' => 'Instagram', 'slug' => 'instagram', 'icon' => 'instagram'],
            ['id' => 2, 'name' => 'YouTube', 'slug' => 'youtube', 'icon' => 'youtube'],
            ['id' => 3, 'name' => 'Telegram', 'slug' => 'telegram', 'icon' => 'send'],
            ['id' => 4, 'name' => 'Facebook', 'slug' => 'facebook', 'icon' => 'facebook'],
            ['id' => 5, 'name' => 'TikTok', 'slug' => 'tiktok', 'icon' => 'video'],
            ['id' => 6, 'name' => 'Twitter (X)', 'slug' => 'twitter-x', 'icon' => 'twitter']
        ],
        'services' => [
            ['id' => 1, 'category_id' => 1, 'name' => 'Instagram Followers', 'rate_per_1k' => 35.00, 'min_quantity' => 1000, 'max_quantity' => 1010000, 'badges' => 'High quality followers | Instant Start | No Drop', 'speed_tag' => 'Fast Delivery'],
            ['id' => 2, 'category_id' => 1, 'name' => 'Instagram Likes', 'rate_per_1k' => 20.00, 'min_quantity' => 100, 'max_quantity' => 500000, 'badges' => 'High Quality | Instant Start | Non-Drop', 'speed_tag' => 'Fast Delivery'],
            ['id' => 3, 'category_id' => 1, 'name' => 'Instagram Views', 'rate_per_1k' => 15.00, 'min_quantity' => 500, 'max_quantity' => 2000000, 'badges' => 'Instant Start | High Retention | 100% Safe', 'speed_tag' => 'Ultra Fast'],
            ['id' => 4, 'category_id' => 1, 'name' => 'Instagram Comments', 'rate_per_1k' => 50.00, 'min_quantity' => 10, 'max_quantity' => 50000, 'badges' => 'Custom Comments | Verified Look | HQ', 'speed_tag' => 'Speed 1K/day'],
            ['id' => 5, 'category_id' => 2, 'name' => 'YouTube Views', 'rate_per_1k' => 120.00, 'min_quantity' => 1000, 'max_quantity' => 1000000, 'badges' => 'Monetizable | High Retention | Refill', 'speed_tag' => 'Fast Delivery'],
            ['id' => 7, 'category_id' => 3, 'name' => 'Telegram Members', 'rate_per_1k' => 90.00, 'min_quantity' => 500, 'max_quantity' => 200000, 'badges' => '0% Drop | Real Profiles | Fast Add', 'speed_tag' => 'Instant Start']
        ]
    ]);
}
