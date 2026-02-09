<?php
declare(strict_types=1);

namespace App\Controllers;

/**
 * HomeController - Homepage
 */
class HomeController
{
    public function index(): void
    {
        view('home');
    }

    public function dashboard(): void
    {
        requireLogin();
        view('user/dashboard', [
            'username' => user()['username']
        ]);
    }

    public function propose(): void
    {
        requireLogin();
        view('propose');
    }
}
