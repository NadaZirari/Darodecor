<?php

use App\Http\Controllers\VendorController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

Route::post('/login', function (Request $request) {

     try {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

    $credentials = $request->only('email', 'password');
    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $token = $user->createToken('API Token')->plainTextToken;
        return response()->json(['token' => $token], 200);
    }
    return response()->json(['error' => 'Non autorisé'], 401);
    } catch (ValidationException $e) {
        return response()->json(['error' => $e->validator->errors()], 422);
    }
});

Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);         // Lire tous les produits
    Route::post('/', [ProductController::class, 'store']);         // Créer un produit
    Route::get('{id}', [ProductController::class, 'show']);        // Lire un seul produit
    Route::put('{id}', [ProductController::class, 'update']);      // Mettre à jour un produit
    Route::delete('{id}', [ProductController::class, 'destroy']);  // Supprimer un produit
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('vendors', VendorController::class);
    Route::apiResource('products', ProductController::class);
});