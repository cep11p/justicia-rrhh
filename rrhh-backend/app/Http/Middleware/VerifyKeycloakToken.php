<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\GenericUser;

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
            $realm = 'poder-judicial-rn'; // tu realm
            $url = "http://keycloak:8080/realms/{$realm}/protocol/openid-connect/certs";
            $response = Http::get($url);
            $keys = $response->json()['keys'];

            // 2. Buscar la clave correspondiente (kid)
            $header = json_decode(base64_decode(explode('.', $token)[0]), true);
            $kid = $header['kid'];

            // Buscar la clave correspondiente (kid)
            $keyData = collect($keys)->firstWhere('kid', $kid);


            $signingKeys = collect([$keyData]);

            // 3. Intentar verificar el token con todas las claves disponibles
            $decoded = null;
            $lastError = null;

            foreach ($signingKeys as $keyData) {
                try {
                    // Construir la clave pública correctamente
                    $cert = "-----BEGIN CERTIFICATE-----\n" .
                            chunk_split($keyData['x5c'][0], 64, "\n") .
                            "-----END CERTIFICATE-----\n";

                    // Extraer la clave pública del certificado
                    $certResource = openssl_x509_read($cert);
                    if (!$certResource) {
                        Log::warning('Error al leer el certificado', ['kid' => $keyData['kid']]);
                        continue;
                    }

                    $publicKey = openssl_pkey_get_public($certResource);
                    if (!$publicKey) {
                        Log::warning('Error al extraer la clave pública', ['kid' => $keyData['kid']]);
                        continue;
                    }

                    $publicKeyDetails = openssl_pkey_get_details($publicKey);
                    $publicKeyPem = $publicKeyDetails['key'];

                    // Intentar verificar con esta clave
                    $decoded = JWT::decode($token, new Key($publicKeyPem, 'RS256'));

                    // Si llegamos aquí, la verificación fue exitosa
                    Log::info('Token verified successfully', ['used_kid' => $keyData['kid']]);
                    break;

                } catch (\Throwable $e) {
                    $lastError = $e;
                    Log::debug('Token verification failed with key', [
                        'kid' => $keyData['kid'],
                        'error' => $e->getMessage()
                    ]);
                    continue;
                }
            }

            if (!$decoded) {
                return response()->json([
                    'error' => 'Token inválido',
                    'details' => $lastError ? $lastError->getMessage() : 'No se pudo verificar con ninguna clave'
                ], 401);
            }

            // Podés usar info del token si querés
            $request->attributes->add(['token_decoded' => (array) $decoded]);

            // Extraer claims del token decodificado
            $claims = (array) $decoded;

        } catch (\Throwable $e) {
            return response()->json(['error' => 'Token inválido', 'details' => $e->getMessage()], 401);
        }

        // Crear y establecer el usuario autenticado
        $user = new GenericUser([
            'id'                    => $claims['sub'],
            'email'                 => $claims['email'] ?? null,
            'name'                  => $claims['name'] ?? null,
            'given_name'            => $claims['given_name'] ?? null,
            'family_name'           => $claims['family_name'] ?? null,
            'preferred_username'    => $claims['preferred_username'] ?? null,
            'email_verified'        => $claims['email_verified'] ?? false,
            'realm_roles'           => $claims['realm_access']->roles ?? [],
            'resource_roles'        => (array) $claims['resource_access'],
            'scope'                 => $claims['scope'] ?? '',
            'client_id'             => $claims['azp'] ?? null,
        ]);

        // Establecer el usuario en el request para acceso posterior
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        // También establecer en el guard web para compatibilidad
        Auth::guard('web')->setUser($user);

        return $next($request);
    }
}
