<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LiquidacionController;

// Rutas para liquidaciones
Route::prefix('liquidacion')
        ->group(base_path('routes/liquidacion.php'));
