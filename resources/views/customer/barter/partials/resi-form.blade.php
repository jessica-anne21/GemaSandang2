@php
    $isSender = (auth()->id() == $barter->sender_id);
    $myResi = $isSender ? $barter->sender_resi : $barter->receiver_resi;
    $partnerResi = $isSender ? $barter->receiver_resi : $barter->sender_resi;
    $partnerName = $isSender ? $barter->receiver->name : $barter->sender->name;
@endphp

<div class="resi-section mt-2">
    <form action="{{ route('barter.update-resi', $barter->id) }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="small fw-bold text-muted mb-2 d-block text-uppercase" style="font-size: 0.65rem;">
                Nomor Resi / Nama Kurir:
            </label>
            <div class="input-group shadow-sm rounded-pill overflow-hidden border">
                <input type="text" name="resi" class="form-control border-0 px-3 py-2" 
                       placeholder="Contoh: JNT-12345678" 
                       value="{{ $myResi }}" required>
                <button class="btn btn-dark px-4 fw-bold" type="submit">Simpan</button>
            </div>
            @if($myResi)
                <small class="text-success fw-bold mt-1 d-block" style="font-size: 0.7rem;">
                    <i class="bi bi-check2-all me-1"></i> Resi tersimpan.
                </small>
            @endif
        </div>
    </form>

    {{-- Resi Partner --}}
    @if($barter->method_selection == 'standard')
        <div class="p-3 rounded-4 bg-light mb-4 border border-dashed">
            <div class="d-flex justify-content-between align-items-center">
                <span class="small text-muted">Resi Kurir Partner:</span>
                @if($partnerResi)
                    <span class="badge bg-success rounded-pill px-3">{{ $partnerResi }}</span>
                @else
                    <span class="badge bg-secondary rounded-pill px-3">Menunggu...</span>
                @endif
            </div>
        </div>
    @endif

    {{-- Bagian Konfirmasi Terima Barang --}}
<div class="mt-4 pt-4 border-top">
    @php
        $hasIConfirmed = $isSender ? $barter->sender_confirmed_at : $barter->receiver_confirmed_at;
        $hasPartnerConfirmed = $isSender ? $barter->receiver_confirmed_at : $barter->sender_confirmed_at;
        $isBothResiFilled = ($barter->sender_resi && $barter->receiver_resi);
    @endphp

    @if($barter->status != 'completed')
        @if($isBothResiFilled)
            @if(!$hasIConfirmed)
                <div class="alert alert-info rounded-4 border-0 shadow-sm small mb-3">
                    <i class="bi bi-info-circle me-2"></i> Barang sudah dikirim oleh kedua pihak. Klik tombol di bawah jika kamu sudah menerima paketnya.
                </div>
                <form action="{{ route('barter.complete', $barter->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn text-white w-100 rounded-pill fw-bold py-3 shadow-sm btn-hover-effect" style="background-color: #800000;">
                        <i class="bi bi-check2-square me-2"></i> Konfirmasi Sudah Terima Barang
                    </button>
                </form>
            @else
                {{-- Status nunggu partner --}}
                <div class="text-center p-4 rounded-4 bg-light border border-dashed">
                    <div class="spinner-border spinner-border-sm text-success mb-2"></div>
                    <p class="small fw-bold mb-0 text-success">Kamu sudah konfirmasi terima.</p>
                    @if(!$hasPartnerConfirmed)
                        <p class="x-small text-muted mb-0">Menunggu konfirmasi dari <strong>{{ $partnerName }}</strong> untuk menyelesaikan transaksi.</p>
                    @endif
                </div>
            @endif
        @else
            {{-- Kalau resi belum lengkap --}}
            <div class="text-center p-3 rounded-4 bg-light">
                <p class="x-small text-muted mb-0 italic">Tombol konfirmasi terima akan muncul setelah kedua pihak memasukkan nomor resi.</p>
            </div>
        @endif
    @endif
</div>
</div>