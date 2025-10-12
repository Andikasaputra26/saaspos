<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Products;
use Illuminate\Support\Str;
use App\Models\StoreProduct;
use Illuminate\Http\Request;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $storeId = session('store_id') ?? Store::where('user_id', Auth::id())->value('id');

        $query = StoreProduct::with('product')
            ->where('store_id', $storeId);

        $status = $request->input('status');
        if ($status === 'aktif') {
            $query->where('is_active', true);
        } elseif ($status === 'nonaktif') {
            $query->where('is_active', false);
        }

        if ($search = $request->input('search')) {
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%")
                ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $storeProducts = $query->orderByDesc('id')->paginate(10)->withQueryString();

        if ($request->ajax()) {
            $html = view('products.partials._product_rows', compact('storeProducts'))->render();
            return response()->json(['html' => $html]);
        }

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
            'is_active' => $validated['is_active'] ?? true, 
        ]);

        $storeProduct = StoreProduct::create([
            'store_id' => $storeId,
            'product_id' => $product->id,
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // catat stok awal
        StockMovement::create([
            'store_id' => $storeId,
            'store_product_id' => $storeProduct->id,
            'type' => 'in',
            'quantity' => $validated['stock'],
            'note' => 'Stok awal produk baru',
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
        $oldStock = $storeProduct->stock;

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
            'is_active' => $validated['is_active'] ?? true,
        ]);

        $diff = $validated['stock'] - $oldStock;
        if ($diff != 0) {
            \App\Models\StockMovement::create([
                'store_id' => $storeProduct->store_id,
                'store_product_id' => $storeProduct->id,
                'type' => $diff > 0 ? 'in' : 'out',
                'quantity' => abs($diff),
                'notes' => 'Perubahan stok manual dari halaman edit produk',
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()
            ->route('products.index')
            ->with('success', '✅ Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $storeProduct = StoreProduct::with(['product', 'saleItems'])->findOrFail($id);
        $product = $storeProduct->product;

        try {
            if ($storeProduct->saleItems()->exists()) {
                $storeProduct->update(['is_active' => false]);
                $message = '⚠️ Produk sudah pernah digunakan di transaksi, jadi hanya dinonaktifkan.';

                if (request()->ajax()) {
                    return response()->json(['status' => 'warning', 'message' => $message]);
                }

                return redirect()->route('products.index')->with('error', $message);
            }

            if ($storeProduct->stock > 0) {
                \App\Models\StockMovement::create([
                    'store_id' => $storeProduct->store_id,
                    'store_product_id' => $storeProduct->id,
                    'type' => 'out',
                    'quantity' => $storeProduct->stock,
                    'note' => 'Produk dihapus dari toko',
                ]);
            }

            $storeProduct->delete();

            $usedInOtherStores = StoreProduct::where('product_id', $product->id)
                ->where('id', '!=', $storeProduct->id)
                ->exists();

            if (!$usedInOtherStores) {
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                $product->delete();
            }

            $message = '✅ Produk berhasil dihapus permanen.';
            if (request()->ajax()) {
                return response()->json(['status' => 'success', 'message' => $message]);
            }

            return redirect()->route('products.index')->with('success', $message);

        } catch (\Exception $e) {
            $error = '❌ Gagal menghapus produk: ' . $e->getMessage();

            if (request()->ajax()) {
                return response()->json(['status' => 'error', 'message' => $error], 500);
            }

            return redirect()->route('products.index')->with('error', $error);
        }
    }

    public function deactivate($id)
    {
        $storeProduct = StoreProduct::findOrFail($id);
        $storeProduct->update(['is_active' => false]);

        $message = '✅ Produk berhasil dinonaktifkan.';
        if (request()->ajax()) {
            return response()->json(['status' => 'success', 'message' => $message]);
        }

        return back()->with('success', $message);
    }

    public function activate($id)
    {
        $storeProduct = StoreProduct::findOrFail($id);
        $storeProduct->update(['is_active' => true]);

        $msg = '✅ Produk berhasil diaktifkan kembali.';
        return request()->ajax()
            ? response()->json(['status' => 'success', 'message' => $msg])
            : back()->with('success', $msg);
    }
}
