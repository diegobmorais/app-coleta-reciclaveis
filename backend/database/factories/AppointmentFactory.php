<?php

namespace Database\Factories;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Appointment::class;
    public function definition(): array
    {
        return [
            'protocol' => 'COLETA-' . Str::upper(Str::random(8)),
            'full_name' => $this->faker->name,
            'street' => $this->faker->streetName,
            'number' => $this->faker->buildingNumber,
            'neighborhood' => $this->faker->citySuffix,
            'city' => $this->faker->city,
            'suggested_date' => now()->addDays(3)->format('Y-m-d'),
            'phone' => $this->faker->phoneNumber,
            'email' => $this->faker->safeEmail,
            'status' => 'Pendente', 
        ];
    }
}
