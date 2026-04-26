<div class="d-flex flex-column flex-shrink-0 p-3 text-white sidebar-custom" style="width: 280px;">    
    <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <span class="fs-4 fw-bold" style="font-family: 'Playfair Display', serif;">Admin Panel</span>
    </a>
    <hr>
    
    <ul class="nav nav-pills flex-column mb-auto">
        {{-- DASHBOARD --}}
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        {{-- VERIFIKASI USER --}}
        <li>
            <a href="{{ route('admin.verify.index') }}" class="nav-link text-white d-flex align-items-center {{ request()->routeIs('admin.verify.*') ? 'active' : '' }}">
                <i class="bi bi-shield-check me-2"></i> Verifikasi User
                @if(isset($notifVerifyAdmin) && $notifVerifyAdmin > 0)
                    <span class="badge rounded-pill bg-info text-dark ms-auto shadow-sm">{{ $notifVerifyAdmin }}</span>
                @endif
            </a>
        </li>

        {{-- KELOLA TRENDS --}}
        <li>
            <a href="{{ route('admin.trends.index') }}" class="nav-link text-white {{ request()->routeIs('admin.trends.*') ? 'active' : '' }}">
                <i class="bi bi-lightning-charge me-2"></i> Kelola Trends
            </a>
        </li>

        {{-- KELOLA PRODUK --}}
        <li>
            <a href="{{ route('admin.products.index') }}" class="nav-link text-white {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam me-2"></i> Kelola Produk
            </a>
        </li>

        {{-- KELOLA KATEGORI --}}
        <li>
            <a href="{{ route('admin.categories.index') }}" class="nav-link text-white {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="bi bi-tags me-2"></i> Kelola Kategori
            </a>
        </li>

        {{-- KELOLA PESANAN --}}
        <li>
            <a href="{{ route('admin.orders.index') }}" class="nav-link text-white d-flex align-items-center {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-card-checklist me-2"></i> Kelola Pesanan
                @if($notifOrderAdmin > 0)
                    <span class="badge rounded-pill bg-danger ms-auto shadow-sm">{{ $notifOrderAdmin }}</span>
                @endif
            </a>
        </li>

        {{-- KELOLA TAWARAN --}}
        <li>
            <a href="{{ route('admin.bargains.index') }}" class="nav-link text-white d-flex align-items-center {{ request()->routeIs('admin.bargains.*') ? 'active' : '' }}">
                <i class="bi bi-hand-index-thumb me-2"></i> Kelola Tawaran
                @if($notifBargainAdmin > 0)
                    <span class="badge rounded-pill bg-warning text-dark ms-auto shadow-sm">{{ $notifBargainAdmin }}</span>
                @endif
            </a>
        </li>

        {{-- KELOLA PELANGGAN --}}
        <li>
            <a href="{{ route('admin.customers.index') }}" class="nav-link text-white {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}"> 
                <i class="bi bi-people me-2"></i> Kelola Pelanggan
            </a>
        </li>
    </ul>
    
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person-circle me-2"></i>
            <strong>{{ Auth::user()->name }}</strong>
        </a>
        <ul class="dropdown-menu text-small shadow" aria-labelledby="dropdownUser1">
            <li>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger border-0 bg-transparent">
                        <i class="bi bi-box-arrow-right me-2"></i> Keluar
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>