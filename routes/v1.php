<?php

use Illuminate\Support\Facades\Route;

Route::post('/login', [App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('/regenerate-token', [App\Http\Controllers\AuthController::class, 'regenerateToken'])->name('regenerate-token');

Route::get('/locations/nearest', [App\Http\Controllers\LocationController::class, 'nearest'])->name('locations.nearest');
Route::apiResource('locations', App\Http\Controllers\LocationController::class)->only(['index', 'show']);
Route::apiResource('locations', App\Http\Controllers\LocationController::class)->only(['store', 'update', 'destroy'])->middleware('auth:sanctum');

Route::post('/cashback', App\Http\Controllers\CashbackController::class)->name('cashback');
