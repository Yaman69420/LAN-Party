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

// Profile & Social
$router->get('/profile', 'ProfileController', 'index');
$router->get('/profile', 'ProfileController', 'index'); // Eigen profiel
$router->get('/user/profile/{slug}', 'ProfileController', 'viewUser'); // Ander profiel via slug
$router->get('/user/profile', 'ProfileController', 'viewUser'); // Fallback voor oude ID-links
$router->get('/user/search', 'ProfileController', 'search');
$router->post('/user/add-friend', 'ProfileController', 'addFriend');
$router->post('/user/accept-friend', 'ProfileController', 'acceptFriend');

// --- 3. Gebruiker Dashboard ---
$router->get('/dashboard', 'UserDashboardController', 'index');

// --- 4. Overige Gebruiker Pagina's ---
$router->get('/resources', 'RentalController', 'index');
$router->post('/rentals/store', 'RentalController', 'store');

// Proposals
$router->get('/proposals', 'ProposeController', 'list');      // De LIJST
$router->post('/proposals/join', 'ProposeController', 'join');// Join actie
$router->post('/proposals/unjoin', 'ProposeController', 'unjoin'); // Unjoin actie
$router->get('/propose', 'ProposeController', 'create');      // Het FORMULIER
$router->post('/propose', 'ProposeController', 'store');      // Opslaan formulier

// --- 5. Admin Sectie (Slechts 1 keer!) ---
$router->get('/admin', 'AdminController', 'index');

// Admin - Users
$router->get('/admin/users', 'AdminController', 'users');
$router->get('/admin/users/edit', 'AdminController', 'userEdit');
$router->post('/admin/users/update', 'AdminController', 'userUpdate');
$router->post('/admin/users/toggle-status', 'AdminController', 'toggleUserStatus');

// Admin - LANs
$router->get('/admin/lans', 'AdminController', 'lans');
$router->get('/admin/lans/edit', 'AdminController', 'lanEdit'); // Nieuw
$router->post('/admin/lans/update', 'AdminController', 'lanUpdate'); // Nieuw
$router->post('/admin/lans/status', 'AdminController', 'updateLanStatus');

// Admin Resources
$router->get('/admin/resources', 'AdminController', 'resources');
$router->get('/admin/resources/create', 'AdminController', 'resourceCreate');
$router->post('/admin/resources/store', 'AdminController', 'resourceStore');
$router->get('/admin/resources/edit/{slug}', 'AdminController', 'resourceEdit'); // Edit via slug
$router->get('/admin/resources/edit', 'AdminController', 'resourceEdit'); // Fallback ID
$router->post('/admin/resources/update', 'AdminController', 'resourceUpdate');
$router->post('/admin/resources/delete', 'AdminController', 'resourceDelete');

// Admin Reservations
$router->get('/admin/reservations', 'AdminController', 'reservations');
$router->post('/admin/reservations/update', 'AdminController', 'reservationUpdate');