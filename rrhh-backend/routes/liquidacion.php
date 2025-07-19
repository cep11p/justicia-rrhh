<?php

use App\Http\Controllers\LiquidacionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LiquidacionController::class, 'index']);
Route::post('/store', [LiquidacionController::class, 'store']);
Route::get('/show/{liquidacion}', [LiquidacionController::class, 'show']);
Route::patch('/{id}/update', [LiquidacionController::class, 'update']);



