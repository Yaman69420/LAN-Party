<?php
declare(strict_types=1);

/**
 * LAN-Party Public Website - Entry Point
 */

// Start sessie
session_start();

// Haal de request URI op
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($requestUri, PHP_URL_PATH);

// Simpele router - voeg hier je routes toe
switch ($path) {
    case '/':
        // Homepage
        include __DIR__ . '/views/home.php';
        break;
    
    default:
        // 404 - Pagina niet gevonden
        http_response_code(404);
        include __DIR__ . '/views/404.php';
        break;
}
