<?php

namespace App\Exports;

use App\Models\SalesItems;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class ReportExport implements FromView
{
    protected $startDate, $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
    }

    public function view(): View
    {
        $storeId = session('store_id');

        $produkTerjual = SalesItems::select(
                'store_product_id',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('SUM(subtotal) as total_subtotal')
            )
            ->whereHas('sale', function ($q) use ($storeId) {
                $q->where('store_id', $storeId);
            })
            ->with(['storeProduct.product'])
            ->groupBy('store_product_id')
            ->get();

        return view('reports.export-excel', [
            'produkTerjual' => $produkTerjual,
            'startDate' => $this->startDate,
            'endDate' => $this->endDate,
        ]);
    }
}
