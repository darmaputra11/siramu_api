<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\KematianController;
use App\Http\Controllers\PindahController;
use App\Http\Controllers\UserController;

// Test route
Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});

// ✅ Public endpoints
Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'username' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6',
        'role' => 'required|in:admin,bjps,diskes,dinsos',
    ]);

    $user = User::create([
        'username' => $validated['username'],
        'email' => $validated['email'],
        'password' => bcrypt($validated['password']),
        'role' => $validated['role'],
    ]);

    $token = $user->createToken('api_token')->plainTextToken;

    return response()->json(['token' => $token, 'user' => $user]);
});

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $credentials['email'])->first();

    if (!$user || !Hash::check($credentials['password'], $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = $user->createToken('api_token')->plainTextToken;

    return response()->json(['token' => $token, 'user' => $user]);
});


Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Logged out']);
});


// ✅ Protected routes
Route::middleware(['auth:sanctum'])->group(function () {

    // Only Admin
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('users', UserController::class)->except(['index', 'show']);
        Route::post('kematian', [KematianController::class, 'store']);
        Route::post('pindah', [PindahController::class, 'store']);
        Route::put('kematian/{id}', [KematianController::class, 'update']);
        Route::put('pindah/{id}', [PindahController::class, 'update']);
        Route::delete('kematian/{id}', [KematianController::class, 'destroy']);
        Route::delete('pindah/{id}', [PindahController::class, 'destroy']);
    });

    // Admin + bjps + diskes + dinsos → Boleh lihat data
    Route::middleware('role:admin,bjps,diskes,dinsos')->group(function () {
        Route::get('kematian', [KematianController::class, 'index']);
        Route::get('pindah', [PindahController::class, 'index']);
        Route::get('kematian/{id}', [KematianController::class, 'show']);
        Route::get('pindah/{id}', [PindahController::class, 'show']);
    });
});
