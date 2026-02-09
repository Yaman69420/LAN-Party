<?php
declare(strict_types=1);

namespace App\Controllers\User;

/**
 * DashboardController
 * Het dashboard voor ingelogde gebruikers
 */
class DashboardController
{
    public function index(): void
    {
        requireLogin();
        
        view('user/dashboard', [
            'username' => user()['username']
        ]);
    }
}
