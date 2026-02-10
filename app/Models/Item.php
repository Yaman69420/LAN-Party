<?php
declare(strict_types=1);

namespace App\Models;

class Item
{
    public int $id;
    public string $name;
    public string $category;
    public int $total_stock;
    public string $created_at;
    public ?string $deleted_at = null;
    public ?string $slug = null;
}
