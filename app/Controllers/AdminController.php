<?php
declare(strict_types=1);

namespace App\Controllers;

class AdminController
{
    public function __construct() {
        requireAdmin(); // Beveiliging: alleen admins mogen hier komen
    }

    public function index(): void {
        view('admin/dashboard');
    }

    public function users(): void {
        // Hier zou je users ophalen uit de database
        view('admin/users');
    }

    public function approvals(): void {
        view('admin/approvals');
    }

    // --- Resources Management ---

    public function resources(): void {
        $repo = new \App\Repositories\ItemRepository();
        $items = $repo->getAll();
        view('admin/resources/index', ['items' => $items]);
    }

    public function resourceCreate(): void {
        view('admin/resources/create');
    }

    public function resourceStore(): void {
        if (!csrf_verify()) die('Invalid CSRF');
        
        $name = $_POST['name'] ?? '';
        $category = $_POST['category'] ?? '';
        $totalStock = (int)($_POST['total_stock'] ?? 0);

        $repo = new \App\Repositories\ItemRepository();
        $repo->create($name, $category, $totalStock);
        
        redirect('/admin/resources');
    }

    public function resourceEdit(): void {
        $id = (int)($_GET['id'] ?? 0);
        $repo = new \App\Repositories\ItemRepository();
        $item = $repo->find($id);

        if (!$item) {
            redirect('/admin/resources');
        }

        view('admin/resources/edit', ['item' => $item]);
    }

    public function resourceUpdate(): void {
        if (!csrf_verify()) die('Invalid CSRF');

        $id = (int)($_POST['id'] ?? 0);
        $name = $_POST['name'] ?? '';
        $category = $_POST['category'] ?? '';
        $totalStock = (int)($_POST['total_stock'] ?? 0);

        $repo = new \App\Repositories\ItemRepository();
        $repo->update($id, $name, $category, $totalStock);

        redirect('/admin/resources');
    }

    public function resourceDelete(): void {
        if (!csrf_verify()) die('Invalid CSRF');

        $id = (int)($_POST['id'] ?? 0);
        $repo = new \App\Repositories\ItemRepository();
        $repo->delete($id);

        redirect('/admin/resources');
    }
}
