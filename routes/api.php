<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Botble\ACL\Http\Controllers\Auth\RegisterController;


use Illuminate\Validation\ValidationException;

Route::prefix('custom')->group(function () {
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
              return response()->json([
                    'success' => true,
                    'token' => $token,
                    'user' => $user,
                    'redirect_url' => '/dashboard/products'
                ], 200);
            }
            return response()->json(['error' => 'Non autorisé'], 401);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 422);
        }
    });





    // Inscription utilisateur
    Route::post('/register', function (Request $request) {
        try {
            $request->validate([
                'first_name' => 'required|string|max:120',
                'last_name' => 'required|string|max:120',
                'email' => 'required|email|unique:users,email',
                'username' => 'nullable|string|max:60|unique:users,username',
                'password' => 'required|string|min:6|confirmed',
            ]);

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'super_user' => false, // Utilisateur normal
                'manage_supers' => false,
            ]);

            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'message' => 'Compte créé avec succès',
                'user' => $user,
                'token' => $token,
                'redirect_url' => '/dashboard/products'
            ], 201);

        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 422);
        }
    });







    Route::get('/products', [ProductController::class, 'index'])->name('api.products.index');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('api.products.show');

    Route::middleware('auth:sanctum')->group(function () {

          // Dashboard utilisateur
        Route::get('/my-products', [ProductController::class, 'myProducts'])->name('api.my-products');
               // CRUD Produits


        Route::post('/products', [ProductController::class, 'store'])->name('api.products.store');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('api.products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('api.products.destroy');
    });
});