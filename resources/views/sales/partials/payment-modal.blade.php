<!-- ============================= -->
<!-- 🧾 MODAL PEMBAYARAN (FINAL) -->
<!-- ============================= -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg rounded-4">

      <!-- === HEADER === -->
      <div class="modal-header border-0 pb-0 pt-4 px-4">
        <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="paymentModalLabel">
          <div class="icon-circle bg-primary-subtle">
            <i class="icon-credit-card text-primary"></i>
          </div>
          Metode Pembayaran
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <!-- === BODY === -->
      <div class="modal-body p-4">

        <!-- === TOTAL INFO === -->
        <div class="total-info-card mb-4">
          <div class="d-flex justify-content-between align-items-center">
            <span class="text-light">Total Pembayaran:</span>
            <h3 class="mb-0 fw-bold text-white" id="modalTotal">Rp 0</h3>
          </div>
        </div>

        <!-- === PILIH METODE PEMBAYARAN === -->
        <div class="payment-methods mb-4">
          <label class="form-label fw-semibold mb-3">Pilih Metode Pembayaran</label>
          <div class="row g-3">

            <!-- Tunai -->
            <div class="col-md-4">
              <button type="button" class="pay-option w-100" data-method="cash">
                <div class="icon-wrap bg-blue">
                  <i class="icon-dollar-sign"></i>
                </div>
                <span class="method-name">Tunai</span>
                <div class="check-mark"><i class="icon-check"></i></div>
              </button>
            </div>

            <!-- QRIS -->
            <div class="col-md-4">
              <button type="button" class="pay-option w-100" data-method="qris">
                <div class="icon-wrap bg-qris">
                  <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M3 3h8v8H3V3zm2 2v4h4V5H5zm4 4H7V7h2v2zm-4 2h8v8H3v-8zm2 2v4h4v-4H5zm4 4H7v-2h2v2zm4-14h8v8h-8V3zm2 2v4h4V5h-4zm4 4h-2V7h2v2zm-4 2h8v8h-8v-8zm2 2v4h4v-4h-4zm4 4h-2v-2h2v2z"/>
                  </svg>
                </div>
                <span class="method-name">QRIS</span>
                <div class="check-mark"><i class="icon-check"></i></div>
              </button>
            </div>

            <!-- E-Wallet -->
            <div class="col-md-4">
              <button type="button" class="pay-option w-100" data-method="ewallet">
                <div class="icon-wrap bg-ewallet">
                  <i class="icon-smartphone"></i>
                </div>
                <span class="method-name">E-Wallet</span>
                <div class="check-mark"><i class="icon-check"></i></div>
              </button>
            </div>

          </div>
        </div>

        <!-- === FORM PEMBAYARAN TUNAI === -->
        <div id="paymentForm" class="d-none">
          <div class="cash-form-card">
            <div class="row g-3">

              <!-- Total -->
              <div class="col-12">
                <label class="form-label">Total Pembayaran</label>
                <div class="input-group input-group-lg">
                  <span class="input-group-text bg-light"><i class="icon-tag"></i></span>
                  <input type="text" id="payTotal" class="form-control" readonly>
                </div>
              </div>

              <!-- Uang Diterima -->
              <div class="col-12">
                <label class="form-label">Uang Diterima</label>
                <div class="input-group input-group-lg">
                  <span class="input-group-text bg-light"><i class="icon-dollar-sign"></i></span>
                  <input type="number" id="payReceived" class="form-control" placeholder="0">
                </div>

                <!-- Quick Amounts -->
                <div class="quick-amounts mt-2">
                  <button type="button" class="btn btn-sm btn-outline-secondary quick-amount" data-amount="50000">50K</button>
                  <button type="button" class="btn btn-sm btn-outline-secondary quick-amount" data-amount="100000">100K</button>
                  <button type="button" class="btn btn-sm btn-outline-secondary quick-amount" data-amount="200000">200K</button>
                  <button type="button" class="btn btn-sm btn-outline-secondary quick-amount" data-amount="500000">500K</button>
                </div>
              </div>

              <!-- Kembalian -->
              <div class="col-12">
                <label class="form-label">Kembalian</label>
                <div class="input-group input-group-lg">
                  <span class="input-group-text bg-success text-white"><i class="icon-trending-down"></i></span>
                  <input type="text" id="payChange" class="form-control bg-success-subtle fw-bold" readonly>
                </div>
              </div>

            </div>
          </div>
        </div>

        <!-- === INFO NON-TUNAI === -->
        <div id="nonCashInfo" class="d-none">
          <div class="alert alert-info border-0 d-flex align-items-center gap-3">
            <i class="icon-info fs-4"></i>
            <div>
              <div class="fw-semibold mb-1">Instruksi Pembayaran</div>
              <div class="small">Silakan scan kode QR atau buka aplikasi e-wallet untuk menyelesaikan pembayaran.</div>
            </div>
          </div>
        </div>

      </div>

      <!-- === FOOTER === -->
      <div class="modal-footer border-0 pt-0 px-4 pb-4">
        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">
          <i class="icon-x me-1"></i> Batal
        </button>
        <button id="confirmPaymentBtn" class="btn btn-primary btn-lg px-5" disabled>
          <i class="icon-check-circle me-2"></i> Proses Pembayaran
        </button>
      </div>

    </div>
  </div>
</div>
