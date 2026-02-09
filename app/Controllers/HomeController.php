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
}
