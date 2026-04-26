@extends('layouts.main')

@section('content')
<style>
    .btn-maroon-pop {
        background-color: #800000;
        transition: all 0.3s ease;
    }
    .btn-maroon-pop:hover {
        background-color: #a00000; 
        transform: translateY(-3px); 
        shadow: 0 10px 20px rgba(0,0,0,0.2);
    }
    
    .link-kembali {
        transition: color 0.3s ease;
    }
    .link-kembali:hover {
        color: #444 !important; 
        text-decoration: underline !important;
    }
</style>

<div class="container my-5">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5" style="font-family: 'Playfair Display', serif;">Checkout</h1>
        </div>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-lg-7 mb-4">
                <div class="card border-0 shadow-sm p-4" style="border-radius: 15px;">
                    <h4 class="mb-4" style="color: #800000; font-family: 'Playfair Display', serif;">Detail Pengiriman</h4>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Nama Penerima</label>
                            <input type="text" name="nama_penerima" class="form-control border" value="{{ Auth::user()->name }}" style="border-radius: 8px;" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Nomor WhatsApp / HP</label>
                            <input type="text" name="nomor_hp" class="form-control" value="{{ Auth::user()->nomor_hp }}" placeholder="Contoh: 0812..." required style="border-radius: 8px;">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted small text-uppercase fw-bold">Alamat Lengkap</label>
                        <textarea name="alamat_pengiriman" class="form-control" rows="4" placeholder="Nama Jalan, No Rumah, Kecamatan, Kota, Kode Pos" required style="border-radius: 8px;">{{ Auth::user()->alamat }}</textarea>
                    </div>

                    <hr class="my-4" style="opacity: 0.1;">

                    <h4 class="mb-4" style="color: #800000; font-family: 'Playfair Display', serif;">Opsi Pengiriman</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Pilih Kurir</label>
                            <select name="kurir" class="form-select" required style="border-radius: 8px;">
                                <option value="" disabled selected>Pilih Kurir</option>
                                <option value="jne">JNE (Regular)</option>
                                <option value="jnt">J&T Express</option>
                                <option value="sicepat">SiCepat</option>
                                <option value="grab">GrabExpress</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-muted small text-uppercase fw-bold">Biaya Ongkir</label>
                            <select name="ongkir" class="form-select" id="ongkirSelect" required style="border-radius: 8px;">
                                <option value="" disabled selected>Pilih lokasi tujuan</option>
                                <option value="20000">Bandung - Rp 20.000</option>
                                <option value="35000">Luar Bandung - Rp 35.000</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="form-label text-muted small text-uppercase fw-bold">Catatan (Opsional)</label>
                        <textarea name="catatan_customer" class="form-control" rows="2" placeholder="Contoh: Tolong packing yang aman ya" style="border-radius: 8px;"></textarea>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm p-4" style="background-color: #fafafa; border-radius: 15px;">
                    <h4 class="mb-4" style="color: #800000; font-family: 'Playfair Display', serif;">Ringkasan Pesanan</h4>
                    
                    <div class="checkout-items mb-4" style="max-height: 300px; overflow-y: auto;">
                        @php $subtotal = 0; @endphp
                        @foreach($cartItems as $item)
                            @php 
                                $itemTotal = $item->harga * $item->kuantitas;
                                $subtotal += $itemTotal; 
                            @endphp
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset($item->product->foto_produk) }}" class="rounded" width="60" height="60" style="object-fit: cover;" onerror="this.src='{{ asset('products/default.jpg') }}'">
                                    <div class="ms-3">
                                        <h6 class="mb-0 fw-bold" style="font-size: 0.9rem;">{{ $item->product->nama_produk }}</h6>
                                        <small class="text-muted">Rp {{ number_format($item->harga, 0, ',', '.') }}</small>
                                    </div>
                                </div>
                                <span class="fw-bold">Rp {{ number_format($itemTotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between mb-2 text-muted">
                        <span>Subtotal</span>
                        <span class="fw-bold text-dark">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="text-muted">Ongkos Kirim</span>
                        <span id="ongkirDisplay" class="text-success fw-bold">Rp 0 (Pilih Kurir)</span>
                    </div>
                    
                    <hr style="opacity: 0.1;">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <span class="h5 mb-0" style="color: #444;">Total Pembayaran</span>
                        <span class="h4 fw-bold mb-0" id="totalDisplay" style="color: #800000;">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>

                    <button type="submit" class="btn btn-maroon-pop w-100 py-3 mb-3 shadow-sm text-white fw-bold d-flex align-items-center justify-content-center" 
                            style="border-radius: 50px; border: none; letter-spacing: 1px;">
                        BUAT PESANAN <i class="bi bi-arrow-right ms-2"></i>
                    </button>

                    <div class="text-center">
                        <a href="{{ route('shop') }}" class="link-kembali text-decoration-none small text-muted d-flex align-items-center justify-content-center">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Keranjang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('ongkirSelect').addEventListener('change', function() {
        let ongkir = parseInt(this.value);
        let subtotal = {{ $subtotal }};
        let total = subtotal + ongkir;
        document.getElementById('ongkirDisplay').innerText = 'Rp ' + ongkir.toLocaleString('id-ID');
        document.getElementById('totalDisplay').innerText = 'Rp ' + total.toLocaleString('id-ID');
    });
</script>
@endsection