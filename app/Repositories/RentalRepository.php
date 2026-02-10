<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use PDO;

class RentalRepository
{
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getByUser(int $userId): array {
        $sql = "
            SELECT 
                r.id, 
                r.created_at, 
                r.reservation_date,
                r.rental_status, 
                i.name as item_name, 
                i.category
            FROM rentals r
            JOIN items i ON r.item_id = i.id
            WHERE r.user_id = :user_id
            ORDER BY r.created_at DESC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAllPending(): array {
        $sql = "
            SELECT 
                r.id, 
                r.created_at, 
                r.reservation_date,
                r.rental_status, 
                i.name as item_name, 
                u.username
            FROM rentals r
            JOIN items i ON r.item_id = i.id
            JOIN users u ON r.user_id = u.id
            WHERE r.rental_status = 'pending'
            ORDER BY r.created_at ASC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAll(): array {
        $sql = "
            SELECT 
                r.id, 
                r.created_at, 
                r.reservation_date,
                r.rental_status, 
                i.name as item_name, 
                u.username
            FROM rentals r
            JOIN items i ON r.item_id = i.id
            JOIN users u ON r.user_id = u.id
            ORDER BY r.created_at DESC
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function updateStatus(int $id, string $status): bool {
        $stmt = $this->db->prepare("UPDATE rentals SET rental_status = :status WHERE id = :id");
        return $stmt->execute(['id' => $id, 'status' => $status]);
    }
}
