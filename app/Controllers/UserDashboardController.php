<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\LanPartyRepository;

class UserDashboardController
{
    private LanPartyRepository $lanPartyRepo;
    private \App\Repositories\RentalRepository $rentalRepo;

    public function __construct() {
        $this->lanPartyRepo = new LanPartyRepository();
        $this->rentalRepo = new \App\Repositories\RentalRepository();
    }

    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        requireLogin();

        $parties = $this->lanPartyRepo->getAllPartiesWithReservations();
        $rentals = $this->rentalRepo->getByUser($_SESSION['user']['id']);

        view('user/dashboard', [
            'username' => $_SESSION['user']['username'] ?? 'Gast-Gebruiker',
            'parties'  => $parties,
            'rentals'  => $rentals
        ]);
    }
}