<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PurchaseController extends Controller
{
   
    public function index(Request $request)
    {
        $storeId = session('store_id');

        $query = Purchase::with(['supplier'])
            ->where('store_id', $storeId)
            ->orderByDesc('created_at');

        // === FILTER TANGGAL ===
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        if ($request->filled('supplier_id') && $request->supplier_id !== 'all') {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('payment_method', 'like', "%{$search}%");
            });
        }

        $purchases = $query->paginate(10)->withQueryString();
        $suppliers = Supplier::where('store_id', $storeId)->get();

        return view('purchases.index', compact('purchases', 'suppliers'));
    }

    public function show($id)
    {
        $purchase = Purchase::with(['supplier', 'items.storeProduct.product'])
            ->findOrFail($id);

        return view('purchases.show', compact('purchase'));
    }

    public function getPurchases(Request $request)
    {
        $storeId = session('store_id');

        $query = Purchase::with(['supplier'])
            ->where('store_id', $storeId)
            ->orderByDesc('created_at');

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->input('date'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhere('payment_method', 'like', "%{$search}%");
            });
        }

        $purchases = $query->get()->map(function ($p, $i) {
            return [
                'no' => $i + 1,
                'id' => $p->id,
                'invoice_number' => $p->invoice_number,
                'supplier' => $p->supplier->name ?? '-',
                'tanggal' => $p->created_at->format('d M Y, H:i'),
                'payment_method' => ucfirst($p->payment_method),
                'total' => 'Rp ' . number_format($p->total, 0, ',', '.'),
                'link' => route('purchases.show', $p->id),
            ];
        });

        return response()->json([
            'status' => 'success',
            'purchases' => $purchases,
        ]);
    }
}
