<?php
declare(strict_types=1);

namespace App\Models;

class User
{
    public int $id;
    public string $username;
    public string $email;
    public string $password = '';
    public string $role = 'user';
    public ?string $first_name = null;
    public ?string $last_name = null;
    public ?string $password_hash = null;
    public ?int $is_active = null;
    public ?string $created_at = null;
}