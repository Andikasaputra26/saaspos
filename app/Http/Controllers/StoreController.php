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
            'name'    => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'phone'   => 'nullable|string|max:20',
        ]);

        Store::create([
            'user_id' => Auth::id(),
            'name'    => $request->name,
            'address' => $request->address,
            'phone'   => $request->phone,
        ]);

        return redirect()->route('stores.index')->with('success', '✅ Toko berhasil dibuat!');
    }

    public function edit($id)
    {
        $store = Store::where('user_id', Auth::id())->findOrFail($id);
        return view('stores.edit', compact('store'));
    }

    public function update(Request $request, $id)
    {
        $store = Store::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'name'    => 'required|string|max:100',
            'address' => 'nullable|string|max:255',
            'phone'   => 'nullable|string|max:20',
        ]);

        $store->update([
            'name'    => $request->name,
            'address' => $request->address,
            'phone'   => $request->phone,
        ]);

        return redirect()->route('stores.index')->with('success', '✅ Toko berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $store = Store::where('user_id', Auth::id())->findOrFail($id);

        try {
            $store->delete();
            session()->forget('store_id'); 
            return redirect()->route('stores.index')->with('success', '🗑️ Toko berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('stores.index')->with('error', '❌ Gagal menghapus toko: ' . $e->getMessage());
        }
    }

    public function select($id)
    {
        $store = Store::where('user_id', Auth::id())->findOrFail($id);
        session(['store_id' => $store->id]);
        return redirect()->route('dashboard')->with('success', "🏬 Toko <strong>{$store->name}</strong> sekarang aktif.");
    }
}
