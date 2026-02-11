<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\RentalRepository;
use DateTime;

class UserDashboardController
{
    private UserRepository $userRepo;
    private RentalRepository $rentalRepo;

    public function __construct() {
        $this->userRepo = new UserRepository();
        $this->rentalRepo = new RentalRepository();
    }

    public function index(): void {
        // Sessie starten indien nodig
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        if (!isset($_SESSION['user'])) { header('Location: /login'); exit; }

        $rawParties = $this->userRepo->getAllParties();
        $parties = [];

        foreach($rawParties as $p) {
            // DE HARDE FILTER: Haal status op, forceer kleine letters en trim spaties.
            $status = trim(strtolower((string)($p['status'] ?? '')));

            // Als het NIET exact 'approved' is, slaan we deze over!
            if ($status !== 'approved') {
                continue;
            }

            $startDate = $p['start_date'] ?? null;
            $endDate = $p['end_date'] ?? $startDate;

            if ($startDate) {
                $start = new DateTime($startDate);
                $end = new DateTime($endDate);

                // Tijd strippen om puur op datum te vergelijken
                $start->setTime(0, 0, 0);
                $end->setTime(0, 0, 0);

                while ($start <= $end) {
                    $dateKey = $start->format('Y-m-d');

                    // Data klaarmaken voor de view (en jouw pop-up!)
                    $partyData = $p;
                    $partyData['laptops'] = (int)($p['reserved_laptops'] ?? 0);
                    $partyData['vr'] = (int)($p['reserved_vr'] ?? 0);

                    $parties[$dateKey] = $partyData;

                    $start->modify('+1 day');
                }
            }
        }

        // Gegevens doorsturen naar de weergave (View)
        view('user/dashboard', [
            'username' => $_SESSION['user']['username'] ?? 'Operative',
            'parties' => $parties,
            'resources' => $this->userRepo->getFeaturedResources(),
            'rentals' => $this->rentalRepo->getByUser((int)$_SESSION['user']['id'])
        ]);
    }
}