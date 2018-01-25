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

$pdo = new PDO("mysql:host=".$config['host'].";dbname=".$config['dbname'], $config['user'], $config['pass']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$container->config = $config;
$container->pdo = $pdo;

Router::start();
