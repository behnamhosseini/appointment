<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Bramus\Router\Router;


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');

$dotenv->safeLoad();
$router = new Router();

$requestUri = $_SERVER['REQUEST_URI'];

if (strpos($requestUri, '/api/') === 0) {
    require_once __DIR__ . '/../routes/api.php';
} else {
    require_once __DIR__ . '/../routes/web.php';
}
$router->run();
?>
