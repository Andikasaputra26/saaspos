@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0">Ringkasan Penjualan</h5>

        <div class="col-xl-3">
            <div class="select">
                <select id="rangeFilter">
                    <option value="7d" selected>7 Hari Terakhir</option>
                    <option value="30d">30 Hari Terakhir</option>
                    <option value="month">Bulan Ini</option>
                </select>
            </div>
        </div>
    </div>

    <div class="wg-box mb-4">
        <div class="flex flex-wrap gap40" id="summaryBox">
            <div>
                <div class="text-tiny text-muted mb-1">Total Penjualan</div>
                <div class="flex items-center gap10">
                    <h4 class="fw-bold" id="totalSales">Rp {{ number_format($total, 0, ',', '.') }}</h4>
                    <i class="icon-trending-up text-success"></i>
                </div>
            </div>

            <div>
                <div class="text-tiny text-muted mb-1">Jumlah Transaksi</div>
                <div class="flex items-center gap10">
                    <h4 class="fw-bold" id="countSales">{{ $count }}</h4>
                    <i class="icon-trending-up text-success"></i>
                </div>
            </div>

            <div>
                <div class="text-tiny text-muted mb-1">Rata-rata per Transaksi</div>
                <div class="flex items-center gap10">
                    <h4 class="fw-bold" id="averageSales">Rp {{ number_format($average, 0, ',', '.') }}</h4>
                    <i class="icon-trending-up text-success"></i>
                </div>
            </div>
        </div>

        <div id="line-chart-7" class="mt-4"></div>
    </div>
</div>
@endsection


@push('scripts')
<script>
let chart;

document.addEventListener("DOMContentLoaded", function () {
    const labels = @json($labels);
    const values = @json($data);

    const options = {
        chart: {
            type: 'area',
            height: 320,
            toolbar: { show: false },
            foreColor: '#777',
        },
        series: [{ name: 'Total Penjualan', data: values }],
        stroke: { width: 3, curve: 'smooth' },
        colors: ['#2575fc'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 0.5,
                opacityFrom: 0.4,
                opacityTo: 0.05,
            }
        },
        xaxis: {
            categories: labels,
            labels: { style: { colors: '#888', fontSize: '12px' } }
        },
        yaxis: {
            labels: {
                formatter: val => 'Rp ' + val.toLocaleString('id-ID'),
                style: { colors: '#888' }
            }
        },
        tooltip: {
            y: { formatter: val => 'Rp ' + val.toLocaleString('id-ID') }
        },
        grid: {
            borderColor: '#eee',
            strokeDashArray: 4,
        },
        markers: {
            size: 4,
            colors: ['#2575fc'],
            strokeColors: '#fff',
            strokeWidth: 2,
        }
    };

    chart = new ApexCharts(document.querySelector("#line-chart-7"), options);
    chart.render();

    document.getElementById("rangeFilter").addEventListener("change", function() {
        loadDashboardData(this.value);
    });
});

async function loadDashboardData(range = '7d') {
    try {
        const res = await fetch(`{{ route('dashboard.data') }}?range=${range}`);
        const data = await res.json();

        if (data.empty) {
            chart.updateSeries([{ name: 'Total Penjualan', data: [] }]);
            document.querySelector("#totalSales").innerText = "Rp 0";
            document.querySelector("#countSales").innerText = "0";
            document.querySelector("#averageSales").innerText = "Rp 0";
            return;
        }

        chart.updateOptions({
            xaxis: { categories: data.labels },
        });
        chart.updateSeries([{ name: 'Total Penjualan', data: data.data }]);

        document.querySelector("#totalSales").innerText = "Rp " + data.total.toLocaleString('id-ID');
        document.querySelector("#countSales").innerText = data.count;
        document.querySelector("#averageSales").innerText = "Rp " + data.average.toLocaleString('id-ID');

    } catch (error) {
        console.error("Gagal memuat data:", error);
    }
}
</script>
@endpush
