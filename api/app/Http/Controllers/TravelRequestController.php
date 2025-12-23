<?php

namespace App\Http\Controllers;

use App\Models\TravelRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TravelRequestController extends Controller
{
    public function viewTravelRequests(Request $request)
    {
        Gate::authorize('viewAnyTravelRequest', TravelRequest::class);

        return TravelRequest::with('user:id,name,email')
            ->forUser($request->user())
            ->filter($request->all())
            ->latest()
            ->paginate(15);
    }

    public function createTravelRequest(Request $request)
    {
        Gate::authorize('createTravelRequest', TravelRequest::class);

        $validated = $request->validate([
            'requester_name' => 'required|string|max:255', // Campo obrigatório e independente
            'destination'    => 'required|string|max:255',
            'departure_date' => 'required|date|after_or_equal:today',
            'return_date'    => 'required|date|after_or_equal:departure_date',
        ]);

        $travelRequest = TravelRequest::create([
            'user_id'        => $request->user()->id, // Dono do registro (quem criou)
            'requester_name' => $validated['requester_name'], // Nome do viajante real
            'destination'    => $validated['destination'],
            'departure_date' => $validated['departure_date'],
            'return_date'    => $validated['return_date'],
            'status'         => TravelRequest::STATUS_SOLICITADO,
        ]);

        return response()->json([
            'message' => 'Viagem solicitada com sucesso!',
            'data'    => $travelRequest
        ], 201);
    }

    public function approveTravelRequest(TravelRequest $travelRequest)
    {
        Gate::authorize('approveTravelRequest', TravelRequest::class);

        $travelRequest->markAsApproved();

        return response()->json([
            'message' => 'Viagem aprovada com sucesso!',
            'data' => $travelRequest
        ]);
    }

    public function cancelTravelRequest(TravelRequest $travelRequest)
    {
        Gate::authorize('cancelTravelRequest', $travelRequest);

        try {
            $travelRequest->markAsCancelled();

            return response()->json([
                'message' => 'Viagem cancelada.',
                'data' => $travelRequest
            ]);
        } catch (\DomainException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function showTravelRequest(TravelRequest $travelRequest)
    {
        Gate::authorize('viewTravelRequest', $travelRequest);

        return response()->json($travelRequest->load('user'));
    }
}
