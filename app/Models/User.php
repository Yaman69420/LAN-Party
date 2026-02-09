<?php
declare(strict_types=1);

namespace App\Models;

class User
{
    public int $id;
    public string $first_name;
    public string $last_name;
    public string $username;
    public string $email;
    public string $password_hash; // Changed from 'password' to match DB column
    public string $role;
    public int $is_active; // Changed to int to match TINYINT(1) DB return
    public string $created_at;

    // Helper to get full name
    public function getFullName(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}