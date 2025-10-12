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
    public function index()
    {
        $user = Auth::user();
        $storeId = session('store_id');

        if ($user->role === 'owner') {
            $storeIds = Store::where('user_id', $user->id)->pluck('id');
        } else {
            $storeIds = [$storeId];
        }

        $products = StoreProduct::with('product')
            ->whereIn('store_id', $storeIds)
            ->where('is_active', true) 
            ->orderByDesc('id')
            ->get();

        return view('sales.index', compact('products'));
    }

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

            $sale = Sales::create([
                'store_id' => $storeId,
                'user_id' => $userId,
                'invoice_number' => $invoiceNumber,
                'total' => $request->total,
                'payment_method' => $request->payment_method,
            ]);

            foreach ($request->cart as $item) {
                $storeProduct = StoreProduct::find($item['id']);

                SalesItems::create([
                    'sale_id' => $sale->id,
                    'store_product_id' => $storeProduct->id,
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['qty'],
                ]);

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

    public function invoice($id)
    {
        $sale = Sales::with([
            'items.storeProduct.product',
            'store',
            'user'
        ])->findOrFail($id);

        return view('sales.invoice', compact('sale'));
    }

    public function history(Request $request)
    {
        $storeId = session('store_id');
        $date = $request->input('date', now()->toDateString());
        $search = $request->input('search');

        $query = \App\Models\Sales::with('user')
            ->where('store_id', $storeId)
            ->whereDate('created_at', $date)
            ->orderByDesc('created_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                ->orWhereHas('user', function ($qu) use ($search) {
                    $qu->where('name', 'like', "%{$search}%");
                })
                ->orWhere('payment_method', 'like', "%{$search}%");
            });
        }

        $sales = $query->get();
        $totalHariIni = $sales->sum('total');

        if ($request->ajax()) {
            $data = $sales->map(function ($s, $i) {
                return [
                    'id' => $s->id,
                    'no' => $i + 1,
                    'invoice_number' => $s->invoice_number,
                    'kasir' => $s->user->name ?? '-',
                    'tanggal' => $s->created_at->format('d M Y, H:i'),
                    'metode' => ucfirst($s->payment_method),
                    'total' => 'Rp ' . number_format($s->total, 0, ',', '.'),
                    'link' => route('sales.invoice', $s->id),
                ];
            });

            return response()->json([
                'sales' => $data,
                'totalHariIni' => 'Rp ' . number_format($totalHariIni, 0, ',', '.'),
            ]);
        }

        return view('sales.history', compact('sales', 'totalHariIni'));
    }

    public function destroy($id)
    {
        try {
            $sale = \App\Models\Sales::with('items')->findOrFail($id);

            foreach ($sale->items as $item) {
                $item->delete();
            }

            $sale->delete();

            return response()->json(['message' => 'Transaksi berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menghapus transaksi: ' . $e->getMessage()
            ], 500);
        }
    }
}
