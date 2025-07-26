<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\GenericUser;

class CheckKeycloakRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role = null, string $type = 'realm'): Response
    {
        $user = $request->user();

        if (!$user instanceof GenericUser) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        if ($role) {
            $hasRole = false;

            if ($type === 'realm') {
                $hasRole = $user->hasRealmRole($role);
            } elseif ($type === 'resource') {
                $hasRole = $user->hasResourceRole($role);
            } else {
                $hasRole = $user->hasRole($role);
            }

            if (!$hasRole) {
                return response()->json(['error' => 'Acceso denegado. Rol requerido: ' . $role], 403);
            }
        }

        return $next($request);
    }
}
