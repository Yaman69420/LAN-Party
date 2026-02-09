<?php
declare(strict_types=1);

namespace App\Models;

class User
{
    public int $id;
    public string $username;
    public string $email;
    public string $password;
    public string $role;
    public bool $is_active;
    public string $created_at;

    // Mag ook met constructor, maar public properties is vaak makkelijker voor FETCH_CLASS
}
