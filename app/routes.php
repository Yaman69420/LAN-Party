<?php
declare(strict_types=1);

/**
 * Centraal Route Bestand - DEFINITIEVE VERSIE
 */

// --- 1. Home & Redirects ---
// Deze zorgt dat de HomeController je direct doorstuurt naar /login of /dashboard
$router->get('/', 'HomeController', 'index');

// --- 2. Authenticatie (Login/Logout/Register) ---
$router->get('/login', 'AuthController', 'showLogin');
$router->post('/login', 'AuthController', 'login');
$router->get('/register', 'AuthController', 'showRegister');
$router->post('/register', 'AuthController', 'register');
$router->get('/logout', 'AuthController', 'logout');

// --- 3. Gebruiker Dashboard (Jouw Kalender) ---
// De Router koppelt de URL /dashboard nu direct aan jouw UserDashboardController
$router->get('/dashboard', 'UserDashboardController', 'index');

// --- 4. Overige Gebruiker Pagina's ---
$router->get('/resources', 'RentalController', 'index');
$router->get('/propose', 'HomeController', 'propose');

// --- 5. Admin Sectie (Collega) ---
$router->get('/admin', 'AdminController', 'index');
$router->get('/admin/users', 'AdminController', 'users');
$router->get('/admin/approvals', 'AdminController', 'approvals');