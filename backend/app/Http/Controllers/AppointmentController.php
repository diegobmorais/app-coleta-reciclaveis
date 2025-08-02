<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentStatusRequest;
use App\Models\Appointment;
use App\Models\StatusLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Str;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Appointment::with('materials');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->whereRaw("unaccent(full_name) ILIKE unaccent(?)", ["%{$search}%"])
                    ->orWhereRaw("unaccent(protocol) ILIKE unaccent(?)", ["%{$search}%"]);
            });
        }

        if ($request->filled('suggested_date')) {
            $query->whereDate('suggested_date', $request->suggested_date);
        }

        if ($request->filled('status')) {
            $query->whereRaw('LOWER(status) = ?', [strtolower($request->status)]);
        }

        $query->orderBy('suggested_date', 'asc');

        $appointments = $query->paginate(10);

        return response()->json($appointments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAppointmentRequest $request)
    {
        DB::beginTransaction();

        try {
            // Gera um número de protocolo único
            do {
                $protocol = 'COLETA-' . strtoupper(Str::random(8));
            } while (Appointment::where('protocol', $protocol)->exists());

            $appointment = Appointment::create([
                'protocol' => $protocol,
                'full_name' => $request->full_name,
                'street' => $request->street,
                'number' => $request->number,
                'neighborhood' => $request->neighborhood,
                'city' => $request->city,
                'suggested_date' => $request->suggested_date,
                'phone' => $request->phone,
                'email' => $request->email,
                'observation' => $request->observation,
                'status' => Appointment::STATUS_PENDENTE,
                'status_observation' => null
            ]);

            $appointment->materials()->sync($request->material_id);

            //cria log de agendamento
            StatusLog::create([
                'appointment_id' => $appointment->id,
                'status' => Appointment::STATUS_PENDENTE,
                'observation' => 'Cidadão solicitou agendamento.',
                'user_id' => null,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Agendamento realizado com sucesso!',
                'appointment' => $appointment->load('materials')
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erro ao salvar agendamento.', $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $appointment = Appointment::with([
            'materials:id,name,category',
            'statusLogs.user:id,name',
            'user:id,name',
        ])->findOrFail($id);

        return response()->json([
            'data' => $appointment
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateStatus(UpdateAppointmentStatusRequest $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        DB::beginTransaction();

        try {
            $appointment->status = $request->status;
            $appointment->status_updated_at = Carbon::now();
            $appointment->save();

            $appointment->statusLogs()->create([
                'status' => $request->status,
                'observation' => $request->observation ?? null,
                'user_id' => auth()->id(),
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Status atualizado com sucesso',
                'appointment' => $appointment->load('materials', 'statusLogs'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erro ao atualizar status', $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->materials()->detach();
        $appointment->delete();

        return response()->json(['message' => 'Agendamento deletado com sucesso.']);
    }
}
