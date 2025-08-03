<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\StatusLog;
use App\Repositories\AppointmentRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AppointmentService
{
    protected $appointmentRepository;

    public function __construct(AppointmentRepository $appointmentRepository)
    {
        $this->appointmentRepository = $appointmentRepository;
    }
    public function getFilteredAppointments(array $filters)
    {
        return $this->appointmentRepository->filterAppointments($filters);
    }
    public function createAppointment(array $data): Appointment
    {  
        return DB::transaction(function () use ($data) {
            do {
                $protocol = 'COLETA-' . strtoupper(Str::random(8));
            } while ($this->appointmentRepository->existsProtocol($protocol));

            $data['protocol'] = $protocol;
            $data['status'] = Appointment::STATUS_PENDENTE;   
                 
            $appointment = $this->appointmentRepository->create($data);

            if (!empty($data['material_id'])) {
                $this->appointmentRepository->syncMaterials($appointment, $data['material_id']);
            }

            StatusLog::create([
                'appointment_id' => $appointment->id,
                'status' => Appointment::STATUS_PENDENTE,
                'observation' => 'Cidadão solicitou agendamento.',
                'user_id' => null,
            ]);

            return $appointment->load('materials');
        });
    }
    public function show(int $id): Appointment
    {
        $appointment = $this->appointmentRepository->findByIdWithRelations($id, [
            'materials:id,name,category',
            'statusLogs.user:id,name',
            'user:id,name',
        ]);

        if (!$appointment) {
            throw new ModelNotFoundException("Agendamento não encontrado");
        }

        return $appointment;
    }
    public function updateStatus(int $id, string $status, ?string $observation, ?int $userId)
    {
        return DB::transaction(function () use ($id, $status, $observation, $userId) {
            $appointment = $this->appointmentRepository->findById($id);

            if (!$appointment) {
                throw new ModelNotFoundException("Agendamento não encontrado");
            }

            $appointment->status = $status;
            $appointment->status_updated_at = Carbon::now();

            $this->appointmentRepository->save($appointment);

            $this->appointmentRepository->createStatusLog($appointment, [
                'status' => $status,
                'observation' => $observation,
                'user_id' => $userId,
            ]);

            return $this->appointmentRepository->loadRelations($appointment, ['materials', 'statusLogs']);
        });
    }
    public function destroy(int $id): void
    {
        $appointment = $this->appointmentRepository->findById($id);

        if (!$appointment) {
            throw new ModelNotFoundException("Agendamento não encontrado");
        }

        $this->appointmentRepository->detachMaterials($appointment);
        $this->appointmentRepository->delete($appointment);
    }
}
