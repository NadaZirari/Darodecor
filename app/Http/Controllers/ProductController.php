<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
      public function index()
    {
        return response()->json(Product::with('vendor')->get(), 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        $vendor = Vendor::where('user_id', Auth::id())->first();

        if (!$vendor) {
            return response()->json(['error' => 'Vendeur non trouvé'], 403);
        }

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'vendor_id' => $vendor->id,
        ]);

        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        return response()->json($product->load('vendor'), 200);
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        $vendor = Vendor::where('user_id', Auth::id())->first();
        if ($product->vendor_id !== $vendor->id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $product->update($request->all());
        return response()->json($product, 200);
    }

    public function destroy(Product $product)
    {
        $vendor = Vendor::where('user_id', Auth::id())->first();
        if ($product->vendor_id !== $vendor->id) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $product->delete();
        return response()->json(null, 204);
    }
}
