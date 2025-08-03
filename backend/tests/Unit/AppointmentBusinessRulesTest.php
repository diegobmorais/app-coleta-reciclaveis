<?php

namespace Tests\Unit;

use App\Models\Material;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class AppointmentBusinessRulesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function nao_pode_agendar_com_data_menor_que_2_dias_uteis()
    {
        $invalidDate = Carbon::now()->addDays(1)->format('Y-m-d');

        $response = $this->postJson('/api/appointments', [
            'full_name' => 'João Silva',
            'street' => 'Rua A',
            'number' => '123',
            'neighborhood' => 'Centro',
            'city' => 'São Paulo',
            'suggested_date' => $invalidDate,
            'phone' => '(11) 98765-4321',
            'email' => 'joao@email.com',
            'material_id' => [10, 12]
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['suggested_date']);
        $this->assertTrue(
            Str::contains($response->json('errors.suggested_date.0'), '2 dias úteis')
        );
    }

    /**
     * @test
     */
    public function pode_agendar_com_data_valida_de_2_dias_uteis_ou_mais()
    {
        $validDate = Carbon::now()->addWeekdays(2)->format('Y-m-d');

        $materials = Material::factory()->count(2)->create();
        $materialIds = $materials->pluck('id')->toArray();

        $response = $this->postJson('/api/appointments', [
            'full_name' => 'João Silva',
            'street' => 'Rua A',
            'number' => '123',
            'neighborhood' => 'Centro',
            'city' => 'São Paulo',
            'suggested_date' => $validDate,
            'phone' => '(11) 98765-4321',
            'email' => 'joao@email.com',
            'material_id' => $materialIds
        ]);

        $response->assertStatus(201);

        $appointmentId = $response->json('data.id') ?? 1;

        $this->assertDatabaseHas('appointments', [
            'suggested_date' => $validDate,
            'full_name' => 'João Silva'
        ]);
        
        foreach ($materialIds as $materialId) {
            $this->assertDatabaseHas('appointment_material', [
                'appointment_id' => $appointmentId,
                'material_id' => $materialId,
            ]);
        }
    }
}
