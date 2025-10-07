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

        $storeIds = [];
        if ($user->role === 'owner') {
            $storeIds = Store::where('user_id', $user->id)->pluck('id');
        } else {
            $storeIds = [$storeId];
        }

        $products = StoreProduct::with('product')
            ->whereIn('store_id', $storeIds)
            ->orderBy('id', 'desc')
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

            $sale = Sales::create([
                'store_id' => $storeId,
                'user_id' => $userId,
                'invoice_number' => $invoiceNumber,
                'total' => $request->total,
                'payment_method' => $request->payment_method,
            ]);

            foreach ($request->cart as $item) {
                SalesItems::create([
                    'sale_id' => $sale->id,
                    'store_product_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['qty'],
                ]);

                StoreProduct::where('id', $item['id'])->decrement('stock', $item['qty']);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'redirect_url' => route('sales.invoice', $sale->id)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function invoice($id)
    {
        $sale = Sales::with([
            'items.product.product', // SalesItems → StoreProduct → Products
            'store',
            'user'
        ])->findOrFail($id);
        return view('sales.invoice', compact('sale'));
    }

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
