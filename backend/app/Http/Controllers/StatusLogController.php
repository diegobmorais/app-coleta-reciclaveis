<?php

namespace App\Http\Controllers;

use App\Services\StatusLogService;

class StatusLogController extends Controller
{
    protected $statusLogService;

    public function __construct(StatusLogService $statusLogService)
    {
        $this->statusLogService = $statusLogService;
    }

    public function index($appointmentId)
    {
        $logs = $this->statusLogService->listByAppointment($appointmentId);

        return response()->json($logs, 200);
    }
}
