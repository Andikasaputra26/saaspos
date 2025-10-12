<div class="section-menu-left">
    <div class="box-logo d-flex align-items-center justify-content-between">
        <a href="{{ route('dashboard') }}" id="site-logo-inner">
        </a>
        <div class="button-show-hide">
            <i class="icon-menu-left"></i>
        </div>
    </div>

    <div class="section-menu-left-wrap">
        <div class="center">
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

            <div class="center-item">
                <div class="center-heading">Menu Utama</div>
                <ul class="menu-list">

                    <li class="menu-item has-children {{ request()->is('products*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="menu-item-button">
                            <div class="icon"><i class="icon-shopping-cart"></i></div>
                            <div class="text">Produk</div>
                        </a>
                        <ul class="sub-menu" style="{{ request()->is('products*') ? 'display:block;' : '' }}">
                            <li class="sub-menu-item">
                                <a href="{{ route('products.index') }}" 
                                   class="{{ request()->routeIs('products.index') ? 'active' : '' }}">
                                    <div class="icon"><i class="icon-list"></i></div>
                                    <div class="text">Daftar Produk</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item has-children {{ request()->is('sales*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="menu-item-button">
                            <div class="icon"><i class="icon-shopping-cart"></i></div>
                            <div class="text">Penjualan</div>
                        </a>
                        <ul class="sub-menu" style="{{ request()->is('sales*') ? 'display:block;' : '' }}">
                            <li class="sub-menu-item">
                                <a href="{{ route('sales.index') }}" 
                                   class="{{ request()->routeIs('sales.index') ? 'active' : '' }}">
                                    <div class="icon"><i class="icon-monitor"></i></div>
                                    <div class="text">Kasir POS</div>
                                </a>
                            </li>
                            <li class="sub-menu-item">
                                <a href="{{ route('sales.history') }}" 
                                   class="{{ request()->routeIs('sales.history') ? 'active' : '' }}">
                                    <div class="icon"><i class="icon-clock"></i></div>
                                    <div class="text">Riwayat Transaksi</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item has-children {{ request()->is('purchases*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="menu-item-button">
                            <div class="icon"><i class="icon-box"></i></div>
                            <div class="text">Pembelian</div>
                        </a>
                        <ul class="sub-menu" style="{{ request()->is('purchases*') ? 'display:block;' : '' }}">
                           <li class="sub-menu-item">
                                <a href="{{ route('purchases.index') }}" 
                                   class="{{ request()->routeIs('purchases.index') ? 'active' : '' }}">
                                    <div class="icon"><i class="icon-file-text"></i></div>
                                    <div class="text">Daftar Pembelian</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="menu-item has-children {{ request()->is('report*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="menu-item-button">
                            <div class="icon"><i class="icon-file"></i></div>
                            <div class="text">Laporan</div>
                            <div class="arrow"><i class="icon-chevron-down"></i></div>
                        </a>

                        <ul class="sub-menu" style="{{ request()->is('report*') ? 'display:block;' : '' }}">
                            <li class="sub-menu-item">
                                <a href="{{ route('report.index') }}" 
                                class="{{ request()->routeIs('report.index') ? 'active' : '' }}">
                                    <div class="icon"><i class="icon-bar-chart-2"></i></div>
                                    <div class="text">Laporan Penjualan</div>
                                </a>
                            </li>

                            <li class="sub-menu-item">
                                <a href="{{ route('report.stock_movement') }}" 
                                class="{{ request()->routeIs('report.stock_movement') ? 'active' : '' }}">
                                    <div class="icon"><i class="icon-refresh-cw"></i></div>
                                    <div class="text">Pergerakan Stok</div>
                                </a>
                            </li>
                         
                        </ul>
                    </li>


                    <li class="menu-item has-children {{ request()->is('stores*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="menu-item-button">
                            <div class="icon"><i class="icon-store"></i></div>
                            <div class="text">Toko Saya</div>
                            <div class="arrow"><i class="icon-chevron-down"></i></div>
                        </a>
                        <ul class="sub-menu" style="{{ request()->is('stores*') ? 'display:block;' : '' }}">
                            <li class="sub-menu-item">
                                <a href="{{ route('stores.index') }}" 
                                   class="{{ request()->routeIs('stores.index') ? 'active' : '' }}">
                                    <div class="icon"><i class="icon-list"></i></div>
                                    <div class="text">Daftar Toko</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</div>
