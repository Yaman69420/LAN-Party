<?php
declare(strict_types=1);

namespace App\Core;

class Database
{
    private static ?self $instance = null;
    private \PDO $pdo;

    private function __construct()
    {
        // 1. Probeer de .env variabelen in te laden (als het bestand bestaat)
        $envPath = __DIR__ . '/../../.env';
        if (file_exists($envPath)) {
            $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) continue;
                list($name, $value) = explode('=', $line, 2);
                $_ENV[trim($name)] = trim($value);
            }
        }

        // 2. Haal de config op (die nu $_ENV gebruikt)
        $config = require __DIR__ . '/../../config/database.php';

        // 3. Bouw de DSN op met fallback waarden voor de zekerheid
        $host    = $_ENV['DB_HOST'] ?? $config['host'] ?? '127.0.0.1';
        $port    = $_ENV['DB_PORT'] ?? $config['port'] ?? '3306';
        $dbname  = $_ENV['DB_NAME'] ?? $config['dbname'] ?? 'lan_party_db';
        $user    = $_ENV['DB_USER'] ?? $config['user'] ?? 'root';
        $pass    = $_ENV['DB_PASS'] ?? $config['pass'] ?? '';
        $charset = $config['charset'] ?? 'utf8mb4';

        $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";

        $this->pdo = new \PDO($dsn, $user, $pass, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): \PDO
    {
        return $this->pdo;
    }
}