<?php

namespace App\Models;

class Appointment
{
    public $id;
    public $user_id;
    public $date;
    public $start_time;
    public $end_time;

    public function __construct($user_id, $date, $start_time, $end_time, $id = null)
    {
        $this->id = $id;
        $this->user_id = $user_id;
        $this->date = $date;
        $this->start_time = $start_time;
        $this->end_time = $end_time;
    }
}
