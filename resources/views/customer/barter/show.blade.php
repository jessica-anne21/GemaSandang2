@extends('layouts.main')

@section('content')
<div class="container py-5" style="background-color: #fdf5f5; min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
            {{-- Navigasi Back --}}
            <div class="mb-4">
                <a href="{{ route('barter.index') }}" class="text-decoration-none text-muted fw-bold">
                    <i class="bi bi-arrow-left me-2"></i> Kembali 
                </a>
            </div>

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                        <div>
                            <strong>Aduh!</strong> {{ session('error') }}
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            @endif

            {{-- Card Detail Produk --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="row g-0">
                    
                    {{-- Foto Produk --}}
                    <div class="col-md-6">
                        <div class="h-100 bg-light d-flex align-items-center justify-content-center">
                            <img src="{{ asset('storage/' . $item->foto_barang) }}" 
                                 class="img-fluid w-100 h-100" 
                                 style="object-fit: cover; min-height: 500px;" 
                                 alt="{{ $item->nama_barang }}">
                        </div>
                    </div>

                    {{-- Detail Produk --}}
                    <div class="col-md-6 bg-white p-4 p-lg-5">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <span class="badge rounded-pill px-3 py-2" style="background-color: #fff0f0; color: #800000; border: 1px solid #ffcccc;">
                                {{ $item->kategori }}
                            </span>
                            <span class="text-muted small">Kondisi: <strong class="text-dark">{{ $item->kondisi }}</strong></span>
                        </div>

                        <h1 class="fw-bold mb-3" style="color: #444; font-family: 'Playfair Display', serif; font-size: 2.5rem;">
                            {{ $item->nama_barang }}
                        </h1>

                        {{-- Card Pemilik --}}
                        <div class="d-flex align-items-center mb-4 p-3 rounded-4" style="background-color: #fef9f9; border: 1px solid #fceaea;">
                            <div class="rounded-circle me-3 d-flex align-items-center justify-content-center text-white fw-bold" 
                                 style="width: 50px; height: 50px; background-color: #800000; font-size: 1.1rem;">
                                {{ strtoupper(substr($item->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="mb-0 small text-muted">Pemilik Barang</p>
                                <p class="mb-0 fw-bold text-dark">{{ $item->user->name }} 
                                    <i class="bi bi-patch-check-fill text-primary ms-1"></i>
                                </p>
                            </div>
                        </div>

                        <div class="mb-5">
                            <h6 class="fw-bold text-uppercase small text-muted mb-3" style="letter-spacing: 1px;">Deskripsi Barang</h6>
                            <p class="text-muted" style="line-height: 1.8; font-size: 1.05rem;">
                                {{ $item->deskripsi }}
                            </p>
                        </div>

                        {{-- TOMBOL AKSI BERDASARKAN STATUS --}}
                        <div class="d-grid gap-3">
                            @php
                                $barterRequest = \App\Models\BarterRequest::where('requested_item_id', $item->id)
                                                ->where('sender_id', auth()->id())
                                                ->first();
                            @endphp

                            @if($userProducts->isEmpty())
                                <div class="alert alert-warning rounded-4 small border-0 shadow-sm">
                                    <i class="bi bi-info-circle me-2"></i> Kamu belum punya barang untuk dibarter. 
                                    <a href="{{ route('profile.my-profile') }}" class="fw-bold text-dark">Upload dulu yuk!</a>
                                </div>
                            
                            @elseif($barterRequest && $barterRequest->status == 'accepted')
                                <div class="alert alert-success rounded-4 border-0 shadow-sm p-3 mb-2 d-flex align-items-center">
                                    <i class="bi bi-check-circle-fill fs-4 me-3"></i>
                                    <div>
                                        <h6 class="fw-bold mb-0">Barter Disetujui!</h6>
                                        <small>Silakan lanjut berdiskusi melalui fitur chat.</small>
                                    </div>
                                </div>
                                <a href="{{ route('chat.show', $barterRequest->id) }}" class="btn text-white rounded-pill py-3 fw-bold shadow-sm" 
                                   style="background-color: #800000; font-size: 1.1rem;">
                                    <i class="bi bi-chat-dots-fill me-2"></i> Lanjut ke Chat Negosiasi
                                </a>

                            @elseif($barterRequest && $barterRequest->status == 'pending')
                                <button class="btn btn-secondary w-100 rounded-pill py-3 fw-bold shadow-sm" disabled style="cursor: not-allowed;">
                                    <i class="bi bi-clock-history me-2"></i> Penawaran Sedang Diproses...
                                </button>
                                <div class="p-3 rounded-4 mt-2 text-center" style="background-color: #f8f9fa; border: 1px dashed #dee2e6;">
                                    <p class="mb-0 small text-muted">
                                        Kamu sudah mengajukan barter. Tunggu pemilik barang menyetujui ya!
                                    </p>
                                </div>

                            @elseif($barterRequest && $barterRequest->status == 'rejected')
                                <div class="alert alert-danger rounded-4 border-0 shadow-sm text-center">
                                    <i class="bi bi-x-circle-fill me-2"></i> Penawaran kamu sebelumnya ditolak oleh pemilik barang.
                                </div>
                                <button type="button" class="btn text-white rounded-pill py-3 fw-bold shadow-sm" 
                                        style="background-color: #800000;" data-bs-toggle="modal" data-bs-target="#offerBarterModal">
                                    Coba Ajukan Barang Lain
                                </button>

                            @else
                                <button type="button" class="btn text-white rounded-pill py-3 fw-bold shadow-sm btn-hover-effect" 
                                        style="background-color: #800000; font-size: 1.1rem;"
                                        data-bs-toggle="modal" data-bs-target="#offerBarterModal">
                                    <i class="bi bi-arrow-left-right me-2"></i> Ajukan Penawaran Barter
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL PILIH BARANG & OTP STEP --}}
@if(!$userProducts->isEmpty())
<div class="modal fade" id="offerBarterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4">
            {{-- Form kita arahkan ke route store yang baru --}}
            <form id="formKirimPenawaran" action="{{ route('barter.request.send', $item->id) }}" method="POST">
                @csrf
                <div class="modal-header border-0 p-4 bg-light">
                    <h5 class="modal-title fw-bold" style="color: #800000; font-family: 'Playfair Display';">Pengajuan Barter</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                {{-- STEP 1: PILIH BARANG --}}
                <div class="modal-body p-4" id="section-pilih-barang">
                    <p class="text-muted small mb-4">Pilih satu barang dari lemari virtualmu untuk ditawarkan ke <strong>{{ $item->user->name }}</strong>.</p>
                    
                    <div class="row row-cols-2 row-cols-md-3 g-3 overflow-auto" style="max-height: 350px;">
                        @foreach($userProducts as $myProduct)
                            <div class="col">
                                <input type="radio" class="btn-check" name="my_item_id" id="item{{ $myProduct->id }}" value="{{ $myProduct->id }}" required>
                                <label class="card h-100 border-2 rounded-4 overflow-hidden shadow-sm barter-option-card" for="item{{ $myProduct->id }}" style="cursor: pointer;">
                                    <img src="{{ asset('storage/' . $myProduct->foto_barang) }}" class="card-img-top" style="height: 120px; object-fit: cover;">
                                    <div class="card-body p-2 text-center">
                                        <h6 class="small fw-bold mb-0 text-truncate">{{ $myProduct->nama_barang }}</h6>
                                        <small class="text-muted" style="font-size: 0.7rem;">{{ $myProduct->kondisi }}</small>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-4">
                        <label class="small fw-bold text-muted text-uppercase mb-2">Pesan Negosiasi (Opsional)</label>
                        <textarea name="pesan" class="form-control rounded-3" rows="2" placeholder="Halo kak, mari barter!"></textarea>
                    </div>

                    <div class="mt-4">
                        <button type="button" onclick="prosesOtpRequester()" id="btn-request-otp" class="btn text-white w-100 rounded-pill py-3 fw-bold shadow-sm" style="background-color: #800000;">
                            Lanjut Verifikasi OTP
                        </button>
                    </div>
                </div>

                {{-- STEP 2: INPUT OTP (Hidden by Default) --}}
                <div class="modal-body p-5 text-center" id="section-otp-requester" style="display: none;">
                    <div class="mb-4">
                        <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                            <i class="bi bi-shield-lock-fill fs-1" style="color: #800000;"></i>
                        </div>
                        <h4 class="fw-bold">Verifikasi Pengajuan</h4>
                        <p class="text-muted small">Kami telah mengirimkan kode OTP ke email kamu. Masukkan kode tersebut untuk meresmikan penawaran ini.</p>
                    </div>

                    <div class="mb-4">
                        <input type="text" name="otp_input" class="form-control text-center fw-bold fs-2 rounded-4 py-3" placeholder="000000" maxlength="6" style="letter-spacing: 8px; border: 2px solid #eee;">
                    </div>

                    <button type="submit" class="btn text-white w-100 rounded-pill py-3 fw-bold shadow mb-2" style="background-color: #800000;">
                        Konfirmasi & Kirim Penawaran
                    </button>
                    <button type="button" onclick="backToSelect()" class="btn btn-link text-muted small text-decoration-none">
                        <i class="bi bi-chevron-left me-1"></i> Kembali Pilih Barang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
    function prosesOtpRequester() {
        const btn = document.getElementById('btn-request-otp');
        const selectedItem = document.querySelector('input[name="my_item_id"]:checked');

        if (!selectedItem) {
            alert('Pilih barang penukar dulu ya!');
            return;
        }

        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Mengirim OTP...';
        btn.disabled = true;

        // AJAX ke route send-otp (tanpa ID karena data belum di DB)
        fetch("{{ route('barter.send-otp') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                document.getElementById('section-pilih-barang').style.display = 'none';
                document.getElementById('section-otp-requester').style.display = 'block';
            } else {
                alert('Gagal mengirim OTP. Cek koneksi Mailtrap kamu!');
                btn.innerHTML = 'Lanjut Verifikasi OTP';
                btn.disabled = false;
            }
        })
        .catch(error => {
            alert('Sistem sedang sibuk. Coba sesaat lagi.');
            btn.innerHTML = 'Lanjut Verifikasi OTP';
            btn.disabled = false;
        });
    }

    function backToSelect() {
        document.getElementById('section-pilih-barang').style.display = 'block';
        document.getElementById('section-otp-requester').style.display = 'none';
        document.getElementById('btn-request-otp').innerHTML = 'Lanjut Verifikasi OTP';
        document.getElementById('btn-request-otp').disabled = false;
    }

    @if(session('error'))
        // 1. Buka modal secara otomatis
        var myModal = new bootstrap.Modal(document.getElementById('offerBarterModal'));
        myModal.show();
        
        // 2. Langsung lompat ke bagian input OTP
        document.getElementById('section-pilih-barang').style.display = 'none';
        document.getElementById('section-otp-requester').style.display = 'block';
        
        // 3. (Opsional) Kasih warna merah di input biar user tau ada yang salah
        document.querySelector('input[name="otp_input"]').style.borderColor = 'red';
    @endif
</script>

<style>
    .barter-option-card {
        border-color: transparent;
        transition: all 0.3s ease;
    }
    .btn-check:checked + .barter-option-card {
        border: 3px solid #800000 !important;
        background-color: #fff9f9;
        transform: scale(1.05);
    }
    .btn-hover-effect:hover {
        opacity: 0.9;
        transform: translateY(-2px);
        transition: 0.3s;
    }
    #section-otp-requester input:focus {
        border-color: #800000 !important;
        box-shadow: none;
    }
</style>
@endsection