@extends('layouts.app')
@section('title', 'Laporan Penjualan')

@push('styles')
<style>
  table td, table th { vertical-align: middle !important; }
  .card-header { border-bottom: 1px solid #eee; }
  .dt-buttons {
    margin-bottom: 10px;
  }
  .dt-buttons .btn {
    margin-right: 6px;
  }
</style>
@endpush

@section('content')
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <div>
      <h2 class="fw-bold text-primary mb-1">Laporan Penjualan</h2>
      <p class="text-muted small mb-0">
        Pilih periode untuk melihat laporan penjualan dan export data.
      </p>
    </div>

    <form id="filterForm" class="d-flex flex-wrap gap-2 mt-3 mt-md-0">
      <input type="date" id="start_date" value="{{ $startDate }}" class="form-control form-control-sm">
      <input type="date" id="end_date" value="{{ $endDate }}" class="form-control form-control-sm">
      <button type="submit" class="btn btn-primary btn-sm">
        <i class="bi bi-filter me-1"></i> Terapkan Filter
      </button>
      <button type="button" id="resetFilter" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-clockwise me-1"></i> Reset
      </button>
    </form>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <x-card-stat title="Total Transaksi" value="{{ number_format($totalTransaksi) }} Nota" icon="bi-receipt" color="primary" />
    </div>
    <div class="col-md-4">
      <x-card-stat title="Total Item Terjual" value="{{ number_format($totalItemTerjual) }} Item" icon="bi-basket" color="warning" />
    </div>
    <div class="col-md-4">
      <x-card-stat title="Total Omzet" value="Rp {{ number_format($totalOmzet, 0, ',', '.') }}" icon="bi-cash-stack" color="success" />
    </div>
  </div>

  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-light fw-semibold">
      <i class="bi bi-box-seam me-2"></i> Penjualan per Produk
    </div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table id="reportTable" class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th width="60">#</th>
              <th>Produk</th>
              <th class="text-center" width="120">Qty</th>
              <th class="text-end" width="180">Subtotal</th>
              <th hidden>Tanggal</th>
            </tr>
          </thead>
          <tbody>
            @forelse($produkTerjual as $i => $p)
              <tr data-tanggal="{{ $p->tanggal ?? now()->format('Y-m-d') }}">
                <td>{{ $i + 1 }}</td>
                <td>{{ $p->storeProduct->product->name ?? 'Produk dihapus' }}</td>
                <td class="text-center">{{ $p->total_qty }}</td>
                <td class="text-end">Rp {{ number_format($p->total_subtotal, 0, ',', '.') }}</td>
                <td hidden>{{ $p->tanggal ?? now()->format('Y-m-d') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-muted py-4">
                  <i class="bi bi-emoji-neutral fs-4 d-block mb-2"></i>
                  Tidak ada transaksi pada periode ini.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function () {
  const table = $('#reportTable').DataTable({
    dom: 'Bfrtip',
    buttons: [
      { extend: 'excelHtml5', text: '<i class="bi bi-file-earmark-excel me-1"></i> Excel', className: 'btn btn-success btn-sm' },
      { extend: 'pdfHtml5', text: '<i class="bi bi-file-earmark-pdf me-1"></i> PDF', className: 'btn btn-danger btn-sm', orientation: 'portrait', pageSize: 'A4' },
      { extend: 'print', text: '<i class="bi bi-printer me-1"></i> Print', className: 'btn btn-secondary btn-sm' }
    ],
    order: [],
    paging: false,
    searching: true,
    info: false,
    language: { emptyTable: 'Tidak ada data penjualan pada periode ini' }
  });

  $('#filterForm').on('submit', function (e) {
    e.preventDefault();
    const start = $('#start_date').val();
    const end = $('#end_date').val();

    table.rows().every(function () {
      const date = this.data()[4]; 
      if (date >= start && date <= end) {
        $(this.node()).show();
      } else {
        $(this.node()).hide();
      }
    });
  });

  $('#resetFilter').on('click', function () {
    $('#start_date').val('');
    $('#end_date').val('');
    table.rows().every(function () {
      $(this.node()).show();
    });
  });
});
</script>
@endpush
