<?php
declare(strict_types=1);

/**
 * Admin Entry Point Workaround
 * Dit bestand vangt requests naar /admin op (vanwege de VHost Alias)
 * en stuurt ze door naar de hoofd-applicatie.
 */

// Laad de autoloader van de hoofd-applicatie
require_once __DIR__ . '/../public/autoload.php';

// Laad helpers
require_once __DIR__ . '/../app/Core/helpers.php';

use App\Core\Router;

// Start sessie
session_start();

// Maak router aan
$router = new Router();

// Laad routes vanuit centraal bestand
require __DIR__ . '/../app/routes.php';

// Verwijder query parameters indien nodig voor path matching
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Zorg dat Router werkt met deze path
$_SERVER['REQUEST_URI'] = $path; 

// Dispatch
$router->dispatch();
