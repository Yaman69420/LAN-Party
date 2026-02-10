<?php
declare(strict_types=1);

namespace App\Controllers;

class RentalController
{
    public function __construct() {
        requireLogin(); // Alleen ingelogde gebruikers mogen resources zien
    }

    public function index(): void {
        view('resources/index');
    }
}
