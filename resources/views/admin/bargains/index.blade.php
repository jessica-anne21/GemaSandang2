@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800" style="font-family: 'Playfair Display', serif; color: #800000;">Kelola Tawaran</h1>

    @if(session('success'))
        <div class="alert alert-success shadow-sm border-0">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0" style="border-radius: 0.75rem;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="p-3 ps-4">ID Produk</th>
                        <th class="p-3 ps-4">Produk</th>
                        <th class="p-3">Pelanggan</th>
                        <th class="p-3">Stok</th> 
                        <th class="p-3">Harga Asli</th>
                        <th class="p-3">Tawaran</th>
                        <th class="p-3 text-center">Status</th>
                        <th class="p-3 text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($bargains as $bargain)
                        <tr>
                            <td class="p-3 ps-4">
                                <div class="fw-bold">#{{ $bargain->product->id }}</div>
                            </td>
                            <td class="p-3 ps-4">
                                <div class="fw-bold">{{ $bargain->product->nama_produk }}</div>
                            </td>
                            <td class="p-3">
                                <div class="small fw-bold">{{ $bargain->user->name }}</div>
                            </td>

                            <td class="p-3">
                                @if($bargain->product->stok > 0)
                                    <span class="badge bg-info text-dark rounded-pill">{{ $bargain->product->stok }} pcs</span>
                                @else
                                    <span class="badge bg-secondary rounded-pill">Habis</span>
                                @endif
                            </td>

                            <td class="p-3">Rp {{ number_format($bargain->product->harga, 0, ',', '.') }}</td>
                            <td class="p-3 fw-bold text-danger">Rp {{ number_format($bargain->harga_tawaran, 0, ',', '.') }}</td>
                            
                            <td class="p-3 text-center">
                                @if($bargain->status == 'pending')
                                    <span class="badge bg-warning text-dark rounded-pill px-3">Pending</span>
                                @elseif($bargain->status == 'accepted')
                                    <span class="badge bg-success rounded-pill px-3">Diterima</span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-3">Ditolak</span>
                                @endif
                            </td>
                            
                            <td class="p-3 text-end pe-4">
                                @if($bargain->status == 'pending')
                                    
                                    @if($bargain->product->stok > 0)
                                        <form action="{{ route('admin.bargains.update', $bargain->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="accepted">
                                            <button type="submit" class="btn btn-sm btn-success rounded-pill px-3" onclick="return confirm('Yakin ingin menerima tawaran ini?')">
                                                <i class="bi bi-check-lg"></i> Terima
                                            </button>
                                        </form>
                                    @else
                                        <button class="btn btn-sm btn-secondary rounded-pill px-3" disabled title="Stok Habis">
                                            <i class="bi bi-x-circle"></i> Habis
                                        </button>
                                    @endif

                                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-3 ms-1" data-bs-toggle="modal" data-bs-target="#rejectModal-{{ $bargain->id }}">
                                        <i class="bi bi-x-lg"></i> Tolak
                                    </button>

                                @else
                                    {{-- JIKA SUDAH DIPROSES, CUMA MUNCUL INFO WAKTU/CATATAN --}}
                                    <div class="text-muted small">
                                        {{ $bargain->updated_at->format('d/m/y H:i') }}
                                        @if($bargain->status == 'rejected' && $bargain->catatan_admin)
                                            <br>
                                            <button type="button" class="btn btn-link btn-sm text-decoration-none p-0 text-muted" data-bs-toggle="modal" data-bs-target="#reasonViewModal-{{ $bargain->id }}">
                                                <i class="bi bi-info-circle"></i> Alasan Penolakan
                                            </button>
                                        @endif
                                    </div>
                                @endif
                            </td>
                        </tr>

                        {{-- MODAL REJECT (TOLAK) --}}
                        <div class="modal fade" id="rejectModal-{{ $bargain->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header bg-danger text-white">
                                        <h5 class="modal-title fs-5">Tolak Tawaran</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('admin.bargains.update', $bargain->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="rejected">
                                        <div class="modal-body text-start">
                                            <p>Anda akan menolak tawaran sebesar <strong>Rp {{ number_format($bargain->harga_tawaran, 0, ',', '.') }}</strong>.</p>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Alasan Penolakan:</label>
                                                <textarea class="form-control" name="catatan_admin" rows="3" required></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger rounded-pill">Kirim Penolakan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- MODAL LIHAT ALASAN (KHUSUS REJECTED) --}}
                        @if($bargain->status == 'rejected' && $bargain->catatan_admin)
                        <div class="modal fade" id="reasonViewModal-{{ $bargain->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-sm">
                                <div class="modal-content">
                                    <div class="modal-header bg-light py-2">
                                        <h6 class="modal-title fw-bold">Catatan Penolakan</h6>
                                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-0 small text-danger">{{ $bargain->catatan_admin }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">Belum ada negosiasi harga masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection