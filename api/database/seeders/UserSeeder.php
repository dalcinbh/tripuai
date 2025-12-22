<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Usuários Fixos (Os teus originais)
        User::create([
            'name' => 'João Solicitante',
            'email' => 'joao@tripuai.com.br',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        User::create([
            'name' => 'Admin TripUAI',
            'email' => 'admin@tripuai.com.br',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // 2. 10 Usuários Comuns com e-mail @tripuai.com.br
        User::factory(10)->create([
            'is_admin' => false,
            'password' => Hash::make('password'),
            'email' => function () {
                // Gera um nome de usuário único e concatena o domínio
                return fake()->unique()->userName() . '@tripuai.com.br';
            }
        ]);

        // 3. 1 Novo Admin com e-mail @tripuai.com.br
        User::factory(1)->create([
            'is_admin' => true,
            'password' => Hash::make('password'),
            'email' => function () {
                return fake()->unique()->userName() . '@tripuai.com.br';
            }
        ]);
    }
}
