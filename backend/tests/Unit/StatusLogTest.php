<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Appointment;
use App\Models\StatusLog;
use App\Models\User;

class StatusLogTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * @test
     */
    public function cria_log_ao_atualizar_status()
    {
        $appointment = Appointment::factory()->create(['status' => 'Pendente']);

        $this->actingAs($this->user, 'sanctum')->patchJson("/api/appointments/{$appointment->id}/status", [
            'status' => 'Agendado',
            'observation' => 'Reciclagem agendada para data prevista'
        ]);

        $this->assertDatabaseHas('status_logs', [
            'appointment_id' => $appointment->id,
            'user_id' => $this->user->id,
            'status' => 'Agendado',
            'observation' => 'Reciclagem agendada para data prevista'
        ]);

        $log = StatusLog::first();
        $this->assertNotNull($log->created_at);
    }

    /**
     * @test
     */
    public function log_de_status_pendente_nao_tem_user_id()
    {
        $appointment = Appointment::factory()->create(['status' => 'Pendente']);
       
        $statusLog = StatusLog::create([
            'appointment_id' => $appointment->id,
            'user_id' => null,
            'status' => 'Pendente',
            'observation' => 'Cidadão solicitou coleta',
            'created_at' => now()
        ]);

        $this->assertDatabaseHas('status_logs', [
            'appointment_id' => $appointment->id,
            'user_id' => null,
            'status' => 'Pendente'
        ]);
    }
}