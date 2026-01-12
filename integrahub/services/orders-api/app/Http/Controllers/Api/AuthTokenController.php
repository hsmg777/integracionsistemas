<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Firebase\JWT\JWT;

class AuthTokenController extends Controller
{
    public function token(Request $request)
    {
        // “Equivalente OAuth2” tipo client_credentials (simple para demo)
        $data = $request->validate([
            'client_id' => ['required', 'string'],
            'client_secret' => ['required', 'string'],
        ]);

        $expectedId = (string) env('OAUTH_CLIENT_ID', 'demo-client');
        $expectedSecret = (string) env('OAUTH_CLIENT_SECRET', 'demo-secret');

        if ($data['client_id'] !== $expectedId || $data['client_secret'] !== $expectedSecret) {
            return response()->json([
                'message' => 'invalid_client',
            ], 401);
        }

        $secret = (string) env('JWT_SECRET');
        $issuer = (string) env('JWT_ISSUER', 'integrahub');
        $ttl    = (int) env('JWT_TTL', 3600);

        $now = time();

        $payload = [
            'iss' => $issuer,
            'sub' => $data['client_id'],
            'iat' => $now,
            'exp' => $now + $ttl,
            'scope' => ['orders:create', 'orders:read'],
        ];

        $jwt = JWT::encode($payload, $secret, 'HS256');

        return response()->json([
            'access_token' => $jwt,
            'token_type'   => 'Bearer',
            'expires_in'   => $ttl,
        ]);
    }
}
