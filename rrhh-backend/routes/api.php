<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LiquidacionController;

// Rutas para liquidaciones
Route::middleware(['verifyKeycloakToken'])
    ->prefix('liquidacion')
    ->group(base_path('routes/liquidacion.php'));
