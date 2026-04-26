@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0" style="font-family: 'Playfair Display', serif; color: #600000; font-weight: 800;">
                Profil Pelanggan
            </h1>
            <p class="text-muted small mb-0">Informasi detail, status identitas, dan riwayat belanja.</p>
        </div>
        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="row g-4">
        {{-- SIDEBAR PROFIL (KIRI) --}}
        <div class="col-lg-4">
            {{-- INFO DASAR --}}
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 1rem;">
                <div class="card-body text-center p-4">
                    <div class="rounded-circle text-white d-flex justify-content-center align-items-center mx-auto mb-3 shadow" 
                         style="width: 100px; height: 100px; font-size: 3rem; font-weight: bold; background: linear-gradient(135deg, #600000 0%, #400000 100%);">
                        {{ substr($customer->name, 0, 1) }}
                    </div>
                    <h5 class="fw-bold mb-1" style="font-family: 'Playfair Display', serif;">{{ $customer->name }}</h5>
                    <p class="text-muted small mb-0">@<span>{{ $customer->username ?? 'user'.$customer->id }}</span></p>
                    
                    <hr class="my-4 opacity-10">
                    
                    <div class="text-start px-2">
                        <div class="mb-3">
                            <label class="small text-muted fw-bold text-uppercase d-block mb-1">Email</label>
                            <span class="text-dark"><i class="bi bi-envelope me-2 text-muted"></i>{{ $customer->email }}</span>
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted fw-bold text-uppercase d-block mb-1">Nomor HP</label>
                            <span class="text-dark"><i class="bi bi-telephone me-2 text-muted"></i>{{ $customer->nomor_hp ?? '-' }}</span>
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted fw-bold text-uppercase d-block mb-1">Alamat Utama</label>
                            <span class="text-dark d-block bg-light p-2 rounded border-0 small">
                                <i class="bi bi-geo-alt me-1 text-muted"></i> {{ $customer->alamat ?? 'Belum mengatur alamat' }}
                            </span>
                        </div>
                        <div>
                            <label class="small text-muted fw-bold text-uppercase d-block mb-1">Bergabung Sejak</label>
                            <span class="text-dark"><i class="bi bi-calendar3 me-2 text-muted"></i>{{ $customer->created_at->format('d F Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- VERIFIKASI IDENTITAS --}}
            <div class="card shadow-sm border-0 mb-4" style="border-radius: 1rem;">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h6 class="fw-bold text-uppercase" style="color: #600000; font-size: 0.8rem; letter-spacing: 1px;">
                        <i class="bi bi-shield-check me-2"></i> Verifikasi Identitas
                    </h6>
                </div>
                <div class="card-body p-4">
                    @if($customer->verification)
                        <div class="mb-3">
                            <label class="small text-muted d-block">Status Verifikasi:</label>
                            @if($customer->verification->status == 'verified')
                                <span class="badge rounded-pill px-3 py-2 mt-1" style="background-color: #e6f4ea; color: #1e7e34; border: 1px solid #c3e6cb;">
                                    <i class="bi bi-patch-check-fill me-1"></i> VERIFIED
                                </span>
                            @elseif($customer->verification->status == 'pending')
                                <span class="badge rounded-pill px-3 py-2 mt-1" style="background-color: #fff8e1; color: #856404; border: 1px solid #ffeeba;">
                                    <i class="bi bi-hourglass-split me-1"></i> WAITING APPROVAL
                                </span>
                            @else
                                <span class="badge rounded-pill px-3 py-2 mt-1 text-danger" style="background-color: #fceaea; border: 1px solid #f5c6cb;">
                                    <i class="bi bi-x-circle-fill me-1"></i> REJECTED
                                </span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted d-block">NIK:</label>
                            <span class="fw-bold text-dark">{{ $customer->verification->nik ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <label class="small text-muted d-block mb-2">Foto KTP:</label>
                            <a href="{{ asset('storage/' . $customer->verification->ktp_path) }}" target="_blank">
                                <img src="{{ asset('storage/' . $customer->verification->ktp_path) }}" 
                                     class="img-fluid rounded shadow-sm border" 
                                     style="max-height: 200px; width: 100%; object-fit: cover; cursor: pointer;"
                                     alt="Foto KTP Pelanggan">
                            </a>
                            <small class="text-muted d-block mt-2 font-italic text-center">*Klik gambar untuk memperbesar</small>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="bi bi-person-x fs-2 text-muted opacity-50"></i>
                            <p class="text-muted small mt-2">Pelanggan belum mengajukan verifikasi identitas.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- TOTAL BELANJA --}}
            <div class="card shadow-sm border-0 text-white" style="border-radius: 1rem; background: linear-gradient(135deg, #600000, #300000);">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-uppercase opacity-75 mb-0" style="letter-spacing: 1px;">Total Belanja</h6>
                        <i class="bi bi-wallet2 fs-4 opacity-50"></i>
                    </div>
                    <h2 class="fw-bold mb-0">Rp {{ number_format($totalSpent, 0, ',', '.') }}</h2>
                    <div class="mt-3 small opacity-75">
                        Total Pesanan Selesai: <strong>{{ $totalPesananSelesai }}</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIWAYAT PESANAN (KANAN) --}}
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 1rem;">
                <div class="card-header bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 fw-bold text-uppercase" style="color: #600000; letter-spacing: 1px;">
                        <i class="bi bi-clock-history me-2"></i> Riwayat Pesanan
                    </h6>
                    <span class="badge bg-light text-dark border-0 rounded-pill px-3 py-2 shadow-sm">
                        Total: {{ $customer->orders->count() }} Transaksi
                    </span>
                </div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-hover">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="p-3 border-0 ps-4">ID Order</th>
                                <th class="p-3 border-0">Tanggal</th>
                                <th class="p-3 border-0">Total</th>
                                <th class="p-3 border-0 text-center">Status</th>
                                <th class="p-3 border-0 text-end pe-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($customer->orders as $order)
                                <tr>
                                    <td class="p-3 ps-4 fw-bold">#{{ $order->id }}</td>
                                    <td class="p-3 small text-muted">{{ $order->created_at->format('d M Y') }}</td>
                                    <td class="p-3 fw-bold text-dark">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                    <td class="p-3 text-center">
                                        @php
                                            $statusColors = [
                                                'menunggu_pembayaran' => ['#856404', '#fff3cd'],
                                                'menunggu_konfirmasi' => ['#0c5460', '#d1ecf1'],
                                                'diproses' => ['#383d41', '#e2e3e5'],
                                                'dikirim' => ['#004085', '#cce5ff'],
                                                'selesai' => ['#155724', '#d4edda'],
                                                'dibatalkan' => ['#721c24', '#f8d7da']
                                            ];
                                            $currColor = $statusColors[$order->status] ?? ['#333', '#eee'];
                                        @endphp
                                        <span class="badge rounded-pill px-3 py-2" 
                                              style="color: {{ $currColor[0] }}; background-color: {{ $currColor[1] }}; font-size: 0.7rem;">
                                            {{ strtoupper(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-end pe-4">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-dark rounded-pill px-3" style="font-size: 0.75rem;">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5 text-muted">
                                        <i class="bi bi-cart-x display-6 d-block mb-3 opacity-25"></i>
                                        Belum ada riwayat pesanan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card { transition: 0.3s; }
    .table-hover tbody tr:hover { background-color: #fcf8f8 !important; }
</style>
@endsection