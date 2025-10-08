@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
<div class="main-content-wrap">

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

    <div class="wg-box">
        <div class="title-box">
            <i class="icon-coffee"></i>
            <div class="body-text">
                Tip: Cari berdasarkan nama atau SKU produk. Setiap produk memiliki ID unik untuk memudahkan pencarian.
            </div>
        </div>

        <div class="flex items-center justify-between gap20 flex-wrap mb-4">
            <div class="wg-filter flex items-center gap15 flex-wrap flex-grow">
                <div class="flex items-center gap10 flex-wrap">

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
                <form class="form-search ms-auto" id="ajaxSearchForm" onsubmit="return false;">
                    <fieldset class="name">
                        <input type="text"
                            id="searchInput"
                            name="search"
                            placeholder="Search here..."
                            value="{{ request('search') }}"
                            autocomplete="off"
                            required>
                    </fieldset>
                    <div class="button-submit">
                        <button type="submit"><i class="icon-search"></i></button>
                    </div>
                </form>
            </div>

            <a href="{{ route('products.create') }}" class="tf-button style-1 w208 shrink-0">
                <i class="icon-plus"></i> Tambahkan Data
            </a>
        </div>

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

                            <div>
                                @if($item->stock > 10)
                                    <div class="block-available">Available</div>
                                @elseif($item->stock > 0)
                                    <div class="block-limited">Limited ({{ $item->stock }})</div>
                                @else
                                    <div class="block-not-available">Out of stock</div>
                                @endif
                            </div>

                            <div>
                                @if($item->is_active)
                                    <div class="block-available">Active</div>
                                @else
                                    <div class="block-not-available">Inactive</div>
                                @endif
                            </div>

                            <div class="list-icon-function">
                                <a href="{{ route('products.show', $item->id) }}" class="item eye" title="View">
                                    <i class="icon-eye"></i>
                                </a>
                                <a href="{{ route('products.edit', $item->id) }}" class="item edit" title="Edit">
                                    <i class="icon-edit-3"></i>
                                </a>
                                <form action="{{ route('products.destroy', $item->id) }}" method="POST" class="delete-form d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="item trash" title="Delete" style="border:none;background:none;">
                                        <i class="icon-trash-2"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="text-center py-4 text-muted">No products found.</li>
                @endforelse
            </ul>
        </div>

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

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {
    const $searchForm = $('#ajaxSearchForm');
    const $searchInput = $('#searchInput');
    const $productList = $('#productList');
    const $statusFilter = $('#statusFilter');
    const $perPageSelect = $('#perPageSelect');
    let typingTimer;
    const delay = 400; 

    function loadProducts() {
        $.ajax({
            url: "{{ route('products.index') }}",
            type: 'GET',
            data: {
                search: $searchInput.val(),
                status: $statusFilter.val(),
                per_page: $perPageSelect.val(),
                ajax: true
            },
            beforeSend: function() {
                $productList.html(`
                    <li class="text-center py-4 text-muted">
                        <i class="fa fa-spinner fa-spin"></i> Loading products...
                    </li>
                `);
            },
            success: function(response) {
                $productList.html(response.html);
            },
            error: function() {
                $productList.html('<li class="text-center py-4 text-danger">⚠️ Error loading data</li>');
            }
        });
    }

    $searchInput.on('keyup', function() {
        clearTimeout(typingTimer);
        typingTimer = setTimeout(loadProducts, delay);
    });

    $searchForm.on('submit', function(e) {
        e.preventDefault();
        loadProducts();
    });

    $statusFilter.on('change', loadProducts);
    $perPageSelect.on('change', loadProducts);

    $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: 'Produk ini akan dihapus permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
@endpush

@push('styles')
<style>
.filter-group {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 6px 10px;
    display: flex;
    align-items: center;
    transition: all 0.2s ease;
}
.filter-group:hover {
    background: #f3f4f6;
}
.filter-group .label {
    font-size: 13px;
    color: #6b7280;
    font-weight: 500;
}
.select-wrapper {
    position: relative;
}
.filter-select {
    border: 1px solid #d1d5db;
    border-radius: 6px;
    padding: 5px 8px;
    background: #fff;
    color: #111827;
    font-size: 13px;
    font-weight: 500;
    outline: none;
    cursor: pointer;
    transition: all 0.2s;
}
.filter-select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37,99,235,0.2);
}

@media (max-width: 768px) {
    .filter-group {
        width: 100%;
        justify-content: space-between;
    }
}
</style>
@endpush

