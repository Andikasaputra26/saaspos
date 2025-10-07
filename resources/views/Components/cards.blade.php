<div class="row g-3 mb-4">

  {{-- === Total Penjualan Hari Ini === --}}
  <div class="col-md-4">
    <div class="card shadow-sm border-0 rounded-4 h-100">
      <div class="card-body d-flex align-items-center">
        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
          <i class="bi bi-cash-stack fs-4 text-primary"></i>
        </div>
        <div>
          <h6 class="text-muted small mb-1">Total Penjualan Hari Ini</h6>
          <h4 class="fw-bold mb-0">
            Rp {{ number_format($totalToday ?? 0, 0, ',', '.') }}
          </h4>
        </div>
      </div>
    </div>
  </div>

  {{-- === Jumlah Transaksi Minggu Ini === --}}
  <div class="col-md-4">
    <div class="card shadow-sm border-0 rounded-4 h-100">
      <div class="card-body d-flex align-items-center">
        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
          <i class="bi bi-graph-up-arrow fs-4 text-success"></i>
        </div>
        <div>
          <h6 class="text-muted small mb-1">Transaksi Minggu Ini</h6>
          <h4 class="fw-bold mb-0">
            {{ $sales->count() ?? 0 }} transaksi
          </h4>
        </div>
      </div>
    </div>
  </div>

  {{-- === Status Akun === --}}
  <div class="col-md-4">
    <div class="card shadow-sm border-0 rounded-4 h-100">
      <div class="card-body d-flex align-items-center">
        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
          <i class="bi bi-shop fs-4 text-warning"></i>
        </div>
        <div>
          <h6 class="text-muted small mb-1">Status Akun</h6>
          <h4 class="fw-bold mb-0 text-capitalize">
            {{ auth()->user()->role }}
          </h4>
        </div>
      </div>
    </div>
  </div>

</div>
