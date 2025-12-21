<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Autentica o usuário e retorna o token JWT
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
     * Retorna o perfil do usuário logado
     */
    public function me()
    {
        return response()->json(Auth::guard('api')->user());
    }

    /**
     * Finaliza a sessão (Invalida o token)
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
