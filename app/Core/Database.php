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
        // We forceren hier even jouw lokale instellingen voor WAMP
        $host = '127.0.0.1';
        $db   = 'lan_party_db';
        $user = 'root';
        $pass = ''; // WAMP heeft standaard GEEN wachtwoord
        $port = 3306; // Of 3307 als je MariaDB gebruikt in WAMP
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;port=$port;charset=$charset";

        try {
            $this->pdo = new \PDO($dsn, $user, $pass, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (\PDOException $e) {
            // Als dit nog steeds niet werkt, proberen we poort 3307 (MariaDB)
            if ($port === 3306) {
                $dsn = "mysql:host=$host;dbname=$db;port=3307;charset=$charset";
                $this->pdo = new \PDO($dsn, $user, $pass, [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::ATTR_EMULATE_PREPARES => false,
                ]);
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
