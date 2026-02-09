<?php
declare(strict_types=1);

/**
 * LAN-Party Autoloader
 * Laadt classes automatisch op basis van namespace
 */

spl_autoload_register(function (string $class): void {
    // App namespace prefix
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../app/';

    // Controleer of de class onze prefix heeft
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Haal relatieve class naam op
    $relativeClass = substr($class, $len);

    // Vervang namespace separators door directory separators
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
