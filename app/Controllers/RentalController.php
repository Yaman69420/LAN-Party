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
}
