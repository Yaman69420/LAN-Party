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

    /**
     * Haal alle gebruikers op voor de admin
     */
    public function getAllUsers(): array {
        $stmt = $this->db->query("SELECT id, username, email, role, is_active FROM users ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Wissel de status tussen actief (1) en geblokkeerd (0)
     */
    public function toggleUserStatus(int $id, int $currentStatus): bool {
        $newStatus = ($currentStatus === 1) ? 0 : 1;
        $stmt = $this->db->prepare("UPDATE users SET is_active = :status WHERE id = :id");
        return $stmt->execute(['status' => $newStatus, 'id' => $id]);
    }

    /**
     * Update gebruiker gegevens (voor de Edit functie)
     */
    public function update(int $id, array $data): bool {
        $stmt = $this->db->prepare("UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id");
        return $stmt->execute([
            'username' => $data['username'],
            'email'    => $data['email'],
            'role'     => $data['role'],
            'id'       => $id
        ]);
    }

    public function findBySlug(string $slug): ?array {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE slug = :slug LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findByEmail(string $email): ?User {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, User::class);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT id, username, email, role, first_name, last_name FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function searchUsers(string $query, int $excludeId): array {
        $stmt = $this->db->prepare("SELECT id, username, slug FROM users WHERE username LIKE :q AND id != :excl LIMIT 10");
        $stmt->execute(['q' => "%$query%", 'excl' => $excludeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFriends(int $userId): array {
        $sql = "SELECT u.id, u.username, u.slug FROM users u
            JOIN friends f ON (u.id = f.friend_id OR u.id = f.user_id)
            WHERE (f.user_id = :uid1 OR f.friend_id = :uid2)
            AND u.id != :uid3 AND f.status = 'accepted'";

        $stmt = $this->db->prepare($sql);

        // We sturen de ID nu 3x mee voor elke unieke placeholder
        $stmt->execute([
            'uid1' => $userId,
            'uid2' => $userId,
            'uid3' => $userId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getFriendshipStatus(int $myId, int $otherId): ?string {
        $sql = "SELECT status FROM friends 
            WHERE (user_id = :me AND friend_id = :other) 
            OR (user_id = :other2 AND friend_id = :me2) LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'me'     => $myId,
            'other'  => $otherId,
            'other2' => $otherId,
            'me2'    => $myId
        ]);

        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ? $res['status'] : null;
    }

    public function sendFriendRequest(int $from, int $to): bool {
        $stmt = $this->db->prepare("INSERT INTO friends (user_id, friend_id, status) VALUES (:from, :to, 'pending')");
        return $stmt->execute(['from' => $from, 'to' => $to]);
    }

    /**
     * Haal alle binnenkomende vriendschapsverzoeken op die nog op 'pending' staan
     */
    public function getPendingRequests(int $userId): array {
        $sql = "SELECT f.id as request_id, u.id as sender_id, u.username 
            FROM friends f
            JOIN users u ON f.user_id = u.id
            WHERE f.friend_id = :uid AND f.status = 'pending'";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['uid' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function acceptFriendRequest(int $requestId, int $userId): bool {
        $sql = "UPDATE friends SET status = 'accepted' WHERE id = :id AND friend_id = :uid";
        return $this->db->prepare($sql)->execute(['id' => $requestId, 'uid' => $userId]);
    }

    public function create(string $username, string $email, string $passwordhash, string $firstName = '', string $lastName = ''): bool {
        // Slug generatie
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $username), '-'));
        // Uniek maken indien nodig (simpele check, in productie robuuster)
        if ($this->findBySlug($slug)) {
            $slug .= '-' . time();
        }

        $stmt = $this->db->prepare("
            INSERT INTO users (username, slug, email, password_hash, first_name, last_name, role, is_active) 
            VALUES (:username, :slug, :email, :password_hash, :first_name, :last_name, 'user', 1)
        ");
        return $stmt->execute([
            'username' => $username, 
            'slug' => $slug,
            'email' => $email, 
            'password_hash' => $passwordhash,
            'first_name' => $firstName, 
            'last_name' => $lastName
        ]);
    }

    // Haalt 3 specifieke items op voor de 'The Armory' sidebar
    public function getFeaturedResources(): array {
        $sql = "SELECT * FROM items ORDER BY id ASC LIMIT 3";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Haalt alle parties op voor de kalender
    public function getAllParties(): array {
        $stmt = $this->db->prepare("SELECT * FROM lan_parties ORDER BY start_date ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}