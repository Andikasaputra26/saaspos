@extends('layouts.app')
@section('title', 'Kasir POS')

@section('content')
<div class="main-content-wrap">

    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
        <h3>Kasir POS</h3>
        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
            <li><a href="{{ route('dashboard') }}"><div class="text-tiny">Dashboard</div></a></li>
            <li><i class="icon-chevron-right"></i></li>
            <li><div class="text-tiny">Kasir</div></li>
        </ul>
    </div>

    <div class="all-gallery-wrap">
        <div class="wg-box left flex-grow">
            <div class="flex items-center justify-between gap10 flex-wrap mb-4">
                <div class="flex items-center flex-wrap gap10">
                    <div class="tf-button-funtion" onclick="clearCart()">
                        <i class="icon-trash-2"></i>
                        <div class="body-title">Kosongkan</div>
                    </div>
                    <div class="tf-button-funtion">
                        <i class="icon-filter"></i>
                        <div class="body-title">Filter</div>
                    </div>
                    <div class="tf-button-funtion" id="toggleView">
                        <i class="icon-eye"></i>
                        <div class="body-title">Tampilan</div>
                    </div>
                </div>

                <form class="form-search w286" onsubmit="return false;">
                    <fieldset class="name">
                        <input type="text" id="searchProduct" placeholder="Cari produk..." tabindex="2">
                    </fieldset>
                    <div class="button-submit">
                        <button type="submit"><i class="icon-search"></i></button>
                    </div>
                </form>
            </div>

            <div class="wrap-title flex items-center justify-between gap20 flex-wrap mb-3">
                <div class="body-title">Daftar Produk</div>
                <div class="flex items-center gap20">
                    <div class="select style-default">
                        <select id="sortOption">
                            <option value="name">Urutkan: Nama</option>
                            <option value="price">Urutkan: Harga</option>
                        </select>
                    </div>
                    <div class="grid-list-style">
                        <div class="button-grid-style active"><i class="icon-grid"></i></div>
                        <div class="button-list-style"><i class="icon-list"></i></div>
                    </div>
                </div>
            </div>

            <div class="wrap-gallery-item" id="productGrid">
                @forelse($products as $p)
                    <a href="javascript:void(0)"
                       class="gallery-item {{ !$p->is_active || $p->stock <= 0 ? 'opacity-50 pointer-events-none' : '' }}"
                       onclick="addToCart({{ $p->id }}, '{{ e($p->product->name) }}', {{ $p->price }}, {{ $p->stock }})">
                        <div class="image">
                            <img src="{{ $p->product->image ? asset('storage/'.$p->product->image) : asset('assets/img/no-image.png') }}"
                                 alt="{{ e($p->product->name) }}">
                        </div>
                        <div class="text-tiny fw-semibold mt-1">{{ e($p->product->name) }}</div>
                        <div class="text-tiny text-gray-500">Rp {{ number_format($p->price,0,',','.') }}</div>
                    </a>
                @empty
                    <div class="text-center py-10 text-muted">Belum ada produk tersedia.</div>
                @endforelse
            </div>
        </div>

        <!-- ===== RIGHT PANEL - CART ===== -->
        <div class="wg-box right cart-panel">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap10">
                    <i class="icon-shopping-cart text-primary fs-5"></i>
                    <h5 class="fw-semibold mb-0">Keranjang</h5>
                </div>
                <a href="{{ route('sales.history') }}" class="tf-button style-1 px-3 py-2">
                    <i class="icon-clock me-1"></i> Riwayat
                </a>
            </div>

            <div id="cartList" class="cart-list custom-scroll mb-4">
                <div class="empty-cart text-center text-gray-500 py-4">
                    <i class="icon-shopping-bag fs-3 d-block mb-2"></i>
                    Keranjang kosong
                </div>
            </div>

            <div class="divider my-3"></div>

            <div class="cart-footer">
                <div class="flex items-center justify-between mb-3">
                    <div class="body-title text-gray-600">Total Pembayaran</div>
                    <h4 id="cartTotal" class="fw-bold text-primary">Rp 0</h4>
                </div>

                <button id="checkoutBtn" class="tf-button style-1 w-full py-3 fw-semibold flex items-center justify-center gap2">
                    <i class="icon-credit-card"></i>
                    <span>Proses Pembayaran</span>
                </button>
            </div>
        </div>
    </div>
</div>

@include('sales.partials.payment-modal') 
@endsection

@push('scripts')
@include('sales.partials.pos-script') 
@endpush

@push('styles')
<style>
.gallery-item {
  display: inline-block;
  width: 190px;
  margin: 8px;
  text-align: center;
  transition: all 0.25s ease;
  cursor: pointer;
}
.gallery-item img {
  width: 100%;
  height: 130px;
  object-fit: cover;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}
.gallery-item:hover {
  transform: scale(1.04);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.cart-panel {
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
  padding: 24px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  min-height: 600px;
}
.cart-list {
  max-height: 55vh;
  overflow-y: auto;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 12px;
}
.cart-item {
  background: white;
  border-radius: 12px;
  padding: 16px;
  margin-bottom: 12px;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
  border: 1px solid #f1f5f9;
  transition: 0.2s;
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.cart-item:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}
.cart-item .item-name {
  font-weight: 600;
  font-size: 15px;
  color: #1e293b;
  margin-bottom: 4px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
.cart-item .item-price {
  font-size: 13px;
  color: #64748b;
  font-weight: 500;
}
.cart-item .item-subtotal {
  font-weight: 700;
  font-size: 15px;
  color: #3b82f6;
  min-width: 100px;
  text-align: right;
}
.cart-footer .tf-button {
  background: #2563eb;
  color: #fff;
  font-size: 15px;
  border-radius: 10px;
  box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
  border: none;
  transition: 0.2s;
}
.cart-footer .tf-button:hover {
  background: #1e3a8a;
  box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
  transform: translateY(-2px);
}

#paymentModal .modal-content {
  background-color: #f9fafb;
  color: #1e293b;
  border-radius: 20px !important;
  overflow: hidden;
  border: none;
}
#paymentModal .modal-title {
  font-weight: 700;
  color: #1e293b;
}
#paymentModal #modalTotal {
  color: #2563eb !important;
  font-weight: 700;
}

.total-info-card {
  background: linear-gradient(135deg, #2563eb, #7c3aed);
  color: #fff;
  border-radius: 12px;
  padding: 18px 20px;
  box-shadow: 0 6px 14px rgba(37, 99, 235, 0.3);
}

.pay-option {
  background-color: #fff;
  border: 2px solid #e2e8f0;
  border-radius: 14px;
  padding: 24px 16px;
  text-align: center;
  color: #1e293b;
  transition: all 0.25s ease;
  position: relative;
}
.pay-option:hover {
  border-color: #3b82f6;
  box-shadow: 0 6px 14px rgba(59, 130, 246, 0.2);
  transform: translateY(-3px);
}
.pay-option.active {
  border-color: #3b82f6;
  background: linear-gradient(135deg, rgba(59,130,246,0.08), rgba(147,51,234,0.08));
  box-shadow: 0 0 12px rgba(59, 130, 246, 0.25);
}
.pay-option .icon-wrap {
  width: 60px;
  height: 60px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 28px;
  color: #fff;
  margin-bottom: 12px;
  box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
}
.pay-option .bg-blue { background: linear-gradient(135deg, #2563eb, #1d4ed8); }
.pay-option .bg-qris { background: linear-gradient(135deg, #10b981, #059669); }
.pay-option .bg-ewallet { background: linear-gradient(135deg, #f59e0b, #d97706); }

.pay-option .method-name {
  font-weight: 600;
  font-size: 15px;
}
.pay-option .check-mark {
  position: absolute;
  top: 10px;
  right: 10px;
  width: 22px;
  height: 22px;
  background: #3b82f6;
  color: #fff;
  border-radius: 50%;
  display: none;
  align-items: center;
  justify-content: center;
}
.pay-option.active .check-mark {
  display: flex;
}

#nonCashInfo {
  background: #eff6ff;
  border-radius: 10px;
  padding: 16px;
  color: #1e40af;
}

#paymentModal .btn-primary {
  background-color: #2563eb;
  border: none;
  font-weight: 600;
  transition: all 0.2s ease;
}
#paymentModal .btn-primary:hover {
  background-color: #1d4ed8;
  box-shadow: 0 4px 10px rgba(37, 99, 235, 0.4);
}
#paymentModal .btn-light {
  background: #f1f5f9;
  color: #334155;
}
#paymentModal .btn-light:hover {
  background: #e2e8f0;
}

@media (max-width: 768px) {
  .pay-option {
    padding: 16px;
  }
  .pay-option .icon-wrap {
    width: 52px;
    height: 52px;
  }
}

</style>
@endpush
