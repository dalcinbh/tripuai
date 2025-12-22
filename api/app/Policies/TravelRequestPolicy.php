<?php

namespace App\Policies;

use App\Models\TravelRequest;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TravelRequestPolicy
{
    private function isOwnerOrAdmin(User $user, TravelRequest $travelRequest): bool
    {
        return $user->is_admin || $user->id === $travelRequest->user_id;
    }

    public function viewAnyTravelRequest(User $user): Response
    {
        return Response::allow();
    }

    public function viewTravelRequest(User $user, TravelRequest $travelRequest): Response
    {
        return $this->isOwnerOrAdmin($user, $travelRequest)
            ? Response::allow()
            : Response::deny('Você não tem permissão para visualizar esta solicitação.');
    }

    public function approveTravelRequest(User $user): Response
    {
        return $user->is_admin
            ? Response::allow()
            : Response::deny('Apenas administradores podem aprovar solicitações de viagem.');
    }

    public function cancelTravelRequest(User $user): Response
    {
        return $user->is_admin
            ? Response::allow()
            : Response::deny('Apenas administradores podem cancelar solicitações de viagem.');
    }
}
