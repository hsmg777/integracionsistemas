<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Throwable;

class JwtAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $auth = (string) $request->header('Authorization', '');

        if (!str_starts_with($auth, 'Bearer ')) {
            return response()->json([
                'message' => 'Unauthorized (missing Bearer token)',
            ], 401);
        }

        $token = trim(substr($auth, 7));
        if ($token === '') {
            return response()->json([
                'message' => 'Unauthorized (empty token)',
            ], 401);
        }

        $secret = (string) env('JWT_SECRET', '');
        if ($secret === '') {
            return response()->json([
                'message' => 'Server misconfigured (JWT_SECRET missing)',
            ], 500);
        }

        try {
            $payload = JWT::decode($token, new Key($secret, 'HS256'));

            // (Opcional) validar issuer
            $issuer = (string) env('JWT_ISSUER', '');
            if ($issuer !== '' && isset($payload->iss) && (string)$payload->iss !== $issuer) {
                return response()->json(['message' => 'Unauthorized (invalid issuer)'], 401);
            }

            // Guardamos el payload para que lo uses en controllers si quieres
            $request->attributes->set('jwt', (array) $payload);

            return $next($request);
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Unauthorized (invalid/expired token)',
                'error'   => $e->getMessage(), // para demo/debug; si quieres lo quitas luego
            ], 401);
        }
    }
}
