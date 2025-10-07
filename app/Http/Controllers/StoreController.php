<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::where('user_id', Auth::id())->get();
        return view('stores.index', compact('stores'));
    }

    public function create()
    {
        return view('stores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        Store::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
        ]);

        return redirect()->route('stores.index')->with('success', 'Toko berhasil dibuat!');
    }

    public function select($id)
    {
        $store = Store::where('user_id', Auth::id())->findOrFail($id);
        session(['store_id' => $store->id]);
        return redirect()->route('dashboard')->with('success', "Toko {$store->name} dipilih!");
    }
}
