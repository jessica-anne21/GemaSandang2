@extends('layouts.main')

@section('content')
<div class="container my-5">
    <h3 class="mb-4" style="font-family: 'Playfair Display', serif;">
        Riwayat Pesanan Saya
    </h3>

    @if($orders->isEmpty())
        <div class="alert alert-info shadow-sm border-0">
            <i class="bi bi-info-circle me-2"></i> Anda belum memiliki riwayat pesanan.
        </div>
    @else
        <div class="card border-0 shadow-sm" style="border-radius: 10px; overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary small text-uppercase">
                        <tr>
                            <th class="p-3 ps-4">Produk</th>
                            <th class="p-3">ID Pesanan</th>
                            <th class="p-3">Status</th>
                            <th class="p-3">Total Belanja</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td class="p-3 ps-4">
                                    <div class="d-flex align-items-center">
                                        @php
                                            $firstItem = $order->items->first();
                                        @endphp
                                        
                                        @if($firstItem && $firstItem->product && $firstItem->product->foto_produk)
                                            <img src="{{ asset($firstItem->product->foto_produk) }}" 
                                                 alt="Produk" 
                                                 class="rounded me-3 shadow-sm border" 
                                                 style="width: 50px; height: 50px; object-fit: cover;"
                                                 onerror="this.src='{{ asset('images/default.jpg') }}'">
                                        @else
                                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center border" style="width: 50px; height: 50px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                        
                                        <div>
                                            <div class="fw-bold text-dark">
                                                @if($order->items->count() > 1)
                                                    {{ $firstItem->product->nama_produk ?? 'Produk' }} (+{{ $order->items->count() - 1 }} lainnya)
                                                @else
                                                    {{ $firstItem->product->nama_produk ?? 'Produk tidak tersedia' }}
                                                @endif
                                            </div>
                                            <small class="text-muted">
                                                {{ $order->created_at->format('d M Y') }}
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                <td class="p-3 fw-bold text-secondary">
                                    #{{ $order->id }}
                                </td>

                                <td class="p-3">
                                    @if($order->status == 'menunggu_pembayaran')
                                        <span class="badge bg-warning text-dark rounded-pill px-3">Menunggu Bayar</span>
                                    @elseif($order->status == 'menunggu_konfirmasi')
                                        <span class="badge bg-info text-dark rounded-pill px-3">Menunggu Konfirmasi</span>
                                    @elseif($order->status == 'diproses')
                                        <span class="badge bg-secondary text-white rounded-pill px-3">Diproses</span>
                                    @elseif($order->status == 'dikirim')
                                        <span class="badge bg-primary rounded-pill px-3">Dikirim</span>
                                    @elseif($order->status == 'selesai')
                                        <span class="badge bg-success rounded-pill px-3">Selesai</span>
                                    @elseif($order->status == 'dibatalkan')
                                        <span class="badge bg-danger rounded-pill px-3">Dibatalkan</span>
                                    @endif
                                </td>

                                <td class="p-3 fw-bold text-dark">
                                    Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                </td>

                                <td class="p-3 text-center">
                                    <div class="d-flex flex-column gap-2 justify-content-center">
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                                            Detail Pesanan
                                        </a>
                                        
                                        @if($order->status == 'menunggu_pembayaran')
                                            <a href="{{ route('checkout.success', $order->id) }}" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                                                <i class="bi bi-wallet2 me-1"></i> Bayar
                                            </a>
                                        @elseif($order->status == 'dikirim')
                                            <form action="{{ route('orders.complete', $order->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm" onclick="return confirm('Apakah barang sudah benar-benar diterima?')">
                                                    Pesanan Diterima
                                                </button>
                                            </form>
                                        @endif
                                    </div>
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