@extends('layouts.app')

@section('title', 'Daftar Pembelian')

@section('content')
<div class="main-content-wrap">
    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
        <h3>Daftar Pembelian</h3>
        <ul class="breadcrumbs flex items-center gap10">
            <li><a href="{{ route('dashboard') }}"><div class="text-tiny">Dashboard</div></a></li>
            <li><i class="icon-chevron-right"></i></li>
            <li><div class="text-tiny">Pembelian</div></li>
        </ul>
    </div>

    <div class="wg-box">

        {{-- === FILTER BAR === --}}
        <div class="flex flex-wrap justify-between gap20 mb-4">
            <form method="GET" class="flex items-center gap10">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari invoice atau metode..."
                       class="border border-gray-300 rounded-md p-2 text-sm">
                <select name="supplier_id" class="border border-gray-300 rounded-md p-2 text-sm">
                    <option value="all">Semua Supplier</option>
                    @foreach($suppliers as $s)
                        <option value="{{ $s->id }}" {{ request('supplier_id') == $s->id ? 'selected' : '' }}>
                            {{ $s->name }}
                        </option>
                    @endforeach
                </select>
                <input type="date" name="date" value="{{ request('date') }}" class="border border-gray-300 rounded-md p-2 text-sm">
                <button type="submit" class="tf-button style-1"><i class="icon-search"></i> Filter</button>
            </form>
        </div>

        {{-- === TABEL DATA === --}}
        @if($purchases->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="icon-alert-circle fs-1 mb-2"></i>
                <div class="body-text">Belum ada data pembelian.</div>
            </div>
        @else
            <div class="wg-table table-all-category">
                <ul class="table-title flex gap20 mb-14">
                    <li><div class="body-title">#</div></li>
                    <li><div class="body-title">Invoice</div></li>
                    <li><div class="body-title">Supplier</div></li>
                    <li><div class="body-title">Tanggal</div></li>
                    <li><div class="body-title">Metode</div></li>
                    <li><div class="body-title">Total</div></li>
                    <li><div class="body-title">Aksi</div></li>
                </ul>

                <ul class="flex flex-column">
                    @foreach($purchases as $i => $p)
                        <li class="product-item gap14">
                            <div class="flex items-center justify-between gap20 flex-grow">
                                <div class="body-text">{{ $i + 1 }}</div>
                                <div class="body-title-2">#{{ $p->invoice_number }}</div>
                                <div class="body-text">{{ $p->supplier->name ?? '-' }}</div>
                                <div class="body-text">{{ $p->created_at->format('d M Y, H:i') }}</div>
                                <div class="body-text text-capitalize">{{ $p->payment_method }}</div>
                                <div class="body-text tf-color fw-bold">
                                    Rp {{ number_format($p->total, 0, ',', '.') }}
                                </div>
                                {{-- <div class="list-icon-function">
                                    <a href="{{ route('purchases.show', $p->id) }}" class="item eye" title="Lihat Detail">
                                        <i class="icon-eye"></i>
                                    </a>
                                </div> --}}
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="divider mt-3"></div>
            <div class="flex items-center justify-between flex-wrap gap10">
                <div class="text-tiny">Menampilkan {{ $purchases->count() }} data pembelian</div>
                {{ $purchases->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection
