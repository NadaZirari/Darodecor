<?php

namespace App\Http\Controllers\API;
use Botble\Ecommerce\Models\Product;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
     public function index()
    {
        $products = Product::with('slugable')->get(); // Récupère tous les produits avec leurs slugs
        return response()->json($products);
    }
}
