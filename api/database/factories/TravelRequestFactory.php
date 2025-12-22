<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\TravelRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class TravelRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Isso cria um usuário e usa o ID dele automaticamente
            'user_id' => User::factory(),
            // Adicione o requester_name se ele for obrigatório na sua migration
            'requester_name' => $this->faker->name(),
            'destination' => $this->faker->city(),
            'departure_date' => now()->addDays(5),
            'return_date' => now()->addDays(10),
            'status' => TravelRequest::STATUS_SOLICITADO,
        ];
    }
}
