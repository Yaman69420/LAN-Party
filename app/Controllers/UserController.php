<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Repositories\UserRepository;

class UserController {
    private UserRepository $userRepo;

    public function __construct() {
        // Alleen admins mogen hier komen
        if ($_SESSION['user']['role'] !== 'admin') {
            header('Location: /dashboard');
            exit;
        }
        $this->userRepo = new UserRepository();
    }

    public function index() {
        $users = $this->userRepo->getAllUsers();
        view('admin/users/index', ['users' => $users]);
    }

    public function toggleStatus() {
        $id = (int)$_POST['id'];
        $currentStatus = (int)$_POST['current_status'];
        $this->userRepo->toggleUserStatus($id, $currentStatus);
        header('Location: /admin/users');
    }
}
