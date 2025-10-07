@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
<div class="main-content">
    <div class="main-content-inner">
        <div class="main-content-wrap">

            {{-- === HEADER & BREADCRUMB === --}}
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Daftar Produk</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('dashboard') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><div class="text-tiny">Produk</div></li>
                </ul>
            </div>

            {{-- === PRODUCT LIST === --}}
            <div class="wg-box">
                {{-- Header Box --}}
                <div class="title-box">
                    <i class="icon-shopping-cart"></i>
                    <div class="body-text">
                        Kelola seluruh produk toko 
                        <strong>{{ optional(\App\Models\Store::find(session('store_id')))->name ?? 'Toko Anda' }}</strong>.
                    </div>
                </div>

                {{-- Filter dan Tombol Tambah --}}
                <div class="flex items-center justify-between gap10 flex-wrap mb-2">
                    {{-- Search --}}
                    <div class="wg-filter flex-grow">
                        <form action="{{ route('products.index') }}" method="GET" class="form-search">
                            <fieldset class="name">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Cari produk di sini..." 
                                       class="form-control"
                                       aria-required="true">
                            </fieldset>
                            <div class="button-submit">
                                <button type="submit">
                                    <i class="icon-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- Tambah Produk --}}
                    <a href="{{ route('products.create') }}" class="tf-button style-1 w208">
                        <i class="icon-plus"></i> Tambah Produk
                    </a>
                </div>

                {{-- Notifikasi --}}
                @if(session('success'))
                    <div class="alert alert-success mb-3">{{ session('success') }}</div>
                @elseif(session('error'))
                    <div class="alert alert-danger mb-3">{{ session('error') }}</div>
                @endif

                {{-- === TABEL PRODUK === --}}
                <div class="wg-table table-product-list">
                    {{-- Header Tabel --}}
                    <ul class="table-title flex gap20 mb-14">
                        <li><div class="body-title">Produk</div></li>
                        <li><div class="body-title">SKU</div></li>
                        <li><div class="body-title">Kategori</div></li>
                        <li><div class="body-title">Harga</div></li>
                        <li><div class="body-title">Stok</div></li>
                        <li><div class="body-title">Status</div></li>
                        <li><div class="body-title">Aksi</div></li>
                    </ul>

                    {{-- Body Tabel --}}
                    <ul class="flex flex-column">
                        @forelse($storeProducts as $item)
                            @php $product = $item->product; @endphp
                            <li class="product-item gap14">
                                {{-- Gambar Produk --}}
                                <div class="image no-bg">
                                    <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('assets/img/no-image.png') }}" 
                                         alt="{{ $product->name }}">
                                </div>

                                {{-- Isi Baris --}}
                                <div class="flex items-center justify-between gap20 flex-grow">
                                    {{-- Nama --}}
                                    <div class="name">
                                        <a href="#" class="body-title-2 text-truncate">{{ $product->name }}</a>
                                        <div class="text-tiny text-muted">
                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    {{-- SKU --}}
                                    <div class="body-text">#{{ $product->sku ?? '-' }}</div>

                                    {{-- Kategori --}}
                                    <div class="body-text">{{ $product->category ?? '-' }}</div>

                                    {{-- Harga --}}
                                    <div class="body-text">Rp {{ number_format($item->price, 0, ',', '.') }}</div>

                                    {{-- Stok --}}
                                    <div>
                                        @if($item->stock > 10)
                                            <div class="block-available">Tersedia ({{ $item->stock }})</div>
                                        @elseif($item->stock > 0)
                                            <div class="block-limited">Terbatas ({{ $item->stock }})</div>
                                        @else
                                            <div class="block-not-available">Habis</div>
                                        @endif
                                    </div>

                                    {{-- Status --}}
                                    <div>
                                        @if($item->is_active)
                                            <div class="block-available">Aktif</div>
                                        @else
                                            <div class="block-not-available">Nonaktif</div>
                                        @endif
                                    </div>

                                    {{-- Aksi --}}
                                    <div class="list-icon-function">
                                        <a href="{{ route('products.edit', $item->id) }}" 
                                           class="item edit" title="Edit">
                                            <i class="icon-edit-3"></i>
                                        </a>

                                        <form action="{{ route('products.destroy', $item->id) }}" 
                                              method="POST" 
                                              class="delete-form d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                    class="item trash" 
                                                    title="Hapus" 
                                                    style="border:none;background:none;">
                                                <i class="icon-trash-2"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        @empty
                            <li class="text-center py-4 text-muted">
                                Belum ada produk.
                            </li>
                        @endforelse
                    </ul>
                </div>

                {{-- PAGINATION --}}
                <div class="divider mt-3"></div>
                <div class="flex items-center justify-between flex-wrap gap10">
                    <div class="text-tiny">
                        Menampilkan {{ $storeProducts->firstItem() ?? 0 }}–{{ $storeProducts->lastItem() ?? 0 }} 
                        dari {{ $storeProducts->total() ?? 0 }} entri
                    </div>
                    {{ $storeProducts->links('pagination::bootstrap-5') }}
                </div>
            </div>
            {{-- /wg-box --}}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: 'Data produk akan dihapus dari toko ini.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) this.submit();
        });
    });
});
</script>
@endpush

@push('styles')
<style>
.product-item img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 10px;
}
.block-available { color: #16a34a; font-weight: 600; }
.block-limited { color: #d97706; font-weight: 600; }
.block-not-available { color: #dc2626; font-weight: 600; }

.list-icon-function .item {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 34px;
    height: 34px;
    border-radius: 8px;
    background: #f3f4f6;
    transition: all 0.2s;
}
.list-icon-function .item:hover {
    background: #2563eb;
    color: #fff;
}
</style>
@endpush
