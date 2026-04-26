@extends('layouts.main')

@section('content')
<div class="container my-5">
    <h3 class="mb-4" style="font-family: 'Playfair Display', serif;">
        Riwayat Tawaran Saya
    </h3>

    @if($bargains->isEmpty())
        <div class="alert alert-info shadow-sm border-0">
            <i class="bi bi-info-circle me-2"></i> Anda belum pernah melakukan tawaran harga.
        </div>
    @else
        <div class="card border-0 shadow-sm" style="border-radius: 10px; overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary small text-uppercase">
                        <tr>
                            <th class="p-3 ps-4">Produk</th>
                            <th class="p-3">Harga Asli</th>
                            <th class="p-3">Tawaranmu</th>
                            <th class="p-3 text-center">Status</th>
                            <th class="p-3" style="width: 25%;">Pesan Admin</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bargains as $bargain)
                            <tr>
                                <td class="p-3 ps-4">
                                    <div class="d-flex align-items-center">
                                        @if($bargain->product && $bargain->product->foto_produk)
                                            <img src="{{ asset($bargain->product->foto_produk) }}" 
                                                 alt="Produk" 
                                                 class="rounded me-3" 
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                        
                                        <div>
                                            <div class="fw-bold text-dark">
                                                {{ $bargain->product->nama_produk ?? 'Produk tidak tersedia' }}
                                            </div>
                                            <small class="text-muted">
                                                {{ $bargain->created_at->format('d M Y') }}
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <td class="p-3 text-muted text-decoration-line-through">
                                    Rp {{ number_format($bargain->product->harga ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="p-3 text-primary fw-bold">
                                    Rp {{ number_format($bargain->harga_tawaran, 0, ',', '.') }}
                                </td>

                                <td class="p-3 text-center">
                                    @if($bargain->status === 'pending')
                                        <span class="badge bg-warning text-dark rounded-pill px-3">Menunggu</span>
                                    @elseif($bargain->status === 'accepted')
                                        <span class="badge bg-success rounded-pill px-3">Diterima</span>
                                    @else
                                        <span class="badge bg-danger rounded-pill px-3">Ditolak</span>
                                    @endif
                                </td>
                                
                                <td class="p-3">
                                    @if($bargain->status === 'rejected' && $bargain->catatan_admin)
                                        <div class="alert alert-danger p-2 mb-0 small border-0 bg-danger-subtle text-danger">
                                            <i class="bi bi-exclamation-circle-fill me-1"></i> 
                                            {{ $bargain->catatan_admin }}
                                        </div>
                                    @elseif($bargain->status === 'accepted')
                                        <small class="text-success">
                                            <i class="bi bi-check-circle-fill"></i> Selamat! Silakan checkout.
                                        </small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td class="p-3 text-center">
                                    @if($bargain->status === 'accepted')
                                        @php
                                            // 1. Cek Stok Produk
                                            $stokHabis = $bargain->product && $bargain->product->stok < 1;
                                            
                                            // 2. Cek Keranjang (Apakah produk ini sudah ada di session cart)
                                            $cart = session('cart', []);
                                            $isInCart = isset($bargain->product) && array_key_exists($bargain->product->id, $cart);
                                        @endphp

                                        @if($stokHabis)
                                            <button class="btn btn-sm btn-secondary w-100" disabled>
                                                <i class="bi bi-x-circle"></i> Stok Habis
                                            </button>
                                        @elseif($isInCart)
                                            <a href="{{ route('cart.index') }}" class="btn btn-sm btn-info text-white w-100">
                                                <i class="bi bi-cart-check"></i> Lihat Keranjang
                                            </a>
                                        @else
                                            <form action="{{ route('cart.add.bargain') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="bargain_id" value="{{ $bargain->id }}">
                                                <button type="submit" class="btn btn-sm btn-success w-100">
                                                    <i class="bi bi-cart-plus"></i> Beli Sekarang
                                                </button>
                                            </form>
                                        @endif

                                    @elseif($bargain->status === 'rejected')
                                        
                                        @php
                                            $stokHabis = $bargain->product && $bargain->product->stok < 1;
                                        @endphp

                                        @if($stokHabis)
                                            <button class="btn btn-sm btn-secondary w-100" disabled>
                                                <i class="bi bi-x-circle"></i> Stok Habis
                                            </button>
                                        @elseif($bargain->product)
                                            <a href="{{ route('product.show', $bargain->product->id) }}?last_offer={{ $bargain->harga_tawaran }}" 
                                               class="btn btn-sm btn-outline-dark w-100">
                                                <i class="bi bi-arrow-repeat"></i> Tawar Ulang
                                            </a>
                                        @endif

                                    @else
                                        <button class="btn btn-sm btn-secondary w-100" disabled>
                                            Menunggu
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection