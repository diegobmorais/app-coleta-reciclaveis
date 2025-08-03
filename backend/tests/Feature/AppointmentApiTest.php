<?php

namespace Tests\Feature;

use App\Models\Material;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_appointment()
    {
        $materials = Material::factory()->count(2)->create();
        $materialIds = $materials->pluck('id')->toArray();

        $payload = [
            'full_name' => 'João Silva',
            'street' => 'Rua Exemplo',
            'number' => '123',
            'neighborhood' => 'Centro',
            'city' => 'São Paulo',
            'suggested_date' => now()->addDays(3)->format('Y-m-d'),
            'phone' => '(11) 91234-5678',
            'email' => 'joao@example.com',
            'observation' => 'Teste de agendamento',
            'material_id' => $materialIds
        ];

        $response = $this->postJson('/api/appointments', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'appointment' => [
                    'full_name',
                    'street',
                    'number',
                    'neighborhood',
                    'city',
                    'suggested_date',
                    'phone',
                    'email',
                    'protocol',
                    'status',
                    'updated_at',
                    'created_at',
                    'id',
                    'materials' => [
                        '*' => [
                            'id',
                            'name',
                            'category',
                            'description',
                            'created_at',
                            'updated_at',
                            'pivot' => [
                                'appointment_id',
                                'material_id',
                            ],
                        ],
                    ],
                ],
            ]);

        $this->assertDatabaseHas('appointments', [
            'full_name' => 'João Silva',
            'city' => 'São Paulo',
        ]);
    }
}
