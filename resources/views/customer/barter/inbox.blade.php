@extends('layouts.main')

@section('content')
<div class="container py-5" style="background-color: #fdf5f5; min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-lg-11">
            
            @php
                $isVerified = auth()->user()->verification && auth()->user()->verification->status == 'verified';
            @endphp

            @if(!$isVerified)
                {{-- TAMPILAN JIKA USER BELUM VERIFIED --}}
                <div class="text-center py-5 mt-5">
                    <div class="mb-4">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 120px; height: 120px; border: 3px dashed #800000;">
                            <i class="bi bi-shield-lock-fill" style="font-size: 3.5rem; color: #800000;"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold text-dark" style="font-family: 'Playfair Display';">Akses Terbatas</h2>
                    <p class="text-muted mx-auto" style="max-width: 500px;">
                        Halaman riwayat barter hanya bisa diakses oleh user yang sudah terverifikasi identitasnya demi keamanan transaksi.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('verification.form') }}" class="btn text-white rounded-pill px-5 py-3 fw-bold shadow" style="background-color: #800000;">
                            Verifikasi KTP Sekarang
                        </a>
                    </div>
                </div>
            @else
                {{-- TAMPILAN NORMAL RIWAYAT BARTER --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold mb-0" style="font-family: 'Playfair Display'; color: #800000;">Riwayat Barter</h2>
                </div>

                {{-- Nav Tabs --}}
                <ul class="nav nav-pills mb-4 gap-2" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active rounded-pill px-4 shadow-sm fw-bold" id="inbox-tab" data-bs-toggle="pill" data-bs-target="#inbox" type="button">
                            <i class="bi bi-download me-2"></i>Tawaran Masuk
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link rounded-pill px-4 shadow-sm fw-bold" id="sent-tab" data-bs-toggle="pill" data-bs-target="#sent" type="button">
                            <i class="bi bi-send me-2"></i>Tawaran Saya
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    
                    {{-- ============================ TAB 1: TAWARAN MASUK ============================ --}}
                    <div class="tab-pane fade show active" id="inbox" role="tabpanel">
                        <div class="row g-3">
                            @forelse($incomingRequests as $req)
                                <div class="col-12">
                                    <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-4 
                                        {{ $req->status == 'pending' ? 'border-warning' : ($req->status == 'accepted' ? 'border-success' : ($req->status == 'completed' ? 'border-primary' : 'border-danger')) }}">
                                        <div class="row align-items-center">
                                            <div class="col-md-5 d-flex align-items-center gap-3">
                                                <img src="{{ asset('storage/' . $req->requestedItem->foto_barang) }}" class="rounded-3 shadow-sm" style="width: 70px; height: 70px; object-fit: cover;">
                                                <i class="bi bi-arrow-left-right text-muted fs-5"></i>
                                                <img src="{{ asset('storage/' . $req->offeredItem->foto_barang) }}" class="rounded-3 shadow-sm" style="width: 70px; height: 70px; object-fit: cover; border: 2px solid #800000;">
                                                <div class="ms-1">
                                                    <h6 class="mb-0 fw-bold text-dark" style="font-size: 0.95rem;">Tawaran dari {{ $req->sender->name }}</h6>
                                                    <small class="text-muted d-block small">Ingin menukar dengan <strong>{{ $req->requestedItem->nama_barang }}</strong> milikmu</small>
                                                </div>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <span class="badge rounded-pill px-3 py-2 fw-bold {{ $req->status == 'pending' ? 'bg-warning text-dark' : ($req->status == 'accepted' ? 'bg-success' : ($req->status == 'completed' ? 'bg-primary text-white' : 'bg-danger text-white')) }}">
                                                    {{ strtoupper($req->status) }}
                                                </span>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <button type="button" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold me-1" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $req->id }}">
                                                    <i class="bi bi-eye me-1"></i> Detail
                                                </button>
                                                @if($req->status == 'accepted')
                                                    <a href="{{ route('chat.show', $req->id) }}" class="btn btn-sm rounded-pill px-3 fw-bold text-white shadow-sm" style="background-color: #800000;">
                                                        <i class="bi bi-chat-dots me-1"></i> Chat
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- MODAL DETAIL TAWARAN MASUK --}}
                                <div class="modal fade" id="modalDetail{{ $req->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                            <div class="modal-header border-0 bg-light p-4">
                                                <h5 class="modal-title fw-bold" style="color: #800000; font-family: 'Playfair Display';">Review Penawaran</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4 text-center">
                                                <p class="small text-muted mb-3"><i class="bi bi-info-circle me-1"></i> Klik gambar untuk memperbesar detail kondisi barang</p>
                                                <div class="d-flex justify-content-center align-items-center gap-4 mb-4 pb-4 border-bottom">
                                                    <div class="text-center">
                                                        <img src="{{ asset('storage/' . $req->requestedItem->foto_barang) }}" 
                                                             class="rounded-4 shadow-sm mb-2 img-zoomable" 
                                                             style="width: 140px; height: 140px; object-fit: cover; border: 1px solid #ddd; cursor: zoom-in;"
                                                             onclick="zoomThisImage(this.src, 'Barang Kamu: {{ $req->requestedItem->nama_barang }}')">
                                                        <p class="small fw-bold mb-0 text-muted">Barang Kamu</p>
                                                    </div>
                                                    <i class="bi bi-arrow-left-right fs-2 text-muted"></i>
                                                    <div class="text-center">
                                                        <img src="{{ asset('storage/' . $req->offeredItem->foto_barang) }}" 
                                                             class="rounded-4 shadow-sm mb-2 img-zoomable" 
                                                             style="width: 140px; height: 140px; object-fit: cover; border: 3px solid #800000; cursor: zoom-in;"
                                                             onclick="zoomThisImage(this.src, 'Barang Penawar: {{ $req->offeredItem->nama_barang }}')">
                                                        <p class="small fw-bold mb-0 text-danger">Barang Penawar</p>
                                                    </div>
                                                </div>

                                                <div class="row mb-4 text-start">
                                                    <div class="col-md-6 border-end">
                                                        <h6 class="fw-bold mb-2 small text-uppercase text-muted">Pesan dari {{ $req->sender->name }}</h6>
                                                        <div class="p-3 bg-light rounded-4 italic" style="font-size: 0.9rem;">
                                                            "{{ $req->message ?? 'Halo, mari barter barang ini.' }}"
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6 class="fw-bold mb-2 small text-uppercase text-muted">Detail Barang Penawar</h6>
                                                        <ul class="list-unstyled small">
                                                            <li><strong>Kondisi:</strong> {{ $req->offeredItem->kondisi }}</li>
                                                            <li><strong>Kategori:</strong> {{ $req->offeredItem->kategori }}</li>
                                                            <li class="mt-2 text-muted">{{ Str::limit($req->offeredItem->deskripsi, 100) }}</li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                <div class="mt-4 pt-3 border-top text-start">
                                                    @if($req->status == 'pending')
                                                        <div id="initial-actions-{{ $req->id }}">
                                                            <div class="row g-2">
                                                                <div class="col-6">
                                                                    <form action="{{ route('barter.update-status', $req->id) }}" method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="status" value="rejected">
                                                                        <button type="submit" class="btn btn-outline-danger w-100 rounded-pill fw-bold py-2">Tolak Barter</button>
                                                                    </form>
                                                                </div>
                                                                <div class="col-6">
                                                                    <button type="button" onclick="kirimOtp({{ $req->id }})" class="btn text-white w-100 rounded-pill fw-bold py-2 shadow-sm" style="background-color: #800000;">
                                                                        Terima & Verifikasi OTP
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div id="otp-section-{{ $req->id }}" style="display: none;">
                                                            <div class="p-4 rounded-4 border bg-light text-center">
                                                                <h6 class="fw-bold mb-2">Persetujuan Transaksi</h6>
                                                                <p class="small text-muted mb-3">Masukkan 6 digit kode OTP dari email kamu.</p>
                                                                <form action="{{ route('barter.verify-otp', $req->id) }}" method="POST">
                                                                    @csrf
                                                                    <div class="d-flex justify-content-center gap-2 mb-3">
                                                                        <input type="text" name="otp_input" class="form-control text-center fw-bold fs-3 rounded-3" placeholder="000000" maxlength="6" style="max-width: 180px; letter-spacing: 5px;" required>
                                                                    </div>
                                                                    <button type="submit" class="btn text-white px-5 rounded-pill fw-bold py-2" style="background-color: #800000;">Verifikasi & Deal!</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @elseif($req->status == 'accepted')
                                                        <div class="p-3 rounded-4 bg-light">
                                                            <h6 class="fw-bold mb-3" style="color: #800000;"><i class="bi bi-truck me-2"></i>Informasi Logistik</h6>
                                                            <div class="row g-3">
                                                                <div class="col-md-6">
                                                                    <form action="{{ route('barter.update-resi', $req->id) }}" method="POST">
                                                                        @csrf
                                                                        <label class="small fw-bold text-muted">Update Resimu:</label>
                                                                        <div class="input-group input-group-sm">
                                                                            <input type="text" name="resi" class="form-control" placeholder="JNE/JNT/Grab..." value="{{ $req->receiver_resi }}" required>
                                                                            <button class="btn btn-dark" type="submit">Update</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                                <div class="col-md-6 border-start text-center">
                                                                    <label class="small fw-bold text-muted d-block text-start">Konfirmasi Terima Barang:</label>
                                                                    <form action="{{ route('barter.confirm-arrival', $req->id) }}" method="POST" enctype="multipart/form-data">
                                                                        @csrf
                                                                        <input type="file" name="photo_evidence" class="form-control form-control-sm mb-2" required>
                                                                        <button type="submit" class="btn btn-sm btn-success w-100 rounded-pill fw-bold">Konfirmasi & Selesai</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5">
                                    <i class="bi bi-inbox display-1 text-light"></i>
                                    <p class="text-muted mt-3 italic">Belum ada penawaran masuk.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- ============================ TAB 2: TAWARAN SAYA ============================ --}}
                    <div class="tab-pane fade" id="sent" role="tabpanel">
                        <div class="row g-3">
                            @forelse($myRequests as $req)
                                <div class="col-12">
                                    <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-4 border-info">
                                        <div class="row align-items-center">
                                            <div class="col-md-5 d-flex align-items-center gap-3">
                                                <img src="{{ asset('storage/' . $req->offeredItem->foto_barang) }}" class="rounded-3 shadow-sm" style="width: 70px; height: 70px; object-fit: cover; border: 2px solid #800000;">
                                                <i class="bi bi-arrow-right text-muted fs-5"></i>
                                                <img src="{{ asset('storage/' . $req->requestedItem->foto_barang) }}" class="rounded-3 shadow-sm" style="width: 70px; height: 70px; object-fit: cover;">
                                                <div class="ms-1">
                                                    <h6 class="mb-0 fw-bold">Barter dengan {{ $req->receiver->name }}</h6>
                                                    <small class="text-muted d-block small">Menukar koleksimu dengan <strong>{{ $req->requestedItem->nama_barang }}</strong></small>
                                                </div>
                                            </div>
                                            <div class="col-md-3 text-center">
                                                <span class="badge rounded-pill px-3 py-2 fw-bold bg-light text-dark border">
                                                    {{ strtoupper($req->status) }}
                                                </span>
                                            </div>
                                            <div class="col-md-4 text-end">
                                                <button type="button" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold" data-bs-toggle="modal" data-bs-target="#modalDetailSent{{ $req->id }}">
                                                    <i class="bi bi-eye me-1"></i> Resi & Detail
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- MODAL DETAIL TAWARAN SAYA --}}
                                <div class="modal fade" id="modalDetailSent{{ $req->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                            <div class="modal-header border-0 bg-light p-4">
                                                <h5 class="modal-title fw-bold" style="color: #0d6efd;">Review Penawaran Saya</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4 text-center">
                                                <p class="small text-muted mb-3"><i class="bi bi-info-circle me-1"></i> Klik gambar untuk memperbesar detail</p>
                                                <div class="d-flex justify-content-center align-items-center gap-4 mb-4 pb-4 border-bottom">
                                                    <div class="text-center">
                                                        <img src="{{ asset('storage/' . $req->offeredItem->foto_barang) }}" 
                                                             class="rounded-4 shadow-sm mb-2 img-zoomable" 
                                                             style="width: 140px; height: 140px; object-fit: cover; border: 3px solid #800000; cursor: zoom-in;"
                                                             onclick="zoomThisImage(this.src, 'Barang Kamu: {{ $req->offeredItem->nama_barang }}')">
                                                        <p class="small fw-bold mb-0 text-danger">Barang Kamu</p>
                                                    </div>
                                                    <i class="bi bi-arrow-right fs-2 text-muted"></i>
                                                    <div class="text-center">
                                                        <img src="{{ asset('storage/' . $req->requestedItem->foto_barang) }}" 
                                                             class="rounded-4 shadow-sm mb-2 img-zoomable" 
                                                             style="width: 140px; height: 140px; object-fit: cover; border: 1px solid #ddd; cursor: zoom-in;"
                                                             onclick="zoomThisImage(this.src, 'Barang Target: {{ $req->requestedItem->nama_barang }}')">
                                                        <p class="small fw-bold mb-0 text-muted">Barang {{ $req->receiver->name }}</p>
                                                    </div>
                                                </div>

                                                @if($req->status == 'accepted')
                                                    <div class="p-3 rounded-4 bg-light text-start">
                                                        <h6 class="fw-bold mb-3 text-primary"><i class="bi bi-truck me-2"></i>Logistik Pengiriman</h6>
                                                        <div class="row g-3">
                                                            <div class="col-md-6 border-end">
                                                                <form action="{{ route('barter.update-resi', $req->id) }}" method="POST">
                                                                    @csrf
                                                                    <label class="small fw-bold text-muted">Update Resimu:</label>
                                                                    <div class="input-group input-group-sm">
                                                                        <input type="text" name="resi" class="form-control" placeholder="Nomor Resi..." value="{{ $req->sender_resi }}" required>
                                                                        <button class="btn btn-primary" type="submit">Update</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="small fw-bold text-muted">Konfirmasi Barang Diterima:</label>
                                                                <form action="{{ route('barter.confirm-arrival', $req->id) }}" method="POST" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <input type="file" name="photo_evidence" class="form-control form-control-sm mb-2" required>
                                                                    <button type="submit" class="btn btn-sm btn-success w-100 rounded-pill fw-bold">Barang Sudah Diterima</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="alert alert-info border-0 rounded-4 shadow-sm text-start">
                                                        <i class="bi bi-info-circle me-2"></i> 
                                                        Menunggu <strong>{{ $req->receiver->name }}</strong> menyetujui tawaran barter kamu.
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5">
                                    <i class="bi bi-send-x display-1 text-light"></i>
                                    <p class="text-muted mt-3 italic">Kamu belum pernah mengajukan penawaran barter.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- MODAL ZOOM --}}
<div class="modal fade" id="imageZoomModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 bg-transparent">
            <div class="modal-body p-0 text-center position-relative">
                <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index: 1051;"></button>
                <img src="" id="zoomedImage" class="img-fluid rounded-4 shadow-lg" style="max-height: 85vh; border: 2px solid white;">
                <div class="bg-dark bg-opacity-75 text-white py-2 px-4 rounded-pill d-inline-block mt-3 shadow">
                    <span id="zoomCaption" class="fw-bold"></span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JAVASCRIPT --}}
<script>
    // FUNGSI ZOOM IMAGE
    function zoomThisImage(src, title) {
        document.getElementById('zoomedImage').src = src;
        document.getElementById('zoomCaption').innerText = title;
        var myModal = new bootstrap.Modal(document.getElementById('imageZoomModal'));
        myModal.show();
    }

    // FUNGSI KIRIM OTP
    function kirimOtp(id) {
        const btn = event.target;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Mengirim...';
        btn.disabled = true;

        fetch(`/barter/send-otp/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                document.getElementById(`initial-actions-${id}`).style.display = 'none';
                document.getElementById(`otp-section-${id}`).style.display = 'block';
            } else {
                alert('Gagal mengirim OTP. Coba lagi nanti.');
                btn.innerHTML = 'Terima & Verifikasi OTP';
                btn.disabled = false;
            }
        });
    }
</script>

<style>
    /* FIX WARNA TAB ACTIVE */
    .nav-pills .nav-link.active { 
        background-color: #800000 !important; 
        color: #ffffff !important; 
    }
    
    .nav-pills .nav-link { 
        color: #800000; 
        border: 1px solid #800000; 
        margin-right: 5px; 
    }

    /* EFEK ZOOMABLE IMAGE */
    .img-zoomable {
        transition: all 0.3s ease;
    }

    .img-zoomable:hover {
        transform: scale(1.05);
        filter: brightness(85%);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2) !important;
    }

    .card { transition: 0.3s; }
    .card:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1) !important; }
    .italic { font-style: italic; }

    /* CSS FOR ZOOM MODAL */
    #imageZoomModal .btn-close-white {
        background-color: rgba(0,0,0,0.5);
        border-radius: 50%;
        padding: 10px;
    }
</style>
@endsection