<?php

namespace App\Http\Middleware;

use App\Models\AppUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthAppMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');

        // 1. Comprobar que existe el header
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        // 2. Extraer token
        $plainToken = substr($authHeader, 7);

        // 3. Hashear token (igual que al guardarlo)
        $hashedToken = hash('sha256', $plainToken);

        // 4. Buscar usuario
        $user = AppUser::where('api_token', $hashedToken)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        }

        // 5. Inyectar usuario en la request
        $request->attributes->set('app_user', $user);

        return $next($request);
    }
}
