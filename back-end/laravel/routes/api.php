<?php

use App\Http\Controllers\Api\PromotionController;
use App\Http\Controllers\Api\SessionController;
use App\Http\Controllers\Api\TableController;
use App\Http\Controllers\Api\TimePricingController;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::apiResource('tables', TableController::class);
Route::apiResource('promotions', PromotionController::class);
Route::apiResource('time_pricings', TimePricingController::class);
Route::apiResource('sessions', SessionController::class);
Route::prefix('tables')->group(function () {
    Route::get('/', [TableController::class, 'index']);
    Route::post('/', [TableController::class, 'store']);
    Route::put('/{id}/toggle', [TableController::class, 'toggle']);

Route::post('/tables/{id}/toggle', [TableController::class, 'toggle']);
});
