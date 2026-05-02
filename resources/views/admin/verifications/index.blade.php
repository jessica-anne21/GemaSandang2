@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 style="font-family: 'Playfair Display', serif; color: #800000; font-weight: bold;">Verifikasi Identitas</h2>
            <p class="text-muted">Review dokumen KTP dan Selfie untuk keamanan barter.</p>
        </div>
        <div class="text-end">
            <span class="badge rounded-pill bg-danger px-3 py-2">
                <i class="bi bi-clock-history"></i> {{ $verifications->count() }} Menunggu Review
            </span>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Main Table Card -->
    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="ps-4 py-3">User</th>
                            <th>NIK</th>
                            <th>KTP</th>
                            <th>Selfie dengan KTP</th>
                            <th>Tanggal Daftar</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($verifications as $v)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-3 bg-soft-maroon text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; background: #800000;">
                                        {{ strtoupper(substr($v->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">{{ $v->user->name }}</div>
                                        <small class="text-muted">{{ $v->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-light text-dark font-monospace">{{ $v->nik }}</span></td>
                            <td>
                                <a href="{{ asset('storage/' . $v->ktp_path) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-card-image"></i> Lihat KTP
                                </a>
                            </td>
                            <td>
                                @if($v->selfie_path)
                                <a href="{{ asset('storage/' . $v->selfie_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-person-bounding-box"></i> Lihat Selfie
                                </a>
                                @else
                                <span class="text-muted small italic">Tidak ada foto selfie</span>
                                @endif
                            </td>
                            <td>{{ $v->created_at->format('d M Y') }}</td>
                            <td class="text-end pe-4">
                                <!-- Approve Form -->
                                <form action="{{ route('admin.verify.approve', $v->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success px-3 rounded-pill shadow-sm" onclick="return confirm('Apakah wajah di KTP dan Selfie sudah sesuai?')">
                                        <i class="bi bi-check-lg"></i> Approve
                                    </button>
                                </form>

                                <!-- Reject Button -->
                                <button type="button" class="btn btn-sm btn-outline-danger px-3 ms-1 rounded-pill" 
                                        data-bs-toggle="modal" data-bs-target="#rejectModal{{ $v->id }}">
                                    <i class="bi bi-x-lg"></i> Reject
                                </button>

                                <!-- Reject Modal -->
                                <div class="modal fade" id="rejectModal{{ $v->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                                            <form action="{{ route('admin.verify.reject', $v->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title fw-bold" style="color: #800000;">Tolak Verifikasi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-start">
                                                    <div class="alert alert-warning small border-0">
                                                        <i class="bi bi-exclamation-triangle-fill"></i> Memberikan alasan yang jelas membantu user untuk memperbaiki dokumen mereka.
                                                    </div>
                                                    <p class="mb-2">Alasan penolakan untuk <strong>{{ $v->user->name }}</strong>:</p>
                                                    <textarea name="reason" class="form-control border-0 bg-light" rows="4" 
                                                              placeholder="Misal: Foto selfie tidak jelas, wajah tidak terlihat, atau data NIK tidak sinkron dengan foto." required></textarea>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger rounded-pill px-4">Kirim Penolakan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <p class="text-muted fw-bold">Belum ada antrian.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-soft-maroon { background-color: rgba(128, 0, 0, 0.1); color: #800000; }
    .table thead th { font-size: 0.85rem; letter-spacing: 0.5px; text-transform: uppercase; }
    .btn-sm { font-size: 0.75rem; font-weight: 600; }
    .card { overflow: hidden; }
</style>
@endsection