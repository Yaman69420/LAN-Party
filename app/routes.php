<?php
declare(strict_types=1);

/**
 * Centraal Route Bestand - OPGESCHOOND & GEFIXT
 */

// --- 1. Home & Redirects ---
$router->get('/', 'HomeController', 'index');

// --- 2. Authenticatie ---
$router->get('/login', 'AuthController', 'showLogin');
$router->post('/login', 'AuthController', 'login');
$router->get('/register', 'AuthController', 'showRegister');
$router->post('/register', 'AuthController', 'register');
$router->get('/logout', 'AuthController', 'logout');

// --- 3. Gebruiker Dashboard ---
$router->get('/dashboard', 'UserDashboardController', 'index');

// --- 4. Overige Gebruiker Pagina's ---
$router->get('/resources', 'RentalController', 'index');
$router->post('/rentals/store', 'RentalController', 'store');
$router->get('/propose', 'ProposeController', 'index');
$router->post('/propose', 'ProposeController', 'store');

// --- 5. Admin Sectie (Slechts 1 keer!) ---
$router->get('/admin', 'AdminController', 'index');
$router->get('/admin/users', 'AdminController', 'users');

// DEZE MOET HIER STAAN:
$router->get('/admin/lans', 'AdminController', 'lans');
$router->post('/admin/lans/status', 'AdminController', 'updateLanStatus');

// Admin Resources
$router->get('/admin/resources', 'AdminController', 'resources');
$router->get('/admin/resources/create', 'AdminController', 'resourceCreate');
$router->post('/admin/resources/create', 'AdminController', 'resourceStore');
$router->get('/admin/resources/edit', 'AdminController', 'resourceEdit');
$router->post('/admin/resources/edit', 'AdminController', 'resourceUpdate');
$router->post('/admin/resources/delete', 'AdminController', 'resourceDelete');

// Admin Reservations
$router->get('/admin/reservations', 'AdminController', 'reservations');
$router->post('/admin/reservations/update', 'AdminController', 'reservationUpdate');