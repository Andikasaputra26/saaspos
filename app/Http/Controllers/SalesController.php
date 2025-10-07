<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\SalesItems;
use App\Models\StoreProduct;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    /**
     * Menampilkan halaman transaksi (daftar produk aktif)
     */
    public function index()
    {
        $user = Auth::user();
        $storeId = session('store_id');

        // Ambil toko yang dimiliki user owner atau yang sedang aktif
        if ($user->role === 'owner') {
            $storeIds = Store::where('user_id', $user->id)->pluck('id');
        } else {
            $storeIds = [$storeId];
        }

        // 🔥 Ambil hanya produk aktif yang bisa dijual
        $products = StoreProduct::with('product')
            ->whereIn('store_id', $storeIds)
            ->where('is_active', true) // ✅ hanya produk aktif
            ->orderByDesc('id')
            ->get();

        return view('sales.index', compact('products'));
    }

    /**
     * Simpan transaksi penjualan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'cart' => 'required|array|min:1',
            'total' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:cash,qris,ewallet',
        ]);

        $storeId = session('store_id') ?? Store::where('user_id', Auth::id())->value('id');
        $userId = Auth::id();

        DB::beginTransaction();
        try {
            $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . str_pad(Sales::count() + 1, 4, '0', STR_PAD_LEFT);

            // 🔎 Cek apakah semua produk masih aktif dan stok cukup
            foreach ($request->cart as $item) {
                $storeProduct = StoreProduct::find($item['id']);

                if (!$storeProduct) {
                    throw new \Exception("Produk dengan ID {$item['id']} tidak ditemukan.");
                }

                if (!$storeProduct->is_active) {
                    throw new \Exception("Produk {$storeProduct->product->name} sudah nonaktif dan tidak dapat dijual.");
                }

                if ($storeProduct->stock < $item['qty']) {
                    throw new \Exception("Stok produk {$storeProduct->product->name} tidak mencukupi (tersisa {$storeProduct->stock}).");
                }
            }

            // 🔥 Simpan data penjualan utama
            $sale = Sales::create([
                'store_id' => $storeId,
                'user_id' => $userId,
                'invoice_number' => $invoiceNumber,
                'total' => $request->total,
                'payment_method' => $request->payment_method,
            ]);

            // Simpan item transaksi
            foreach ($request->cart as $item) {
                $storeProduct = StoreProduct::find($item['id']);

                SalesItems::create([
                    'sale_id' => $sale->id,
                    'store_product_id' => $storeProduct->id,
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['qty'],
                ]);

                // Kurangi stok produk
                $storeProduct->decrement('stock', $item['qty']);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'redirect_url' => route('sales.invoice', $sale->id)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi gagal: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Menampilkan halaman nota/invoice penjualan
     */
    public function invoice($id)
    {
        $sale = Sales::with([
            'items.storeProduct.product',
            'store',
            'user'
        ])->findOrFail($id);

        return view('sales.invoice', compact('sale'));
    }

    /**
     * Menampilkan riwayat penjualan hari ini
     */
    public function history()
    {
        $storeId = session('store_id');

        $sales = Sales::with('user')
            ->where('store_id', $storeId)
            ->whereDate('created_at', now()->toDateString())
            ->orderByDesc('created_at')
            ->get();

        $totalHariIni = $sales->sum('total');

        return view('sales.history', compact('sales', 'totalHariIni'));
    }
}
