<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Models\User;
use PDO;

class UserRepository
{
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findByEmail(string $email): ?User
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    public function create(string $username, string $email, string $passwordhash, string $firstName = '', string $lastName = ''): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (username, email, password_hash, first_name, last_name, role, is_active) 
            VALUES (:username, :email, :password_hash, :first_name, :last_name, 'user', 1)
        ");
        
        return $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password_hash' => $passwordhash,
            'first_name' => $firstName,
            'last_name' => $lastName
        ]);
    }
}