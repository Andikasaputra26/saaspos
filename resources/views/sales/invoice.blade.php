@extends('layouts.app')

@section('title', 'Nota Penjualan')

@section('content')
<div class="container py-4" id="invoiceArea">
  <div class="text-center mb-4">
    <h4 class="fw-bold text-primary">NOTA PENJUALAN</h4>
    <p class="text-muted small mb-0">Invoice: {{ $sale->invoice_number }}</p>
    <p class="text-muted small">Tanggal: {{ $sale->created_at->format('d M Y, H:i') }}</p>
  </div>

  <table class="table table-sm table-bordered">
    <thead class="table-light">
      <tr>
        <th>Produk</th>
        <th class="text-center" width="80">Qty</th>
        <th class="text-end" width="120">Harga</th>
        <th class="text-end" width="120">Subtotal</th>
      </tr>
    </thead>
    <tbody>
      @foreach($sale->items as $item)
        <tr>
          <td>{{ $item->product->product->name ?? '-' }}</td>
          <td class="text-center">{{ $item->quantity }}</td>
          <td class="text-end">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
          <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div class="text-end mt-3">
    <h5 class="fw-bold text-primary">Total: Rp {{ number_format($sale->total, 0, ',', '.') }}</h5>
    <p class="text-muted small mb-0">Metode Pembayaran: {{ ucfirst($sale->payment_method) }}</p>
    <p class="text-muted small">Kasir: {{ $sale->user->name ?? '-' }}</p>
  </div>

  <div class="text-center mt-4">
    <button class="btn btn-outline-secondary" onclick="window.print()">
      <i class="bi bi-printer me-1"></i> Cetak Nota
    </button>
  </div>
</div>
@endsection
