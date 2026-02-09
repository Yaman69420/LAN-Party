<?php
declare(strict_types=1);

/**
 * Centraal Route Bestand
 * Hier worden alle routes gedefinieerd voor zowel public als admin entry points.
 * 
 * @var \App\Core\Router $router
 */

// --- Public Routes ---
$router->get('/', 'HomeController', 'index');

// Auth
$router->get('/login', 'Auth\AuthController', 'showLogin');
$router->post('/login', 'Auth\AuthController', 'login');
$router->get('/logout', 'Auth\AuthController', 'logout');

// --- User Routes (Login verplicht) ---
$router->get('/dashboard', 'User\DashboardController', 'index');

// --- Admin Routes ---
// Deze werken nu via /admin (via admin/index.php) én /public/admin (via public/index.php)
$router->get('/admin', 'Admin\DashboardController', 'index');
$router->get('/admin/users', 'Admin\UserController', 'index');
$router->get('/admin/parties', 'Admin\PartyController', 'index');
