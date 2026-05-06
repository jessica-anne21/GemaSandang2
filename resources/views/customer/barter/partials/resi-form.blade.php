@php
    $isSender = (auth()->id() == $barter->sender_id);
    $myResi = $isSender ? $barter->sender_resi : $barter->receiver_resi;
    $partnerResi = $isSender ? $barter->receiver_resi : $barter->sender_resi;
    $partnerName = $isSender ? $barter->receiver->name : $barter->sender->name;
    
    $hasIConfirmed = $isSender ? $barter->sender_confirmed_at : $barter->receiver_confirmed_at;
    $hasPartnerConfirmed = $isSender ? $barter->receiver_confirmed_at : $barter->sender_confirmed_at;
    
    // Logic Tombol Konfirmasi Muncul
    if($barter->method_selection == 'protection') {
        // Harus resi balik admin ke SENDER & RECEIVER ada semua
        $canConfirm = ($barter->resi_from_admin_to_sender && $barter->resi_from_admin_to_receiver);
    } else {
        // Cukup resi kedua pihak (direct)
        $canConfirm = ($barter->sender_resi && $barter->receiver_resi);
    }
@endphp

<div class="resi-section">
    {{-- 1. INPUT RESI SAYA --}}
    @if(!in_array($barter->status, ['completed', 'rejected_qc', 'cancelled']))
    <div class="card border-0 bg-white shadow-sm rounded-4 p-3 mb-4 border-start border-4 border-maroon">
        <form action="{{ route('barter.update-resi', $barter->id) }}" method="POST">
            @csrf
            <label class="x-small fw-bold text-muted mb-2 d-block text-uppercase tracking-wider">
                <i class="bi bi-box-arrow-up me-1"></i> Resi Pengiriman Anda
            </label>
            <div class="input-group rounded-pill overflow-hidden border p-1" style="background: #f8f9fa;">
                <input type="text" name="resi" class="form-control border-0 bg-transparent px-3 py-2 x-small shadow-none" 
                       placeholder="Contoh: JNT-12345678" 
                       value="{{ $myResi }}" required>
                <button class="btn btn-maroon px-4 fw-bold rounded-pill x-small" type="submit">Update</button>
            </div>
            @if($myResi)
                <div class="mt-2 d-flex align-items-center text-success x-small fw-bold">
                    <i class="bi bi-check-circle-fill me-1"></i> Tersimpan: {{ $myResi }}
                </div>
            @endif
        </form>
    </div>
    @endif

    {{-- 2. STATUS LOGISTIK PARTNER --}}
    <div class="card border-0 bg-white shadow-sm rounded-4 p-3 mb-4">
        <label class="x-small fw-bold text-muted mb-3 d-block text-uppercase tracking-wider">
            <i class="bi bi-truck me-1 text-maroon"></i> Logistik {{ explode(' ', $partnerName)[0] }}
        </label>
        
        <div class="d-flex flex-column gap-2">
            {{-- Status Kiriman Lawan ke Gudang/Partner --}}
            <div class="d-flex justify-content-between align-items-center p-2 rounded-3 bg-light border">
                <div>
                    <span class="d-block x-small text-muted">Resi Partner:</span>
                    <span class="fw-bold text-dark x-small">{{ $partnerResi ?? 'Menunggu...' }}</span>
                </div>
                @if($partnerResi)
                    <span class="badge bg-success rounded-pill x-small px-3">Sudah Kirim</span>
                @else
                    <span class="badge bg-white text-muted border rounded-pill x-small px-3">Pending</span>
                @endif
            </div>

            {{-- Khusus Protection: Tampilkan Status Balik Gudang ke Partner --}}
            @if($barter->method_selection == 'protection')
                @php
                    $adminResiToPartner = $isSender ? $barter->resi_from_admin_to_receiver : $barter->resi_from_admin_to_sender;
                @endphp
                <div class="d-flex justify-content-between align-items-center p-2 rounded-3 bg-light border">
                    <div>
                        <span class="d-block x-small text-muted">Gudang ke Partner:</span>
                        <span class="fw-bold text-dark x-small">{{ $adminResiToPartner ?? 'Proses QC' }}</span>
                    </div>
                    @if($adminResiToPartner)
                        <i class="bi bi-check2-all text-primary fs-5 pe-2"></i>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- 3. TOMBOL KONFIRMASI PENERIMAAN --}}
    @if($barter->status != 'completed' && !in_array($barter->status, ['rejected_qc', 'cancelled']))
        <div class="mt-4 pt-3 border-top">
            @if($canConfirm)
                @if(!$hasIConfirmed)
                    <div class="alert alert-soft-primary rounded-4 border-0 shadow-sm x-small mb-3 animate-fade-in">
                        <strong>Barang Siap Dikonfirmasi!</strong><br>
                        Silakan klik tombol di bawah jika barang sudah Anda terima dan periksa.
                    </div>
                    <form action="{{ route('barter.complete', $barter->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-maroon w-100 rounded-pill fw-bold py-3 shadow btn-hover-effect">
                            Konfirmasi Terima Barang
                        </button>
                    </form>
                @else
                    <div class="text-center p-4 rounded-4 bg-light border border-dashed animate-fade-in">
                        <i class="bi bi-clock-history d-block mb-2 text-success fs-3"></i>
                        <p class="x-small fw-bold mb-0 text-success">Anda Telah Mengonfirmasi</p>
                        <p class="x-small text-muted mb-0 mt-1 italic">Menunggu konfirmasi dari <strong>{{ $partnerName }}</strong>...</p>
                    </div>
                @endif
            @else
                <div class="text-center p-3 rounded-4 bg-light opacity-75 border">
                    <p class="x-small text-muted mb-0 italic">
                        <i class="bi bi-lock me-1"></i> Tombol konfirmasi aktif setelah barang dikirim balik oleh Admin.
                    </p>
                </div>
            @endif
        </div>
    @endif
</div>