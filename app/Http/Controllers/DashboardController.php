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

        $stores = $user->role === 'owner'
            ? Store::where('user_id', $user->id)->get()
            : collect();

        $sales = Sales::query()
            ->when($storeId, fn($q) => $q->where('store_id', $storeId))
            ->whereBetween('created_at', [now()->subDays(6), now()])
            ->orderBy('created_at')
            ->get(['created_at', 'total']);

        $hasSales = $sales->isNotEmpty();

        $labels = $sales->pluck('created_at')->map(fn($d) => $d->format('d M'));
        $data   = $sales->pluck('total');

        $total   = $sales->sum('total');
        $count   = $sales->count();
        $average = $count ? round($total / $count, 2) : 0;

        return view('dashboard.index', compact(
            'sales', 'stores', 'hasSales', 'labels', 'data', 'total', 'count', 'average'
        ));
    }


    public function getData(Request $request)
    {
        $storeId = session('store_id');
        $range   = $request->input('range', 'month');

        $query = Sales::query()
            ->when($storeId, fn($q) => $q->where('store_id', $storeId));

        $start = now()->startOfMonth();
        $end   = now()->endOfDay();

        switch ($range) {
            case '7d':
                $start = now()->subDays(6)->startOfDay();
                break;
            case '30d':
                $start = now()->subDays(29)->startOfDay();
                break;
            case 'month':
            default:
                $start = now()->startOfMonth();
                break;
        }

        $sales = $query
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at')
            ->get(['created_at', 'total']);

        if ($sales->isEmpty()) {
            return response()->json([
                'empty' => true,
                'labels' => [],
                'data' => [],
                'total' => 0,
                'count' => 0,
                'average' => 0,
            ]);
        }

        $labels  = $sales->pluck('created_at')->map(fn($d) => $d->timezone('Asia/Jakarta')->format('d M'));
        $data    = $sales->pluck('total');
        $total   = $sales->sum('total');
        $count   = $sales->count();
        $average = $count ? round($total / $count, 2) : 0;

        return response()->json([
            'empty' => false,
            'labels' => $labels,
            'data' => $data,
            'total' => $total,
            'count' => $count,
            'average' => $average,
        ]);
    }
}
