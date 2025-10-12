@extends('layouts.app')

@section('title', 'Laporan Pergerakan Stok')

@section('content')
<div class="main-content-wrap">

    {{-- === HEADER === --}}
    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
        <h3>Pergerakan Stok</h3>
        <ul class="breadcrumbs flex items-center flex-wrap gap10">
            <li><a href="{{ route('dashboard') }}"><div class="text-tiny">Dashboard</div></a></li>
            <li><i class="icon-chevron-right"></i></li>
            <li><div class="text-tiny">Laporan</div></li>
        </ul>
    </div>

    {{-- === FILTER === --}}
    <div class="wg-box mb-4">
        <form method="GET" class="flex items-end gap20 flex-wrap">
            <div>
                <label class="body-title mb-2">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="form-control w180">
            </div>
            <div>
                <label class="body-title mb-2">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="form-control w180">
            </div>
            <button class="tf-button style-1"><i class="icon-filter"></i> Filter</button>
        </form>
    </div>

    {{-- === RINGKASAN === --}}
    <div class="flex flex-wrap gap40 mb-4">
        <div>
            <div class="text-tiny text-muted mb-1">Total Barang Masuk</div>
            <h4 class="fw-bold text-success">{{ number_format($totalMasuk, 0, ',', '.') }}</h4>
        </div>
        <div>
            <div class="text-tiny text-muted mb-1">Total Barang Keluar</div>
            <h4 class="fw-bold text-danger">{{ number_format($totalKeluar, 0, ',', '.') }}</h4>
        </div>
        <div>
            <div class="text-tiny text-muted mb-1">Saldo Periode Ini</div>
            <h4 class="fw-bold tf-color">{{ number_format($totalMasuk - $totalKeluar, 0, ',', '.') }}</h4>
        </div>
    </div>

    {{-- === GRAFIK KOMPARASI === --}}
    <div class="wg-box mb-4">
        <h5 class="mb-3"><i class="icon-bar-chart-2"></i> Grafik Stok Masuk vs Keluar</h5>
        <div id="stockChart" style="min-height: 320px;"></div>
    </div>

    {{-- === TABEL DETAIL === --}}
    <div class="wg-box">
        <h5 class="mb-3"><i class="icon-refresh-cw"></i> Riwayat Pergerakan Stok</h5>
        <div class="wg-table table-all-attribute">
            <ul class="table-title flex gap20 mb-14">
                <li><div class="body-title w160">Tanggal</div></li>
                <li><div class="body-title flex-grow">Produk</div></li>
                <li><div class="body-title w100 text-center">Tipe</div></li>
                <li><div class="body-title w100 text-center">Qty</div></li>
                <li><div class="body-title w180">Referensi</div></li>
                <li><div class="body-title w200">Catatan</div></li>
            </ul>
            <ul class="flex flex-column">
                @forelse($movements as $m)
                    <li class="attribute-item flex items-center justify-between gap20">
                        <div class="body-text w160">{{ \Carbon\Carbon::parse($m->created_at)->format('d M Y H:i') }}</div>
                        <div class="body-text flex-grow">{{ $m->storeProduct->product->name ?? '-' }}</div>
                        <div class="body-text w100 text-center">
                            <span class="badge {{ $m->type == 'in' ? 'bg-success' : 'bg-danger' }}">
                                {{ strtoupper($m->type) }}
                            </span>
                        </div>
                        <div class="body-text w100 text-center">{{ number_format($m->quantity, 0, ',', '.') }}</div>
                        <div class="body-text w180">{{ $m->reference ?? '-' }}</div>
                        <div class="body-text w200 text-muted">{{ $m->note ?? '-' }}</div>
                    </li>
                @empty
                    <li class="text-center text-muted py-3">Tidak ada data stok pada periode ini</li>
                @endforelse
            </ul>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    const chartData = @json($chartData);
    const labels = chartData.map(x => x.tanggal);
    const qtyIn  = chartData.map(x => x.qty_in);
    const qtyOut = chartData.map(x => x.qty_out);

    const options = {
        chart: {
            height: 340,
            type: 'line',
            stacked: false,
            toolbar: { show: false },
            foreColor: '#64748b'
        },
        stroke: {
            width: [3, 3],
            curve: 'smooth'
        },
        series: [
            { name: 'Stok Masuk', type: 'area', data: qtyIn },
            { name: 'Stok Keluar', type: 'line', data: qtyOut }
        ],
        colors: ['#22c55e', '#ef4444'],
        fill: {
            type: 'solid',
            opacity: [0.25, 1]
        },
        labels: labels,
        xaxis: {
            categories: labels,
            labels: { style: { fontSize: '12px' } }
        },
        yaxis: [{
            title: { text: 'Jumlah (Qty)' },
            labels: { style: { fontSize: '12px' } }
        }],
        grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
        legend: { position: 'top', horizontalAlign: 'right' },
        tooltip: {
            shared: true,
            y: {
                formatter: val => val.toLocaleString('id-ID') + ' unit'
            }
        }
    };

    const chart = new ApexCharts(document.querySelector("#stockChart"), options);
    chart.render();
});
</script>
@endpush

@push('styles')
<style>
/* === Table Style === */
.attribute-item { border-bottom: 1px dashed #e5e7eb; padding: 8px 0; transition: .2s; }
.attribute-item:hover { background: #f9fafb; border-radius: 6px; }
.w100 { width: 100px; } .w160 { width: 160px; } .w180 { width: 180px; } .w200 { width: 200px; }
.bg-success { background: #22c55e; color: #fff; border-radius: 5px; padding: 3px 8px; font-size: 12px; }
.bg-danger { background: #ef4444; color: #fff; border-radius: 5px; padding: 3px 8px; font-size: 12px; }
.text-muted { color: #9ca3af; }
.text-success { color: #16a34a; }
.text-danger { color: #dc2626; }
</style>
@endpush
