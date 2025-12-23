<?php

use App\Models\User;
use App\Models\TravelRequest;
// Importamos os helpers diretamente da API do Pest para Laravel
use function Pest\Laravel\{actingAs, getJson, patchJson};
use Illuminate\Foundation\Testing\RefreshDatabase; // Certifique-se desta importação

uses(RefreshDatabase::class);

test('um usuário comum pode criar uma viagem', function () {
    $user = User::factory()->create(['is_admin' => false]);

    actingAs($user, 'api')
        ->postJson(route('travel-requests.create'), [
            'requester_name' => 'Viajante Teste',
            'destination' => 'Test Destination',
            'departure_date' => now()->addDay()->format('Y-m-d'),
            'return_date' => now()->addDays(5)->format('Y-m-d'),
        ])
        ->assertStatus(201) // Status de recurso criado
        ->assertJsonFragment(['message' => 'Viagem solicitada com sucesso!']);
});

test('um administrador não pode criar uma solicitação de viagem', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    actingAs($admin, 'api')
        ->postJson(route('travel-requests.create'), [
            'requester_name' => 'Admin Tentando Criar',
            'destination' => 'Destino Proibido',
            'departure_date' => now()->addDay()->format('Y-m-d'),
            'return_date' => now()->addDays(5)->format('Y-m-d'),
        ])
        ->assertStatus(403); // Acesso negado pela Policy
});

test('usuário comum não pode aprovar ou cancelar viagens (apenas admin)', function () {
    // Criamos os usuários usando as Factories
    $user = User::factory()->create(['is_admin' => false]);
    $travel = TravelRequest::factory()->create();

    // Testamos a tentativa de aprovação
    actingAs($user, 'api')
        ->patchJson(route('travel-requests.approve', $travel))
        ->assertStatus(403);

    // Testamos a tentativa de cancelamento
    actingAs($user, 'api')
        ->patchJson(route('travel-requests.cancel', $travel))
        ->assertStatus(403);
});

test('não é possível cancelar uma viagem que já foi aprovada (Regra de Ouro)', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    // Criamos um recurso em um estado específico para testar a regra de domínio
    $travel = TravelRequest::factory()->create(['status' => TravelRequest::STATUS_APROVADO]);

    actingAs($admin, 'api')
        ->patchJson(route('travel-requests.cancel', $travel))
        ->assertStatus(422)
        ->assertJsonFragment(['message' => 'Não é possível cancelar um pedido que já foi aprovado.']);
});

test('um usuário pode visualizar suas próprias viagens mas não as de outros', function () {
    $user = User::factory()->create(['is_admin' => false]);
    $minhaViagem = TravelRequest::factory()->create(['user_id' => $user->id]);
    $outraViagem = TravelRequest::factory()->create();

    // Verificação de acesso ao próprio recurso
    actingAs($user, 'api')
        ->getJson(route('travel-requests.show', $minhaViagem))
        ->assertStatus(200);

    // Verificação de bloqueio a recurso de terceiros (Segurança/Policy)
    actingAs($user, 'api')
        ->getJson(route('travel-requests.show', $outraViagem))
        ->assertStatus(403);
});
