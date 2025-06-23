<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
     public function index()
    {
        return response()->json(Product::with(['user'])->get(), 200);
    }


     // Récupérer les produits de l'utilisateur connecté
    public function myProducts()
    {
        $products = Auth::user()->products()->with('user')->get();
        return response()->json($products, 200);
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|string',
            'quantity' => 'nullable|integer|min:0',
            'stock_status' => 'nullable|string|in:in_stock,out_of_stock',
        ]);

        

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $request->image,
            'quantity' => $request->quantity,
            'user_id' => $user->id,
            'stock_status' => $request->stock_status ?? 'in_stock',
        ]);

         return response()->json($product->load('user'), 201);
    }

    public function show(Product $product)
    {
        return response()->json($product->load('user'), 200);
    }

    public function update(Request $request, Product $product)
    {

        // Vérifier que l'utilisateur est propriétaire du produit
        if ($product->user_id !== Auth::id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|string',
            'quantity' => 'nullable|integer|min:0',
            'stock_status' => 'nullable|string|in:in_stock,out_of_stock',
        ]);

       

        $product->update($request->only(['name', 'description', 'price', 'image', 'quantity', 'stock_status']));
   return response()->json($product->load('user'), 200);
        }

    public function destroy(Product $product)
    {
       if ($product->user_id !== Auth::id()) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $product->delete();
        return response()->json(null, 204);
    }
}