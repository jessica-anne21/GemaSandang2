<div class="d-flex flex-column flex-shrink-0 p-3 text-white sidebar-custom shadow" style="width: 280px; background-color: #800000; min-height: 100vh;">    
    {{-- LOGO / BRAND --}}
    <a href="{{ route('admin.dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none px-2">
        <i class="bi bi-gem me-2 fs-3"></i>
        <span class="fs-4 fw-bold" style="font-family: 'Playfair Display', serif;">Gema Admin</span>
    </a>
    <hr class="border-light opacity-25">
    
    <ul class="nav nav-pills flex-column mb-auto">
        {{-- CORE --}}
        <li class="nav-item mb-1">
            <a href="{{ route('admin.dashboard') }}" class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active-custom' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
        </li>

        {{-- USER MANAGEMENT SECTION --}}
        <li class="mt-3 mb-2 px-3 small text-uppercase opacity-50 fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">User & Safety</li>
        <li class="mb-1">
            <a href="{{ route('admin.verify.index') }}" class="nav-link text-white d-flex align-items-center {{ request()->routeIs('admin.verify.*') ? 'active-custom' : '' }}">
                <i class="bi bi-shield-check me-2"></i> Verifikasi User
                @if(isset($notifVerifyAdmin) && $notifVerifyAdmin > 0)
                    <span class="badge rounded-pill bg-info text-dark ms-auto shadow-sm">{{ $notifVerifyAdmin }}</span>
                @endif
            </a>
        </li>
        <li class="mb-1">
            <a href="{{ route('admin.customers.index') }}" class="nav-link text-white {{ request()->routeIs('admin.customers.*') ? 'active-custom' : '' }}"> 
                <i class="bi bi-people me-2"></i> Kelola Pelanggan
            </a>
        </li>

        {{-- E-COMMERCE SECTION --}}
        <li class="mt-3 mb-2 px-3 small text-uppercase opacity-50 fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Shop Management</li>
        <li class="mb-1">
            <a href="{{ route('admin.trends.index') }}" class="nav-link text-white {{ request()->routeIs('admin.trends.*') ? 'active-custom' : '' }}">
                <i class="bi bi-lightning-charge me-2"></i> Kelola Trends
            </a>
        </li>
        <li class="mb-1">
            <a href="{{ route('admin.products.index') }}" class="nav-link text-white {{ request()->routeIs('admin.products.*') ? 'active-custom' : '' }}">
                <i class="bi bi-box-seam me-2"></i> Kelola Produk
            </a>
        </li>
        <li class="mb-1">
            <a href="{{ route('admin.orders.index') }}" class="nav-link text-white d-flex align-items-center {{ request()->routeIs('admin.orders.*') ? 'active-custom' : '' }}">
                <i class="bi bi-cart-check me-2"></i> Pesanan Toko
                @if($notifOrderAdmin > 0)
                    <span class="badge rounded-pill bg-danger ms-auto shadow-sm">{{ $notifOrderAdmin }}</span>
                @endif
            </a>
        </li>
        <li class="mb-1">
            <a href="{{ route('admin.bargains.index') }}" class="nav-link text-white d-flex align-items-center {{ request()->routeIs('admin.bargains.*') ? 'active-custom' : '' }}">
                <i class="bi bi-chat-quote me-2"></i> Kelola Tawaran
                @if($notifBargainAdmin > 0)
                    <span class="badge rounded-pill bg-warning text-dark ms-auto shadow-sm">{{ $notifBargainAdmin }}</span>
                @endif
            </a>
        </li>

        
        <li class="mt-3 mb-2 px-3 small text-uppercase opacity-50 fw-bold" style="font-size: 0.7rem; letter-spacing: 1px;">Barter</li>
        
        
        {{-- MENU BARU: MONITORING BARTER --}}
        <li class="mb-1">
            <a href="{{ route('admin.barter.index') }}" class="nav-link text-white d-flex align-items-center {{ request()->routeIs('admin.barter.*') ? 'active-custom' : '' }}">
                <i class="bi bi-arrow-left-right me-2"></i> Monitoring Barter
                {{-- Kamu bisa tambahin notif payment proof di sini nanti --}}
            </a>
        </li>
    </ul>
    
    <hr class="border-light opacity-25">
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle px-2" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="bg-light text-dark rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                <i class="bi bi-person-fill"></i>
            </div>
            <strong>{{ Auth::user()->name }}</strong>
        </a>
        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
            <li>
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="bi bi-box-arrow-right me-2"></i> Keluar
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>

<style>
    /* Tambahin ini di file CSS admin kamu */
    .active-custom {
        background-color: rgba(255, 255, 255, 0.2) !important;
        border-left: 4px solid #f8f9fa;
        border-radius: 0 50px 50px 0 !important;
        font-weight: bold;
    }
    
    .nav-link {
        transition: all 0.3s ease;
        border-radius: 8px;
    }

    .nav-link:hover:not(.active-custom) {
        background-color: rgba(255, 255, 255, 0.1);
        padding-left: 1.5rem;
    }
</style>