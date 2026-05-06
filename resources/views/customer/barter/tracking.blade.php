@extends('layouts.main')

@section('content')
@php
    $isSender = (auth()->id() == $barter->sender_id);
    
    // Identitas & Item
    $partner = $isSender ? $barter->receiver : $barter->sender;
    $partnerName = $partner->name;
    $myItem = $isSender ? $barter->offeredItem : $barter->requestedItem;
    $partnerItem = $isSender ? $barter->requestedItem : $barter->offeredItem;

    // Status Pembayaran & Logistik Saya
    $myStatus = $isSender ? $barter->sender_payment_status : $barter->receiver_payment_status;
    $myProof = $isSender ? $barter->sender_payment_proof : $barter->receiver_payment_proof;
    $myLogStatus = $isSender ? $barter->sender_logistic_status : $barter->receiver_logistic_status;
    $adminResiToMe = $isSender ? $barter->resi_from_admin_to_sender : $barter->resi_from_admin_to_receiver;
    $myResi = $isSender ? $barter->sender_resi : $barter->receiver_resi;
    $hasIConfirmed = $isSender ? $barter->sender_confirmed_at : $barter->receiver_confirmed_at;

    // Status Logistik Partner (Untuk resi-form)
    $partnerLogStatus = $isSender ? $barter->receiver_logistic_status : $barter->sender_logistic_status;
    $partnerResi = $isSender ? $barter->receiver_resi : $barter->sender_resi;
@endphp

<div class="container py-5" style="background-color: #fdf5f5; min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            
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
                    <div class="position-absolute top-50 start-0 translate-middle-y w-100 bg-light shadow-inner" style="height: 4px; z-index: 1;"></div>
                    
                    @php 
                        $isRejected = ($barter->status == 'rejected_qc' || $barter->status == 'cancelled');
                        $steps = [
                            ['icon' => 'bi-hand-thumbs-up-fill', 'label' => 'Deal', 'done' => true, 'fail' => false],
                            ['icon' => $isRejected ? 'bi-x-circle-fill' : 'bi-shield-lock-fill', 'label' => 'QC & Logistik', 'done' => $barter->terms_accepted, 'fail' => $isRejected],
                            ['icon' => 'bi-check-all', 'label' => 'Selesai', 'done' => ($barter->status == 'completed'), 'fail' => false]
                        ];
                    @endphp

                    @foreach($steps as $step)
                    <div class="position-relative" style="z-index: 2; width: 80px;">
                        <div class="rounded-circle {{ $step['fail'] ? 'bg-danger text-white' : ($step['done'] ? 'bg-maroon text-white shadow-maroon' : 'bg-white border text-muted') }} mx-auto mb-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 50px; height: 50px; transition: 0.3s;">
                            <i class="{{ $step['icon'] }} fs-5"></i>
                        </div>
                        <small class="fw-bold d-block x-small {{ $step['fail'] ? 'text-danger' : ($step['done'] ? 'text-maroon' : 'text-muted') }}">{{ $step['label'] }}</small>
                    </div>
                    @endforeach
                </div>

                @if($barter->status == 'completed')
                    <div class="text-center py-5 animate-fade-in">
                        <i class="bi bi-patch-check-fill text-success mb-4" style="font-size: 5rem;"></i>
                        <h3 class="fw-bold">Barter Berhasil!</h3>
                        <p class="text-muted">Barang pilihanmu sudah resmi berpindah tangan.</p>
                        <a href="{{ route('barter.inbox') }}" class="btn btn-maroon rounded-pill px-5 fw-bold shadow">Ke Riwayat Barter</a>
                    </div>

                @elseif($barter->status == 'rejected_qc')
                    {{-- TAMPILAN JIKA GAGAL QC ADMIN --}}
                    <div class="text-center py-5 animate-fade-in">
                        <div class="mb-4">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 100px; height: 100px; background-color: #fff0f0; border: 2px solid #dc3545;">
                                <i class="bi bi-shield-x text-danger" style="font-size: 3rem;"></i>
                            </div>
                        </div>
                        <h3 class="fw-bold text-dark">Barter Dibatalkan (Gagal QC)</h3>
                        <p class="text-muted mx-auto mb-4" style="max-width: 500px;">Mohon maaf, Admin Gema Sandang memutuskan untuk membatalkan barter ini karena barang tidak lolos pengecekan kualitas.</p>
                        
                        <div class="card border-0 rounded-4 p-4 mx-auto mb-4 bg-light shadow-sm" style="max-width: 600px;">
                            <h6 class="fw-bold text-danger text-uppercase x-small mb-3 tracking-wider"><i class="bi bi-exclamation-triangle-fill me-2"></i>Catatan Admin:</h6>
                            <p class="mb-0 italic text-dark fw-medium">"{{ $barter->admin_note ?? 'Kondisi barang tidak sesuai dengan deskripsi yang diinput.' }}"</p>
                        </div>

                        <div class="alert alert-warning border-0 rounded-4 x-small d-inline-block px-4">
                            <i class="bi bi-info-circle me-2"></i> Barang akan dikirim kembali ke alamat masing-masing user.
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('barter.inbox') }}" class="btn btn-outline-dark rounded-pill px-4 fw-bold">Tutup</a>
                        </div>
                    </div>

                @elseif($barter->status == 'cancelled')
                    <div class="text-center py-5 animate-fade-in">
                        <i class="bi bi-x-circle-fill text-danger mb-4" style="font-size: 5rem;"></i>
                        <h3 class="fw-bold">Transaksi Dibatalkan</h3>
                        <p class="text-muted italic">Alasan: {{ $barter->cancel_reason ?? 'Dibatalkan oleh pengguna' }}</p>
                        <a href="{{ route('barter.inbox') }}" class="btn btn-outline-dark rounded-pill px-4 mt-3">Kembali</a>
                    </div>

                @elseif(!$barter->terms_accepted)
                    {{-- UI PILIH METODE --}}
                    @include('customer.barter.partials.method-selector')
                @else
                    {{-- TRACKING AKTIF --}}
                    <div class="row g-4">
                        <div class="col-md-7 border-end pe-md-4">
                            {{-- Pembayaran --}}
                            @if($barter->method_selection == 'protection' && $myStatus != 'paid')
                                <div class="p-4 rounded-4 border border-warning shadow-sm mb-4" style="background-color: #fffdf5;">
                                    <h6 class="fw-bold mb-3 text-warning-emphasis"><i class="bi bi-wallet2 me-2"></i>Biaya Layanan Protection</h6>
                                    @if(!$myProof)
                                        <p class="x-small text-muted mb-3">Transfer <strong>Rp 25.000</strong> ke BCA: <strong>1234 5678 90</strong> (Gema Sandang)</p>
                                        <form action="{{ route('barter.upload-payment', $barter->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="input-group input-group-sm">
                                                <input type="file" name="payment_proof" class="form-control rounded-start-pill" required>
                                                <button class="btn btn-warning text-white fw-bold px-3 rounded-end-pill">Kirim</button>
                                            </div>
                                        </form>
                                    @else
                                        <div class="text-center py-2 text-warning-emphasis fw-bold small">
                                            <div class="spinner-border spinner-border-sm me-2"></div> Menunggu Validasi Pembayaran
                                        </div>
                                    @endif
                                </div>
                            @endif

                            {{-- Form & Status Logistik --}}
                            <div class="p-3 rounded-4 x-small mb-4 border {{ $barter->method_selection == 'protection' ? 'bg-soft-primary border-primary border-opacity-10 text-primary' : 'bg-light text-dark' }}">
                                <i class="bi bi-geo-alt-fill me-1"></i>
                                <strong>Tujuan Kiriman Anda:</strong>
                                {{ $barter->method_selection == 'protection' ? 'Gudang QC Gema Sandang (Jl. Maranatha 123, Bandung)' : ($partner->name . ' | ' . $partner->alamat) }}
                            </div>

                            @include('customer.barter.partials.resi-form')

                            {{-- Status Barang di Gudang (Protection Only) --}}
                            @if($barter->method_selection == 'protection' && $myStatus == 'paid')
                                <div class="mt-4 pt-4 border-top">
                                    <label class="x-small fw-bold text-maroon mb-3 d-block text-uppercase tracking-wider">Update Gudang Gema Sandang</label>
                                    <div class="p-3 rounded-4 bg-light border d-flex flex-column gap-3">
                                        <div class="d-flex align-items-center gap-3">
                                            @if(Str::contains($myLogStatus, 'shipped')) 
                                                <div class="p-2 bg-success text-white rounded-circle"><i class="bi bi-check-lg"></i></div>
                                            @else 
                                                <div class="spinner-border spinner-border-sm text-maroon"></div> 
                                            @endif
                                            <div class="x-small fw-bold text-dark">
                                                @if($myLogStatus == 'pending') Menunggu barangmu tiba.
                                                @elseif($myLogStatus == 'at_warehouse') Barang sudah kami terima.
                                                @elseif($myLogStatus == 'qc_process') Sedang tahap pemeriksaan kualitas.
                                                @else Lolos QC! Barang sedang meluncur ke kamu. @endif
                                            </div>
                                        </div>
                                        @if($adminResiToMe)
                                            <div class="p-2 rounded-3 bg-white border border-success border-opacity-50 d-flex justify-content-between align-items-center">
                                                <span class="x-small fw-bold text-success"><i class="bi bi-truck me-1"></i>Resi Admin ke Kamu: {{ $adminResiToMe }}</span>
                                                <button class="btn btn-sm p-0 text-success" onclick="navigator.clipboard.writeText('{{ $adminResiToMe }}'); alert('Resi disalin!')"><i class="bi bi-clipboard"></i></button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- SIDEBAR RINGKASAN --}}
                        <div class="col-md-5">
                            <div class="card border-0 bg-light rounded-4 p-4 shadow-sm sticky-top" style="top: 20px;">
                                <h6 class="fw-bold text-maroon mb-4 border-bottom pb-2 x-small text-uppercase tracking-wider">Ringkasan Barter</h6>
                                <div class="row g-2 align-items-center mb-4 text-center">
                                    <div class="col-5">
                                        <div class="p-1 bg-white rounded-3 shadow-sm mb-2">
                                            <img src="{{ asset('storage/' . $myItem->foto_barang) }}" class="rounded-2 w-100" style="height: 80px; object-fit: cover;">
                                        </div>
                                        <p class="x-small fw-bold mb-0 text-truncate">Milik Saya</p>
                                    </div>
                                    <div class="col-2"><i class="bi bi-arrow-left-right text-muted opacity-50"></i></div>
                                    <div class="col-5">
                                        <div class="p-1 bg-white rounded-3 shadow-sm mb-2">
                                            <img src="{{ asset('storage/' . $partnerItem->foto_barang) }}" class="rounded-2 w-100" style="height: 80px; object-fit: cover;">
                                        </div>
                                        <p class="x-small fw-bold mb-0 text-truncate">Partner</p>
                                    </div>
                                </div>
                                <div class="x-small d-flex flex-column gap-2">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Metode:</span>
                                        <span class="badge {{ $barter->method_selection == 'protection' ? 'bg-primary' : 'bg-dark' }} rounded-pill">{{ ucfirst($barter->method_selection) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="text-muted">Partner:</span>
                                        <span class="fw-bold text-dark">{{ $partner->name }}</span>
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

<style>
    .btn-maroon { background-color: #800000; color: white; border: none; }
    .btn-maroon:hover { background-color: #600000; color: white; }
    .text-maroon { color: #800000 !important; }
    .bg-soft-maroon { background-color: #fff0f0; }
    .bg-soft-primary { background-color: #e7f1ff; }
    .x-small { font-size: 0.75rem; }
    .tracking-wider { letter-spacing: 0.05em; }
    .shadow-maroon { box-shadow: 0 0 15px rgba(128, 0, 0, 0.2); }
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .shadow-inner { box-shadow: inset 0 2px 4px rgba(0,0,0,0.05); }
    .italic { font-style: italic; }
</style>
@endsection