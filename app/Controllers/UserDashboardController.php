<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\LanPartyRepository;

class UserDashboardController
{
    private LanPartyRepository $lanPartyRepo;

    public function __construct() {
        $this->lanPartyRepo = new LanPartyRepository();
    }

    public function index(): void
    {
        // STAP A: Start de sessie handmatig (soms vergeet de core dit)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // STAP B: Debugging (TIJDELIJK)
        // Als dit op je scherm verschijnt, weten we dat de controller bereikt wordt
        // die $_SESSION['user'] = null; // Haal dit weg als het werkt

        $parties = $this->lanPartyRepo->getAllPartiesWithReservations();

        view('user/dashboard', [
            'username' => $_SESSION['user']['username'] ?? 'Gast-Gebruiker',
            'parties'  => $parties
        ]);
    }
}