<!-- header-dashboard -->
<div class="header-dashboard">
    <div class="wrap d-flex justify-content-between align-items-center">
        {{-- LEFT --}}
        <div class="header-left d-flex align-items-center gap-3">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center">
                <img id="logo_header_mobile"
                     alt="{{ config('app.name') }}"
                     src="{{ asset('images/logo/logo.png') }}"
                     data-light="{{ asset('images/logo/logo.png') }}"
                     data-dark="{{ asset('images/logo/logo-dark.png') }}"
                     style="height: 38px;">
            </a>

            <button type="button" class="button-show-hide btn btn-light border-0">
                <i class="icon-menu-left fs-5"></i>
            </button>

            {{-- Search diarahkan ke products.index --}}
            <form class="form-search flex-grow" action="{{ route('products.index') }}" method="GET">
                <fieldset class="name mb-0">
                    <input type="text"
                           placeholder="Cari produk, penjualan, laporan..."
                           class="show-search"
                           name="search"
                           tabindex="2"
                           value="{{ request('search') }}">
                </fieldset>
                <div class="button-submit">
                    <button type="submit"><i class="icon-search"></i></button>
                </div>
            </form>
        </div>

        {{-- RIGHT --}}
        <div class="header-grid d-flex align-items-center gap-3">

            {{-- Language (dummy) --}}
            <div class="header-item country">
                <select class="image-select no-text form-select form-select-sm">
                    <option data-thumbnail="{{ asset('images/country/1.png') }}" value="id" selected>ID</option>
                    <option data-thumbnail="{{ asset('images/country/2.png') }}" value="en">EN</option>
                </select>
            </div>

            {{-- Dark mode --}}
            <div class="header-item button-dark-light" title="Toggle Dark Mode" style="cursor:pointer;">
                <i class="icon-moon"></i>
            </div>

            {{-- Fullscreen --}}
            <div class="header-item button-zoom-maximize" title="Fullscreen">
                <i class="icon-maximize"></i>
            </div>

            {{-- Notifications (tanpa unreadNotifications) --}}
            <div class="popup-wrap noti type-header">
                <div class="dropdown">
                    <button class="btn btn-light border-0 dropdown-toggle" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="icon-bell fs-5"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end p-0 shadow-sm">
                        <li class="px-3 py-2 border-bottom fw-semibold text-muted">Notifikasi</li>
                        <li class="text-center text-muted py-3">
                            <small>Tidak ada notifikasi baru</small>
                        </li>
                    </ul>
                </div>
            </div>

            {{-- User --}}
            <div class="popup-wrap user type-header">
                <div class="dropdown">
                    <button class="btn btn-light border-0 dropdown-toggle d-flex align-items-center"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="{{ auth()->user()->avatar ?? asset('images/avatar/user-1.png') }}"
                             class="rounded-circle me-2" width="36" height="36" alt="avatar">
                        <div class="text-start d-none d-sm-block">
                            <div class="fw-semibold small mb-0">{{ auth()->user()->name }}</div>
                            <small class="text-muted">{{ ucfirst(auth()->user()->role) }}</small>
                        </div>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li>
                            <a href="{{ route('stores.index') }}" class="dropdown-item">
                                <i class="icon-store me-2"></i> Toko Saya
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('dashboard') }}" class="dropdown-item">
                                <i class="icon-grid me-2"></i> Dashboard
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="icon-log-out me-2"></i> Keluar
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- /header-dashboard -->

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  // Dark mode
  const toggleDark = document.querySelector('.button-dark-light');
  toggleDark.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');
    localStorage.setItem('darkMode', document.body.classList.contains('dark-mode') ? 'on' : 'off');
  });
  if (localStorage.getItem('darkMode') === 'on') document.body.classList.add('dark-mode');

  // Fullscreen
  const btnFs = document.querySelector('.button-zoom-maximize');
  btnFs.addEventListener('click', () => {
    if (!document.fullscreenElement) document.documentElement.requestFullscreen();
    else document.exitFullscreen();
  });
});
</script>
@endpush
