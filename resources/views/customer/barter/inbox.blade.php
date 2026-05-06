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
                <div class="text-center py-5 mt-5 animate-fade-in">
                    <div class="mb-4">
                        <div class="rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 120px; height: 120px; border: 3px dashed #8b6262; background-color: white;">
                            <i class="bi bi-shield-lock-fill" style="font-size: 3.5rem; color: #8b6262;"></i>
                        </div>
                    </div>
                    <h2 class="fw-bold text-dark" style="font-family: 'Playfair Display';">Akses Terbatas</h2>
                    <p class="text-muted mx-auto" style="max-width: 500px;">Halaman riwayat barter hanya bisa diakses oleh user yang sudah terverifikasi identitasnya demi keamanan transaksi.</p>
                    <a href="{{ route('verification.form') }}" class="btn text-white rounded-pill px-5 py-3 fw-bold shadow" style="background-color: #8b6262;">Verifikasi KTP Sekarang</a>
                </div>
            @else
                {{-- HEADER --}}
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold mb-0" style="font-family: 'Playfair Display'; color: #8b6262;">Riwayat Barter</h2>
                </div>

                {{-- NAV TABS --}}
                <ul class="nav nav-pills mb-4 gap-2" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active rounded-pill px-4 fw-bold shadow-sm" id="inbox-tab" data-bs-toggle="pill" data-bs-target="#inbox" type="button">
                            <i class="bi bi-download me-2"></i>Tawaran Masuk
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link rounded-pill px-4 fw-bold shadow-sm" id="sent-tab" data-bs-toggle="pill" data-bs-target="#sent" type="button">
                            <i class="bi bi-send me-2"></i>Tawaran Saya
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="pills-tabContent">
                    {{-- TAB 1: TAWARAN MASUK (INBOX) --}}
                    <div class="tab-pane fade show active" id="inbox" role="tabpanel">
                        <div class="row g-3">
                            @forelse($incomingRequests as $req)
                                <div class="col-12">
                                    @php
                                        $borderColor = 'border-secondary';
                                        $statusLabel = strtoupper($req->status);
                                        
                                        if($req->status == 'pending') $borderColor = 'border-warning';
                                        elseif(in_array($req->status, ['accepted', 'on_going', 'completed'])) $borderColor = 'border-success';
                                        elseif(in_array($req->status, ['rejected', 'cancelled', 'rejected_qc'])) $borderColor = 'border-danger';

                                        if($req->status == 'rejected_qc') $statusLabel = 'REJECTED QC';
                                    @endphp

                                    <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-4 {{ $borderColor }} {{ in_array($req->status, ['rejected', 'cancelled', 'rejected_qc']) ? 'opacity-75' : '' }}">
                                        <div class="row align-items-center">
                                            <div class="col-md-5 d-flex align-items-center gap-3">
                                                <div class="position-relative">
                                                    <img src="{{ asset('storage/' . ($req->requestedItem->foto_barang ?? 'default.jpg')) }}" class="rounded-3 shadow-sm" style="width: 70px; height: 70px; object-fit: cover;">
                                                    <span class="position-absolute top-0 start-0 translate-middle badge rounded-pill bg-dark border border-light" style="font-size: 0.5rem;">Milikmu</span>
                                                </div>
                                                <i class="bi bi-arrow-left-right text-muted fs-5"></i>
                                                <img src="{{ asset('storage/' . ($req->offeredItem->foto_barang ?? 'default.jpg')) }}" class="rounded-3 shadow-sm" style="width: 70px; height: 70px; object-fit: cover; border: 2px solid #8b6262;">
                                                <div class="ms-1">
                                                    <h6 class="mb-0 fw-bold text-dark">{{ $req->sender->name }}</h6>
                                                    <small class="text-muted d-block small">Barang Penawar: <strong>{{ $req->offeredItem->nama_barang ?? 'N/A' }}</strong></small>
                                                </div>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <span class="badge rounded-pill px-3 py-2 fw-bold 
                                                    {{ $req->status == 'pending' ? 'bg-warning text-dark' : '' }}
                                                    {{ in_array($req->status, ['accepted', 'on_going', 'completed']) ? 'bg-success text-white' : '' }}
                                                    {{ in_array($req->status, ['rejected', 'cancelled', 'rejected_qc']) ? 'bg-danger text-white' : '' }}">
                                                    {{ $statusLabel }}
                                                </span>
                                            </div>
                                            <div class="col-md-5 text-end">
                                                @if(in_array($req->status, ['accepted', 'on_going', 'completed', 'rejected_qc']))
                                                    <a href="{{ route('chat.show', ['user_id' => $req->sender_id, 'barter_id' => $req->id]) }}" 
                                                       class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold me-1">
                                                        <i class="bi bi-chat-dots me-1"></i> Chat
                                                    </a>
                                                @endif

                                                <button class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold me-1" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $req->id }}">Detail</button>
                                                
                                                @if(in_array($req->status, ['accepted', 'on_going', 'completed', 'rejected_qc']))
                                                    <a href="{{ route('barter.tracking', $req->id) }}" class="btn btn-sm rounded-pill px-3 fw-bold text-white shadow-sm" style="background-color: #8b6262;">Tracking</a>
                                                @endif
                                            </div>

                                            @if(in_array($req->status, ['rejected', 'cancelled', 'rejected_qc']))
                                                <div class="col-12 mt-2">
                                                    <div class="p-2 bg-light rounded-3 small text-danger fw-bold border-start border-danger border-3">
                                                        <i class="bi bi-info-circle me-2"></i>
                                                        @if($req->status == 'rejected') Penawaran ditolak oleh Anda.
                                                        @elseif($req->status == 'cancelled') Barter dibatalkan oleh partner.
                                                        @elseif($req->status == 'rejected_qc') Dibatalkan oleh Admin (Gagal QC). @endif
                                                        <span class="text-muted fw-normal ms-1">({{ $req->cancel_reason ?? $req->admin_note ?? 'Tidak ada catatan tambahan' }})</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- MODAL DETAIL --}}
                                <div class="modal fade" id="modalDetail{{ $req->id }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                                            <div class="modal-header border-0 bg-light p-4">
                                                <h5 class="modal-title fw-bold" style="color: #8b6262; font-family: 'Playfair Display';">Review Penawaran</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body p-4 text-center">
                                                <div class="d-flex justify-content-center align-items-center gap-4 mb-4 border-bottom pb-4">
                                                    <div>
                                                        <img src="{{ asset('storage/' . ($req->requestedItem->foto_barang ?? 'default.jpg')) }}" class="rounded-4 mb-2 shadow-sm" style="width: 140px; height: 140px; object-fit: cover;">
                                                        <p class="small fw-bold text-muted mb-0 text-uppercase" style="font-size: 0.65rem;">Barang Kamu</p>
                                                    </div>
                                                    <i class="bi bi-arrow-left-right fs-2 text-muted opacity-50"></i>
                                                    <div>
                                                        <img src="{{ asset('storage/' . ($req->offeredItem->foto_barang ?? 'default.jpg')) }}" class="rounded-4 mb-2 shadow-sm" style="width: 140px; height: 140px; object-fit: cover; border: 3px solid #8b6262;">
                                                        <p class="small fw-bold text-maroon mb-0 text-uppercase" style="font-size: 0.65rem;">Barang Penawar</p>
                                                    </div>
                                                </div>
                                                <div class="row text-start mb-4">
                                                    <div class="col-md-6 border-end">
                                                        <h6 class="fw-bold small text-muted text-uppercase mb-2">Pesan Penawar</h6>
                                                        <p class="italic p-3 bg-light rounded-4 small text-dark">"{{ $req->message ?? 'Mari barter kak!' }}"</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <h6 class="fw-bold small text-muted text-uppercase mb-2">Keterangan Barang</h6>
                                                        <ul class="list-unstyled small mb-0">
                                                            <li class="mb-1"><strong>Kondisi:</strong> <span class="badge bg-soft-maroon text-maroon border">{{ $req->offeredItem->kondisi ?? '-' }}</span></li>
                                                            <li><strong>Kategori:</strong> {{ $req->offeredItem->kategori ?? '-' }}</li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                @if($req->status == 'pending')
                                                    <div class="pt-3 border-top">
                                                        {{-- BAGIAN INPUT OTP (Awalnya Sembunyi) --}}
                                                        <div id="otp-section-{{ $req->id }}" style="display: none;" class="animate-fade-in">
                                                            <div class="p-3 rounded-4 bg-soft-maroon mb-3">
                                                                <label class="fw-bold text-maroon d-block mb-2">Masukkan Kode OTP</label>
                                                                <p class="x-small text-muted mb-3">Kode 6 digit telah dikirim ke email kamu.</p>
                                                                <form action="{{ route('barter.accept', $req->id) }}" method="POST">
                                                                    @csrf
                                                                    <div class="d-flex justify-content-center gap-2 mb-3">
                                                                        <input type="text" name="otp_code" class="form-control text-center fw-bold fs-4 rounded-3" style="letter-spacing: 10px;" maxlength="6" required placeholder="000000">
                                                                    </div>
                                                                    <button type="submit" class="btn btn-success w-100 rounded-pill fw-bold">Verifikasi & Terima Barter</button>
                                                                </form>
                                                            </div>
                                                        </div>

                                                        {{-- TOMBOL AKSI AWAL --}}
                                                        <div class="d-flex gap-2">
                                                            <form action="{{ route('barter.reject', $req->id) }}" method="POST" class="flex-grow-1">
                                                                @csrf
                                                                <button type="submit" class="btn btn-outline-danger rounded-pill w-100 fw-bold" onclick="return confirm('Yakin ingin menolak penawaran ini?')">Tolak Penawaran</button>
                                                            </form>
                                                            <button onclick="kirimOtp({{ $req->id }})" class="btn text-white flex-grow-1 rounded-pill fw-bold btn-accept-{{ $req->id }}" style="background-color: #8b6262;">Terima & Verifikasi</button>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5">
                                    <i class="bi bi-inbox display-1 text-muted opacity-25"></i>
                                    <p class="text-muted mt-3 italic">Belum ada tawaran masuk.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- TAB 2: TAWARAN SAYA (SENT) --}}
                    <div class="tab-pane fade" id="sent" role="tabpanel">
                        <div class="row g-3">
                            @forelse($myRequests as $req)
                                <div class="col-12">
                                    @php
                                        $sentBorder = 'border-info';
                                        $sentLabel = strtoupper($req->status);
                                        if(in_array($req->status, ['accepted', 'on_going', 'completed'])) $sentBorder = 'border-success';
                                        elseif(in_array($req->status, ['rejected', 'cancelled', 'rejected_qc'])) $sentBorder = 'border-danger';
                                        if($req->status == 'rejected_qc') $sentLabel = 'GAGAL QC';
                                    @endphp

                                    <div class="card border-0 shadow-sm rounded-4 p-3 border-start border-4 {{ $sentBorder }} {{ in_array($req->status, ['rejected', 'cancelled', 'rejected_qc']) ? 'opacity-75' : '' }}">
                                        <div class="row align-items-center">
                                            <div class="col-md-5 d-flex align-items-center gap-3">
                                                <img src="{{ asset('storage/' . ($req->offeredItem->foto_barang ?? 'default.jpg')) }}" class="rounded-3 shadow-sm" style="width: 70px; height: 70px; object-fit: cover; border: 2px solid #8b6262;">
                                                <i class="bi bi-arrow-right text-muted fs-5"></i>
                                                <img src="{{ asset('storage/' . ($req->requestedItem->foto_barang ?? 'default.jpg')) }}" class="rounded-3 shadow-sm" style="width: 70px; height: 70px; object-fit: cover;">
                                                <div class="ms-1">
                                                    <h6 class="mb-0 fw-bold">Barter dengan {{ $req->receiver->name ?? 'User' }}</h6>
                                                    <small class="text-muted small">Menawarkan barang: <strong>{{ $req->offeredItem->nama_barang ?? 'N/A' }}</strong></small>
                                                </div>
                                            </div>
                                            <div class="col-md-2 text-center">
                                                <span class="badge rounded-pill px-3 py-2 fw-bold bg-light text-dark border">{{ $sentLabel }}</span>
                                            </div>
                                            <div class="col-md-5 text-end">
                                                @if(in_array($req->status, ['accepted', 'on_going', 'completed', 'rejected_qc']))
                                                    <a href="{{ route('chat.show', ['user_id' => $req->receiver_id, 'barter_id' => $req->id]) }}" 
                                                       class="btn btn-outline-success btn-sm rounded-pill px-3 fw-bold me-1">
                                                        <i class="bi bi-chat-dots me-1"></i> Chat
                                                    </a>
                                                    <a href="{{ route('barter.tracking', $req->id) }}" class="btn btn-sm rounded-pill px-3 fw-bold text-white shadow-sm" style="background-color: #8b6262;">Tracking</a>
                                                @elseif($req->status == 'pending')
                                                    <span class="small text-muted italic">Menunggu respon partner...</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12 text-center py-5">
                                    <i class="bi bi-send-x display-1 text-muted opacity-25"></i>
                                    <p class="text-muted mt-3 italic">Kamu belum pernah mengajukan penawaran.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    function kirimOtp(id) {
        let btn = document.querySelector(`.btn-accept-${id}`);
        let originalText = btn.innerHTML;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Loading...';
        btn.disabled = true;

        fetch(`/barter/send-otp/${id}`, { 
            method: 'POST', 
            headers: { 
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            } 
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) { 
                // MUNCULIN BAGIAN OTP NYA JES!
                document.getElementById(`otp-section-${id}`).style.display = 'block'; 
                btn.parentElement.style.display = 'none'; // Sembunyiin tombol tawar/terima
            } else {
                alert('Gagal mengirim OTP: ' + (data.message || 'Coba lagi.'));
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        })
        .catch(error => {
            alert('Terjadi kesalahan koneksi.');
            btn.disabled = false;
            btn.innerHTML = originalText;
        });
    }
</script>

<style>
    .nav-pills .nav-link.active { background-color: #8b6262 !important; color: white !important; border-color: #8b6262 !important; }
    .nav-pills .nav-link { color: #8b6262; border: 1px solid #8b6262; transition: 0.3s; background-color: white; }
    .nav-pills .nav-link:hover { background-color: #fff0f0; color: #7a5555; }
    .bg-soft-maroon { background-color: #fff0f0; }
    .text-maroon { color: #8b6262 !important; }
    .x-small { font-size: 0.7rem; }
    .italic { font-style: italic; }
    .animate-fade-in { animation: fadeIn 0.4s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection