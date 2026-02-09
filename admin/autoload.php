<?php
declare(strict_types=1);

/**
 * LAN-Party Autoloader
 * Laadt automatisch classes op basis van namespace
 */

spl_autoload_register(function (string $class): void {
    // Base directory voor Admin namespace
    $baseDir = __DIR__ . '/classes/';
    
    // Vervang namespace separator door directory separator
    $file = $baseDir . str_replace('\\', '/', $class) . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});
