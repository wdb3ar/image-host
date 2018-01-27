<?php



/**
 * Auto-load classes
 */
spl_autoload_register(function ($class) {
    $path = __DIR__ . '/../src/classes/' . $class . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});

$container = Container::getInstance();

$config = require __DIR__.'/../config.php';

if ($config['debug']) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}

$container->config = $config;

try {
    Router::start();
} catch (NotFoundException $e) {
    if ($container->config['debug']) {
        throw $e;
    }

    $e->showNotFoundPage();
}
