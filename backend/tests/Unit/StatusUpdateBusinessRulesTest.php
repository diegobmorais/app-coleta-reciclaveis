<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Appointment;
use App\Models\User;

class StatusUpdateBusinessRulesTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create(['name' => 'Admin']);
    }

    /**
     * @test
     */
    public function observacao_e_obrigatoria_para_status_cancelado()
    {
        $appointment = Appointment::factory()->create(['status' => 'Pendente']);

        $response = $this->actingAs($this->user, 'sanctum')->patchJson(
            "/api/appointments/{$appointment->id}/status",
            [
                'status' => 'Cancelado',
                'observation' => null
            ]
        );
     
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['observation']);
        $response->assertJsonFragment([
            'message' => 'A observação é obrigatória ao concluir ou cancelar o agendamento.'
        ]);
    }

    /**
     * @test
     */
    public function observacao_e_obrigatoria_para_status_concluido()
    {
        $appointment = Appointment::factory()->create(['status' => 'Pendente']);

        $response = $this->actingAs($this->user, 'sanctum')->patchJson(
            "/api/appointments/{$appointment->id}/status",
            [
                'status' => 'Concluído',
                'observation' => null
            ]
        );
       
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['observation']);
    }

    /**
     * @test
     */
    public function pode_atualizar_para_agendado_sem_observacao()
    {
        $appointment = Appointment::factory()->create(['status' => 'Pendente']);

        $response = $this->actingAs($this->user, 'sanctum')->patchJson(
            "/api/appointments/{$appointment->id}/status",
            [
                'status' => 'Agendado',
                'observation' => null
            ]
        );
      
        $response->assertStatus(200);
        $this->assertDatabaseHas('appointments', [
            'id' => $appointment->id,
            'status' => 'Agendado'
        ]);
    }
}