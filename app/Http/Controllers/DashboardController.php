<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $storeId = session('store_id');

        // Jika user owner => bisa pilih toko
        $stores = $user->role === 'owner'
            ? Store::where('user_id', $user->id)->get()
            : collect();

        // Ambil 7 hari terakhir berdasarkan created_at
        $sales = Sales::query()
            ->when($storeId, fn($q) => $q->where('store_id', $storeId))
            ->orderByDesc('created_at')
            ->take(7)
            ->get(['created_at', 'total']);

        // Total penjualan hari ini
        $totalToday = Sales::whereDate('created_at', now()->toDateString())
            ->when($storeId, fn($q) => $q->where('store_id', $storeId))
            ->sum('total');

        // Cek apakah ada data penjualan
        $hasSales = $sales->isNotEmpty();

        return view('dashboard.index', compact('sales', 'totalToday', 'stores', 'hasSales'));
    }

    public function getData(Request $request)
    {
        $storeId = $request->input('store_id');
        $range   = $request->input('range', '7d');

        $query = Sales::query();

        if ($storeId) {
            $query->where('store_id', $storeId);
        }

        // Filter waktu
        if ($range === '7d') {
            $query->whereBetween('created_at', [now()->subDays(6), now()]);
        } elseif ($range === '30d') {
            $query->whereBetween('created_at', [now()->subDays(29), now()]);
        } elseif ($range === 'month') {
            $query->whereMonth('created_at', now()->month);
        }

        $sales = $query->orderBy('created_at')->get(['created_at', 'total']);

        // Jika belum ada data
        if ($sales->isEmpty()) {
            return response()->json([
                'empty'   => true,
                'message' => 'Belum ada data penjualan pada periode ini.',
                'labels'  => [],
                'data'    => [],
                'total'   => 0,
                'count'   => 0,
            ]);
        }

        return response()->json([
            'empty'  => false,
            'labels' => $sales->pluck('created_at')->map(fn($d) => $d->format('d M')),
            'data'   => $sales->pluck('total'),
            'total'  => $sales->sum('total'),
            'count'  => $sales->count(),
        ]);
    }
}
