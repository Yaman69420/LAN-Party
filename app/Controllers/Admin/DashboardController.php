<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

/**
 * Admin DashboardController
 * Het dashboard voor administrators
 */
class DashboardController
{
    public function index(): void
    {
        // TODO: Check hier of user admin is
        // if (!isAdmin()) { redirect('/login'); }
        
        view('admin/dashboard');
    }
}
