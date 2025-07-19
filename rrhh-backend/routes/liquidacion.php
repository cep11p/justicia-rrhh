<?php

use App\Http\Controllers\LiquidacionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LiquidacionController::class, 'index']);
Route::post('/store', [LiquidacionController::class, 'store']);
Route::get('/show/{liquidacion}', [LiquidacionController::class, 'show']);
Route::delete('/delete/{liquidacion}', [LiquidacionController::class, 'delete']);
Route::patch('/{id}/update', [LiquidacionController::class, 'update']);
Route::get('/view-to-pdf/{liquidacion}', [LiquidacionController::class, 'viewToPdf']);




