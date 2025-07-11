<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;

class VerifyKeycloakToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization');

        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['error' => 'Token no provisto'], 401);
        }

        $token = substr($authHeader, 7);

        try {
            // 1. Obtener las claves públicas de Keycloak
            $realm = 'poder-judicial'; // tu realm
            $url = "http://keycloak:8080/realms/{$realm}/protocol/openid-connect/certs";
            $response = Http::get($url);
            $keys = $response->json()['keys'];

            // 2. Buscar la clave correspondiente (kid)
            $header = json_decode(base64_decode(explode('.', $token)[0]), true);
            $kid = $header['kid'];

            $keyData = collect($keys)->firstWhere('kid', $kid);
            if (!$keyData) {
                return response()->json(['error' => 'Clave pública no encontrada'], 401);
            }

            $publicKey = "-----BEGIN PUBLIC KEY-----\n" .
                         chunk_split($keyData['x5c'][0], 64, "\n") .
                         "-----END PUBLIC KEY-----\n";

            // 3. Verificar el token
            $decoded = JWT::decode($token, new Key($publicKey, 'RS256'));

            // Podés usar info del token si querés
            $request->attributes->add(['token_decoded' => (array) $decoded]);

        } catch (\Throwable $e) {
            return response()->json(['error' => 'Token inválido', 'details' => $e->getMessage()], 401);
        }

        return $next($request);
    }
}
