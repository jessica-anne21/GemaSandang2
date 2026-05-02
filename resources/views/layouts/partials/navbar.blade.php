@php
    $unreadChats = 0;
    $cartCount = 0;
    $unreadBarter = 0;
    $totalProfileNotification = 0;

    if (Auth::check()) {
        $userId = Auth::id();

        // 1. Hitung Chat belum terbaca
        $unreadChats = \Illuminate\Support\Facades\DB::table('messages') 
                        ->where('receiver_id', $userId)
                        ->where('is_read', false)
                        ->count();

        // 2. Hitung item di keranjang
        $cartCount = \Illuminate\Support\Facades\DB::table('carts') 
                        ->where('user_id', $userId)
                        ->count();

        // 3. Hitung Notifikasi Barter Baru (Pending request masuk)
        $unreadBarter = \Illuminate\Support\Facades\DB::table('barter_requests')
                        ->where('receiver_id', $userId)
                        ->where('status', 'pending')
                        ->count();
        
        // Total notifikasi yang akan muncul di inisial profil
        $totalProfileNotification = $unreadBarter; 
    }
@endphp

<style>
    /* UI LUXURY POLISH */
    .navbar-custom {
        background-color: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-bottom: 1px solid rgba(128, 0, 0, 0.08);
        padding: 12px 0;
        transition: all 0.3s ease;
    }

    .navbar-brand {
        font-size: 1.6rem;
        letter-spacing: -0.5px;
    }

    .nav-link {
        font-size: 0.95rem;
        font-weight: 500;
        color: #444 !important;
        margin: 0 8px;
        position: relative;
        transition: all 0.3s ease;
    }

    .nav-link:hover, .nav-link.active {
        color: #800000 !important;
    }

    .nav-link::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: -2px;
        left: 50%;
        background-color: #800000;
        transition: all 0.3s ease;
        transform: translateX(-50%);
        opacity: 0;
    }

    .nav-link:hover::after, .nav-link.active::after {
        width: 20px;
        opacity: 1;
    }

    .dropdown-menu {
        margin-top: 15px !important;
        border-radius: 18px !important;
        padding: 12px !important;
        box-shadow: 0 15px 35px rgba(128, 0, 0, 0.1) !important;
        border: 1px solid rgba(128, 0, 0, 0.05) !important;
        animation: fadeInDown 0.3s ease;
    }

    @keyframes fadeInDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .dropdown-item {
        border-radius: 10px;
        padding: 10px 18px;
        font-size: 0.9rem;
        transition: 0.2s;
    }

    .dropdown-item:hover {
        background-color: #fdf5f5;
        color: #800000;
        transform: translateX(5px);
    }

    .icon-wrapper {
        transition: transform 0.2s;
        display: inline-block;
    }
    
    .icon-wrapper:hover {
        transform: scale(1.1);
    }

    .badge-custom {
        background-color: #800000 !important;
        border: 2px solid #fff;
        font-size: 0.6rem;
        font-weight: bold;
    }
</style>

<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}" style="font-family: 'Playfair Display', serif; color: #800000; font-weight: bold;">
            Gema Sandang
        </a>

        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a></li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->is('kategori/*') ? 'active' : '' }}" href="#" id="navbarDropdownKategori" data-bs-toggle="dropdown">Kategori</a>
                    <ul class="dropdown-menu border-0 shadow-sm">
                        @foreach ($categories as $category)
                            <li><a class="dropdown-item" href="{{ route('category.show', $category->id) }}">{{ $category->nama_kategori }}</a></li>
                        @endforeach
                        <li><hr class="dropdown-divider opacity-50"></li>
                        <li><a class="dropdown-item fw-bold" href="{{ route('shop') }}">Lihat Semua Produk</a></li>
                    </ul>
                </li>

                <li class="nav-item"><a class="nav-link {{ request()->routeIs('trends.*') ? 'active' : '' }}" href="{{ route('trends.index') }}">Tren Fashion</a></li>
                <li class="nav-item"><a class="nav-link {{ Request::is('barter-area*') ? 'active' : '' }}" href="{{ route('barter.index') }}">Barter Area</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">Tentang</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Kontak</a></li>
            </ul>

            <div class="d-flex align-items-center">
                @auth
                    {{-- CHAT ICON --}}
                    <a href="{{ route('chat.index') }}" class="nav-link position-relative me-3 icon-wrapper p-0">
                        <i class="bi bi-chat-dots fs-4" style="color: #444;"></i>
                        @if($unreadChats > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-circle badge-custom" style="padding: 0.35em 0.5em;">
                                {{ $unreadChats }}
                            </span>
                        @endif
                    </a> 

                    {{-- CART ICON --}}
                    <a href="{{ route('cart.index') }}" class="nav-link position-relative me-4 icon-wrapper p-0">
                        <i class="bi bi-cart fs-4" style="color: #444;"></i>
                        @if($cartCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill badge-custom">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a> 
                        
                    {{-- PROFILE DROPDOWN DENGAN BADGE --}}
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center p-0 position-relative" href="#" id="navbarDropdown" data-bs-toggle="dropdown">
                            <div class="rounded-circle bg-white d-flex align-items-center justify-content-center me-2 shadow-sm" style="width: 35px; height: 35px; border: 1.5px solid #800000;">
                                <span class="fw-bold" style="color: #800000; font-size: 0.85rem;">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                            </div>
                            
                            {{-- BADGE BARTER DI INISIAL NAMA --}}
                            @if($totalProfileNotification > 0)
                                <span class="position-absolute translate-middle badge rounded-circle badge-custom" style="top: 5px; left: 30px; padding: 0.35em 0.5em;">
                                    {{ $totalProfileNotification }}
                                </span>
                            @endif

                            <span class="d-none d-md-inline fw-semibold text-dark">{{ explode(' ', Auth::user()->name)[0] }}</span>
                        </a>
                        
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-3">
                            <li><a class="dropdown-item" href="{{ route('profile.my-profile') }}"><i class="bi bi-person me-2"></i> Profil Saya</a></li>
                            <li><a class="dropdown-item" href="{{ route('customer.bargains.index') }}"><i class="bi bi-tags me-2"></i> Riwayat Tawaran</a></li>
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="bi bi-bag-check me-2"></i> Riwayat Pesanan</a></li>
                            
                            {{-- RIWAYAT BARTER DENGAN NOTIF INTERNAL --}}
                            <li>
                                <a class="dropdown-item d-flex justify-content-between align-items-center" href="{{ route('barter.inbox') }}">
                                    <span><i class="bi bi-arrow-left-right me-2"></i> Riwayat Barter</span>
                                    @if($unreadBarter > 0)
                                        <span class="badge rounded-pill bg-danger" style="font-size: 0.7rem;">{{ $unreadBarter }} Baru</span>
                                    @endif
                                </a>
                            </li>
                            
                            <li><hr class="dropdown-divider opacity-50"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <a class="dropdown-item text-danger fw-medium" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-dark rounded-pill px-4 btn-sm fw-bold shadow-sm">Masuk</a>
                @endauth
            </div>
        </div>
    </div>
</nav>