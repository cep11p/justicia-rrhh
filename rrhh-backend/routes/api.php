<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LiquidacionController;

// Ruta de prueba sin autenticación
Route::get('/test', function () {
    return response()->json(['message' => 'API funcionando correctamente']);
});

// Rutas para liquidaciones
Route::middleware(['verifyKeycloakToken'])
    ->prefix('liquidacion')
    ->group(base_path('routes/liquidacion.php'));
