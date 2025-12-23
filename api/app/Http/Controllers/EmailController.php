<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\BroadcastEmailJob;

class EmailController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/admin/broadcast",
     *     tags={"Administrativo"},
     *     summary="Enviar email para todos os usuários",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"subject", "body"},
     *                 @OA\Property(property="subject", type="string", example="Nova Funcionalidade"),
     *                 @OA\Property(property="body", type="string", example="Nós lançamos uma nova funcionalidade...")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=202,
     *         description="Processamento de envio iniciado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="O processamento do broadcast de email foi iniciado em segundo plano.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Não autenticado"),
     *     @OA\Response(response=403, description="Não autorizado")
     * )
     */
    public function broadcast(Request $request)
    {
        //write in storage/logs/laravel.log
        $validated = $request->validate([
            'subject' => 'required|string',
            'body' => 'required|string',
        ]);

        // Dispatch the job to the queue
        BroadcastEmailJob::dispatch($validated['subject'], $validated['body']);

        return response()->json([
            'message' => 'Broadcast email processing started in background.',
        ], 202);
    }
}
