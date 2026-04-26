@extends('layouts.main')

@section('content')
<div class="container my-5">
    <h1 class="display-5 mb-4" style="font-family: 'Playfair Display', serif;">Keranjang Belanja Anda</h1>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px;">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Notifikasi Peringatan --}}
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius: 12px; background-color: #fff3cd; color: #856404;">
            <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                <div>
                    <strong>Waduh, Maaf Banget!</strong><br>
                    {{ session('warning') }}
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($cartItems->count() > 0)
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Produk</th>
                        <th scope="col">Harga</th>
                        <th scope="col">Kuantitas</th>
                        <th scope="col" class="text-end">Subtotal</th>
                        <th scope="col" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach($cartItems as $item)
                        @php 
                            $subtotal = $item->harga * $item->kuantitas;
                            $total += $subtotal;
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    {{-- Menggunakan path langsung dari database --}}
                                    <img src="{{ asset($item->product->foto_produk) }}" width="80" height="80" class="rounded shadow-sm me-3" style="object-fit: cover;" alt="{{ $item->product->nama_produk }}" onerror="this.src='{{ asset('products/default.jpg') }}'">
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $item->product->nama_produk }}</h6>
                                        @if($item->is_bargain)
                                            <span class="badge bg-success small" style="font-size: 0.7rem;">Harga Negosiasi</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                            <td>
                                <input type="number" value="{{ $item->kuantitas }}" class="form-control form-control-sm text-center" readonly style="width: 50px;">
                            </td>
                            <td class="text-end fw-bold">
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" onsubmit="return confirm('Hapus produk ini dari keranjang?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5" class="text-end py-4">
                            <h4 class="mb-0">Total: <span class="text-danger">Rp {{ number_format($total, 0, ',', '.') }}</span></h4>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5" class="text-end border-0">
                            <a href="{{ route('shop') }}" class="btn btn-outline-secondary me-2">
                                <i class="bi bi-arrow-left"></i> Kembali Belanja
                            </a>
                            <a href="{{ route('checkout.index') }}" class="btn btn-dark px-4">
                                Checkout <i class="bi bi-arrow-right"></i>
                            </a>                    
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @else
        <div class="alert alert-light text-center py-5 border shadow-sm" style="border-radius: 15px;">
            <i class="bi bi-cart-x text-muted" style="font-size: 3rem;"></i>
            <p class="mt-3 text-muted">Keranjang Anda masih kosong.</p>
            <a href="{{ route('shop') }}" class="btn btn-dark px-4">Lihat Koleksi Produk</a>
        </div>
    @endif 
</div>
@endsection