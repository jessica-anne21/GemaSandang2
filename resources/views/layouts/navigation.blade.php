<nav class="navbar navbar-expand-lg navbar-custom sticky-top">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">Gema Sandang</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav mx-auto">
        <li class="nav-item"><a class="nav-link" href="{{ route('home') }}">Beranda</a></li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle {{ request()->is('kategori/*') ? 'active' : '' }}" href="#" id="navbarDropdownKategori" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Kategori
            </a>
            <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDropdownKategori">
                @foreach ($categories as $category)
                    <li>
                        <a class="dropdown-item" href="{{ route('category.show', $category->id) }}">
                            {{ $category->nama_kategori }}
                        </a>
                    </li>
                @endforeach
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="{{ route('shop') }}">Lihat Semua Produk</a>
                </li>
            </ul>
        </li>
        <li class="nav-item"><a class="nav-link" href="{{ route('about') }}">Tentang</a></li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Kontak</a>
        </li>
      </ul>

      <div class="d-flex align-items-center">
        {{-- BAGIAN KERANJANG - SUDAH FIX DATABASE --}}
        <a href="{{ route('cart.index') }}" class="nav-link position-relative me-3">
            <i class="bi bi-cart fs-4"></i>
            @auth
                @php
                    $cartCount = \App\Models\Cart::where('user_id', auth()->id())->count();
                @endphp
                @if($cartCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.7rem;">
                        {{ $cartCount }}
                        <span class="visually-hidden">produk di keranjang</span>
                    </span>
                @endif
            @endauth
        </a> 
                
        @auth
            <div class="nav-item dropdown position-relative">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                
                @if(isset($badgeOrders) && isset($badgeBargains) && ($badgeOrders + $badgeBargains > 0))
                    <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle" style="margin-left: -10px; margin-top: 10px;">
                        <span class="visually-hidden">New alerts</span>
                    </span>
                @endif
              </a>
              
              <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm" aria-labelledby="navbarDropdown">
                <li>
                    <a class="dropdown-item" href="{{ route('profile.my-profile') }}">Profil Saya</a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item d-flex justify-content-between align-items-center" href="{{ route('customer.bargains.index') }}">
                        Riwayat Tawaran
                        @if(isset($badgeBargains) && $badgeBargains > 0)
                            <span class="badge bg-danger rounded-pill ms-2">{{ $badgeBargains }}</span>
                        @endif
                    </a>
                </li>
                <li>
                    <a class="dropdown-item d-flex justify-content-between align-items-center" href="{{ route('orders.index') }}">
                        Riwayat Pesanan
                        @if(isset($badgeOrders) && $badgeOrders > 0)
                            <span class="badge bg-danger rounded-pill ms-2">{{ $badgeOrders }}</span>
                        @endif
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                            Keluar
                        </a>
                    </form>
                </li>
              </ul>
            </div>
        @else
            <a href="{{ route('login') }}" class="nav-link"><i class="bi bi-box-arrow-in-right me-1"></i> Masuk</a>
        @endauth
      </div>
    </div>
  </div>
</nav>