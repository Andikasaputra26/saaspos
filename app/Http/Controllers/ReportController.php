<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Store;
use App\Models\SalesItems;
use Illuminate\Http\Request;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $storeId = session('store_id') ?? Store::where('user_id', $user->id)->value('id');

        $startDate = $request->input('start_date') ?? now()->format('Y-m-01');
        $endDate   = $request->input('end_date') ?? now()->format('Y-m-d');

        $salesQuery = Sales::where('store_id', $storeId)
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);

        $totalTransaksi   = $salesQuery->count();
        $totalOmzet       = $salesQuery->sum('total');

        $totalItemTerjual = SalesItems::whereHas('sale', function ($q) use ($storeId, $startDate, $endDate) {
                $q->where('store_id', $storeId)
                ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);
            })
            ->sum('quantity');

        $produkTerjual = SalesItems::select(
                'store_product_id',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(subtotal) as total_subtotal')
            )
            ->whereHas('sale', function ($q) use ($storeId, $startDate, $endDate) {
                $q->where('store_id', $storeId)
                ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate]);
            })
            ->with(['storeProduct.product'])
            ->groupBy('store_product_id')
            ->orderByDesc('total_subtotal')
            ->get();

        $paymentSummaryRaw = Sales::select('payment_method', DB::raw('SUM(total) as total'))
            ->where('store_id', $storeId)
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method');

        $paymentSummary = collect([
            ['method' => 'Cash', 'total' => $paymentSummaryRaw['cash'] ?? 0],
            ['method' => 'QRIS', 'total' => $paymentSummaryRaw['qris'] ?? 0],
            ['method' => 'E-Wallet', 'total' => $paymentSummaryRaw['ewallet'] ?? 0],
        ]);

        $chartData = Sales::select(
                DB::raw('DATE(created_at) as tanggal'),
                DB::raw('SUM(total) as omzet')
            )
            ->where('store_id', $storeId)
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        return view('report.index', compact(
            'storeId', 'startDate', 'endDate',
            'totalTransaksi', 'totalOmzet', 'totalItemTerjual',
            'produkTerjual', 'chartData', 'paymentSummary'
        ));
    }

    public function stockMovement(Request $request)
    {
        $user = Auth::user();

        $storeId = session('store_id') ?? Store::where('user_id', $user->id)->value('id');

        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', now()->toDateString());

        $movements = StockMovement::with(['storeProduct.product'])
            ->where('store_id', $storeId)
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->get();

        $totalMasuk = $movements->where('type', 'in')->sum('quantity');
        $totalKeluar = $movements->where('type', 'out')->sum('quantity');

        $chartData = StockMovement::selectRaw("
                DATE(created_at) AS tanggal,
                SUM(CASE WHEN type = 'in' THEN quantity ELSE 0 END) AS qty_in,
                SUM(CASE WHEN type = 'out' THEN quantity ELSE 0 END) AS qty_out
            ")
            ->where('store_id', $storeId)
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        return view('report.stock_movement', compact(
            'movements',
            'chartData',
            'startDate',
            'endDate',
            'totalMasuk',
            'totalKeluar'
        ));
    }
}
