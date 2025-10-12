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
                <div class="text-center text-gray-500 py-4">
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

<div class="modal fade" id="paymentModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content border-0 shadow-lg rounded-3 overflow-hidden">
      <div class="modal-header bg-gradient-to-r from-blue-600 to-indigo-700 text-white py-3 px-4">
        <h5 class="modal-title fw-semibold"><i class="icon-wallet me-2"></i> Pilih Metode Pembayaran</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body py-4 px-5 text-center">
        <p class="text-gray-600 mb-4">Silakan pilih salah satu metode pembayaran berikut:</p>
        <div class="row g-3 justify-content-center mb-4">
          <div class="col-6 col-md-4"><button class="pay-option" data-method="cash"><div class="icon-wrap bg-green-100 text-green-600"><i class="icon-dollar-sign fs-3"></i></div><span>Cash</span></button></div>
          <div class="col-6 col-md-4"><button class="pay-option" data-method="qris"><div class="icon-wrap bg-blue-100 text-blue-600"><i class="icon-qr-code fs-3"></i></div><span>QRIS</span></button></div>
          <div class="col-6 col-md-4"><button class="pay-option" data-method="ewallet"><div class="icon-wrap bg-yellow-100 text-yellow-600"><i class="icon-smartphone fs-3"></i></div><span>E-Wallet</span></button></div>
        </div>

        {{-- === FORM PEMBAYARAN (untuk cash) === --}}
        <div id="paymentForm" class="text-start d-none">
          <div class="mb-3">
            <label class="form-label fw-semibold">Total Pembayaran</label>
            <input type="text" id="payTotal" class="form-control" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Uang Diterima</label>
            <input type="number" id="payReceived" class="form-control" placeholder="Masukkan nominal uang...">
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Kembalian</label>
            <input type="text" id="payChange" class="form-control" readonly>
          </div>
          <button id="confirmPaymentBtn" class="tf-button style-1 w-100 py-2 mt-2 fw-semibold" disabled>
            <i class="icon-check"></i> Selesaikan Transaksi
          </button>
        </div>
      </div>

      <div class="modal-footer bg-gray-50 border-0 py-3">
        <button type="button" class="tf-button style-2" data-bs-dismiss="modal"><i class="icon-x"></i> Batal</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
let cart = [];
const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
const storeUrl = @json(route('sales.store'));
let selectedMethod = null;
let totalPayment = 0;

function formatRupiah(num) { return 'Rp ' + num.toLocaleString('id-ID'); }

function addToCart(id, name, price, stock) {
  const item = cart.find(p => p.id === id);
  if (item) {
    if (item.qty < stock) item.qty++;
    else return alert('⚠️ Stok tidak cukup!');
  } else cart.push({ id, name, price, qty: 1, stock });
  renderCart();
}
function updateQty(id, qty) {
  const item = cart.find(p => p.id === id);
  if (!item) return;
  qty = parseInt(qty);
  if (qty < 1) return removeFromCart(id);
  if (qty > item.stock) return alert('Stok tidak cukup!');
  item.qty = qty; renderCart();
}
function removeFromCart(id) { cart = cart.filter(p => p.id !== id); renderCart(); }
function clearCart() { if (confirm('Kosongkan semua item di keranjang?')) { cart = []; renderCart(); } }
function renderCart() {
  let total = 0;
  const html = cart.map(p => {
    const subtotal = p.price * p.qty; total += subtotal;
    return `
      <div class="cart-item">
        <div class="item-info">
          <div class="item-name">${p.name}</div>
          <div class="item-price">${formatRupiah(p.price)}</div>
        </div>
        <div class="flex items-center gap4">
          <input type="number" min="1" class="form-control form-control-sm text-center"
              value="${p.qty}" onchange="updateQty(${p.id}, this.value)">
          <div class="fw-semibold text-sm mx-2">${formatRupiah(subtotal)}</div>
          <button class="remove-btn" onclick="removeFromCart(${p.id})"><i class="icon-trash-2"></i></button>
        </div>
      </div>`;
  }).join('');
  document.getElementById('cartList').innerHTML = html || `
    <div class="text-center text-gray-500 py-4">
      <i class="icon-shopping-bag fs-3 d-block mb-2"></i> Keranjang kosong
    </div>`;
  document.getElementById('cartTotal').innerText = formatRupiah(total);
}

document.getElementById('checkoutBtn')?.addEventListener('click', () => {
  if (cart.length === 0) return alert('Keranjang kosong!');
  modal.show();
});

document.querySelectorAll('.pay-option').forEach(btn => {
  btn.addEventListener('click', () => {
    selectedMethod = btn.dataset.method;
    totalPayment = cart.reduce((sum, p) => sum + p.price * p.qty, 0);

    if (selectedMethod === 'cash') {
      document.getElementById('paymentForm').classList.remove('d-none');
      document.getElementById('payTotal').value = formatRupiah(totalPayment);
      document.getElementById('payReceived').focus();
    } else {
      processPayment(selectedMethod);
    }
  });
});

const payInput = document.getElementById('payReceived');
const payChange = document.getElementById('payChange');
const confirmBtn = document.getElementById('confirmPaymentBtn');
if (payInput) {
  payInput.addEventListener('input', () => {
    const val = parseFloat(payInput.value) || 0;
    const change = val - totalPayment;
    payChange.value = change >= 0 ? formatRupiah(change) : '-';
    confirmBtn.disabled = change < 0;
  });
}

confirmBtn?.addEventListener('click', () => processPayment(selectedMethod));

async function processPayment(method) {
  const total = totalPayment;
  try {
    const res = await fetch(storeUrl, {
      method: 'POST',
      headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
      body: JSON.stringify({ cart, total, payment_method: method })
    });
    const data = await res.json();
    if (data.status === 'success') {
      alert('✅ Transaksi berhasil!');
      window.location.href = data.redirect_url;
    } else alert(data.message || 'Gagal memproses transaksi.');
  } catch (err) {
    alert('Terjadi kesalahan koneksi.');
  }
}

// === SEARCH PRODUK ===
document.getElementById('searchProduct')?.addEventListener('input', function() {
  const q = this.value.toLowerCase();
  document.querySelectorAll('#productGrid .gallery-item').forEach(el => {
    el.style.display = el.textContent.toLowerCase().includes(q) ? '' : 'none';
  });
});
</script>
@endpush

@push('styles')
<style>
.gallery-item { display:inline-block; width:190px; margin:8px; text-align:center; transition:.2s; }
.gallery-item img { width:100%; height:130px; object-fit:cover; border-radius:10px; }
.gallery-item:hover { transform:scale(1.04); box-shadow:0 4px 12px rgba(0,0,0,0.1); }

.cart-panel { background:#fff; border-radius:16px; box-shadow:0 4px 16px rgba(0,0,0,0.05); padding:24px; display:flex; flex-direction:column; justify-content:space-between; }
.custom-scroll { max-height:55vh; overflow-y:auto; border:1px solid #eee; border-radius:8px; background:#fafafa; padding:10px; }
.custom-scroll::-webkit-scrollbar { width:6px; }
.custom-scroll::-webkit-scrollbar-thumb { background:#cbd5e1; border-radius:6px; }

.cart-item { display:flex; justify-content:space-between; align-items:center; border-bottom:1px dashed #e5e7eb; padding:8px 0; transition:.2s; }
.cart-item:last-child { border-bottom:none; }
.cart-item:hover { background:#f9fafb; border-radius:6px; }

.item-name { font-weight:600; font-size:14px; color:#111827; }
.item-price { font-size:12px; color:#6b7280; }

.remove-btn { color:#dc2626; background:none; border:none; cursor:pointer; transition:.2s; }
.remove-btn:hover { color:#fff; background:#dc2626; border-radius:6px; padding:4px 6px; }

.cart-footer .tf-button { background:#2563eb; color:#fff; font-size:15px; border-radius:10px; box-shadow:0 4px 12px rgba(37,99,235,0.3); transition:.2s; }
.cart-footer .tf-button:hover { background:#1e3a8a; box-shadow:0 6px 16px rgba(37,99,235,0.4); }

.pay-option { background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; padding:16px; transition:.2s; width:100%; }
.pay-option:hover { background:#eff6ff; border-color:#2563eb; transform:scale(1.05); color:#2563eb; }

#paymentForm .form-control { border-radius:8px; border:1px solid #d1d5db; padding:10px; font-size:15px; }
#paymentForm label { color:#374151; font-size:14px; }
#confirmPaymentBtn { background:#2563eb; border:none; color:white; border-radius:8px; transition:.2s; }
#confirmPaymentBtn:disabled { background:#9ca3af; cursor:not-allowed; }
</style>
@endpush
