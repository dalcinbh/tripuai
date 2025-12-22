<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TravelRequest;
use Illuminate\Database\Seeder;

class TravelRequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Busca apenas os IDs e Nomes de usuários que NÃO são admins
        $users = User::where('is_admin', false)->get(['id', 'name']);

        if ($users->isEmpty()) {
            return;
        }

        // 2. Loop para criar os 1000 registros
        for ($i = 0; $i < 1000; $i++) {
            // Sorteia um usuário da lista para garantir consistência entre ID e Nome
            $randomUser = $users->random();

            // Gera datas randômicas respeitando as regras de negócio
            $departureDate = fake()->dateTimeBetween('+1 day', '+1 month');

            // Garante que o retorno seja sempre após a partida
            $returnDate = fake()->dateTimeBetween($departureDate->format('Y-m-d') . ' +1 day', '+2 months');

            TravelRequest::create([
                'user_id' => $randomUser->id,
                'requester_name' => $randomUser->name, // Sempre o nome do usuário sorteado
                'destination' => fake()->city() . ', ' . fake()->country(),
                'departure_date' => $departureDate,
                'return_date' => $returnDate,
                'status' => 'solicitado', // Status inicial obrigatório
            ]);
        }
    }
}
