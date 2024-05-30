<?php

namespace App\Controllers;

use App\Config\Database;
use App\Repositories\AppointmentRepository;
use App\Services\AppointmentService;
use Exception;
use OpenApi\Attributes as OA;

#[OA\Info(title: "Appointment Scheduler API", version: "1.0")]
class AppointmentController
{
    private $service;

    public function __construct()
    {
        $pdo = Database::getInstance();
        $repository = new AppointmentRepository($pdo);
        $this->service = new AppointmentService($repository);
    }

    #[OA\Get(
        path: "/api/appointments",
        summary: "Get all appointments",
        responses: [
            new OA\Response(response: 200, description: "A list of appointments")
        ]
    )]
    public function loadAppointments()
    {
        header('Content-Type: application/json');
        echo json_encode($this->service->getAllAppointments());
    }

    #[OA\Post(
        path: "/api/appointments/reserve",
        summary: "Reserve an appointment",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "userId", type: "integer"),
                    new OA\Property(property: "slots", type: "array", items: new OA\Items(
                        properties: [
                            new OA\Property(property: "date", type: "string", format: "date"),
                            new OA\Property(property: "time", type: "string", format: "time")
                        ]
                    ))
                ],
                type: "object"
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Appointment reserved successfully"),
            new OA\Response(response: 400, description: "Invalid input")
        ]
    )]
    public function reserve()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $user_id = $input['userId'];
            $slots = $input['slots'];

            try {
                foreach ($slots as $slot) {
                    $this->service->reserveAppointment($user_id, $slot['date'], $slot['time']);
                }
                echo 'Appointment(s) reserved successfully.';
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }

    #[OA\Post(
        path: "/api/appointments/cancel",
        summary: "Cancel an appointment",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "appointmentId", type: "integer"),
                    new OA\Property(property: "userId", type: "integer")
                ],
                type: "object"
            )
        ),
        responses: [
            new OA\Response(response: 200, description: "Appointment canceled successfully"),
            new OA\Response(response: 400, description: "Invalid input")
        ]
    )]
    public function cancel()
    {
        session_start();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = json_decode(file_get_contents('php://input'), true);
            $appointment_id = $input['appointmentId'];
            $user_id = $input['userId'];

            try {
                $this->service->cancelAppointment($appointment_id, $user_id);
                echo 'Appointment canceled successfully.';
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
    }
}
