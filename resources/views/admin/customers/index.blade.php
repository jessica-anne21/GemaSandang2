@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1" style="font-family: 'Playfair Display', serif; color: #600000; font-weight: 800;">
                Kelola Pelanggan
            </h1>
            <p class="text-muted small mb-0">Manajemen data pengguna dan status verifikasi identitas Gema Sandang.</p>
        </div>
    </div>

    {{-- ALERT NOTIFICATION --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" style="border-radius: 12px; background-color: #fff5f5; color: #c0392b;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 mb-4" role="alert" style="border-radius: 12px;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- TABLE CARD --}}
    <div class="card shadow-sm border-0" style="border-radius: 1rem; overflow: hidden; background: #ffffff;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead style="background-color: #fcf8f8;">
                        <tr class="text-muted small text-uppercase" style="letter-spacing: 1px;">
                            <th class="p-3 border-0 ps-4">ID</th>
                            <th class="p-3 border-0">Pelanggan</th>
                            <th class="p-3 border-0">Status Verifikasi</th>
                            <th class="p-3 border-0">Kontak</th>
                            <th class="p-3 border-0 text-center">Bergabung</th>
                            <th class="p-3 border-0 text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($customers as $customer)
                            <tr style="transition: 0.3s;">
                                <td class="p-3 ps-4 fw-bold text-muted" style="font-size: 0.85rem;">#{{ $customer->id }}</td>
                                <td class="p-3">
                                    <div class="d-flex align-items-center">
                                        {{-- AVATAR GRADIENT --}}
                                        <div class="rounded-circle d-flex justify-content-center align-items-center me-3 shadow-sm text-white" 
                                             style="width: 45px; height: 45px; font-size: 1.1rem; font-weight: bold; background: linear-gradient(135deg, #600000 0%, #400000 100%);">
                                            {{ substr($customer->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">{{ $customer->name }}</div>
                                            <small class="text-muted">@<span>{{ $customer->username ?? 'user'.$customer->id }}</span></small>
                                        </div>
                                    </div>
                                </td>
                                
                                {{-- LOGIKA BADGE VERIFIKASI --}}
                                <td class="p-3">
                                    @if($customer->verification && $customer->verification->status == 'verified')
                                        <span class="badge rounded-pill px-3 py-2" style="background-color: #e6f4ea; color: #1e7e34; border: 1px solid #c3e6cb; font-size: 0.75rem;">
                                            <i class="bi bi-patch-check-fill me-1"></i> Verified
                                        </span>
                                    @elseif($customer->verification && $customer->verification->status == 'pending')
                                        <span class="badge rounded-pill px-3 py-2" style="background-color: #fff8e1; color: #856404; border: 1px solid #ffeeba; font-size: 0.75rem;">
                                            <i class="bi bi-hourglass-split me-1"></i> Waiting
                                        </span>
                                    @else
                                        <span class="badge rounded-pill px-3 py-2" style="background-color: #f8f9fa; color: #6c757d; border: 1px solid #dee2e6; font-size: 0.75rem;">
                                            <i class="bi bi-dash-circle me-1"></i> Unverified
                                        </span>
                                    @endif
                                </td>

                                <td class="p-3">
                                    <div class="d-flex flex-column" style="font-size: 0.85rem;">
                                        <span class="mb-1 text-dark"><i class="bi bi-envelope me-2 text-muted"></i>{{ $customer->email }}</span>
                                        <span class="text-muted"><i class="bi bi-telephone me-2"></i>{{ $customer->nomor_hp ?? '-' }}</span>
                                    </div>
                                </td>
                                
                                <td class="p-3 text-center">
                                    <span class="text-muted small">
                                        {{ $customer->created_at->format('d M Y') }}
                                    </span>
                                </td>

                                <td class="p-3 text-end pe-4">
                                    <a href="{{ route('admin.customers.show', $customer->id) }}" 
                                       class="btn btn-sm px-3 fw-600 btn-action-custom" 
                                       style="border: 1.5px solid #600000; color: #600000; border-radius: 50px; font-size: 0.8rem; transition: 0.3s; font-weight: 700; text-decoration: none; display: inline-block;">
                                        DETAIL <i class="bi bi-arrow-right ms-1"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center opacity-50">
                                        <i class="bi bi-people" style="font-size: 3rem; color: #600000;"></i>
                                        <p class="mt-2 fw-bold">Belum ada pelanggan terdaftar.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- PAGINATION --}}
        @if($customers->hasPages())
            <div class="card-footer bg-white border-0 py-4">
                <div class="d-flex justify-content-center">
                    {{ $customers->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

{{-- CSS KHUSUS ADMIN --}}
<style>
    /* Styling Pagination biar warnanya #600000 */
    .pagination .page-item.active .page-link {
        background-color: #600000 !important;
        border-color: #600000 !important;
        color: #fff !important;
    }
    .page-link {
        color: #600000 !important;
    }

    /* Hover effect buat baris tabel */
    .table-hover tbody tr:hover {
        background-color: #fdf8f8 !important;
    }

    /* Hover effect buat tombol detail */
    .btn-action-custom:hover {
        background-color: #600000 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 10px rgba(96, 0, 0, 0.2);
    }

    /* Card Shadow Soft */
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
    }
</style>
@endsection