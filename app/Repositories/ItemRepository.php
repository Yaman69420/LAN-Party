<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Core\Database;
use App\Models\Item;
use PDO;

class ItemRepository
{
    private PDO $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll(): array {
        // Soft delete: alleen items ophalen die NIET verwijderd zijn
        $stmt = $this->db->prepare("SELECT * FROM items WHERE deleted_at IS NULL");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, Item::class);
    }

    public function find(int $id): ?Item {
        $stmt = $this->db->prepare("SELECT * FROM items WHERE id = :id AND deleted_at IS NULL LIMIT 1");
        $stmt->execute(['id' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, Item::class);
        $item = $stmt->fetch();
        return $item ?: null;
    }

    public function findBySlug(string $slug): ?Item {
        $stmt = $this->db->prepare("SELECT * FROM items WHERE slug = :slug AND deleted_at IS NULL LIMIT 1");
        $stmt->execute(['slug' => $slug]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, Item::class);
        $item = $stmt->fetch();
        return $item ?: null;
    }

    public function create(string $name, string $category, int $totalStock): bool {
        // Slug generatie
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
        // Uniek maken indien nodig
        if ($this->findBySlug($slug)) {
            $slug .= '-' . time();
        }

        $stmt = $this->db->prepare("INSERT INTO items (name, slug, category, total_stock) VALUES (:name, :slug, :category, :total_stock)");
        return $stmt->execute(['name' => $name, 'slug' => $slug, 'category' => $category, 'total_stock' => $totalStock]);
    }

    public function update(int $id, string $name, string $category, int $totalStock): bool {
        $stmt = $this->db->prepare("UPDATE items SET name = :name, category = :category, total_stock = :total_stock WHERE id = :id");
        return $stmt->execute(['id' => $id, 'name' => $name, 'category' => $category, 'total_stock' => $totalStock]);
    }

    // Hard delete (definitief verwijderen)
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM items WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Soft delete (markeren als verwijderd)
    public function softDelete(int $id): bool {
        $stmt = $this->db->prepare("UPDATE items SET deleted_at = NOW() WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
