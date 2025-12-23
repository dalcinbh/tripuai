<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     tags={"Autenticação"},
     *     summary="Login do usuário e retorno do token JWT",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"email", "password"},
     *                 @OA\Property(property="email", type="string", format="email", example="admin@tripuai.com"),
     *                 @OA\Property(property="password", type="string", format="password", example="password")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login realizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
     *             @OA\Property(property="token_type", type="string", example="bearer"),
     *             @OA\Property(property="expires_in", type="integer", example=3600),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Credenciais inválidas")
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! $token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Credenciais inválidas'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * @OA\Get(
     *     path="/api/auth/me",
     *     tags={"Autenticação"},
     *     summary="Obter perfil do usuário logado",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Dados do perfil do usuário",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="Admin User"),
     *             @OA\Property(property="email", type="string", example="admin@tripuai.com")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Não autenticado")
     * )
     */
    public function me()
    {
        return response()->json(Auth::guard('api')->user());
    }

    /**
     * @OA\Post(
     *     path="/api/auth/logout",
     *     tags={"Autenticação"},
     *     summary="Logout do usuário",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout realizado com sucesso",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Logout realizado com sucesso"))
     *     ),
     *     @OA\Response(response=401, description="Não autenticado")
     * )
     */
    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    /**
     * Estrutura a resposta do token com claims customizados
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'user' => Auth::guard('api')->user() // Retorna o usuário junto com o token para facilitar o SPA
        ]);
    }
}
