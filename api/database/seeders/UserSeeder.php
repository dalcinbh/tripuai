<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Criar Usuário Comum (Solicitante)
        User::create([
            'name' => 'João Solicitante',
            'email' => 'joao@tripuai.com.br',
            'password' => Hash::make('password'),
            'is_admin' => false,
        ]);

        // Criar Usuário Administrador
        User::create([
            'name' => 'Admin TripUAI',
            'email' => 'admin@tripuai.com.br',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);
    }
}
