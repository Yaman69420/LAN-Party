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
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        if (!isset($_SESSION['user'])) { header('Location: /login'); exit; }

        $rawParties = $this->userRepo->getAllParties();
        $parties = [];

        foreach($rawParties as $p) {
            $status = trim(strtolower((string)($p['status'] ?? '')));
            if ($status !== 'approved') { continue; }

            $startDate = $p['start_date'] ?? null;
            $endDate = $p['end_date'] ?? $startDate;

            if ($startDate) {
                $start = new DateTime($startDate);
                $end = new DateTime($endDate);
                $start->setTime(0, 0, 0);
                $end->setTime(0, 0, 0);

                while ($start <= $end) {
                    $dateKey = $start->format('Y-m-d');
                    $parties[$dateKey] = [
                        'name'               => $p['name'],
                        'laptops'            => (int)$p['reserved_laptops'],
                        'attendees'          => (int)$p['expected_attendees']
                    ];
                    $start->modify('+1 day');
                }
            }
        }

        view('user/dashboard', [
            'username'  => $_SESSION['user']['username'] ?? 'Operative',
            'parties'   => $parties,
            'resources' => $this->userRepo->getFeaturedResources(),
            'rentals'   => $this->rentalRepo->getByUser((int)$_SESSION['user']['id'])
        ]);
    }
}