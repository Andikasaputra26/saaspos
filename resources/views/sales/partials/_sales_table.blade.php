@if($sales->isEmpty())
    <div class="text-center py-5 text-muted">
        <i class="icon-alert-circle fs-1 mb-2"></i>
        <div class="body-text">Tidak ada transaksi pada tanggal ini.</div>
    </div>
@else
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
        <ul class="flex flex-column">
            @foreach($sales as $i => $s)
                <li class="product-item gap14">
                    <div class="image no-bg">
                        <img src="{{ asset('assets/img/receipt-icon.png') }}" alt="Nota"
                             style="width:45px;height:45px;object-fit:contain;">
                    </div>
                    <div class="flex items-center justify-between gap20 flex-grow">
                        <div class="body-text">{{ $i + 1 }}</div>
                        <div class="body-title-2">#{{ $s->invoice_number }}</div>
                        <div class="body-text">{{ $s->user->name }}</div>
                        <div class="body-text">{{ $s->created_at->format('d M Y, H:i') }}</div>
                        <div class="body-text text-capitalize">{{ $s->payment_method }}</div>
                        <div class="body-text tf-color fw-bold">Rp {{ number_format($s->total, 0, ',', '.') }}</div>
                        <div class="list-icon-function">
                            <a href="{{ route('sales.invoice', $s->id) }}" class="item eye" title="Lihat Nota">
                                <i class="icon-eye"></i>
                            </a>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endif
