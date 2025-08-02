<?php

namespace App\Repositories;

use App\Models\StatusLog;

class StatusLogRepository
{
    public function getByAppointmentId($appointmentId)
    {
        return StatusLog::with([
            'user:id,name',
            'appointment:id,full_name'
        ])->where('appointment_id', $appointmentId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
