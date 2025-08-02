<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentStatusRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Services\AppointmentService;

class AppointmentController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }
    public function index(Request $request)
    {
        $appointments = $this->appointmentService->getFilteredAppointments($request->all());

        return response()->json($appointments, 200);
    }
    public function store(StoreAppointmentRequest $request)
    {
        try {
            $appointment = $this->appointmentService->createAppointment($request->validated());

            return response()->json([
                'message' => 'Agendamento realizado com sucesso!',
                'appointment' => $appointment
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Erro ao salvar agendamento.',
                'details' => $e->getMessage()
            ], 500);
        }
    }
    public function show($id)
    {
        try {
            $appointment = $this->appointmentService->show($id);

            return response()->json(['data' => $appointment]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Agendamento não encontrado'], 404);
        }
    }
    public function updateStatus(UpdateAppointmentStatusRequest $request, $id)
    {
        try {
            $userId = auth()->id();

            $appointment = $this->appointmentService->updateStatus(
                $id,
                $request->status,
                $request->observation ?? null,
                $userId
            );
            return response()->json([
                'message' => 'Status atualizado com sucesso',
                'appointment' => $appointment->load('materials', 'statusLogs'),
            ]);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Erro ao atualizar status', $e->getMessage()], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $this->appointmentService->destroy($id);

            return response()->json(['message' => 'Agendamento deletado com sucesso.']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Agendamento não encontrado'], 404);
        } catch (\Throwable $e) {
            return response()->json(['error' => 'Erro ao deletar agendamento', 'detalhes' => $e->getMessage()], 500);
        }
    }
}
