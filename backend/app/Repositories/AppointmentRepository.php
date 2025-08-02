<?php

namespace App\Repositories;

use App\Models\Appointment;
use Illuminate\Pagination\LengthAwarePaginator;

class AppointmentRepository
{
    public function filterAppointments(array $filters): LengthAwarePaginator
    {
        $query = Appointment::with('materials');

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereRaw("unaccent(full_name) ILIKE unaccent(?)", ["%{$search}%"])
                    ->orWhereRaw("unaccent(protocol) ILIKE unaccent(?)", ["%{$search}%"]);
            });
        }

        if (!empty($filters['suggested_date'])) {
            $query->whereDate('suggested_date', $filters['suggested_date']);
        }

        if (!empty($filters['status'])) {
            $query->whereRaw('LOWER(status) = ?', [strtolower($filters['status'])]);
        }

        $query->orderBy('suggested_date', 'asc');

        return $query->paginate($filters['per_page'] ?? 10);
    }

    public function create(array $data): Appointment
    {
        return Appointment::create($data);
    }

    public function existsProtocol(string $protocol): bool
    {
        return Appointment::where('protocol', $protocol)->exists();
    }

    public function syncMaterials(Appointment $appointment, array $materialIds): void
    {
        $appointment->materials()->sync($materialIds);
    }

    public function findById(int $id): ?Appointment
    {
        return Appointment::find($id);
    }

    public function save(Appointment $appointment): bool
    {
        return $appointment->save();
    }

    public function loadRelations(Appointment $appointment, array $relations): Appointment
    {
        return $appointment->load($relations);
    }
    
    public function createStatusLog(Appointment $appointment, array $data)
    {
        return $appointment->statusLogs()->create($data);
    }

    public function findByIdWithRelations(int $id, array $relations): ?Appointment
    {
        return Appointment::with($relations)->find($id);
    }

    public function delete(Appointment $appointment): bool
    {
        return $appointment->delete();
    }

    public function detachMaterials(Appointment $appointment): void
    {
        $appointment->materials()->detach();
    }
}
