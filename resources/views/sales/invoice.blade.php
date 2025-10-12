@extends('layouts.app')

@section('title', 'Nota Penjualan')

@section('content')
<div class="main-content-wrap">

    {{-- === HEADER & BREADCRUMB === --}}
    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
        <h3>Nota Penjualan</h3>
        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
            <li><a href="{{ route('dashboard') }}"><div class="text-tiny">Dashboard</div></a></li>
            <li><i class="icon-chevron-right"></i></li>
            <li><a href="{{ route('sales.history') }}"><div class="text-tiny">Kasir</div></a></li>
            <li><i class="icon-chevron-right"></i></li>
            <li><div class="text-tiny">Nota Penjualan</div></li>
        </ul>
    </div>

    {{-- === INVOICE BOX === --}}
    <div class="wg-box">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h4 class="fw-bold text-primary mb-1">INVOICE PENJUALAN</h4>
                <p class="text-muted text-sm mb-0">Invoice: <strong>#{{ $sale->invoice_number }}</strong></p>
                <p class="text-muted text-sm">Tanggal: {{ $sale->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div class="text-end">
                <div class="block-available inline-block px-3 py-1 rounded-full text-sm">
                    {{ strtoupper($sale->payment_method) }}
                </div>
            </div>
        </div>

        {{-- === TABLE PRODUK === --}}
        <div class="wg-table table-all-category mb-4">
            <ul class="table-title flex gap20 mb-14">
                <li><div class="body-title">Produk</div></li>
                <li><div class="body-title">Qty</div></li>
                <li><div class="body-title text-end">Harga</div></li>
                <li><div class="body-title text-end">Subtotal</div></li>
            </ul>

            <ul class="flex flex-column">
                @foreach ($sale->items as $item)
                    <li class="product-item gap14">
                        <div class="flex items-center justify-between gap20 flex-grow">
                            {{-- Nama Produk --}}
                            <div class="body-title-2">{{ $item->storeProduct->product->name ?? '-' }}</div>

                            {{-- Qty --}}
                            <div class="body-text">{{ $item->quantity }}</div>

                            {{-- Harga --}}
                            <div class="body-text text-end">
                                Rp {{ number_format($item->price, 0, ',', '.') }}
                            </div>

                            {{-- Subtotal --}}
                            <div class="body-text text-end tf-color fw-bold">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        {{-- === TOTAL DAN DETAIL LAIN === --}}
        <div class="divider mb-4"></div>
        <div class="flex justify-between flex-wrap gap20">
            <div class="text-sm text-muted">
                <p class="mb-1">Kasir: <strong>{{ $sale->user->name ?? '-' }}</strong></p>
                <p class="mb-0">Metode Pembayaran: <strong>{{ ucfirst($sale->payment_method) }}</strong></p>
            </div>

            <div class="text-end">
                <h4 class="fw-bold text-primary mb-1">
                    Total: Rp {{ number_format($sale->total, 0, ',', '.') }}
                </h4>
            </div>
        </div>

        {{-- === TOMBOL PRINT === --}}
        <div class="text-center mt-6">
            <button class="tf-button style-1 px-5" onclick="window.print()">
                <i class="icon-printer"></i> Cetak Nota
            </button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* === UTILITY === */
.text-sm { font-size: 13px; }
.text-muted { color: #6b7280; }
.text-primary { color: #2563eb; }
.text-end { text-align: right; }

.wg-table .body-text { font-size: 13px; }
.wg-table .body-title { font-size: 13px; font-weight: 600; }

.tf-button.style-1 {
    background-color: #2563eb;
    color: #fff;
    border-radius: 8px;
    padding: 8px 14px;
    font-weight: 500;
    transition: 0.2s;
}
.tf-button.style-1:hover {
    background-color: #1d4ed8;
    transform: translateY(-1px);
}

/* === STATUS BADGE === */
.block-available {
    background-color: #dcfce7;
    color: #15803d;
    font-weight: 500;
}
@media print {
    .tf-button, .breadcrumbs, .divider { display: none !important; }
    .wg-box { box-shadow: none; border: none; }
}
</style>
@endpush
