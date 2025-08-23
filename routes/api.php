<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KematianController;
use App\Http\Controllers\PindahController;
use App\Http\Controllers\UserController;

Route::get('/test', fn () => response()->json(['message' => 'API is working!']));

// Public (JWT)
Route::post('/login',   [AuthController::class, 'login']);
Route::post('/refresh', [AuthController::class, 'refresh']);

// Protected (auth:api = jwt)
Route::middleware('auth:api')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Read-only: admin + viewer
    Route::middleware('role:admin,viewer')->group(function () {
        Route::get('users', [UserController::class, 'index']);
        Route::get('users/{id}', [UserController::class, 'show']);

        Route::get('kematian', [KematianController::class, 'index']);
        Route::get('kematian/{id}', [KematianController::class, 'show']);

        Route::get('pindah', [PindahController::class, 'index']);
        Route::get('pindah/{id}', [PindahController::class, 'show']);
    });

    // Admin-only: create/update/delete
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('users', UserController::class)->except(['index','show']);

        Route::post('kematian', [KematianController::class, 'store']);
        Route::put('kematian/{id}', [KematianController::class, 'update']);
        Route::delete('kematian/{id}', [KematianController::class, 'destroy']);

        Route::post('pindah', [PindahController::class, 'store']);
        Route::put('pindah/{id}', [PindahController::class, 'update']);
        Route::delete('pindah/{id}', [PindahController::class, 'destroy']);
    });
});
