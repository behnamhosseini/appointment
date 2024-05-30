<?php

use Bramus\Router\Router;

$router->get('/', function() {
    require_once __DIR__ . '/../views/appointments/index.php';
});
?>
