<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

/**
 * Admin UserController
 * Beheer van gebruikers (alleen toegankelijk voor admins)
 */
class UserController
{
    public function __construct() {
        // TODO: Check hier of gebruiker admin is!
        // if (!isAdmin()) { redirect('/login'); }
    }

    public function index(): void
    {
        // Haal alle users op
        $users = []; // $this->userRepo->getAll();
        
        view('admin/users/index', ['users' => $users]);
    }
}
