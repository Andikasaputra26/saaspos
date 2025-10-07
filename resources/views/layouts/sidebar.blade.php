<div id="sidebar" class="sidebar d-flex flex-column p-3">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <span class="sidebar-logo"><i class="bi bi-shop me-2"></i>POS</span>
        <button class="btn btn-sm btn-outline-secondary d-lg-none" id="toggleSidebar">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <ul class="nav flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i><span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('sales.index') }}" class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                <i class="bi bi-bag-check-fill me-2"></i><span>Transaksi</span>
            </a>
        </li>
        <li>
            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam me-2"></i><span>Produk</span>
            </a>
        </li>
        <li>
            <a href="{{ route('report.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard-data me-2"></i><span>Laporan</span>
            </a>
        </li>
        <li>
            <a href="{{ route('stores.index') }}" class="nav-link {{ request()->routeIs('stores.*') ? 'active' : '' }}">
                <i class="bi bi-building me-2"></i><span>Toko</span>
            </a>
        </li>
    </ul>

    <hr>

    @include('layouts.darkmode-toggle')

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-outline-danger w-100">
            <i class="bi bi-box-arrow-right me-1"></i> Keluar
        </button>
    </form>
</div>
