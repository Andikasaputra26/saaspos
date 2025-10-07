@extends('layouts.app')

@section('title', 'Riwayat Transaksi Kasir')

@section('content')
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold text-primary mb-0"><i class="bi bi-clock-history me-2"></i>Transaksi Hari Ini</h4>
    <span class="badge bg-success fs-6">Total: Rp {{ number_format($totalHariIni, 0, ',', '.') }}</span>
  </div>

  @if($sales->isEmpty())
    <div class="text-center py-5 text-muted">
      <i class="bi bi-emoji-frown fs-1 d-block mb-2"></i>
      Belum ada transaksi hari ini.
    </div>
  @else
    <div class="table-responsive shadow-sm rounded-3">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>Invoice</th>
            <th>Kasir</th>
            <th>Waktu</th>
            <th>Metode</th>
            <th>Total</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach($sales as $i => $s)
            <tr>
              <td>{{ $i + 1 }}</td>
              <td>{{ $s->invoice_number }}</td>
              <td>{{ $s->user->name }}</td>
              <td>{{ $s->created_at->format('H:i') }}</td>
              <td class="text-capitalize">{{ $s->payment_method }}</td>
              <td>Rp {{ number_format($s->total, 0, ',', '.') }}</td>
              <td>
                <a href="{{ route('sales.invoice', $s->id) }}" class="btn btn-outline-primary btn-sm">
                  <i class="bi bi-receipt"></i> Nota
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>
@endsection
