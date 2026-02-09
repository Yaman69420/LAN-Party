<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Repositories\UserRepository;

class AuthController
{
    private UserRepository $userRepo;

    public function __construct() {
        $this->userRepo = new UserRepository();
    }

    public function login(): void {
        if (!csrf_verify()) die('Invalid CSRF token');

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $user = $this->userRepo->findByEmail($email);

        if ($user && password_verify($password, $user->password)) {
            $_SESSION['user'] = [
                'id' => $user->id,
                'username' => $user->username,
                'role' => $user->role
            ];
            header("Location: /dashboard");
            exit;
        } else {
            view('auth/login', ['error' => 'Ongeldige inloggegevens']);
        }
    }

    public function showLogin(): void {
        if (isset($_SESSION['user'])) { header("Location: /dashboard"); exit; }
        view('auth/login');
    }

    public function logout(): void {
        session_destroy();
        header("Location: /login");
        exit;
    }
}