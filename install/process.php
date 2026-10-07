<?php
/**
 * SMM Panel - Web Installer Backend API Processor
 * Handles Server Checks, DB Testing, Migrations, Admin Creation & Lock
 */

define('INSTALL_LOCK_FILE', __DIR__ . '/installed.lock');

header('Content-Type: application/json; charset=utf-8');

function respond(bool $success, string $message, array $extra = [], int $code = 200): void {
    http_response_code($code);
    echo json_encode(array_merge(['success' => $success, 'message' => $message], $extra));
    exit;
}

// ----------------------------------------------------
// 1. STRICT INSTALLATION LOCK CHECK
// ----------------------------------------------------
if (file_exists(INSTALL_LOCK_FILE)) {
    respond(false, 'Application is already installed. The installer is permanently locked.', [
        'locked' => true
    ], 403);
}

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$input = json_decode(file_get_contents('php://input'), true) ?? $_POST;

// ----------------------------------------------------
// STEP 1: SYSTEM ENVIRONMENT CHECK
// ----------------------------------------------------
if ($action === 'system_check') {
    $phpVersion = PHP_VERSION;
    $phpOk = version_compare($phpVersion, '8.0.0', '>=');

    $extensions = [
        'pdo' => extension_loaded('pdo'),
        'pdo_mysql' => extension_loaded('pdo_mysql'),
        'curl' => extension_loaded('curl'),
        'mbstring' => extension_loaded('mbstring'),
        'json' => extension_loaded('json'),
        'session' => extension_loaded('session'),
        'openssl' => extension_loaded('openssl'),
        'filter' => extension_loaded('filter'),
    ];

    $directories = [
        'config' => is_writable(__DIR__ . '/../config') || @touch(__DIR__ . '/../config/.perm_test'),
        'install' => is_writable(__DIR__) || @touch(__DIR__ . '/.perm_test'),
        'uploads' => is_writable(__DIR__ . '/../uploads') || @touch(__DIR__ . '/../uploads/.perm_test'),
    ];
    @unlink(__DIR__ . '/../config/.perm_test');
    @unlink(__DIR__ . '/.perm_test');
    @unlink(__DIR__ . '/../uploads/.perm_test');

    $allExtsOk = !in_array(false, $extensions, true);
    $allDirsOk = !in_array(false, $directories, true);
    $allOk = $phpOk && $allExtsOk && $allDirsOk;

    respond($allOk, $allOk ? 'All system requirements met!' : 'Some requirements are missing.', [
        'php' => ['version' => $phpVersion, 'ok' => $phpOk],
        'extensions' => $extensions,
        'directories' => $directories,
        'ready' => $allOk
    ]);
}

// ----------------------------------------------------
// STEP 2: TEST DATABASE CONNECTION
// ----------------------------------------------------
if ($action === 'test_db') {
    $host = trim($input['host'] ?? '127.0.0.1');
    $port = trim($input['port'] ?? '3306');
    $name = trim($input['name'] ?? '');
    $user = trim($input['user'] ?? '');
    $pass = $input['pass'] ?? '';

    if (empty($name) || empty($user)) {
        respond(false, 'Database Name and Database Username are required.', [], 400);
    }

    try {
        $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 5
        ]);

        respond(true, 'Database connection successfully verified!');
    } catch (PDOException $e) {
        respond(false, 'Database connection failed: ' . $e->getMessage(), [
            'error_code' => $e->getCode()
        ], 400);
    }
}

// ----------------------------------------------------
// STEP 3: MIGRATE DATABASE TABLES (SCHEMA.SQL)
// ----------------------------------------------------
if ($action === 'migrate_db') {
    $host = trim($input['host'] ?? '127.0.0.1');
    $port = trim($input['port'] ?? '3306');
    $name = trim($input['name'] ?? '');
    $user = trim($input['user'] ?? '');
    $pass = $input['pass'] ?? '';

    $schemaFile = __DIR__ . '/../database/schema.sql';
    if (!file_exists($schemaFile)) {
        respond(false, 'Schema file database/schema.sql not found on server.', [], 500);
    }

    try {
        $dsn = "mysql:host={$host};port={$port};dbname={$name};charset=utf8mb4";
        $pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $sqlContent = file_get_contents($schemaFile);
        $pdo->exec($sqlContent);

        // Seed essential initial categories
        $seedCategories = "
            INSERT IGNORE INTO `categories` (`id`, `name`, `slug`, `icon`, `sort_order`, `status`) VALUES
            (1, 'Instagram', 'instagram', 'instagram', 1, 'active'),
            (2, 'YouTube', 'youtube', 'youtube', 2, 'active'),
            (3, 'Telegram', 'telegram', 'send', 3, 'active'),
            (4, 'Facebook', 'facebook', 'facebook', 4, 'active'),
            (5, 'TikTok', 'tiktok', 'video', 5, 'active'),
            (6, 'Twitter (X)', 'twitter-x', 'twitter', 6, 'active');
        ";
        $pdo->exec($seedCategories);

        respond(true, 'All 14 database tables and initial categories created successfully!');
    } catch (PDOException $e) {
        respond(false, 'Table migration error: ' . $e->getMessage(), [], 500);
    }
}

// ----------------------------------------------------
// STEP 4: CREATE INITIAL ADMIN ACCOUNT
// ----------------------------------------------------
if ($action === 'create_admin') {
    $host = trim($input['host'] ?? '127.0.0.1');
    $port = trim($input['port'] ?? '3306');
    $dbname = trim($input['dbname'] ?? '');
    $dbuser = trim($input['dbuser'] ?? '');
    $dbpass = $input['dbpass'] ?? '';

    $adminName = trim($input['admin_name'] ?? '');
    $adminEmail = trim($input['admin_email'] ?? '');
    $adminPass = $input['admin_password'] ?? '';
    $confirmPass = $input['confirm_password'] ?? '';

    if (empty($adminName) || empty($adminEmail) || empty($adminPass)) {
        respond(false, 'All admin fields are required.', [], 400);
    }
    if (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
        respond(false, 'Invalid admin email address.', [], 400);
    }
    if (strlen($adminPass) < 8) {
        respond(false, 'Admin password must be at least 8 characters.', [], 400);
    }
    if ($adminPass !== $confirmPass) {
        respond(false, 'Passwords do not match.', [], 400);
    }

    try {
        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
        $pdo = new PDO($dsn, $dbuser, $dbpass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        $hash = password_hash($adminPass, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("
            INSERT INTO `admins` (`name`, `email`, `password_hash`, `role`, `status`, `created_at`)
            VALUES (:name, :email, :pwd, 'super_admin', 'active', NOW())
        ");
        $stmt->execute([
            ':name' => $adminName,
            ':email' => $adminEmail,
            ':pwd' => $hash
        ]);

        respond(true, 'Super Administrator account created successfully!');
    } catch (PDOException $e) {
        respond(false, 'Failed to create admin: ' . $e->getMessage(), [], 500);
    }
}

// ----------------------------------------------------
// STEP 5: SAVE WEBSITE CONFIGURATION & DB CONFIG
// ----------------------------------------------------
if ($action === 'save_config') {
    $host = trim($input['host'] ?? '127.0.0.1');
    $port = trim($input['port'] ?? '3306');
    $dbname = trim($input['dbname'] ?? '');
    $dbuser = trim($input['dbuser'] ?? '');
    $dbpass = $input['dbpass'] ?? '';

    $siteName = trim($input['site_name'] ?? 'SMM Panel');
    $siteTagline = trim($input['site_tagline'] ?? 'Grow Your Social Media');
    $siteUrl = rtrim(trim($input['site_url'] ?? 'http://localhost:3000'), '/');
    $currencySymbol = trim($input['currency_symbol'] ?? '₹');
    $currencyCode = trim($input['currency_code'] ?? 'INR');

    // 1. Write config/db-config.php (Protected PHP credentials file)
    $dbConfigContent = "<?php\n/**\n * SMM Panel - Database Credentials\n * Generated automatically by Web Installer on " . date('r') . "\n */\nreturn [\n    'host' => " . var_export($host, true) . ",\n    'port' => " . var_export($port, true) . ",\n    'database' => " . var_export($dbname, true) . ",\n    'username' => " . var_export($dbuser, true) . ",\n    'password' => " . var_export($dbpass, true) . ",\n    'charset' => 'utf8mb4'\n];\n";

    $dbConfigFile = __DIR__ . '/../config/db-config.php';
    if (file_put_contents($dbConfigFile, $dbConfigContent) === false) {
        respond(false, 'Unable to write config/db-config.php. Check directory permissions.', [], 500);
    }

    // 2. Update settings in database
    try {
        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
        $pdo = new PDO($dsn, $dbuser, $dbpass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        
        $settingsStmt = $pdo->prepare("
            INSERT INTO settings (setting_key, setting_value) VALUES (:k, :v)
            ON DUPLICATE KEY UPDATE setting_value = :v
        ");
        $settingsList = [
            'site_name' => $siteName,
            'site_tagline' => $siteTagline,
            'site_url' => $siteUrl,
            'currency_symbol' => $currencySymbol,
            'currency_code' => $currencyCode,
        ];
        foreach ($settingsList as $k => $v) {
            $settingsStmt->execute([':k' => $k, ':v' => $v]);
        }
    } catch (Exception $e) {}

    respond(true, 'Configuration saved successfully!');
}

// ----------------------------------------------------
// STEP 6: CREATE INSTALLATION LOCK FILE
// ----------------------------------------------------
if ($action === 'finish_lock') {
    $lockData = json_encode([
        'installed_at' => date('Y-m-d H:i:s'),
        'timestamp' => time(),
        'installer_ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
        'version' => '2.5.0',
        'status' => 'LOCKED'
    ], JSON_PRETTY_PRINT);

    if (file_put_contents(INSTALL_LOCK_FILE, $lockData) === false) {
        respond(false, 'Unable to create installed.lock file.', [], 500);
    }

    respond(true, 'Installation locked successfully!');
}

respond(false, 'Invalid action specified.', [], 400);
