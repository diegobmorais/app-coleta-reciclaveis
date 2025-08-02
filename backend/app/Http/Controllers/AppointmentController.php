<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\StatusLog;
use Illuminate\Support\Facades\DB;
use Str;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::with('materials')->latest()->paginate(10);
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
                'status' => Appointment::STATUS_PENDENTE,
                'observation' => $request->observation
            ]);

            $appointment->materials()->sync($request->materials);

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
                'protocol' => $appointment->protocol,
                'suggested_date' => $appointment->suggested_date,
                'appointment' => $appointment->load('materials'),
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
        $appointment = Appointment::with('materials')->findOrFail($id);
        return response()->json($appointment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreAppointmentRequest $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        DB::beginTransaction();

        try {
            $appointment->update([
                'full_name' => $request->full_name,
                'street' => $request->street,
                'number' => $request->number,
                'neighborhood' => $request->neighborhood,
                'city' => $request->city,
                'suggested_date' => $request->suggested_date,
                'phone' => $request->phone,
                'email' => $request->email,
            ]);

            if ($request->has('materials')) {
                $appointment->materials()->sync($request->materials);
            }

            DB::commit();

            return response()->json([
                'message' => 'Agendamento atualizado com sucesso!',
                'appointment' => $appointment->load('materials'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => 'Erro ao atualizar agendamento.'], 500);
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
