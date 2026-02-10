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
        view('resources/index', ['items' => $items]);
    }

    public function store(): void {
        if (!csrf_verify()) die('Invalid CSRF');

        $itemId = (int)$_POST['item_id'];
        $userId = $_SESSION['user']['id'];
        $reservationDate = $_POST['reservation_date'] ?? date('Y-m-d H:i:s'); // Fallback naar NU als leeg
        
        // Simpele insert zonder validatie (zoals gevraagd in de issue: formulier focus)
        // In een echte app zouden we stock checken en transaction gebruiken.
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("INSERT INTO rentals (user_id, item_id, quantity, reservation_date) VALUES (:user_id, :item_id, 1, :reservation_date)");
        $stmt->execute([
            'user_id' => $userId, 
            'item_id' => $itemId,
            'reservation_date' => $reservationDate
        ]);

        redirect('/resources?status=success');
    }
}
