<?php
declare(strict_types=1);

namespace App\Controllers;

/**
 * Admin DashboardController
 * Het dashboard voor administrators
 */
class DashboardController
{
    public function index(): void
    {
        requireAdmin();
        
        view('admin/dashboard');
    }
}
