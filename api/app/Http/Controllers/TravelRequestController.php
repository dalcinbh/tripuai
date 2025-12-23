<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTravelRequest;
use App\Models\TravelRequest;
use App\Services\TravelRequestService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TravelRequestController extends Controller
{
    protected $service;

    public function __construct(TravelRequestService $service)
    {
        $this->service = $service;
    }

    /**
     * @OA\Get(
     *     path="/api/travel-requests",
     *     tags={"Solicitações de Viagem"},
     *     summary="Listar solicitações de viagem",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número da página",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filtrar por status",
     *         required=false,
     *         @OA\Schema(type="string", enum={"solicitado", "aprovado", "cancelado"})
     *     ),
     *     @OA\Parameter(
     *         name="destination",
     *         in="query",
     *         description="Filtrar por destino",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="start_date",
     *         in="query",
     *         description="Data de início (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Parameter(
     *         name="end_date",
     *         in="query",
     *         description="Data de fim (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(type="string", format="date")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de solicitações de viagem",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="current_page", type="integer"),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object")),
     *             @OA\Property(property="total", type="integer")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Não autenticado")
     * )
     */
    public function viewTravelRequests(Request $request)
    {
        Gate::authorize('viewAnyTravelRequest', TravelRequest::class);

        return $this->service->list($request->user(), $request->all());
    }

    /**
     * @OA\Post(
     *     path="/api/travel-requests",
     *     tags={"Solicitações de Viagem"},
     *     summary="Criar uma nova solicitação de viagem",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"requester_name", "destination", "departure_date", "return_date"},
     *                 @OA\Property(property="requester_name", type="string", example="João Silva"),
     *                 @OA\Property(property="destination", type="string", example="São Paulo"),
     *                 @OA\Property(property="departure_date", type="string", format="date", example="2025-10-10"),
     *                 @OA\Property(property="return_date", type="string", format="date", example="2025-10-15")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Solicitação de viagem criada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Viagem solicitada com sucesso!"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Erro de validação")
     * )
     */
    public function createTravelRequest(StoreTravelRequest $request)
    {
        Gate::authorize('createTravelRequest', TravelRequest::class);

        $travelRequest = $this->service->create($request->user(), $request->validated());

        return response()->json([
            'message' => 'Viagem solicitada com sucesso!',
            'data'    => $travelRequest
        ], 201);
    }

    /**
     * @OA\Patch(
     *     path="/api/travel-requests/{travelRequest}/approve",
     *     tags={"Solicitações de Viagem"},
     *     summary="Aprovar uma solicitação de viagem (Apenas admin)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="travelRequest",
     *         in="path",
     *         description="ID da solicitação de viagem",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Solicitação de viagem aprovada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Viagem aprovada com sucesso!"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=403, description="Não autorizado")
     * )
     */
    public function approveTravelRequest(TravelRequest $travelRequest)
    {
        Gate::authorize('approveTravelRequest', TravelRequest::class);

        $this->service->approve($travelRequest);

        return response()->json([
            'message' => 'Viagem aprovada com sucesso!',
            'data' => $travelRequest
        ]);
    }

    /**
     * @OA\Patch(
     *     path="/api/travel-requests/{travelRequest}/cancel",
     *     tags={"Solicitações de Viagem"},
     *     summary="Cancelar uma solicitação de viagem",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="travelRequest",
     *         in="path",
     *         description="ID da solicitação de viagem",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Solicitação de viagem cancelada",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Viagem cancelada."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Erro de validação (ex: não pode cancelar solicitação aprovada)")
     * )
     */
    public function cancelTravelRequest(TravelRequest $travelRequest)
    {
        Gate::authorize('cancelTravelRequest', $travelRequest);

        try {
            $this->service->cancel($travelRequest);

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

    /**
     * @OA\Get(
     *     path="/api/travel-requests/{travelRequest}/show",
     *     tags={"Solicitações de Viagem"},
     *     summary="Obter detalhes de uma solicitação de viagem",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="travelRequest",
     *         in="path",
     *         description="ID da solicitação de viagem",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalhes da solicitação de viagem",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(response=403, description="Não autorizado")
     * )
     */
    public function showTravelRequest(TravelRequest $travelRequest)
    {
        Gate::authorize('viewTravelRequest', $travelRequest);

        return response()->json($travelRequest->load('user'));
    }
}
