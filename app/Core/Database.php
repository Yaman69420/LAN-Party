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
        $config = require __DIR__ . '/../../config/database.php';

        $port = $config['port'] ?? 3306;

        $dsn = "mysql:host={$config['host']};port={$port};dbname={$config['dbname']};charset={$config['charset']}";

        $this->pdo = new \PDO($dsn, $config['user'], $config['pass'], [
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
