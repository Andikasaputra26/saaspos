<script>
let cart = [];
const modal = new bootstrap.Modal(document.getElementById('paymentModal'));
const storeUrl = @json(route('sales.store'));
let selectedMethod = null;
let totalPayment = 0;

function formatRupiah(num) { 
    return 'Rp ' + num.toLocaleString('id-ID'); 
}

function addToCart(id, name, price, stock) {
  const item = cart.find(p => p.id === id);
  if (item) {
    if (item.qty < stock) {
        item.qty++;
    } else {
        alert('⚠️ Stok tidak cukup!');
        return;
    }
  } else {
    cart.push({ id, name, price, qty: 1, stock });
  }
  renderCart();
}

function updateQty(id, qty) {
  const item = cart.find(p => p.id === id);
  if (!item) return;
  qty = parseInt(qty);
  if (qty < 1) return removeFromCart(id);
  if (qty > item.stock) {
    alert('⚠️ Stok tidak cukup!');
    return;
  }
  item.qty = qty; 
  renderCart();
}

function removeFromCart(id) { 
    cart = cart.filter(p => p.id !== id); 
    renderCart(); 
}

function clearCart() { 
    if (confirm('Kosongkan semua item di keranjang?')) { 
        cart = []; 
        renderCart(); 
    } 
}

function renderCart() {
  let total = 0;
  const cartContainer = document.getElementById('cartList');
  
  if (cart.length === 0) {
    cartContainer.innerHTML = `
      <div class="empty-cart text-center text-gray-500 py-4">
        <i class="icon-shopping-bag fs-3 d-block mb-2"></i>
        <p class="mb-0">Keranjang kosong</p>
      </div>`;
    document.getElementById('cartTotal').innerText = formatRupiah(0);
    return;
  }
  
  const html = cart.map(p => {
    const subtotal = p.price * p.qty;
    total += subtotal;
    return `
      <div class="cart-item">
        <div class="item-info">
          <div class="item-name">${p.name}</div>
          <div class="item-price">${formatRupiah(p.price)}</div>
        </div>
        <div class="item-controls">
          <input type="number" min="1" class="form-control qty-input"
              value="${p.qty}" onchange="updateQty(${p.id}, this.value)">
          <div class="item-subtotal">${formatRupiah(subtotal)}</div>
          <button class="remove-btn" onclick="removeFromCart(${p.id})">
            <i class="icon-trash-2"></i>
          </button>
        </div>
      </div>`;
  }).join('');
  
  cartContainer.innerHTML = html;
  document.getElementById('cartTotal').innerText = formatRupiah(total);
}

// Checkout Button
document.getElementById('checkoutBtn')?.addEventListener('click', () => {
  if (cart.length === 0) {
    alert('⚠️ Keranjang kosong!');
    return;
  }
  
  // Reset modal state
  document.querySelectorAll('.pay-option').forEach(b => b.classList.remove('active'));
  document.getElementById('paymentForm').classList.add('d-none');
  document.getElementById('nonCashInfo').classList.add('d-none');
  document.getElementById('confirmPaymentBtn').disabled = true;
  selectedMethod = null;
  
  const total = cart.reduce((sum, p) => sum + p.price * p.qty, 0);
  document.getElementById('modalTotal').innerText = formatRupiah(total);
  
  modal.show();
});

// Payment Method Selection
document.querySelectorAll('.pay-option').forEach(btn => {
  btn.addEventListener('click', function() {
    // Remove active class from all
    document.querySelectorAll('.pay-option').forEach(b => b.classList.remove('active'));
    // Add active to clicked
    this.classList.add('active');
    
    selectedMethod = this.dataset.method;
    totalPayment = cart.reduce((sum, p) => sum + p.price * p.qty, 0);
    
    // Hide both forms first
    document.getElementById('paymentForm').classList.add('d-none');
    document.getElementById('nonCashInfo').classList.add('d-none');
    
    if (selectedMethod === 'cash') {
      document.getElementById('paymentForm').classList.remove('d-none');
      document.getElementById('payTotal').value = formatRupiah(totalPayment);
      document.getElementById('payReceived').value = '';
      document.getElementById('payChange').value = '-';
      document.getElementById('confirmPaymentBtn').disabled = true;
      setTimeout(() => document.getElementById('payReceived').focus(), 100);
    } else {
      document.getElementById('nonCashInfo').classList.remove('d-none');
      document.getElementById('confirmPaymentBtn').disabled = false;
    }
  });
});

// Quick Amount Buttons
document.querySelectorAll('.quick-amount').forEach(btn => {
  btn.addEventListener('click', function() {
    const amount = parseInt(this.dataset.amount);
    document.getElementById('payReceived').value = amount;
    document.getElementById('payReceived').dispatchEvent(new Event('input'));
  });
});

// Cash Payment Input
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

// Confirm Payment
confirmBtn?.addEventListener('click', () => {
    if (!selectedMethod) {
        alert('⚠️ Pilih metode pembayaran terlebih dahulu!');
        return;
    }
    processPayment(selectedMethod);
});

async function processPayment(method) {
  const total = totalPayment;
  
  // Show loading
  confirmBtn.disabled = true;
  confirmBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
  
  try {
    const res = await fetch(storeUrl, {
      method: 'POST',
      headers: {
        'Content-Type':'application/json',
        'X-CSRF-TOKEN':'{{ csrf_token() }}'
      },
      body: JSON.stringify({ 
        cart, 
        total, 
        payment_method: method 
      })
    });
    
    const data = await res.json();
    
    if (data.status === 'success') {
      alert('✅ Transaksi berhasil!');
      cart = [];
      renderCart();
      modal.hide();
      if (data.redirect_url) {
        window.location.href = data.redirect_url;
      }
    } else {
      alert('❌ ' + (data.message || 'Gagal memproses transaksi.'));
      confirmBtn.disabled = false;
      confirmBtn.innerHTML = '<i class="icon-check-circle me-2"></i> Proses Pembayaran';
    }
  } catch (err) {
    console.error(err);
    alert('❌ Terjadi kesalahan koneksi.');
    confirmBtn.disabled = false;
    confirmBtn.innerHTML = '<i class="icon-check-circle me-2"></i> Proses Pembayaran';
  }
}

// Search Product
document.getElementById('searchProduct')?.addEventListener('input', function() {
  const q = this.value.toLowerCase();
  document.querySelectorAll('#productGrid .gallery-item').forEach(el => {
    el.style.display = el.textContent.toLowerCase().includes(q) ? '' : 'none';
  });
});
</script>