<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\ItemRepository;
use App\Repositories\LanPartyRepository;
use App\Repositories\RentalRepository;
use App\Repositories\UserRepository; // TOEGEVOEGD

class AdminController
{
    private ItemRepository $itemRepo;
    private LanPartyRepository $lanRepo;
    private RentalRepository $rentalRepo;
    private UserRepository $userRepo; // TOEGEVOEGD

    public function __construct() {
        requireAdmin();
        $this->itemRepo = new ItemRepository();
        $this->lanRepo = new LanPartyRepository();
        $this->rentalRepo = new RentalRepository();
        $this->userRepo = new UserRepository(); // TOEGEVOEGD: Nu is hij niet meer 'null'
    }

    public function index(): void {
        view('admin/dashboard');
    }

    // --- User Management (Nieuw & Hersteld) ---

    public function users(): void {
        $users = $this->userRepo->getAllUsers();
        view('admin/users/index', ['users' => $users]);
    }

    public function userEdit(): void {
        $id = (int)($_GET['id'] ?? 0);
        $user = $this->userRepo->findById($id);

        if (!$user) {
            header('Location: /admin/users');
            exit;
        }

        view('admin/users/edit', ['user' => $user]);
    }

    public function userUpdate(): void {
        if (!csrf_verify()) die('Invalid CSRF');

        $id = (int)($_POST['id'] ?? 0);
        $data = [
            'username' => $_POST['username'] ?? '',
            'email'    => $_POST['email'] ?? '',
            'role'     => $_POST['role'] ?? 'user'
        ];

        $this->userRepo->update($id, $data);
        header('Location: /admin/users');
        exit;
    }

    public function toggleUserStatus(): void {
        if (!csrf_verify()) die('Invalid CSRF');

        $id = (int)$_POST['id'];
        $currentStatus = (int)$_POST['current_status'];

        $this->userRepo->toggleUserStatus($id, $currentStatus);
        header('Location: /admin/users');
        exit;
    }

    // --- LAN Operations (Behouden) ---

    public function lans(): void {
        $parties = $this->lanRepo->getAllForAdmin();
        view('admin/lans/index', ['parties' => $parties]);
    }

    public function updateLanStatus(): void {
        if (!csrf_verify()) die('Invalid CSRF');
        $id = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';

        if ($id > 0) {
            $this->lanRepo->updateStatus($id, $status);
        }
        header('Location: /admin/lans');
        exit;
    }

    // --- Resources / The Armory (Behouden) ---

    public function resources(): void {
        $items = $this->itemRepo->getAll();
        view('admin/resources/index', ['items' => $items]);
    }
    public function resourceCreate(): void { view('admin/resources/create'); }
    public function resourceStore(): void {
        if (!csrf_verify()) die('Invalid CSRF');
        $this->itemRepo->create($_POST['name'] ?? '', $_POST['category'] ?? '', (int)$_POST['total_stock']);
        header('Location: /admin/resources'); exit;
    }
    public function resourceEdit(?string $slug = null): void {
        if (!$slug && isset($_GET['id'])) {
            $item = $this->itemRepo->find((int)$_GET['id']);
        } elseif ($slug) {
            $item = $this->itemRepo->findBySlug($slug);
        } else {
            $item = null;
        }

        if(!$item) { header('Location: /admin/resources'); exit; }
        view('admin/resources/edit', ['item' => $item]);
    }
    public function resourceUpdate(): void {
        if (!csrf_verify()) die('Invalid CSRF');
        $this->itemRepo->update((int)$_POST['id'], $_POST['name'], $_POST['category'], (int)$_POST['total_stock']);
        header('Location: /admin/resources'); exit;
    }
    public function resourceDelete(): void {
        if (!csrf_verify()) die('Invalid CSRF');
        $id = (int)($_POST['id'] ?? 0);
        $this->itemRepo->softDelete($id);
        header('Location: /admin/resources'); exit;
    }


    // --- Reservation Management (Behouden) ---

    public function reservations(): void {
        $rentals = $this->rentalRepo->getAll();
        view('admin/reservations/index', ['rentals' => $rentals]);
    }

    public function reservationUpdate(): void {
        if (!csrf_verify()) die('Invalid CSRF');

        $id = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';

        if (!in_array($status, ['reserved', 'declined', 'picked_up', 'returned'])) {
            header('Location: /admin/reservations');
            return;
        }

        $this->rentalRepo->updateStatus($id, $status);
        header('Location: /admin/reservations');
        exit;
    }
}