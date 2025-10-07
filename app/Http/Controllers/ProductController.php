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

        // Filter status (aktif / nonaktif)
        $status = $request->input('status');
        if ($status === 'aktif') {
            $query->where('is_active', true);
        } elseif ($status === 'nonaktif') {
            $query->where('is_active', false);
        }

        // Pencarian nama / SKU / kategori
        if ($search = $request->input('search')) {
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $storeProducts = $query->orderByDesc('id')->paginate(10)->withQueryString();

        return view('products.index', compact('storeProducts', 'status'));
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
            'is_active' => 'nullable|boolean',
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
            'is_active' => $validated['is_active'] ?? true, // ✅ default aktif
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
            'is_active' => 'nullable|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $storeProduct = StoreProduct::with('product')->findOrFail($id);
        $product = $storeProduct->product;

        // Jika ada gambar baru, hapus gambar lama
        if ($request->hasFile('image')) {
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Update produk master
        $product->update([
            'name' => $validated['name'],
            'category' => $validated['category'] ?? null,
            'description' => $validated['description'] ?? null,
            'image' => $validated['image'] ?? $product->image,
        ]);

        // Update produk di toko
        $storeProduct->update([
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return redirect()->route('products.index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $storeProduct = StoreProduct::with(['product', 'saleItems'])->findOrFail($id);
        $product = $storeProduct->product;

        try {
            // Jika produk sudah digunakan di transaksi
            if ($storeProduct->saleItems()->exists()) {
                // Nonaktifkan saja agar tidak muncul di kasir
                $storeProduct->update(['is_active' => false]);

                return redirect()->route('products.index')
                    ->with('error', '⚠️ Produk sudah pernah digunakan di transaksi, jadi hanya dinonaktifkan.');
            }

            // Hapus relasi toko
            $storeProduct->delete();

            // Jika produk tidak digunakan di toko lain, hapus juga master produk dan gambar
            $usedInOtherStores = StoreProduct::where('product_id', $product->id)->exists();

            if (!$usedInOtherStores) {
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                $product->delete();
            }

            return redirect()->route('products.index')
                ->with('success', '✅ Produk berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('products.index')
                ->with('error', '❌ Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function activate($id)
    {
        $storeProduct = StoreProduct::findOrFail($id);
        $storeProduct->update(['is_active' => true]);

        return redirect()->route('products.index')
            ->with('success', '✅ Produk berhasil diaktifkan kembali.');
    }
}
