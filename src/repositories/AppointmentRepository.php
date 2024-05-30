<?php

namespace App\Repositories;

use App\Models\Appointment;
use PDO;

class AppointmentRepository
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll()
    {
        $results = $this->pdo->query("SELECT * FROM appointments")->fetchAll();
        return array_map(function ($row) {
            return new Appointment($row['user_id'], $row['date'], $row['start_time'], $row['end_time'], $row['id']);
        }, $results);
    }

    public function findById($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM appointments WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        if ($row) {
            return new Appointment($row['user_id'], $row['date'], $row['start_time'], $row['end_time'], $row['id']);
        }
        return null;
    }

    public function findByDateAndTime($date, $start_time)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM appointments WHERE date = ? AND start_time = ?");
        $stmt->execute([$date, $start_time]);
        $row = $stmt->fetch();
        if ($row) {
            return new Appointment($row['user_id'], $row['date'], $row['start_time'], $row['end_time'], $row['id']);
        }
        return null;
    }

    public function save(Appointment $appointment)
    {
        if ($appointment->id) {
            $stmt = $this->pdo->prepare("UPDATE appointments SET user_id = ?, date = ?, start_time = ?, end_time = ? WHERE id = ?");
            $stmt->execute([$appointment->user_id, $appointment->date, $appointment->start_time, $appointment->end_time, $appointment->id]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO appointments (user_id, date, start_time, end_time) VALUES (?, ?, ?, ?)");
            $stmt->execute([$appointment->user_id, $appointment->date, $appointment->start_time, $appointment->end_time]);
            $appointment->id = $this->pdo->lastInsertId();
        }
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM appointments WHERE id = ?");
        $stmt->execute([$id]);
    }
}
?>
