<!-- section-menu-left -->
<div class="section-menu-left">
    <!-- === LOGO AREA === -->
    <div class="box-logo d-flex align-items-center justify-content-between">
        <a href="{{ route('dashboard') }}" id="site-logo-inner">
            <img id="logo_header" alt="{{ config('app.name') }}" 
                 src="{{ asset('images/logo/logo.png') }}" 
                 data-light="{{ asset('images/logo/logo.png') }}" 
                 data-dark="{{ asset('images/logo/logo-dark.png') }}">
        </a>
        <div class="button-show-hide">
            <i class="icon-menu-left"></i>
        </div>
    </div>

    <!-- === MENU LIST === -->
    <div class="section-menu-left-wrap">
        <div class="center">
            <!-- DASHBOARD -->
            <div class="center-item">
                <div class="center-heading">Dashboard</div>
                <ul class="menu-list">
                    <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <a href="{{ route('dashboard') }}" class="menu-item-button">
                            <div class="icon"><i class="icon-grid"></i></div>
                            <div class="text">Dashboard</div>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- MENU UTAMA -->
            <div class="center-item">
                <div class="center-heading">Menu Utama</div>
                <ul class="menu-list">

                    {{-- === Produk === --}}
                    <li class="menu-item has-children {{ request()->is('products*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="menu-item-button">
                            <div class="icon"><i class="icon-shopping-cart"></i></div>
                            <div class="text">Produk</div>
                        </a>
                        <ul class="sub-menu" style="{{ request()->is('products*') ? 'display:block;' : '' }}">
                            <li class="sub-menu-item">
                                <a href="{{ route('products.index') }}" 
                                   class="{{ request()->routeIs('products.index') ? 'active' : '' }}">
                                    <div class="text">Daftar Produk</div>
                                </a>
                            </li>
                            <li class="sub-menu-item">
                                <a href="{{ route('products.create') }}" 
                                   class="{{ request()->routeIs('products.create') ? 'active' : '' }}">
                                    <div class="text">Tambah Produk</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- === Penjualan / Kasir === --}}
                    <li class="menu-item has-children {{ request()->is('sales*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="menu-item-button">
                            <div class="icon"><i class="icon-cash"></i></div>
                            <div class="text">Penjualan</div>
                        </a>
                        <ul class="sub-menu" style="{{ request()->is('sales*') ? 'display:block;' : '' }}">
                            <li class="sub-menu-item">
                                <a href="{{ route('sales.index') }}" 
                                   class="{{ request()->routeIs('sales.index') ? 'active' : '' }}">
                                    <div class="text">Kasir POS</div>
                                </a>
                            </li>
                            <li class="sub-menu-item">
                                <a href="{{ route('sales.history') }}" 
                                   class="{{ request()->routeIs('sales.history') ? 'active' : '' }}">
                                    <div class="text">Riwayat Transaksi</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- === Pembelian / Restok === --}}
                    <li class="menu-item has-children {{ request()->is('purchases*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="menu-item-button">
                            <div class="icon"><i class="icon-box"></i></div>
                            <div class="text">Pembelian</div>
                        </a>
                        <ul class="sub-menu" style="{{ request()->is('purchases*') ? 'display:block;' : '' }}">
                            <li class="sub-menu-item">
                                <a href="{{ route('purchases.index') }}" 
                                   class="{{ request()->routeIs('purchases.index') ? 'active' : '' }}">
                                    <div class="text">Daftar Pembelian</div>
                                </a>
                            </li>
                            <li class="sub-menu-item">
                                <a href="{{ route('purchases.create') }}" 
                                   class="{{ request()->routeIs('purchases.create') ? 'active' : '' }}">
                                    <div class="text">Tambah Pembelian</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- === Laporan === --}}
                    <li class="menu-item {{ request()->is('report*') ? 'active' : '' }}">
                        <a href="{{ route('report.index') }}" class="menu-item-button">
                            <div class="icon"><i class="icon-file"></i></div>
                            <div class="text">Laporan</div>
                        </a>
                    </li>

                    {{-- === Manajemen Toko === --}}
                    <li class="menu-item has-children {{ request()->is('stores*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="menu-item-button">
                            <div class="icon"><i class="icon-store"></i></div>
                            <div class="text">Toko Saya</div>
                        </a>
                        <ul class="sub-menu" style="{{ request()->is('stores*') ? 'display:block;' : '' }}">
                            <li class="sub-menu-item">
                                <a href="{{ route('stores.index') }}" 
                                   class="{{ request()->routeIs('stores.index') ? 'active' : '' }}">
                                    <div class="text">Daftar Toko</div>
                                </a>
                            </li>
                            <li class="sub-menu-item">
                                <a href="{{ route('stores.create') }}" 
                                   class="{{ request()->routeIs('stores.create') ? 'active' : '' }}">
                                    <div class="text">Tambah Toko</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</div>
<!-- /section-menu-left -->
