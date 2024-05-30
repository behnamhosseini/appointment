<?php

namespace App\Services;

use App\Repositories\AppointmentRepository;
use App\Models\Appointment;
use Exception;

class AppointmentService
{
    private $repository;

    public function __construct(AppointmentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAllAppointments()
    {
        return $this->repository->findAll();
    }

    public function reserveAppointment($user_id, $date, $start_time)
    {
        $end_time = date('H:i', strtotime($start_time) + 1800); // 30 minutes later
        if ($this->repository->findByDateAndTime($date, $start_time)) {
            throw new Exception('This slot is already booked.');
        }

        $appointment = new Appointment($user_id, $date, $start_time, $end_time);
        $this->repository->save($appointment);
    }

    public function cancelAppointment($id, $user_id)
    {
        $appointment = $this->repository->findById($id);
        if (!$appointment || $appointment->user_id != $user_id) {
            throw new Exception('Invalid appointment ID.');
        }

        $this->repository->delete($id);
    }
}
?>
