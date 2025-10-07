<?php

namespace App\Http\Controllers;

use App\Models\Products;
use App\Models\Store;
use App\Models\StoreProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
  
    public function index(Request $request)
    {
        $storeId = session('store_id') ?? Store::where('user_id', Auth::id())->value('id');

        $query = StoreProduct::with('product')
            ->where('store_id', $storeId);

        if ($search = $request->input('search')) {
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $storeProducts = $query->orderByDesc('id')->paginate(10);

        return view('products.index', compact('storeProducts'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $storeId = session('store_id') ?? Store::where('user_id', Auth::id())->value('id');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Products::create([
            'name' => $validated['name'],
            'sku' => strtoupper(Str::random(8)),
            'category' => $validated['category'] ?? null,
            'description' => $validated['description'] ?? null,
            'image' => $validated['image'] ?? null,
        ]);

        StoreProduct::create([
            'store_id' => $storeId,
            'product_id' => $product->id,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan ke toko!');
    }

    public function edit($id)
    {
        $storeProduct = StoreProduct::with('product')->findOrFail($id);
        return view('products.edit', compact('storeProduct'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $storeProduct = StoreProduct::with('product')->findOrFail($id);
        $product = $storeProduct->product;

        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update([
            'name' => $validated['name'],
            'category' => $validated['category'] ?? null,
            'description' => $validated['description'] ?? null,
            'image' => $validated['image'] ?? $product->image,
        ]);

        $storeProduct->update([
            'price' => $validated['price'],
            'stock' => $validated['stock'],
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $storeProduct = StoreProduct::with('product')->findOrFail($id);
        $product = $storeProduct->product;

        $storeProduct->delete();

        $usedInOtherStores = StoreProduct::where('product_id', $product->id)->exists();

        if (!$usedInOtherStores) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $product->delete();
        }

        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }
}
