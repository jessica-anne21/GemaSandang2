@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0" style="font-family: 'Playfair Display', serif; color: #800000;">Kelola Pesanan</h1>
        <span class="badge bg-light text-dark border p-2" style="border-radius: 0.5rem;">Total: {{ $orders->total() }} Pesanan</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0" style="border-radius: 0.75rem;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="p-3 ps-4 border-0">ID Pesanan</th>
                        <th class="p-3 border-0">Produk</th>
                        <th class="p-3 border-0">Pelanggan</th>
                        <th class="p-3 border-0">Total Harga</th>
                        <th class="p-3 border-0 text-center">Status</th>
                        <th class="p-3 border-0">Tanggal</th>
                        <th class="p-3 border-0 text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="p-3 ps-4 fw-bold">#{{ $order->id }}</td>
                            <td class="p-3">
                                @if($order->items->isNotEmpty())
                                    <div class="fw-bold" style="font-size: 0.9rem;">
                                        {{ $order->items->first()->product->nama_produk }}
                                        @if($order->items->count() > 1)
                                            <span class="text-primary small"> +{{ $order->items->count() - 1 }} lainnya</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="text-muted small">Pesanan Kosong</div>
                                @endif
                            </td>
                            <td class="p-3">
                                <div class="fw-bold" style="font-size: 0.85rem;">{{ $order->user->name ?? 'Guest' }}</div>
                            </td>
                            <td class="p-3">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                            <td class="p-3 text-center">
                                @if($order->status == 'menunggu_pembayaran')
                                    <span class="badge bg-warning text-dark rounded-pill px-3">Menunggu Bayar</span>
                                @elseif($order->status == 'menunggu_konfirmasi')
                                    <span class="badge bg-info text-dark rounded-pill px-3">Cek Bukti</span>
                                @elseif($order->status == 'diproses')
                                    <span class="badge bg-secondary text-white rounded-pill px-3">Diproses</span>
                                @elseif($order->status == 'dikirim')
                                    <span class="badge bg-primary rounded-pill px-3">Dikirim</span>
                                @elseif($order->status == 'selesai')
                                    <span class="badge bg-success rounded-pill px-3">Selesai</span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-3">Dibatalkan</span>
                                @endif
                            </td>
                            <td class="p-3">
                                <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                            </td>
                            <td class="p-3 text-end pe-4">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                    <i class="bi bi-eye me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center p-5 text-muted">
                                <i class="bi bi-inbox display-4 d-block mb-3"></i>
                                Belum ada pesanan masuk.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-footer bg-white border-0 py-4 d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .pagination { margin-bottom: 0; }
    .page-item.active .page-link { background-color: #800000 !important; border-color: #800000 !important; }
    .page-link { color: #800000; }
    .table thead th { letter-spacing: 0.05em; }
</style>
@endsection