@extends('layouts.main')

@section('content')
<div class="container my-5">
    <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-sm mb-4 shadow-sm rounded-pill px-3">
        <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
    </a>

    <div class="card border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
        <div class="card-header bg-white p-4 border-bottom">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="fw-bold mb-1" style="font-family: 'Playfair Display', serif; color: #800000;">Detail Pesanan #{{ $order->id }}</h4>
                    <p class="text-muted small mb-0">{{ $order->created_at->format('d F Y, H:i') }} WIB</p>
                </div>
                <div>
                    @php
                        $badgeClass = [
                            'menunggu_pembayaran' => 'bg-warning text-dark',
                            'menunggu_konfirmasi' => 'bg-info text-dark',
                            'diproses' => 'bg-secondary text-white',
                            'dikirim' => 'bg-primary',
                            'selesai' => 'bg-success',
                            'dibatalkan' => 'bg-danger'
                        ][$order->status] ?? 'bg-light';
                    @endphp
                    <span class="badge {{ $badgeClass }} px-4 py-2 rounded-pill shadow-sm">
                        {{ strtoupper(str_replace('_', ' ', $order->status)) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            @if($order->status == 'dibatalkan')
                <div class="alert alert-danger d-flex align-items-center mb-4 border-0 shadow-sm" style="border-radius: 10px;">
                    <i class="bi bi-exclamation-octagon-fill fs-3 me-3 text-danger"></i>
                    <div>
                        <strong class="d-block">Pesanan Dibatalkan</strong>
                        <span class="small">Stok produk telah dikembalikan ke sistem. Silakan pesan ulang jika produk masih tersedia.</span>
                    </div>
                </div>
            @endif

            @if($order->nomor_resi && $order->status == 'dikirim')
                <div class="alert alert-primary d-flex align-items-center mb-4 border-0 shadow-sm" style="border-radius: 10px;">
                    <i class="bi bi-truck fs-3 me-3"></i>
                    <div>
                        <strong class="d-block">Paket Sedang Dikirim!</strong>
                        <span class="small">Lacak pesananmu dengan nomor resi: </span><strong class="user-select-all">{{ $order->nomor_resi }}</strong>
                    </div>
                </div>
            @endif

            <div class="row g-4">
                <div class="col-lg-8">
                    <h6 class="fw-bold mb-3 text-uppercase small text-muted" style="letter-spacing: 1px;">Produk yang Dibeli</h6>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light small text-muted text-uppercase">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset($item->product->foto_produk) }}" 
                                                 class="rounded border me-3 shadow-sm" 
                                                 style="width: 65px; height: 65px; object-fit: cover;"
                                                 onerror="this.src='{{ asset('products/default.jpg') }}'">
                                            <div>
                                                <div class="fw-bold" style="font-size: 0.9rem;">{{ $item->product->nama_produk ?? 'Produk Tidak Tersedia' }}</div>
                                                <small class="text-muted">{{ $item->product->category->nama_kategori ?? 'Kategori' }}</small>
                                                
                                                @if($item->harga_saat_beli < ($item->product->harga ?? 0))
                                                    <div class="mt-1">
                                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2" style="font-size: 0.65rem;">
                                                            <i class="bi bi-tags-fill me-1"></i> Harga Negosiasi
                                                        </span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center small">x{{ $item->kuantitas }}</td>
                                    <td class="text-end small text-muted">Rp {{ number_format($item->harga_saat_beli, 0, ',', '.') }}</td>
                                    <td class="text-end fw-bold small">Rp {{ number_format($item->harga_saat_beli * $item->kuantitas, 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="bg-light p-4 rounded-4 shadow-sm h-100 border">
                        {{-- BAGIAN ALAMAT --}}
                        <h6 class="fw-bold mb-3 text-uppercase small text-muted">Informasi Pengiriman</h6>
                        <div class="mb-4">
                            <div class="small text-muted mb-1">Nama Penerima:</div>
                            <div class="fw-bold mb-3" style="font-size: 1.1rem; color: #333;">
                                {{ $order->user->name ?? 'Pelanggan Gema Sandang' }}
                            </div>

                            <div class="small text-muted mb-1">Alamat Lengkap:</div>
                            <div class="small text-secondary mb-3" style="line-height: 1.6;">
                                {{ $order->alamat_pengiriman }}
                            </div>

                            <div class="mt-2">
                                <span class="small text-muted">Metode Pengiriman:</span><br>
                                <span class="badge bg-white text-dark border border-secondary-subtle mt-1 px-3 py-2 fw-normal">
                                    <i class="bi bi-box-seam me-1 text-muted"></i> {{ strtoupper($order->kurir) }}
                                </span>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h6 class="fw-bold mb-3 text-uppercase small text-muted">Ringkasan Biaya</h6>
                        <div class="d-flex justify-content-between mb-2 small text-muted">
                            <span>Subtotal Produk</span>
                            <span>Rp {{ number_format($order->total_harga - $order->ongkir, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 small text-muted">
                            <span>Ongkos Kirim</span>
                            <span>Rp {{ number_format($order->ongkir, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mt-3 fw-bold border-top pt-3">
                            <span class="text-dark">Total Pembayaran</span>
                            <span class="text-danger fs-5">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                        </div>

                        @if($order->status == 'menunggu_pembayaran')
                            <div class="d-grid mt-4">
                                <a href="{{ route('checkout.success', $order->id) }}" class="btn btn-dark rounded-pill py-2 shadow-sm fw-bold">
                                    <i class="bi bi-wallet2 me-2"></i>Bayar Sekarang
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection