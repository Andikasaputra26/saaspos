@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="main-content-wrap">

    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
        <h3>Laporan Penjualan</h3>
        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
            <li><a href="{{ route('dashboard') }}"><div class="text-tiny">Dashboard</div></a></li>
            <li><i class="icon-chevron-right"></i></li>
            <li><div class="text-tiny">Laporan Penjualan</div></li>
        </ul>
    </div>

    <div class="wg-box mb-4">
        <form method="GET" class="flex flex-wrap items-end gap20">
            <div>
                <label class="body-title mb-2">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="form-control w180">
            </div>
            <div>
                <label class="body-title mb-2">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="form-control w180">
            </div>
            <button class="tf-button style-1" type="submit">
                <i class="icon-filter"></i> Filter
            </button>
        </form>
    </div>

    <div class="wg-box mb-4">
        <h5 class="mb-4"><i class="icon-bar-chart-2"></i> Ringkasan Penjualan</h5>

        <div class="flex flex-wrap gap40 mb-5">
            <div>
                <div class="text-tiny text-muted mb-1">Total Transaksi</div>
                <h4 class="fw-bold">{{ number_format($totalTransaksi, 0, ',', '.') }}</h4>
            </div>
            <div>
                <div class="text-tiny text-muted mb-1">Total Omzet</div>
                <h4 class="fw-bold tf-color">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</h4>
            </div>
            <div>
                <div class="text-tiny text-muted mb-1">Total Item Terjual</div>
                <h4 class="fw-bold">{{ number_format($totalItemTerjual, 0, ',', '.') }}</h4>
            </div>
        </div>

        <div id="salesChart" class="mt-4"></div>
    </div>

    <div class="grid grid-cols-2 gap20 mb-4">
        <div class="wg-box">
            <h5 class="mb-3"><i class="icon-credit-card"></i> Perbandingan Metode Pembayaran</h5>
            <div id="paymentChart" style="min-height: 320px;"></div>
        </div>

        <div class="wg-box">
            <h5 class="mb-3"><i class="icon-star"></i> Top 5 Produk Terlaris</h5>
            <div id="topProductChart" style="min-height: 320px;"></div>
        </div>
    </div>

    <div class="wg-box">
        <h5 class="mb-3"><i class="icon-list"></i> Detail Produk Terlaris</h5>
        <div class="wg-table table-all-attribute">
            <ul class="table-title flex gap20 mb-14">
                <li><div class="body-title w60">#</div></li>
                <li><div class="body-title flex-grow">Nama Produk</div></li>
                <li><div class="body-title w100 text-center">Qty Terjual</div></li>
                <li><div class="body-title w140 text-end">Total Penjualan</div></li>
            </ul>

            <ul class="flex flex-column">
                @forelse($produkTerjual as $i => $p)
                    <li class="attribute-item flex items-center justify-between gap20">
                        <div class="body-text w60">{{ $i + 1 }}</div>
                        <div class="body-text flex-grow">{{ $p->storeProduct->product->name ?? '-' }}</div>
                        <div class="body-text w100 text-center">{{ number_format($p->total_qty, 0, ',', '.') }}</div>
                        <div class="body-text w140 text-end">
                            Rp {{ number_format($p->total_subtotal, 0, ',', '.') }}
                        </div>
                    </li>
                @empty
                    <li class="text-center text-muted py-3">Tidak ada data penjualan untuk periode ini</li>
                @endforelse
            </ul>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartData = @json($chartData);
    const labels = chartData.length ? chartData.map(x => x.tanggal) : ['-'];
    const values = chartData.length ? chartData.map(x => x.omzet) : [0];

    new ApexCharts(document.querySelector("#salesChart"), {
        chart: { type: 'area', height: 300, toolbar: { show: false }, foreColor: '#64748b' },
        series: [{ name: 'Omzet Harian', data: values }],
        xaxis: { categories: labels, labels: { style: { fontSize: '12px' } } },
        colors: ['#2563eb'],
        stroke: { width: 3, curve: 'smooth' },
        fill: { type: 'gradient', gradient: { shadeIntensity: 0.4, opacityFrom: 0.4, opacityTo: 0.05 } },
        grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
        tooltip: { y: { formatter: val => 'Rp ' + val.toLocaleString('id-ID') } }
    }).render();

    const paymentData = @json($paymentSummary);
    new ApexCharts(document.querySelector("#paymentChart"), {
        chart: { type: 'donut', height: 320 },
        series: paymentData.map(x => x.total),
        labels: paymentData.map(x => x.method),
        colors: ['#22c55e', '#3b82f6', '#f59e0b'],
        legend: { position: 'bottom' },
        tooltip: { y: { formatter: val => 'Rp ' + val.toLocaleString('id-ID') } }
    }).render();

    const topProducts = @json($produkTerjual->take(5));
    new ApexCharts(document.querySelector("#topProductChart"), {
        chart: { type: 'bar', height: 320, toolbar: { show: false } },
        series: [{ name: 'Qty Terjual', data: topProducts.map(p => p.total_qty) }],
        xaxis: { categories: topProducts.map(p => p.store_product?.product?.name ?? '-') },
        colors: ['#10b981'],
        plotOptions: { bar: { horizontal: true, borderRadius: 4 } },
        dataLabels: { enabled: false },
        grid: { borderColor: '#e2e8f0', strokeDashArray: 4 }
    }).render();
});
</script>
@endpush

@push('styles')
<style>
.wg-table ul { list-style: none; margin: 0; padding: 0; }
.table-title { border-bottom: 1px solid #e5e7eb; font-weight: 600; color: #374151; }
.attribute-item { border-bottom: 1px dashed #e5e7eb; padding: 10px 0; transition: .2s; }
.attribute-item:hover { background: #f9fafb; border-radius: 6px; }
.body-text { font-size: 13px; color: #4b5563; }
.text-muted { color: #9ca3af; }

.w60 { width: 60px; }
.w100 { width: 100px; }
.w140 { width: 140px; }

.tf-color { color: #2563eb; }
</style>
@endpush
