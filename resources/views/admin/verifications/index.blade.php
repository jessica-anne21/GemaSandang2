@extends('layouts.admin') {{-- Pastikan kamu punya layout khusus admin --}}

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 style="font-family: 'Playfair Display', serif;color: #800000;">Verifikasi Identitas User</h2>
        <span class="badge bg-soft-primary text-primary">{{ $verifications->count() }} Menunggu Review</span>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm">{{ session('success') }}</div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">User</th>
                            <th>NIK</th>
                            <th>Dokumen</th>
                            <th>Tanggal Daftar</th>
                            <th class="text-end pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($verifications as $v)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="fw-bold">{{ $v->user->name }}</div>
                                </div>
                                <small class="text-muted">{{ $v->user->email }}</small>
                            </td>
                            <td><code class="text-dark">{{ $v->nik }}</code></td>
                            <td>
                                <a href="{{ asset('storage/' . $v->ktp_path) }}" target="_blank" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i> Lihat KTP
                                </a>
                            </td>
                            <td>{{ $v->created_at->format('d M Y') }}</td>
                            <td class="text-end pe-4">
                                <form action="{{ route('admin.verify.approve', $v->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success px-3 shadow-sm">Approve</button>
                                </form>

                                <button type="button" class="btn btn-sm btn-outline-danger px-3 ms-1" 
                                        data-bs-toggle="modal" data-bs-target="#rejectModal{{ $v->id }}">
                                    Reject
                                </button>

                                <div class="modal fade" id="rejectModal{{ $v->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow">
                                            <form action="{{ route('admin.verify.reject', $v->id) }}" method="POST">
                                                @csrf
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title fw-bold">Tolak Verifikasi</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-start">
                                                    <p class="text-muted">Kenapa KTP <strong>{{ $v->user->name }}</strong> ditolak?</p>
                                                    <textarea name="reason" class="form-control" rows="3" placeholder="Contoh: Foto blur atau NIK tidak sesuai." required></textarea>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-danger px-4">Kirim Penolakan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                Belum ada antrean verifikasi nih! 
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection