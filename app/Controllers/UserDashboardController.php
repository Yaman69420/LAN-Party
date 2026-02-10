<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\RentalRepository;
use DateTime; // Dit hebben we nodig om dagen te berekenen!

class UserDashboardController
{
    private UserRepository $userRepo;
    private RentalRepository $rentalRepo;

    public function __construct() {
        $this->userRepo = new UserRepository();
        $this->rentalRepo = new RentalRepository();
    }

    public function index(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!function_exists('requireLogin')) {
            if (!isset($_SESSION['user'])) {
                header('Location: /login');
                exit;
            }
        } else {
            requireLogin();
        }

        // 1. Haal alle LAN parties op uit de database
        $rawParties = $this->userRepo->getAllParties();

        $parties = [];
        foreach($rawParties as $p) {
            $startDate = $p['start_date'] ?? null;
            // Als de einddatum leeg is in de database, gebruiken we de startdatum (1 dag)
            $endDate = $p['end_date'] ?? $startDate;

            if ($startDate) {
                $start = new DateTime($startDate);
                $end = new DateTime($endDate);

                // Zet de tijd op 00:00:00 om fouten met uren te voorkomen
                $start->setTime(0, 0, 0);
                $end->setTime(0, 0, 0);

                // DE FIX: Loop door alle dagen van start tot en met eind
                while ($start <= $end) {
                    $dateKey = $start->format('Y-m-d');

                    // Zet het feestje in de array voor DEZE specifieke dag
                    $parties[$dateKey] = $p;

                    // Schuif de datum 1 dag op (+1 day)
                    $start->modify('+1 day');
                }
            }
        }

        // 2. Haal de rest van de data op
        $featuredResources = $this->userRepo->getFeaturedResources();
        $rentals = $this->rentalRepo->getByUser($_SESSION['user']['id']);

        // 3. Stuur alles naar de View
        view('user/dashboard', [
            'username'  => $_SESSION['user']['username'] ?? 'Operative',
            'parties'   => $parties, // Dit bevat nu meerdere dagen voor hetzelfde feestje!
            'resources' => $featuredResources,
            'rentals'   => $rentals
        ]);
    }
}