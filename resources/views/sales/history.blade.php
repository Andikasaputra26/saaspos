@extends('layouts.app')

@section('title', 'Riwayat Transaksi Kasir')

@section('content')
<div class="main-content-wrap">

    {{-- === HEADER === --}}
    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
        <h3>Riwayat Transaksi Kasir</h3>
        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
            <li><a href="{{ route('dashboard') }}"><div class="text-tiny">Dashboard</div></a></li>
            <li><i class="icon-chevron-right"></i></li>
            <li><div class="text-tiny">Kasir</div></li>
            <li><i class="icon-chevron-right"></i></li>
            <li><div class="text-tiny">Riwayat Transaksi</div></li>
        </ul>
    </div>

    <div class="wg-box">
        {{-- === FILTER BAR === --}}
        <div class="flex flex-wrap justify-between gap20 mb-4">

            {{-- === FORM SEARCH === --}}
            <div class="col-xl-4 mb-20">
                <div>
                    <h5 class="mb-16">Form Search</h5>
                    <form class="form-search" id="ajaxSearchForm" onsubmit="return false;">
                        <fieldset class="name">
                            <input type="text"
                                   id="searchInput"
                                   placeholder="Cari invoice, kasir, atau metode..."
                                   name="search"
                                   autocomplete="off">
                        </fieldset>
                        <div class="button-submit">
                            <button type="button" id="btnSearch"><i class="icon-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- === FORM DATE FILTER === --}}
            <div class="col-xl-4 mb-20">
                <div>
                    <h5 class="mb-16">Form Select Date</h5>
                    <form id="dateFilterForm" onsubmit="return false;">
                        <div class="select">
                            <input type="date"
                                   id="dateFilter"
                                   name="date"
                                   value="{{ now()->format('Y-m-d') }}">
                        </div>
                    </form>
                </div>
            </div>

            {{-- === TOTAL TRANSAKSI === --}}
            <div class="col-xl-4 mb-20 flex items-end justify-end">
                <div id="totalBox" class="tf-button style-1 w208">
                    <i class="icon-wallet"></i>
                    Total: Rp {{ number_format($totalHariIni, 0, ',', '.') }}
                </div>
            </div>
        </div>

        {{-- === TABLE DATA === --}}
        <div id="salesTable">
            @include('sales.partials._sales_table', ['sales' => $sales])
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.form-search { display: flex; align-items: center; gap: 8px; }
.form-search input {
    width: 100%; border: 1px solid #d1d5db; border-radius: 6px;
    padding: 6px 10px; font-size: 13px; transition: all 0.2s ease;
}
.form-search input:focus { border-color: #2563eb; box-shadow: 0 0 0 2px rgba(37,99,235,0.15); }
.form-search .button-submit button {
    background: #2563eb; color: #fff; border: none;
    padding: 7px 10px; border-radius: 6px; transition: 0.2s ease;
}
.form-search .button-submit button:hover { background: #1d4ed8; }

.select input[type="date"] {
    width: 100%; border: 1px solid #d1d5db; border-radius: 6px;
    padding: 6px 10px; font-size: 13px; transition: 0.2s ease;
}
.select input[type="date"]:focus { border-color: #2563eb; box-shadow: 0 0 0 2px rgba(37,99,235,0.15); }

.tf-button.style-1 {
    background-color: #2563eb; color: #fff;
    border-radius: 8px; padding: 8px 14px; font-weight: 500;
}
.tf-button.style-1:hover { background-color: #1d4ed8; }
.icon-wallet { margin-right: 6px; }

.list-icon-function .item.trash {
    color: #dc2626;
    cursor: pointer;
    transition: 0.2s ease;
}
.list-icon-function .item.trash:hover {
    color: #fff;
    background: #dc2626;
    border-radius: 6px;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {

    // === LOAD DATA FUNCTION ===
    function loadSales(date = '', search = '') {
        $.ajax({
            url: "{{ route('sales.history') }}",
            method: "GET",
            data: { date, search },
            success: function(response) {
                let html = '';

                if (response.sales.length === 0) {
                    html = `
                        <div class="text-center py-5 text-muted">
                            <i class="icon-alert-circle fs-1 mb-2"></i>
                            <div class="body-text">Tidak ada transaksi pada tanggal ini.</div>
                        </div>`;
                } else {
                    html += `
                    <div class="wg-table table-all-category">
                        <ul class="table-title flex gap20 mb-14">
                            <li><div class="body-title">#</div></li>
                            <li><div class="body-title">Invoice</div></li>
                            <li><div class="body-title">Kasir</div></li>
                            <li><div class="body-title">Tanggal & Waktu</div></li>
                            <li><div class="body-title">Metode</div></li>
                            <li><div class="body-title">Total</div></li>
                            <li><div class="body-title">Aksi</div></li>
                        </ul>
                        <ul class="flex flex-column">`;

                    response.sales.forEach(item => {
                        html += `
                        <li class="product-item gap14">
                            <div class="flex items-center justify-between gap20 flex-grow">
                                <div class="body-text">${item.no}</div>
                                <div class="body-title-2">#${item.invoice_number}</div>
                                <div class="body-text">${item.kasir}</div>
                                <div class="body-text">${item.tanggal}</div>
                                <div class="body-text">${item.metode}</div>
                                <div class="body-text tf-color fw-bold">${item.total}</div>
                                <div class="list-icon-function">
                                    <a href="${item.link}" class="item eye" title="Lihat Nota">
                                        <i class="icon-eye"></i>
                                    </a>
                                    <div class="item trash" data-id="${item.id}" title="Hapus Transaksi">
                                        <i class="icon-trash-2"></i>
                                    </div>
                                </div>
                            </div>
                        </li>`;
                    });

                    html += `</ul></div>`;
                }

                $('#salesTable').html(html);
                $('#totalBox').html(`<i class="icon-wallet"></i> Total: ${response.totalHariIni}`);
            },
            error: function() {
                alert('Gagal memuat data.');
            }
        });
    }

    // === SEARCH BUTTON ===
    $('#btnSearch').on('click', function() {
        loadSales($('#dateFilter').val(), $('#searchInput').val());
    });

    // === ENTER TO SEARCH ===
    $('#searchInput').on('keypress', function(e) {
        if (e.which == 13) {
            e.preventDefault();
            loadSales($('#dateFilter').val(), $(this).val());
        }
    });

    // === DATE CHANGE ===
    $('#dateFilter').on('change', function() {
        loadSales($(this).val(), $('#searchInput').val());
    });

    // === DELETE TRANSACTION ===
    $(document).on('click', '.item.trash', function() {
        const id = $(this).data('id');
        if (confirm('Yakin ingin menghapus transaksi ini? Data tidak bisa dikembalikan.')) {
            $.ajax({
                url: `/sales/${id}`,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(res) {
                    alert(res.message);
                    loadSales($('#dateFilter').val(), $('#searchInput').val());
                },
                error: function() {
                    alert('Gagal menghapus transaksi.');
                }
            });
        }
    });
});
</script>
@endpush
