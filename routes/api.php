<?php

use App\Controllers\AppointmentController;

$router->get('/api/appointments', function() {
    $controller = new AppointmentController();
    $controller->loadAppointments();
});

$router->post('/api/appointments/reserve', function() {
    $controller = new AppointmentController();
    $controller->reserve();
});

$router->post('/api/appointments/cancel', function() {
    $controller = new AppointmentController();
    $controller->cancel();
});
?>
