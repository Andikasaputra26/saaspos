@extends('layouts.app')
@section('title', 'Kasir POS')

@section('content')
<div class="container-fluid py-3">
  <div class="row">

    {{-- === KOLOM PRODUK === --}}
    <div class="col-lg-8 mb-4 mb-lg-0">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="fw-bold mb-0"><i class="bi bi-box-seam me-2"></i>Produk Toko</h5>
        <a href="{{ route('sales.history') }}" class="btn btn-outline-primary btn-sm">
          <i class="bi bi-clock-history me-1"></i> Riwayat Transaksi
        </a>
      </div>

      @if($products->isEmpty())
        <div class="text-center text-muted py-5 border rounded">
          <i class="bi bi-inbox fs-1 d-block mb-2"></i>
          <p class="mb-0">Belum ada produk yang tersedia di toko ini.</p>
        </div>
      @else
        <div class="row g-3">
          @foreach($products as $p)
            <div class="col-6 col-md-4 col-lg-3">
              <div class="card h-100 border-0 shadow-sm product-card"
                onclick="addToCart({{ $p->id }}, '{{ addslashes($p->product->name) }}', {{ $p->price }}, {{ $p->stock }})">
                
                <img src="{{ $p->product->image ? asset('storage/'.$p->product->image) : asset('assets/img/no-image.png') }}"
                  class="card-img-top rounded-top" style="height:120px; object-fit:cover;">
                
                <div class="card-body text-center p-2">
                  <h6 class="fw-semibold mb-1 text-truncate">{{ $p->product->name }}</h6>
                  <small class="text-muted d-block mb-1">Rp {{ number_format($p->price, 0, ',', '.') }}</small>
                  <small class="text-secondary">Stok: {{ $p->stock }}</small>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    {{-- === KOLOM KERANJANG === --}}
    <div class="col-lg-4">
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
          <h6 class="mb-0 fw-bold"><i class="bi bi-cart-check me-2"></i>Keranjang</h6>
          <button class="btn btn-light btn-sm text-primary" onclick="clearCart()">
            <i class="bi bi-trash3"></i>
          </button>
        </div>

        <div class="card-body p-0" style="max-height: 60vh; overflow-y: auto;">
          <div id="cartList" class="list-group list-group-flush small"></div>
        </div>

        <div class="card-footer bg-white">
          <div class="d-flex justify-content-between">
            <span class="fw-semibold">Total</span>
            <h5 id="cartTotal" class="fw-bold mb-0">Rp 0</h5>
          </div>
          <button id="checkoutBtn" class="btn btn-primary w-100 mt-3">
            <i class="bi bi-credit-card me-2"></i>Proses Pembayaran
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- === MODAL PEMBAYARAN === --}}
<div class="modal fade" id="paymentModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">
      <div class="modal-header bg-primary text-white">
        <h6 class="modal-title fw-semibold"><i class="bi bi-wallet2 me-2"></i>Pilih Metode Pembayaran</h6>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center p-4">
        <div class="row g-3">
          <div class="col-4">
            <button class="btn btn-light border w-100 p-3 payment-option" data-method="cash">
              <i class="bi bi-cash-stack fs-3 text-success d-block mb-2"></i> Cash
            </button>
          </div>
          <div class="col-4">
            <button class="btn btn-light border w-100 p-3 payment-option" data-method="qris">
              <i class="bi bi-qr-code fs-3 text-primary d-block mb-2"></i> QRIS
            </button>
          </div>
          <div class="col-4">
            <button class="btn btn-light border w-100 p-3 payment-option" data-method="ewallet">
              <i class="bi bi-phone fs-3 text-warning d-block mb-2"></i> E-Wallet
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  let cart = [];
  const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
  const storeUrl = @json(route('sales.store'));

  function addToCart(id, name, price, stock) {
    const existing = cart.find(p => p.id === id);
    if (existing) {
      if (existing.qty < stock) existing.qty++;
      else return alert('Stok tidak cukup!');
    } else {
      cart.push({ id, name, price, qty: 1 });
    }
    renderCart();
  }

  function removeFromCart(id) {
    cart = cart.filter(p => p.id !== id);
    renderCart();
  }

  function updateQty(id, qty) {
    const item = cart.find(p => p.id === id);
    if (!item) return;
    qty = parseInt(qty);
    if (qty < 1) return removeFromCart(id);
    item.qty = qty;
    renderCart();
  }

  function renderCart() {
    let html = '';
    let total = 0;

    cart.forEach(p => {
      const subtotal = p.price * p.qty;
      total += subtotal;
      html += `
        <div class="list-group-item d-flex justify-content-between align-items-center">
          <div>
            <strong>${p.name}</strong><br>
            <small>Rp ${p.price.toLocaleString('id-ID')}</small>
          </div>
          <div class="d-flex align-items-center">
            <input type="number" min="1" max="999" class="form-control form-control-sm text-center me-2"
              style="width:70px" value="${p.qty}" onchange="updateQty(${p.id}, this.value)">
            <span class="fw-semibold me-2">Rp ${subtotal.toLocaleString('id-ID')}</span>
            <button class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${p.id})">
              <i class="bi bi-x"></i>
            </button>
          </div>
        </div>`;
    });

    document.getElementById('cartList').innerHTML =
      html || '<div class="p-3 text-center text-muted">Keranjang kosong</div>';
    document.getElementById('cartTotal').innerText = 'Rp ' + total.toLocaleString('id-ID');
  }

  function clearCart() {
    cart = [];
    renderCart();
  }

  document.getElementById('checkoutBtn').addEventListener('click', () => {
    if (cart.length === 0) return alert('Keranjang kosong!');
    modal.show();
  });

  document.querySelectorAll('.payment-option').forEach(btn => {
    btn.addEventListener('click', async () => {
      const method = btn.dataset.method;
      const total = cart.reduce((sum, p) => sum + p.price * p.qty, 0);

      if (cart.length === 0 || total <= 0) {
        alert('Keranjang kosong atau total tidak valid.');
        return;
      }

      btn.disabled = true;
      btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2"></span>Proses...`;

      try {
        const res = await fetch(storeUrl, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({ cart, total, payment_method: method })
        });

        const data = await res.json();
        if (data.status === 'success') {
          window.location.href = data.redirect_url;
        } else {
          alert('Gagal: ' + (data.message ?? 'Terjadi kesalahan.'));
        }
      } catch (e) {
        alert('Kesalahan koneksi ke server.');
      } finally {
        btn.disabled = false;
        btn.innerHTML = btn.dataset.method.toUpperCase();
      }
    });
  });
</script>

<style>
  .product-card { transition: all .2s ease; cursor: pointer; }
  .product-card:hover { transform: translateY(-3px); box-shadow: 0 6px 15px rgba(0,0,0,0.15); }
  .payment-option { transition: .2s ease; }
  .payment-option:hover { transform: scale(1.05); box-shadow: 0 0 10px rgba(0,0,0,0.1); }
</style>
@endsection
