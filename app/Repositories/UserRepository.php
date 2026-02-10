<?php

namespace App\Repositories;

use App\Core\Database;
use PDO;

class UserRepository
{
    private $db;

    public function __construct()
    {
        $wrapper = Database::getInstance();

        // Automatische detectie van de database connectie
        if ($wrapper instanceof PDO) {
            $this->db = $wrapper;
        } elseif (method_exists($wrapper, 'getConnection')) {
            $this->db = $wrapper->getConnection();
        } elseif (property_exists($wrapper, 'pdo')) {
            $this->db = $wrapper->pdo;
        } elseif (property_exists($wrapper, 'connection')) {
            $this->db = $wrapper->connection;
        } else {
            $this->db = $wrapper;
        }
    }

    // --- AUTHENTICATIE (Login & Profiel) ---
    // Deze geven een OBJECT terug ($user->password_hash) voor de AuthController

    public function findById(int $id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function findByEmail(string $email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function findByUsername(string $username)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // --- ADMIN PANEL FUNCTIE (DEZE MISTE JE) ---

    public function getAllUsers(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM users ORDER BY created_at DESC");
        $stmt->execute();
        // We geven arrays terug, dat werkt meestal het makkelijkst in admin tabellen
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // --- PROFIEL UPDATEN ---

    public function updateProfile(int $id, array $data): bool
    {
        $sql = "UPDATE users SET 
                first_name = :f, 
                last_name = :l, 
                email = :email, 
                profile_image = :img 
                WHERE id = :id";

        return $this->db->prepare($sql)->execute([
            'f'     => $data['first_name'],
            'l'     => $data['last_name'],
            'email' => $data['email'],
            'img'   => $data['profile_image'],
            'id'    => $id
        ]);
    }

    // --- SQUAD / VRIENDEN FUNCTIES ---

    public function searchUsers(string $query, int $excludeId): array {
        $sql = "SELECT * FROM users 
                WHERE (username LIKE :q1 OR first_name LIKE :q2 OR last_name LIKE :q3) 
                AND id != :id 
                LIMIT 10";
        $stmt = $this->db->prepare($sql);
        $term = "%$query%";
        $stmt->execute([
            'q1' => $term,
            'q2' => $term,
            'q3' => $term,
            'id' => $excludeId
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFriendshipStatus(int $userId, int $friendId): ?string {
        $sql = "SELECT status FROM friends 
                WHERE (user_id = :u1 AND friend_id = :f1) 
                   OR (user_id = :f2 AND friend_id = :u2)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'u1' => $userId,
            'f1' => $friendId,
            'f2' => $friendId,
            'u2' => $userId
        ]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['status'] : null;
    }

    public function sendFriendRequest(int $userId, int $friendId): bool {
        if ($this->getFriendshipStatus($userId, $friendId)) return false;

        $sql = "INSERT INTO friends (user_id, friend_id, status, created_at) VALUES (:u, :f, 'pending', NOW())";
        return $this->db->prepare($sql)->execute(['u' => $userId, 'f' => $friendId]);
    }

    public function getPendingRequests(int $userId): array {
        $sql = "SELECT f.id as request_id, u.id, u.username, u.profile_image 
                FROM friends f
                JOIN users u ON f.user_id = u.id
                WHERE f.friend_id = :id AND f.status = 'pending'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function acceptFriendRequest(int $requestId): bool {
        $sql = "UPDATE friends SET status = 'accepted' WHERE id = :id";
        return $this->db->prepare($sql)->execute(['id' => $requestId]);
    }

    public function getFriends(int $userId): array {
        $sql = "SELECT u.* FROM users u
                JOIN friends f ON (u.id = f.friend_id OR u.id = f.user_id)
                WHERE (f.user_id = :uid1 OR f.friend_id = :uid2)
                AND f.status = 'accepted'
                AND u.id != :uid3";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'uid1' => $userId,
            'uid2' => $userId,
            'uid3' => $userId
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}