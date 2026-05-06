@extends('layouts.main')

@section('content')
@php
    $isSender = (auth()->id() == $barter->sender_id);
    
    // Identitas Partner
    $partner = $isSender ? $barter->receiver : $barter->sender;
    $partnerName = $partner->name;

    // Item Info
    $myItem = $isSender ? $barter->offeredItem : $barter->requestedItem;
    $partnerItem = $isSender ? $barter->requestedItem : $barter->offeredItem;

    // Status Pembayaran & Logistik (Protection)
    $myStatus = $isSender ? $barter->sender_payment_status : $barter->receiver_payment_status;
    $myProof = $isSender ? $barter->sender_payment_proof : $barter->receiver_payment_proof;
    
    $myLogStatus = $isSender ? $barter->sender_logistic_status : $barter->receiver_logistic_status;
    $adminResiToMe = $isSender ? $barter->resi_from_admin_to_sender : $barter->resi_from_admin_to_receiver;

    // Resi & Konfirmasi
    $myResi = $isSender ? $barter->sender_resi : $barter->receiver_resi;
    $partnerResi = $isSender ? $barter->receiver_resi : $barter->sender_resi;
    $hasIConfirmed = $isSender ? $barter->sender_confirmed_at : $barter->receiver_confirmed_at;
@endphp

<div class="container py-5" style="background-color: #fdf5f5; min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            
            {{-- Notifikasi --}}
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 animate-fade-in">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                </div>
            @endif

            <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="fw-bold mb-0" style="color: #800000; font-family: 'Playfair Display';">Tracking Barter</h4>
                    <span class="badge rounded-pill px-3 py-2 border border-maroon border-opacity-10" style="background-color: #fff0f0; color: #800000;">
                        ID: #{{ str_pad($barter->id, 5, '0', STR_PAD_LEFT) }}
                    </span>
                </div>

                {{-- STEPPER PROGRESS --}}
                <div class="d-flex justify-content-between mb-5 position-relative px-md-5 text-center">
                    <div class="position-absolute top-50 start-0 translate-middle-y w-100 bg-light" style="height: 3px; z-index: 1;"></div>
                    
                    <div class="position-relative" style="z-index: 2; width: 80px;">
                        <div class="rounded-circle bg-success text-white mx-auto mb-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;">
                            <i class="bi bi-hand-thumbs-up-fill"></i>
                        </div>
                        <small class="fw-bold d-block small text-muted">Deal</small>
                    </div>

                    <div class="position-relative" style="z-index: 2; width: 80px;">
                        <div class="rounded-circle {{ $barter->terms_accepted ? 'bg-success text-white' : 'bg-white border text-muted' }} mx-auto mb-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;">
                            <i class="bi bi-shield-lock-fill"></i>
                        </div>
                        <small class="fw-bold d-block small text-muted">Metode</small>
                    </div>

                    <div class="position-relative" style="z-index: 2; width: 80px;">
                        <div class="rounded-circle {{ $barter->status == 'completed' ? 'bg-success text-white' : 'bg-white border text-muted' }} mx-auto mb-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;">
                            <i class="bi bi-check-all"></i>
                        </div>
                        <small class="fw-bold d-block small text-muted">Selesai</small>
                    </div>
                </div>

                <hr class="mb-4 opacity-50">

                @if($barter->status == 'completed')
                    <div class="text-center py-5 animate-fade-in">
                        <i class="bi bi-bag-check-fill text-success mb-4" style="font-size: 5rem;"></i>
                        <h3 class="fw-bold">Barter Berhasil!</h3>
                        <p class="text-muted">Barangmu sudah resmi berpindah tangan.</p>
                        <a href="{{ route('barter.inbox') }}" class="btn btn-outline-dark rounded-pill px-4">Kembali ke Riwayat Barter</a>
                    </div>

                @elseif($barter->status == 'cancelled')
                    <div class="text-center py-5 animate-fade-in">
                        <i class="bi bi-x-circle-fill text-danger mb-4" style="font-size: 5rem;"></i>
                        <h3 class="fw-bold">Transaksi Dibatalkan</h3>
                        <p class="text-muted italic">Alasan: {{ $barter->cancel_reason ?? 'Dibatalkan oleh pengguna' }}</p>
                        <a href="{{ route('barter.inbox') }}" class="btn btn-outline-dark rounded-pill px-4 mt-3">Kembali</a>
                    </div>

                @elseif(!$barter->terms_accepted)
                    {{-- SECTION PEMILIHAN METODE DENGAN S&K --}}
                    <div class="text-center animate-fade-in">
                        <h6 class="fw-bold mb-4 text-dark"><i class="bi bi-gear-wide-connected me-2"></i>Pilih Metode Pengiriman</h6>
                        
                        <form action="{{ route('barter.select-protection', $barter->id) }}" method="POST" id="methodForm">
                            @csrf
                            <div class="row g-3 text-start">
                                {{-- Standard --}}
                                <div class="col-md-6">
                                    <label class="method-card p-4 border rounded-4 d-block h-100" for="std">
                                        <input type="radio" name="method" id="std" value="standard" class="d-none" required>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold h6 mb-0 text-dark">Standard Barter</span>
                                            <i class="bi bi-check-circle-fill check-icon"></i>
                                        </div>
                                        <p class="small text-muted mb-0">Kirim mandiri langsung ke partner barter. Tanpa biaya platform.</p>
                                    </label>
                                </div>
                                
                                {{-- Protection --}}
                                <div class="col-md-6">
                                    <label class="method-card p-4 border rounded-4 d-block h-100" for="prot">
                                        <input type="radio" name="method" id="prot" value="protection" class="d-none">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="fw-bold h6 mb-0 text-primary">Trade Protection</span>
                                            <i class="bi bi-shield-check check-icon text-primary"></i>
                                        </div>
                                        <p class="small text-muted mb-2">Pengecekan kualitas (QC) & Verifikasi oleh Admin Gema Sandang.</p>
                                        <span class="badge bg-soft-primary text-primary border-primary border border-opacity-25 rounded-pill px-3">Biaya: Rp 25.000 / user</span>
                                    </label>
                                </div>
                            </div>

                            {{-- SYARAT DAN KETENTUAN --}}
                            <div class="mt-4 p-3 rounded-4 bg-white border border-dashed text-start animate-fade-in">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms_check" required>
                                    <label class="form-check-label small text-dark fw-medium" for="terms_check">
                                        Saya menyetujui <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal" class="text-maroon text-decoration-none fw-bold">Syarat & Ketentuan</a> Barter di Gema Sandang.
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="btn text-white rounded-pill px-5 py-3 fw-bold mt-4 shadow-sm w-100 btn-lg" style="background-color: #800000;">Konfirmasi & Lanjutkan</button>
                        </form>
                    </div>

                @else
                    {{-- TRACKING AKTIF (SAMA SEPERTI SEBELUMNYA) --}}
                    <div class="row g-4">
                        <div class="col-md-7">
                            @if($barter->method_selection == 'protection' && $myStatus != 'paid')
                                <div class="p-4 rounded-4 border border-warning shadow-sm mb-4" style="background-color: #fffdf5;">
                                    <h6 class="fw-bold mb-3 text-warning-emphasis"><i class="bi bi-wallet2 me-2"></i>Biaya Layanan Protection</h6>
                                    @if(!$myProof)
                                        <p class="small text-muted mb-3">Selesaikan transfer <strong>Rp 25.000</strong> ke BCA: <strong>1234 5678 90</strong></p>
                                        <form action="{{ route('barter.upload-payment', $barter->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <input type="file" name="payment_proof" class="form-control form-control-sm rounded-pill mb-3" required>
                                            <button class="btn btn-warning w-100 rounded-pill fw-bold shadow-sm">Kirim Bukti Bayar</button>
                                        </form>
                                    @else
                                        <div class="text-center py-2 text-warning-emphasis fw-bold small">
                                            <div class="spinner-border spinner-border-sm me-2"></div> Menunggu Verifikasi Admin
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if($barter->method_selection == 'standard' || ($barter->method_selection == 'protection' && $barter->sender_payment_status == 'paid' && $barter->receiver_payment_status == 'paid'))
                                <div class="p-4 rounded-4 border bg-white shadow-sm mb-4">
                                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-truck me-2"></i>Informasi Pengiriman</h6>
                                    
                                    <div class="alert rounded-4 small border-0 py-3 mb-4 shadow-sm {{ $barter->method_selection == 'protection' ? 'alert-primary' : 'alert-secondary' }}">
                                        @if($barter->method_selection == 'protection')
                                            <strong>Kirim ke Gudang Gema Sandang:</strong><br>
                                            Jl. Maranatha No. 123, Sukajadi, Kota Bandung.<br>
                                            <small class="text-muted">Penerima: Admin QC (0812-3456-7890)</small>
                                        @else
                                            <strong>Kirim ke Partner (Direct):</strong><br>
                                            <span class="fw-bold">{{ $partner->name }}</span><br>
                                            {{ $partner->alamat ?? 'Alamat belum diisi' }}<br>
                                            {{ $partner->district }}, {{ $partner->city }}<br>
                                            <span class="fw-bold">Telp: 0{{ $partner->nomor_hp }}</span>
                                        @endif
                                    </div>

                                    @include('customer.barter.partials.resi-form')

                                    @if($barter->method_selection == 'protection')
                                        <div class="mt-4 pt-4 border-top">
                                            <label class="small fw-bold text-maroon mb-2 d-block text-uppercase">Status Gudang & QC</label>
                                            <div class="p-3 rounded-4 bg-light shadow-sm">
                                                <div class="d-flex align-items-center gap-3">
                                                    @if(Str::contains($myLogStatus, 'shipped')) 
                                                        <i class="bi bi-patch-check-fill text-success fs-4"></i>
                                                    @else 
                                                        <div class="spinner-grow text-warning spinner-grow-sm"></div> 
                                                    @endif
                                                    <div class="small fw-bold text-dark">
                                                        @if($myLogStatus == 'pending') Menunggu barang tiba di gudang.
                                                        @elseif($myLogStatus == 'qc_process') Admin sedang mengecek kondisi barang.
                                                        @else Lolos QC! Barang dikirim ke tujuan akhir. @endif
                                                    </div>
                                                </div>
                                                @if($adminResiToMe)
                                                    <div class="mt-2 p-2 rounded-3 bg-white border small fw-bold text-success">
                                                        <i class="bi bi-box-seam me-1"></i> Resi Kurir Admin: {{ $adminResiToMe }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="p-5 rounded-4 bg-light border text-center opacity-75">
                                    <i class="bi bi-lock-fill fs-2 d-block mb-2 text-muted"></i>
                                    <p class="small fw-bold mb-0 text-muted">Logistik terbuka jika pembayaran dikonfirmasi.</p>
                                </div>
                            @endif

                            @if(!$barter->sender_resi && !$barter->receiver_resi && $barter->status != 'completed')
                                <div class="text-center mt-4">
                                    <button type="button" class="btn btn-link text-danger small text-decoration-none" data-bs-toggle="modal" data-bs-target="#cancelModal">
                                        <i class="bi bi-x-circle me-1"></i> Batalkan Barter
                                    </button>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-5">
                            <div class="card border-0 bg-light rounded-4 p-4 shadow-sm">
                                <h6 class="fw-bold text-maroon mb-4 border-bottom pb-2 text-uppercase" style="font-size: 0.7rem;">Ringkasan Barter</h6>
                                <div class="row g-2 align-items-center mb-4 text-center">
                                    <div class="col-5">
                                        <div class="position-relative">
                                            <img src="{{ asset('storage/' . $myItem->foto_barang) }}" class="rounded-3 shadow-sm border border-white border-2 w-100" style="height: 100px; object-fit: cover;">
                                            <div class="badge bg-dark position-absolute bottom-0 start-50 translate-middle-x mb-n2" style="font-size: 0.5rem;">Milikmu</div>
                                        </div>
                                        <p class="x-small fw-bold mt-3 mb-0 text-truncate">{{ $myItem->nama_barang }}</p>
                                    </div>
                                    <div class="col-2"><i class="bi bi-arrow-left-right text-muted"></i></div>
                                    <div class="col-5">
                                        <div class="position-relative">
                                            <img src="{{ asset('storage/' . $partnerItem->foto_barang) }}" class="rounded-3 shadow-sm border border-white border-2 w-100" style="height: 100px; object-fit: cover;">
                                            <div class="badge bg-maroon position-absolute bottom-0 start-50 translate-middle-x mb-n2" style="font-size: 0.5rem;">Partner</div>
                                        </div>
                                        <p class="x-small fw-bold mt-3 mb-0 text-truncate">{{ $partnerItem->nama_barang }}</p>
                                    </div>
                                </div>
                                <div class="list-group list-group-flush bg-transparent mt-2">
                                    <div class="list-group-item bg-transparent border-0 px-0 py-2 d-flex justify-content-between small">
                                        <span class="text-muted">Metode:</span>
                                        <span class="badge rounded-pill {{ $barter->method_selection == 'protection' ? 'bg-primary' : 'bg-secondary' }} px-2 py-1">
                                            {{ ucfirst($barter->method_selection) }}
                                        </span>
                                    </div>
                                    <div class="list-group-item bg-transparent border-0 px-0 py-2 d-flex justify-content-between small">
                                        <span class="text-muted">Partner:</span>
                                        <span class="fw-bold">{{ $partnerName }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- MODAL SYARAT & KETENTUAN --}}
<div class="modal fade" id="termsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 bg-light p-4">
                <h5 class="modal-title fw-bold" style="color: #800000; font-family: 'Playfair Display';">Syarat & Ketentuan Barter</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 text-dark small" style="line-height: 1.6;">
                <p>1. <strong>Standard Barter:</strong> Pengiriman dilakukan secara mandiri. Platform tidak bertanggung jawab atas kondisi fisik barang.</p>
                <p>2. <strong>Trade Protection:</strong> Admin akan melakukan Quality Control (QC) di gudang Bandung sebelum barang diteruskan.</p>
                <p>3. <strong>Biaya Protection:</strong> Rp 25.000 per user untuk menutupi biaya operasional gudang dan verifikator.</p>
                <p>4. <strong>Pembatalan:</strong> Hanya dapat dilakukan jika kedua pihak belum memasukkan nomor resi.</p>
            </div>
            <div class="modal-footer border-0 p-4">
                <button type="button" class="btn text-white w-100 rounded-pill fw-bold py-2 shadow-sm" style="background-color: #800000;" data-bs-dismiss="modal">Saya Mengerti</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL CANCEL (SAMA SEPERTI SEBELUMNYA) --}}
<div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
            <form action="{{ route('barter.cancel', $barter->id) }}" method="POST">
                @csrf
                <div class="modal-body p-4 text-center">
                    <i class="bi bi-exclamation-triangle text-warning display-4 mb-3"></i>
                    <h5 class="fw-bold">Batalkan Barter?</h5>
                    <p class="small text-muted mb-4">Barang akan kembali tersedia di Barter Area untuk user lain.</p>
                    <div class="text-start mb-4">
                        <label class="small fw-bold text-muted mb-2">ALASAN PEMBATALAN</label>
                        <select name="reason" class="form-select rounded-3 shadow-none" required>
                            <option value="" disabled selected>Pilih Alasan</option>
                            <option value="Berubah pikiran">Berubah pikiran</option>
                            <option value="Kondisi barang tidak sesuai deskripsi">Kondisi barang tidak sesuai deskripsi</option>
                            <option value="Ongkir terlalu mahal">Ongkir terlalu mahal</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light rounded-pill flex-grow-1 fw-bold" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-danger rounded-pill flex-grow-1 fw-bold">Ya, Batalkan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .method-card { cursor: pointer; transition: 0.3s; background: white; border: 2px solid #f1f1f1 !important; }
    .method-card:hover { border-color: #ffcccc !important; transform: translateY(-2px); }
    .method-card.selected { border-color: #800000 !important; background: #fff9f9 !important; }
    .check-icon { display: none; color: #800000; font-size: 1.2rem; }
    .method-card.selected .check-icon { display: block; }
    .bg-soft-primary { background-color: #e7f1ff; }
    .text-maroon { color: #800000 !important; }
    .animate-fade-in { animation: fadeIn 0.4s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
    document.querySelectorAll('.method-card').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.method-card').forEach(c => c.classList.remove('selected'));
            this.classList.add('selected');
            this.querySelector('input[type="radio"]').checked = true;
        });
    });
</script>
@endsection