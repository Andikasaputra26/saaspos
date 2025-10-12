@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
<div class="main-content-wrap">

    {{-- === HEADER === --}}
    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
        <h3>Daftar Produk</h3>
        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
            <li>
                <a href="{{ route('dashboard') }}">
                    <div class="text-tiny">Dashboard</div>
                </a>
            </li>
            <li><i class="icon-chevron-right"></i></li>
            <li><a href="#"><div class="text-tiny">Ecommerce</div></a></li>
            <li><i class="icon-chevron-right"></i></li>
            <li><div class="text-tiny">Product List</div></li>
        </ul>
    </div>

    {{-- === MAIN TABLE WRAPPER === --}}
    <div class="wg-box">

        {{-- === HEADER TIP === --}}
        <div class="title-box">
            <i class="icon-coffee"></i>
            <div class="body-text">
                Tip: Cari berdasarkan nama atau SKU produk. Setiap produk memiliki ID unik untuk memudahkan pencarian.
            </div>
        </div>

        {{-- === FILTER & SEARCH === --}}
        <div class="flex items-center justify-between gap20 flex-wrap mb-4">
            <div class="wg-filter flex items-center gap15 flex-wrap flex-grow">
                <div class="flex items-center gap10 flex-wrap">

                    {{-- Per Page Selector --}}
                    <div class="filter-group flex items-center gap6">
                        <span class="label">Show</span>
                        <div class="select-wrapper">
                            <select id="perPageSelect" class="filter-select">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            </select>
                        </div>
                        <span class="label">entries</span>
                    </div>

                    {{-- Status Filter --}}
                    <div class="filter-group flex items-center gap6">
                        <span class="label">Status</span>
                        <div class="select-wrapper">
                            <select id="statusFilter" class="filter-select">
                                <option value="">All</option>
                                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Active</option>
                                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Search Form --}}
                <form class="form-search ms-auto" id="ajaxSearchForm" onsubmit="return false;">
                    <fieldset class="name">
                        <input type="text" id="searchInput" name="search"
                            placeholder="Search here..." value="{{ request('search') }}"
                            autocomplete="off" required>
                    </fieldset>
                    <div class="button-submit">
                        <button type="submit"><i class="icon-search"></i></button>
                    </div>
                </form>
            </div>

            {{-- Tambah Data Button --}}
            <a href="{{ route('products.create') }}" class="tf-button style-1 w208 shrink-0">
                <i class="icon-plus"></i> Tambahkan Data
            </a>
        </div>

        {{-- === TABLE LIST === --}}
        <div class="wg-table table-product-list">
            <ul class="table-title flex gap20 mb-14">
                <li><div class="body-title">Product</div></li>
                <li><div class="body-title">Product ID</div></li>
                <li><div class="body-title">Price</div></li>
                <li><div class="body-title">Quantity</div></li>
                <li><div class="body-title">Category</div></li>
                <li><div class="body-title">Stock</div></li>
                <li><div class="body-title">Status</div></li>
                <li><div class="body-title">Action</div></li>
            </ul>

            <ul class="flex flex-column" id="productList">
                @forelse($storeProducts as $item)
                    @php $product = $item->product; @endphp
                    <li class="product-item gap14">
                        <div class="image no-bg">
                            <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('assets/img/no-image.png') }}"
                                 alt="{{ $product->name }}">
                        </div>

                        <div class="flex items-center justify-between gap20 flex-grow">
                            <div class="name">
                                <a href="#" class="body-title-2">{{ $product->name }}</a>
                            </div>

                            <div class="body-text">#{{ $product->sku ?? $item->id }}</div>
                            <div class="body-text">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                            <div class="body-text">{{ $item->stock }}</div>
                            <div class="body-text">{{ $product->category ?? '-' }}</div>

                            {{-- Stock Info --}}
                            <div>
                                @if($item->stock > 10)
                                    <div class="block-available">Available</div>
                                @elseif($item->stock > 0)
                                    <div class="block-limited">Limited ({{ $item->stock }})</div>
                                @else
                                    <div class="block-not-available">Out of stock</div>
                                @endif
                            </div>

                            {{-- Status Info --}}
                            <div>
                                @if($item->is_active)
                                    <div class="block-available">Active</div>
                                @else
                                    <div class="block-not-available">Inactive</div>
                                @endif
                            </div>

                            {{-- Action Buttons --}}
                            <div class="list-icon-function">
                                <a href="{{ route('products.edit', $item->id) }}" class="item edit" title="Edit">
                                    <i class="icon-edit-3"></i>
                                </a>

                                @if(!$item->is_active)
                                    {{-- Tombol Aktifkan --}}
                                    <button type="button" class="item play btn-activate"
                                            data-id="{{ $item->id }}" title="Aktifkan kembali produk">
                                        <i class="icon-play-circle"></i>
                                    </button>
                                @elseif($item->saleItems->isNotEmpty())
                                    {{-- Tombol Nonaktifkan --}}
                                    <button type="button" class="item pause btn-nonaktifkan"
                                            data-id="{{ $item->id }}"
                                            title="Produk pernah dijual, hanya bisa dinonaktifkan">
                                        <i class="icon-pause-circle"></i>
                                    </button>
                                @else
                                    {{-- Tombol Hapus --}}
                                    <button type="button" class="item trash btn-delete"
                                            data-id="{{ $item->id }}" title="Hapus Permanen">
                                        <i class="icon-trash-2"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="text-center py-4 text-muted">No products found.</li>
                @endforelse
            </ul>
        </div>

        {{-- === FOOTER === --}}
        <div class="divider mt-3"></div>
        <div class="flex items-center justify-between flex-wrap gap10">
            <div class="text-tiny">
                Showing {{ $storeProducts->firstItem() ?? 0 }} - {{ $storeProducts->lastItem() ?? 0 }}
                of {{ $storeProducts->total() ?? 0 }} entries
            </div>
            {{ $storeProducts->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.block-available {
    background: #dcfce7;
    color: #166534;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 12px;
}
.block-not-available {
    background: #fee2e2;
    color: #991b1b;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 12px;
}
.block-limited {
    background: #fef9c3;
    color: #92400e;
    font-weight: 600;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 12px;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function () {

    $(document).on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const $row = $(this).closest('li');

        Swal.fire({
            title: 'Yakin ingin menghapus produk ini?',
            text: 'Produk ini akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/products/${id}`,
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}', _method: 'DELETE' },
                    beforeSend: () => Swal.fire({ title: 'Menghapus...', allowOutsideClick: false, didOpen: () => Swal.showLoading() }),
                    success: (res) => {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message ?? 'Produk berhasil dihapus!', timer: 1500, showConfirmButton: false });
                        $row.fadeOut(300, () => $(this).remove());
                    },
                    error: (xhr) => {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message ?? 'Terjadi kesalahan saat menghapus.' });
                    }
                });
            }
        });
    });

    $(document).on('click', '.btn-nonaktifkan', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Nonaktifkan produk ini?',
            text: 'Produk akan disembunyikan dari kasir.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Ya, nonaktifkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#2563eb'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/products/${id}/deactivate`,
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    beforeSend: () => Swal.fire({ title: 'Menonaktifkan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() }),
                    success: (res) => {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message ?? 'Produk dinonaktifkan.', timer: 1500, showConfirmButton: false });
                        setTimeout(() => location.reload(), 1000);
                    },
                    error: () => {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: 'Tidak bisa menonaktifkan produk ini.' });
                    }
                });
            }
        });
    });

    $(document).on('click', '.btn-activate', function () {
        const id = $(this).data('id');
        Swal.fire({
            title: 'Aktifkan kembali produk ini?',
            text: 'Produk akan muncul lagi di kasir.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, aktifkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#16a34a'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/products/${id}/activate`,
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    beforeSend: () => Swal.fire({ title: 'Mengaktifkan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() }),
                    success: (res) => {
                        Swal.fire({ icon: 'success', title: 'Berhasil', text: res.message ?? 'Produk berhasil diaktifkan!', timer: 1500, showConfirmButton: false });
                        setTimeout(() => location.reload(), 1000);
                    },
                    error: (xhr) => {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: xhr.responseJSON?.message ?? 'Terjadi kesalahan saat mengaktifkan produk.' });
                    }
                });
            }
        });
    });
});
</script>
@endpush
