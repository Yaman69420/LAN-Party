<?php
declare(strict_types=1);

/**
 * LAN-Party Admin Panel - Entry Point
 */

require_once __DIR__ . '/autoload.php';

// Start sessie
session_start();

// Haal de request URI op
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($requestUri, PHP_URL_PATH);

// Verwijder /admin prefix
$path = preg_replace('#^/admin#', '', $path) ?: '/';

// Simpele router - voeg hier je routes toe
switch ($path) {
    case '/':
    case '/dashboard':
        // Dashboard view
        include __DIR__ . '/views/dashboard.php';
        break;
    
    default:
        // 404 - Pagina niet gevonden
        http_response_code(404);
        include __DIR__ . '/views/404.php';
        break;
}
