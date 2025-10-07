<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\PurchasesItems;
use App\Models\StoreProduct;
use App\Models\Supplier;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class PurchaseController extends Controller
{
     public function index()
    {
        $storeId = session('store_id') ?? Store::where('user_id', Auth::id())->value('id');

        $purchases = Purchase::with('supplier')
            ->where('store_id', $storeId)
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $storeId = session('store_id');
        $suppliers = Supplier::where('store_id', $storeId)->get();
        $products = StoreProduct::with('product')->where('store_id', $storeId)->get();

        return view('purchases.create', compact('suppliers', 'products'));
    }

     public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'cart' => 'required|array|min:1',
            'total' => 'required|numeric|min:0',
            'payment_method' => 'required|string|in:cash,transfer,qris,credit',
        ]);

        $storeId = session('store_id');
        DB::beginTransaction();

        try {
            $prefix = 'PB-' . now()->format('Ymd');
            $countToday = Purchase::whereDate('created_at', now())->count() + 1;
            $invoiceNumber = $prefix . '-' . str_pad($countToday, 4, '0', STR_PAD_LEFT);

            $purchase = Purchase::create([
                'store_id' => $storeId,
                'supplier_id' => $request->supplier_id,
                'invoice_number' => $invoiceNumber,
                'total' => $request->total,
                'payment_method' => $request->payment_method,
            ]);

            foreach ($request->cart as $item) {
                PurchasesItems::create([
                    'purchase_id' => $purchase->id,
                    'store_product_id' => $item['id'],
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'subtotal' => $item['qty'] * $item['price'],
                ]);

                StoreProduct::where('id', $item['id'])->increment('stock', $item['qty']);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Pembelian berhasil disimpan!',
                'redirect_url' => route('purchases.invoice', $purchase->id)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function invoice($id)
    {
        $purchase = Purchase::with(['items.storeProduct.product', 'supplier'])->findOrFail($id);
        return view('purchases.invoice', compact('purchase'));
    }
}
