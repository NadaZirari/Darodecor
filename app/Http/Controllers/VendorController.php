<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
     public function index()
    {
        $vendors = Vendor::all();
        return view('vendors.index', compact('vendors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email',
            'phone' => 'nullable|string|max:20',
            'description' => 'nullable|string',
        ]);

        Vendor::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'description' => $request->description,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('vendors.index')->with('success', 'Vendeur ajouté avec succès.');
    }

    public function show(Vendor $vendor)
    {
        $products = $vendor->products;
        return view('vendors.show', compact('vendor', 'products'));
    }
}
