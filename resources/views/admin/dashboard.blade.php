@extends('layouts.admin')

@section('content')

<div class="mb-4">
    <h1 class="h3 mb-0" style="font-family: 'Playfair Display', serif; color: #800000;">Selamat Datang, {{ Auth::user()->name }}!</h1>
    <p class="text-muted">Berikut adalah ringkasan aktivitas toko Gema Sandang hari ini.</p>
</div>

<div class="row">
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card stat-card h-100 shadow-sm border-0" style="border-radius: 1rem; border-left: 5px solid #0d6efd !important;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title text-uppercase text-muted small mb-1">Total Produk</h5>
                    <span class="fs-2 fw-bold text-dark">{{ $totalProducts }}</span>
                </div>
                <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                    <i class="bi bi-box-seam fs-3 text-primary"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card stat-card h-100 shadow-sm border-0" style="border-radius: 1rem; border-left: 5px solid #198754 !important;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title text-uppercase text-muted small mb-1">Total Pendapatan</h5>
                    <span class="fs-3 fw-bold text-success">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                </div>
                <div class="rounded-circle bg-success bg-opacity-10 p-3">
                    <i class="bi bi-cash-stack fs-3 text-success"></i>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-12 mb-4">
        <div class="card stat-card h-100 shadow-sm border-0" style="border-radius: 1rem; border-left: 5px solid #0dcaf0 !important;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="card-title text-uppercase text-muted small mb-1">Total Pelanggan</h5>
                    <span class="fs-2 fw-bold text-dark">{{ $totalCustomers }}</span>
                </div>
                <div class="rounded-circle bg-info bg-opacity-10 p-3">
                    <i class="bi bi-people fs-3 text-info"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm border-0" style="border-radius: 1rem;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">Laporan Penjualan (30 Hari)</h5>
            </div>
            <div class="card-body" style="position: relative; height: 350px;"> 
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm border-0 h-100" style="border-radius: 1rem;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold">Kategori Terlaris</h5>
            </div>
            <div class="card-body pt-0">
                @forelse($topCategories as $cat)
                    <div class="d-flex align-items-center mb-3 p-2 rounded-3 bg-light bg-opacity-50">
                        <div class="bg-white rounded shadow-sm d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px;">
                            <i class="bi bi-tag text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-0 fw-bold text-dark small">{{ $cat->nama_kategori }}</h6>
                            <small class="text-muted">{{ $cat->total_terjual }} terjual</small>
                        </div>
                        <span class="badge rounded-pill bg-primary">#{{ $loop->iteration }}</span>
                    </div>
                @empty
                    <p class="text-center text-muted small py-4">Belum ada data.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0" style="border-radius: 1rem;">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold small">Tawaran Terbaru</h5>
                <a href="{{ route('admin.bargains.index') }}" class="btn btn-sm btn-link p-0 text-decoration-none small">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-3">Produk</th>
                            <th>Tawaran</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBargains as $bargain)
                            <tr>
                                <td>{{ $bargain->product->nama_produk }}</td>
                                <td>{{ $bargain->user->name }}</td>
                                <td class="text-danger fw-bold">Rp {{ number_format($bargain->harga_tawaran, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.bargains.index') }}" class="btn btn-sm btn-dark rounded-pill">Cek</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">
                                    <i class="bi bi-info-circle me-1"></i> Belum ada tawaran baru yang masuk.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card shadow-sm border-0" style="border-radius: 1rem;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-bold small">Pesanan Terbaru</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 small">
                    <tbody>
                        @foreach ($recentOrders as $order)
                        <tr onclick="window.location='{{ route('admin.orders.show', $order->id) }}'" style="cursor: pointer;">
                            <td class="ps-3">
                                <strong>ORD#{{ $order->id }}</strong><br>
                                <small>{{ $order->user->name }}</small>
                            </td>
                            <td class="text-end pe-3">
                                <span class="badge rounded-pill {{ $order->status == 'selesai' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartLabels = @json($labels ?? []);
    const chartData = @json($data ?? []);
    
    const ctx = document.getElementById('salesChart').getContext('2d');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartLabels,
            datasets: [{
                label: 'Penjualan',
                data: chartData,
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.05)',
                pointBackgroundColor: '#0d6efd',
                pointRadius: 5,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (context.parsed.y !== null) {
                                label += ': ' + new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: { grid: { display: false } },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            onHover: (e, elements) => {
                e.native.target.style.cursor = elements.length ? 'pointer' : 'default';
            },
            onClick: (e, elements) => {
                if (elements.length > 0) {
                    const index = elements[0].index;
                    const dateFilter = chartLabels[index]; 
                    
                    const redirectUrl = `{{ route('admin.orders.index') }}?date=${dateFilter}`;
                    window.location.href = redirectUrl;
                }
            }
        }
    });
</script>
@endpush