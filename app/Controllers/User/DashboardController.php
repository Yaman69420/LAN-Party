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
        // TODO: Check hier of user is ingelogd
        // if (!isLoggedIn()) { redirect('/login'); }
        
        view('user/dashboard', [
            'username' => $_SESSION['user']['username'] ?? 'Gebruiker'
        ]);
    }
}
