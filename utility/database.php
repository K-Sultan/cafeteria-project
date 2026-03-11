<?php

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        // Load .env variables
        $this->loadEnv();

        $host = getenv('DB_HOST') ?: "127.0.0.1";
        $port = getenv('DB_PORT') ?: "3306";
        $db   = getenv('DB_NAME') ?: "cafeteria";
        $user = getenv('DB_USER') ?: "root";
        $pass = getenv('DB_PASS') ?: "";

        try {
            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
            $this->conn = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    private function loadEnv() {
        $path = __DIR__ . '/../.env';
        if (!file_exists($path)) return;

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) continue; // Skip comments

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
            }
        }
    }

    public static function getConnection() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance->conn;
    }
}