<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

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
            'description' => 'nullable|string',
        ]);

        Vendor::create([
            'name' => $request->name,
            'email' => $request->email,
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
     public function update(Request $request, Vendor $vendor)
    {
        if ($vendor->user_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendors,email,' . $vendor->id,
            'description' => 'nullable|string',
        ]);

        $vendor->update([
            'name' => $request->name,
            'email' => $request->email,
            'description' => $request->description,
        ]);

        return redirect()->route('vendors.index')->with('success', 'Vendeur mis à jour avec succès.');
    }

    /**
     * Supprimer un vendeur
     */
    public function destroy(Vendor $vendor)
    {
        if ($vendor->user_id !== Auth::id()) {
            abort(403, 'Non autorisé');
        }

        $vendor->delete();
        return redirect()->route('vendors.index')->with('success', 'Vendeur supprimé avec succès.');
    }
}
