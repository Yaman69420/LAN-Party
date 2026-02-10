<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\ItemRepository;
use App\Repositories\LanPartyRepository;
use App\Repositories\RentalRepository;

class AdminController
{
    private ItemRepository $itemRepo;
    private LanPartyRepository $lanRepo;
    private RentalRepository $rentalRepo;

    public function __construct() {
        requireAdmin();
        $this->itemRepo = new ItemRepository();
        $this->lanRepo = new LanPartyRepository();
        $this->rentalRepo = new RentalRepository();
    }

    public function index(): void {
        view('admin/dashboard');
    }

    public function users(): void {
        view('admin/users');
    }

    // --- HIER ZAT DE FOUT: Deze functie is nodig voor de route /admin/lans ---
    public function lans(): void {
        $parties = $this->lanRepo->getAllForAdmin();
        // Let op: hij zoekt nu naar app/Views/admin/lans/index.php
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

    // --- Resources ---
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
        $repo = new \App\Repositories\ItemRepository();
        $repo->softDelete($id);

        redirect('/admin/resources');
    }


    // --- Reservation Management ---
    
    public function reservations(): void {
        $rentals = $this->rentalRepo->getAll();
        view('admin/reservations/index', ['rentals' => $rentals]);
    }

    public function reservationUpdate(): void {
        if (!csrf_verify()) die('Invalid CSRF');

        $id = (int)($_POST['id'] ?? 0);
        $status = $_POST['status'] ?? '';
        
        // Simpele validatie
        if (!in_array($status, ['reserved', 'declined', 'picked_up', 'returned'])) {
            redirect('/admin/reservations');
            return;
        }

        $this->rentalRepo->updateStatus($id, $status);

        redirect('/admin/reservations');
    }
}
