<?php
declare(strict_types=1);

namespace App\Core;

/**
 * Database class - PDO wrapper
 */
class Database
{
    private static ?self $instance = null;
    private \PDO $pdo;

    private function __construct()
    {
        // Laad configuratie
        $config = require __DIR__ . '/../../config/database.php';

        $host = $config['host'] ?? '127.0.0.1';
        $db   = $config['dbname'] ?? 'lan_party_db';
        $user = $config['user'] ?? 'root';
        $pass = $config['pass'] ?? 'root';
        $port = $config['port'] ?? 8889; // Default MAMP
        $charset = $config['charset'] ?? 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;port=$port;charset=$charset";
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            $this->pdo = new \PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            // Fallback: Als 8889 niet werkt, probeer standaard 3306
            if ($port === 8889) {
                try {
                    $dsn = "mysql:host=$host;dbname=$db;port=3306;charset=$charset";
                    $this->pdo = new \PDO($dsn, $user, $pass, $options);
                } catch (\PDOException $e2) {
                    // Gooi originele error als fallback ook faalt
                    throw new \PDOException($e->getMessage(), (int)$e->getCode());
                }
            } else {
                throw new \PDOException($e->getMessage(), (int)$e->getCode());
            }
        }
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
