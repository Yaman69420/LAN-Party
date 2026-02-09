<?php
declare(strict_types=1);

/**
 * LAN-Party - Front Controller
 * Dit is het enige entry point van de applicatie
 */

// Start sessie voor CSRF en flash messages
session_start();

// Laad autoloader (vanuit root)
require_once __DIR__ . '/../autoload.php';

// Laad helpers
require_once __DIR__ . '/../app/Core/helpers.php';

use App\Core\Router;

// Maak router aan
$router = new Router();

// ===========================================
// ROUTES
// ===========================================

// Laad routes vanuit centraal bestand
require __DIR__ . '/../app/routes.php';

// Dispatch request naar juiste controller
$router->dispatch();
