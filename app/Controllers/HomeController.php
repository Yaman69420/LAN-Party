<?php
declare(strict_types=1);

namespace App\Controllers;

class HomeController
{
    public function index(): void {
        if (isset($_SESSION['user'])) {
            header('Location: /dashboard');
        } else {
            header('Location: /login');
        }
        exit;
    }
}