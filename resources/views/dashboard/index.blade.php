@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container py-4">

  {{-- === HEADER SECTION === --}}
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <div class="mb-2">
      <h2 class="fw-bold text-primary mb-1">Dashboard</h2>
      <p class="text-muted small mb-0">
        Selamat datang, <strong>{{ auth()->user()->name }}</strong>
        @if(session('store_id'))
          — <span class="text-secondary">Toko aktif:</span> 
          <strong>{{ \App\Models\Store::find(session('store_id'))->name ?? '-' }}</strong>
        @endif
      </p>
    </div>

    {{-- === FILTER AREA === --}}
    <div class="d-flex align-items-center flex-wrap gap-2">
      {{-- Filter rentang waktu --}}
      <select id="rangeFilter" class="form-select form-select-sm">
        <option value="7d" selected>7 Hari Terakhir</option>
        <option value="30d">30 Hari Terakhir</option>
        <option value="month">Bulan Ini</option>
      </select>

      {{-- Dropdown toko (jika owner) --}}
      @if(auth()->user()->role === 'owner' && $stores->count() > 0)
        <select id="storeFilter" class="form-select form-select-sm">
          @foreach($stores as $store)
            <option value="{{ $store->id }}" {{ session('store_id') == $store->id ? 'selected' : '' }}>
              {{ $store->name }}
            </option>
          @endforeach
        </select>
      @endif

      {{-- Indikator Refresh --}}
      <div id="refreshIndicator" class="text-muted small ms-2 d-flex align-items-center">
        <i class="bi bi-arrow-repeat spin me-1"></i> Memuat data...
      </div>
    </div>
  </div>

  {{-- === STATISTIK CARDS === --}}
  <div id="statsContainer">
    @include('Components.cards')
  </div>

  {{-- === GRAFIK PENJUALAN === --}}
  <div class="card shadow-sm border-0 rounded-4 mb-4">
    <div class="card-header bg-transparent border-0 pb-0 d-flex align-items-center">
      <h5 class="fw-bold mb-0">
        <i class="bi bi-bar-chart-fill me-2 text-primary"></i>
        Penjualan Real-Time
      </h5>
    </div>

    <div class="card-body pt-3 text-center">

      {{-- Jika belum ada penjualan sama sekali --}}
      @if(!$hasSales)
        <div class="py-5">
          <i class="bi bi-emoji-neutral text-muted display-6 d-block mb-3"></i>
          <h6 class="text-muted">Belum ada data penjualan</h6>
          <p class="small text-secondary mb-0">Mulailah mencatat transaksi untuk melihat grafik di sini.</p>
        </div>
      @else
        <canvas id="salesChart" height="100"></canvas>
      @endif
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
  // === Global Variables ===
  let chart;
  const ctx = document.getElementById('salesChart')?.getContext('2d');
  const refreshIndicator = document.getElementById('refreshIndicator');

  /**
   * Memuat data dashboard (chart + statistik)
   */
  async function loadDashboard(range = '7d', store_id = null, silent = false) {
    if (!silent) {
      refreshIndicator.innerHTML = `<i class="bi bi-arrow-repeat spin me-1"></i> Memuat data...`;
    }

    try {
      const response = await fetch(`{{ route('dashboard.data') }}?range=${range}${store_id ? '&store_id=' + store_id : ''}`);
      const res = await response.json();

      // === Handle "no data" ===
      if (res.empty) {
        if (chart) chart.destroy();
        const container = ctx?.canvas.parentElement;
        container.innerHTML = `
          <div class="py-5">
            <i class="bi bi-emoji-neutral text-muted display-6 d-block mb-3"></i>
            <h6 class="text-muted">Belum ada data penjualan</h6>
            <p class="small text-secondary mb-0">Transaksi baru akan muncul secara otomatis di grafik ini.</p>
          </div>
        `;
        refreshIndicator.innerHTML = `<i class="bi bi-info-circle text-warning"></i> Tidak ada data`;
        return;
      }

      // === Update Chart ===
      if (chart) chart.destroy();
      chart = new Chart(ctx, {
        type: 'line',
        data: {
          labels: res.labels,
          datasets: [{
            label: 'Total Penjualan',
            data: res.data,
            borderColor: '#2575fc',
            backgroundColor: 'rgba(37,117,252,0.1)',
            borderWidth: 3,
            fill: true,
            tension: 0.35,
            pointRadius: 4,
            pointBackgroundColor: '#2575fc',
          }]
        },
        options: {
          responsive: true,
          plugins: { legend: { display: false } },
          scales: {
            y: { beginAtZero: true, ticks: { color: '#888' } },
            x: { ticks: { color: '#666' } }
          }
        }
      });

      // === Update Statistik Cards ===
      document.querySelector('#statsContainer').innerHTML = `
        <div class="row g-3 mb-4">
          <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
              <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                  <i class="bi bi-cash-stack fs-4 text-primary"></i>
                </div>
                <div>
                  <h6 class="text-muted small mb-1">Total Penjualan (Periode)</h6>
                  <h4 class="fw-bold mb-0">Rp ${res.total.toLocaleString('id-ID')}</h4>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
              <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                  <i class="bi bi-graph-up-arrow fs-4 text-success"></i>
                </div>
                <div>
                  <h6 class="text-muted small mb-1">Jumlah Transaksi</h6>
                  <h4 class="fw-bold mb-0">${res.count} transaksi</h4>
                </div>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 h-100">
              <div class="card-body d-flex align-items-center">
                <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                  <i class="bi bi-clock-history fs-4 text-warning"></i>
                </div>
                <div>
                  <h6 class="text-muted small mb-1">Terakhir Diperbarui</h6>
                  <h4 class="fw-bold mb-0">${new Date().toLocaleTimeString('id-ID')}</h4>
                </div>
              </div>
            </div>
          </div>
        </div>
      `;

      refreshIndicator.innerHTML = `<i class="bi bi-check-circle text-success me-1"></i> Diperbarui ${new Date().toLocaleTimeString('id-ID')}`;
    } catch (error) {
      console.error(error);
      refreshIndicator.innerHTML = `<i class="bi bi-exclamation-triangle text-danger"></i> Gagal memuat data`;
    }
  }

  // === Load pertama kali ===
  loadDashboard();

  // === Event Listener: Filter Waktu & Toko ===
  document.getElementById('rangeFilter').addEventListener('change', e => {
    const storeId = document.getElementById('storeFilter')?.value || null;
    loadDashboard(e.target.value, storeId);
  });

  document.getElementById('storeFilter')?.addEventListener('change', e => {
    const range = document.getElementById('rangeFilter').value;
    loadDashboard(range, e.target.value);
  });

  // === Auto Refresh Tiap 60 Detik ===
  setInterval(() => {
    const range = document.getElementById('rangeFilter').value;
    const storeId = document.getElementById('storeFilter')?.value || null;
    loadDashboard(range, storeId, true);
  }, 60000);
</script>

{{-- === STYLE TAMBAHAN === --}}
<style>
  #refreshIndicator {
    opacity: 0.7;
    transition: all 0.3s ease;
  }
  .spin {
    animation: spin 1.3s linear infinite;
  }
  @keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
  }
</style>
@endpush
