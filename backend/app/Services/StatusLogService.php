<?php

namespace App\Services;

use App\Repositories\StatusLogRepository;

class StatusLogService
{
    protected $statusLogRepository;

    public function __construct(StatusLogRepository $statusLogRepository)
    {
        $this->statusLogRepository = $statusLogRepository;
    }

    public function listByAppointment($appointmentId)
    {
        return $this->statusLogRepository->getByAppointmentId($appointmentId);
    }
}
