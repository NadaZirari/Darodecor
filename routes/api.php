<?php

use App\Http\Controllers\VendorController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::post('/login', function (Request $request) {
    $credentials = $request->only('email', 'password');
    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $token = $user->createToken('API Token')->plainTextToken;
        return response()->json(['token' => $token], 200);
    }
    return response()->json(['error' => 'Non autorisé'], 401);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('vendors', VendorController::class);
    Route::apiResource('products', ProductController::class);
});