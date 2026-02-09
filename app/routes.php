<?php
declare(strict_types=1);

/**
 * Centraal Route Bestand
 */

// --- Public Routes ---
$router->get('/', 'HomeController', 'index');
$router->get('/resources', 'RentalController', 'index');

// Auth
$router->get('/login', 'AuthController', 'showLogin');
$router->post('/login', 'AuthController', 'login');
$router->get('/register', 'AuthController', 'showRegister');
$router->post('/register', 'AuthController', 'register');
$router->get('/logout', 'AuthController', 'logout');


// --- User Routes (Login verplicht) ---
$router->get('/dashboard', 'HomeController', 'dashboard');
$router->get('/propose', 'HomeController', 'propose');

// --- Admin Routes ---
$router->get('/admin', 'AdminController', 'index');
$router->get('/admin/users', 'AdminController', 'users');
$router->get('/admin/approvals', 'AdminController', 'approvals');
