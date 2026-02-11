<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\ItemRepository;

class RentalController
{
    private ItemRepository $itemRepo;

    public function __construct() {
        requireLogin();
        $this->itemRepo = new ItemRepository();
    }

    public function index(): void {
        $items = $this->itemRepo->getAll();
        $lanRepo = new \App\Repositories\LanPartyRepository();
        $upcomingLans = $lanRepo->getAllUpcoming();
        
        view('resources/index', [
            'items' => $items,
            'upcomingLans' => $upcomingLans
        ]);
    }

    public function store(): void {
        if (!csrf_verify()) die('Invalid CSRF');

        $itemId = (int)$_POST['item_id'];
        $userId = $_SESSION['user']['id'];
        $lanId  = (int)$_POST['lan_party_id'];
        $qty    = (int)($_POST['quantity'] ?? 1);
        
        // 1. LAN Party ophalen voor de datum
        $lanRepo = new \App\Repositories\LanPartyRepository();
        $lan = $lanRepo->findById($lanId);

        if (!$lan) {
            redirect('/resources?status=error&message=invalid_lan');
        }

        // Gebruik de startdatum van de LAN als reserveringsdatum
        $reservationDate = $lan['start_date'];
        
        // 1. Stock Check (Validatie)
        $item = $this->itemRepo->find($itemId);
        if (!$item || $item->total_stock < $qty) {
             redirect('/resources?status=error&message=out_of_stock');
        }

        // 2. Reservering Opslaan
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO rentals (user_id, item_id, quantity, reservation_date, lan_party_id) VALUES (:user_id, :item_id, :qty, :reservation_date, :lan_party_id)");
        $stmt->execute([
            'user_id' => $userId, 
            'item_id' => $itemId,
            'qty'     => $qty,
            'reservation_date' => $reservationDate,
            'lan_party_id' => $lanId
        ]);

        redirect('/resources?status=success');
    }
}
