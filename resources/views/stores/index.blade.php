@extends('layouts.app')

@section('title', 'Daftar Toko')

@section('content')
<div class="main-content-wrap">

    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
        <h3>Daftar Toko Saya</h3>
        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
            <li>
                <a href="{{ route('dashboard') }}">
                    <div class="text-tiny">Dashboard</div>
                </a>
            </li>
            <li><i class="icon-chevron-right"></i></li>
            <li><div class="text-tiny">Toko</div></li>
        </ul>
    </div>

    <div class="wg-box">
        <div class="title-box">
            <i class="icon-store"></i>
            <div class="body-text">
                Daftar seluruh toko yang kamu miliki. Pilih salah satu toko untuk mulai mengelola produk dan penjualan.
            </div>
        </div>

        <div class="flex justify-end mb-4">
            <a href="{{ route('stores.create') }}" class="tf-button style-1 w208">
                <i class="icon-plus"></i> Tambah Toko
            </a>
        </div>

        @if($stores->isEmpty())
            <div class="text-center py-5 text-muted">
                <i class="icon-alert-circle"></i>
                <p class="mt-2">Kamu belum memiliki toko. Buat toko baru untuk mulai berjualan.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($stores as $store)
                    <div class="store-card p-4 rounded-lg border shadow-sm bg-white hover:shadow-md transition">
                        <div class="flex justify-between items-center mb-3">
                            <h4 class="text-lg font-semibold text-gray-800">
                                <i class="icon-home mr-1"></i> {{ $store->name }}
                            </h4>

                            @if(session('store_id') == $store->id)
                                <span class="px-3 py-1 text-xs font-semibold rounded bg-green-100 text-green-700">
                                    Aktif
                                </span>
                            @endif
                        </div>

                        <p class="text-sm text-gray-600 mb-1">
                            <i class="icon-map-pin mr-1"></i>
                            {{ $store->address ?? 'Alamat belum diisi' }}
                        </p>

                        <p class="text-sm text-gray-600 mb-3">
                            <i class="icon-phone mr-1"></i>
                            {{ $store->phone ?? '-' }}
                        </p>

                        <div class="flex justify-between items-center mt-3">
                            <form action="{{ route('stores.select', $store->id) }}" method="POST">
                                @csrf
                                <button type="submit" 
                                        class="tf-button style-1 text-sm py-2 px-4">
                                    <i class="icon-check"></i> Pilih Toko
                                </button>
                            </form>

                            <a href="{{ route('stores.edit', $store->id ?? '#') }}" 
                               class="text-blue-500 hover:text-blue-700 text-sm">
                                <i class="icon-edit-3"></i> Edit
                            </a>

                            <form action="{{ route('stores.destroy', $store->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus toko ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="tf-button style-2 text-red-600">
                                    <i class="icon-trash-2"></i> Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.store-card {
    position: relative;
    border: 1px solid #e5e7eb;
    transition: all 0.2s ease-in-out;
}
.store-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
</style>
@endpush
