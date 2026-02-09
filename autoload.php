<?php
declare(strict_types=1);

spl_autoload_register(function ($class) {
    // De 'virtuele' naam in je code is App (met hoofdletter)
    $prefix = 'App\\';
    // De fysieke map op je schijf is app (kleine letters)
    $baseDir = __DIR__ . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Hier halen we 'App\' van de naam af
    $relativeClass = substr($class, $len);

    // We veranderen de backslashes naar slashes en plakken .php erachter
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});