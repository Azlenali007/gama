<?php
/**
 * SMM Panel - Database Connection Handler (PDO MySQL)
 */

require_once __DIR__ . '/constants.php';

class Database {
    private static ?PDO $instance = null;
    private static string $host = '127.0.0.1';
    private static string $port = '3306';
    private static string $db   = 'smm_panel';
    private static string $user = 'root';
    private static string $pass = '';
    private static string $charset = 'utf8mb4';

    /**
     * Get singleton PDO connection
     */
    public static function getConnection(): PDO {
        if (self::$instance === null) {
            $dsn = "mysql:host=" . self::$host . ";port=" . self::$port . ";dbname=" . self::$db . ";charset=" . self::$charset;
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, self::$user, self::$pass, $options);
            } catch (PDOException $e) {
                // If running without live MySQL, fallback to SQLite or structured mock execution
                try {
                    $sqlitePath = __DIR__ . '/../database/smm_panel.sqlite';
                    self::$instance = new PDO("sqlite:" . $sqlitePath, null, null, $options);
                } catch (Exception $fallbackEx) {
                    throw new RuntimeException("Database connection error: " . $e->getMessage());
                }
            }
        }

        return self::$instance;
    }
}
