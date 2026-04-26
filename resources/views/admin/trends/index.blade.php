@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1" style="font-family: 'Playfair Display', serif; color: #600000; font-weight: 800;">
                Manajemen Tren Fashion
            </h1>
            <p class="text-muted small mb-0">Kelola data hasil scraping.</p>
        </div>
    </div>

    {{-- IMPORT SECTION --}}
    <div id="importSection" class="card shadow-sm border-0 mb-4" style="border-radius: 1rem; overflow: hidden;">
        <div class="card-header border-0 pt-3 pb-0" style="background-color: #fcf8f8;">
            <h6 class="fw-bold text-muted text-uppercase small" style="letter-spacing: 1px;">
                <i class="bi bi-cloud-arrow-up-fill me-2" style="color: #600000;"></i> Import Hasil Scraping (CSV)
            </h6>
        </div>
        <div class="card-body p-4">
            <form action="{{ route('admin.trends.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="small text-muted mb-1 fw-bold">File CSV</label>
                        <input type="file" name="csv_file" class="form-control border-0 bg-light" style="border-radius: 0.5rem;" required>
                    </div>
                    <div class="col-md-4">
                        <label class="small text-muted mb-1 fw-bold">Sumber Brand</label>
                        <select name="sumber" class="form-select border-0 bg-light" style="border-radius: 0.5rem;" required>
                            <option value="">-- Pilih Sumber --</option>
                            <option value="Uniqlo">Uniqlo</option>
                            <option value="Zara">Zara</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn w-100 fw-bold btn-custom-action" style="background-color: #600000; color: white; border-radius: 0.5rem; padding: 10px;">
                            <i class="bi bi-cpu-fill me-2"></i> PROSES DATA
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4 alert-dismissible fade show" style="border-radius: 10px;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- TABLE SECTION --}}
    <div class="card shadow-sm border-0" style="border-radius: 1rem; overflow: hidden;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background-color: #fcf8f8;">
                    <tr class="text-uppercase small fw-bold text-muted" style="letter-spacing: 0.5px;">
                        <th class="p-3 ps-4 border-0">Koleksi Tren</th>
                        <th class="p-3 text-center border-0">Sumber</th>
                        <th class="p-3 text-center border-0">Style/Material/Color</th>
                        <th class="p-3 text-center border-0">Pop. Score</th>
                        <th class="p-3 text-center border-0">Status</th>
                        <th class="p-3 text-center border-0">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trends as $t)
                        <tr>
                            <td class="p-3 ps-4">
                                <div class="d-flex align-items-center">
                                    <div style="position: relative;">
                                        <img src="{{ $t->gambar }}" 
                                             class="rounded-3 shadow-sm border me-3" 
                                             style="width: 60px; height: 75px; object-fit: cover;"
                                             onerror="this.src='https://placehold.co/400x600?text=No+Img';">
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark mb-0" style="font-size: 0.9rem;">{{ \Illuminate\Support\Str::limit($t->judul, 35) }}</div>
                                        <small class="text-muted" style="font-size: 0.75rem;">ID: #TRN-{{ $t->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="p-3 text-center">
                                @if($t->link_sumber)
                                    <a href="{{ $t->link_sumber }}" target="_blank" class="badge bg-light text-dark px-3 py-2 rounded-pill shadow-sm" style="text-decoration: none; border: 1px solid #eee;">
                                        {{ $t->sumber }} <i class="bi bi-box-arrow-up-right ms-1" style="font-size: 0.6rem; color: #600000;"></i>
                                    </a>
                                @else
                                    <span class="badge bg-light text-dark px-3 py-2 rounded-pill">{{ $t->sumber }}</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <div class="mb-1">
                                    <span class="badge bg-opacity-10 text-dark px-2 py-1" style="background-color: #f3e5f5; font-size: 0.7rem;">{{ $t->style ?? 'No Style' }}</span>
                                </div>
                                <div class="mb-1">
                                    <span class="badge bg-opacity-10 text-dark px-2 py-1" style="background-color: #e3f2fd; font-size: 0.7rem;">{{ $t->material ?? 'No Material' }}</span>
                                </div>
                                <div>
                                    <span class="badge bg-opacity-10 text-dark px-2 py-1" style="background-color: #fff3e0; font-size: 0.7rem;">{{ $t->warna ?? 'No Color' }}</span>
                                </div>
                            </td>
                            <td class="p-3 text-center">
                                <div class="fw-bold text-dark" style="font-size: 1.1rem;">{{ $t->skor_popularitas }}</div>
                                <small class="text-muted uppercase" style="font-size: 0.65rem;">Interaksi</small>
                            </td>
                            <td class="p-3 text-center">
                                @if($t->status == 'Published')
                                    <span class="badge bg-success rounded-pill px-3 py-2" style="font-size: 0.65rem; letter-spacing: 0.5px;">PUBLISHED</span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-50 rounded-pill px-3 py-2" style="font-size: 0.65rem; letter-spacing: 0.5px;">DRAFT</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.trends.edit', $t->id) }}" 
                                       class="btn btn-sm btn-outline-dark rounded-circle d-flex align-items-center justify-content-center" 
                                       style="width: 35px; height: 35px;" title="Kurasi & Metadata">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    
                                    <form action="{{ route('admin.trends.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus tren ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                            <i class="bi bi-trash3-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center p-5">
                                <div class="opacity-50">
                                    <i class="bi bi-cloud-slash fs-1" style="color: #600000;"></i>
                                    <p class="mt-2 italic">Belum ada data tren hasil scraping.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .btn-custom-action:hover {
        background-color: #450000 !important;
        transform: translateY(-2px);
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(96, 0, 0, 0.3);
    }
    
    .table tbody tr:hover {
        background-color: #fdfafb !important;
    }

    .badge {
        font-weight: 600;
    }
</style>
@endsection