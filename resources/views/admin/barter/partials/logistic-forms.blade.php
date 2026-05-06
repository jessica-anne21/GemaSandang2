{{-- Label Header --}}
<label class="small text-muted text-uppercase fw-bold mb-3 d-block border-bottom pb-2">
    Detail Barang & Manajemen Logistik
</label>

{{-- BLOK UNTUK SENDER --}}
<div class="p-3 bg-light rounded-4 mb-3 border border-dashed shadow-sm">
    <div class="d-flex justify-content-between align-items-start mb-2">
        <p class="small fw-bold mb-0 text-dark">
            <i class="bi bi-box-seam me-1"></i> Barang {{ explode(' ', $b->sender->name)[0] }}
        </p>
        <span class="badge bg-dark rounded-pill" style="font-size: 0.6rem;">{{ strtoupper($b->shipping_method) }}</span>
    </div>

    {{-- Info Barang & Deskripsi --}}
    <div class="p-2 bg-white rounded-3 border border-maroon border-opacity-10 mb-2">
        <h6 class="fw-bold mb-1 text-maroon" style="font-size: 0.85rem;">{{ $b->offeredItem->nama_barang }}</h6>
        <p class="small text-muted mb-2 italic" style="font-size: 0.75rem; line-height: 1.2;">
            "{{ $b->offeredItem->deskripsi ?? 'Tidak ada deskripsi.' }}"
        </p>
        <hr class="my-2 opacity-25">
        <div class="small text-dark" style="font-size: 0.75rem;">
            <div class="mb-1"><strong>User:</strong> {{ $b->sender->name }}</div>
            <div class="mb-1"><strong>No. HP:</strong> {{ $b->sender->nomor_hp ?? '-' }}</div>
            <div><strong>Alamat:</strong> {{ $b->sender->alamat ?? '-' }}</div>
        </div>
    </div>

    {{-- TAMPILKAN ALASAN JIKA REJECTED --}}
    @if($b->status == 'rejected' || $b->status == 'cancelled')
        <div class="p-2 mt-2 bg-white rounded-3 border border-danger border-opacity-25 shadow-sm">
            <small class="text-danger fw-bold d-block mb-1" style="font-size: 0.65rem;">
                <i class="bi bi-x-circle-fill me-1"></i> ALASAN PENOLAKAN:
            </small>
            <p class="mb-0 small text-dark fw-medium" style="font-size: 0.75rem;">
                {{ $b->cancel_reason ?? 'User tidak menyertakan alasan spesifik.' }}
            </p>
        </div>
    @endif

    {{-- KONTROL LOGISTIK (Hanya Muncul jika Premium/VVIP & Bukan Status Rejected) --}}
    @if($b->shipping_method !== 'standard' && !in_array($b->status, ['rejected', 'cancelled']))
        <div class="mt-3 pt-2 border-top">
            <div class="mb-2 p-2 bg-soft-maroon rounded-3 border border-maroon border-opacity-10">
                <small class="text-muted d-block" style="font-size: 0.7rem;">Resi dari User:</small>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-maroon">{{ $b->sender_resi ?? 'Belum Diinput' }}</span>
                    @if($b->sender_resi)
                        <button class="btn btn-link btn-sm p-0 text-decoration-none" onclick="navigator.clipboard.writeText('{{ $b->sender_resi }}'); alert('Resi disalin!')">
                            <i class="bi bi-clipboard small"></i>
                        </button>
                    @endif
                </div>
            </div>

            <form action="{{ route('admin.barter.update-logistic', [$b->id, 'sender']) }}" method="POST">
                @csrf
                <select name="logistic_status" class="form-select form-select-sm rounded-pill mb-2 border-maroon border-opacity-25 shadow-none">
                    <option value="pending" {{ $b->sender_logistic_status == 'pending' ? 'selected' : '' }}>Menunggu Kurir User</option>
                    <option value="at_warehouse" {{ $b->sender_logistic_status == 'at_warehouse' ? 'selected' : '' }}>Tiba di Gudang</option>
                    <option value="qc_process" {{ $b->sender_logistic_status == 'qc_process' ? 'selected' : '' }}>Sedang Proses QC</option>
                    <option value="shipped_to_receiver" {{ $b->sender_logistic_status == 'shipped_to_receiver' ? 'selected' : '' }}>Lolos & Kirim ke Receiver</option>
                </select>
                <div class="input-group input-group-sm mb-2 shadow-sm">
                    <span class="input-group-text bg-white border-maroon border-opacity-25 rounded-start-pill text-maroon small">RESI BALIK</span>
                    <input type="text" name="admin_resi" class="form-control border-maroon border-opacity-25 rounded-end-pill" 
                           placeholder="Input Resi Baru..." value="{{ $b->resi_from_admin_to_receiver }}">
                </div>
                <button class="btn btn-dark btn-sm w-100 rounded-pill fw-bold">Update Logistik Sender</button>
            </form>
        </div>
    @endif
</div>

{{-- BLOK UNTUK RECEIVER --}}
<div class="p-3 bg-light rounded-4 border border-dashed shadow-sm">
    <div class="d-flex justify-content-between align-items-start mb-2">
        <p class="small fw-bold mb-0 text-dark">
            <i class="bi bi-box-seam me-1"></i> Barang {{ explode(' ', $b->receiver->name)[0] }}
        </p>
        <span class="badge bg-dark rounded-pill" style="font-size: 0.6rem;">{{ strtoupper($b->shipping_method) }}</span>
    </div>

    {{-- Info Barang & Deskripsi --}}
    <div class="p-2 bg-white rounded-3 border border-maroon border-opacity-10 mb-2">
        <h6 class="fw-bold mb-1 text-maroon" style="font-size: 0.85rem;">{{ $b->requestedItem->nama_barang }}</h6>
        <p class="small text-muted mb-2 italic" style="font-size: 0.75rem; line-height: 1.2;">
            "{{ $b->requestedItem->deskripsi ?? 'Tidak ada deskripsi.' }}"
        </p>
        <hr class="my-2 opacity-25">
        <div class="small text-dark" style="font-size: 0.75rem;">
            <div class="mb-1"><strong>User:</strong> {{ $b->receiver->name }}</div>
            <div class="mb-1"><strong>No. HP:</strong> {{ $b->receiver->nomor_hp ?? '-' }}</div>
            <div><strong>Alamat:</strong> {{ $b->receiver->alamat ?? '-' }}</div>
        </div>
    </div>

    {{-- TAMPILKAN ALASAN JIKA REJECTED --}}
    @if($b->status == 'rejected' || $b->status == 'cancelled')
        <div class="p-2 mt-2 bg-white rounded-3 border border-danger border-opacity-25 shadow-sm">
            <small class="text-danger fw-bold d-block mb-1" style="font-size: 0.65rem;">
                <i class="bi bi-x-circle-fill me-1"></i> ALASAN PENOLAKAN:
            </small>
            <p class="mb-0 small text-dark fw-medium" style="font-size: 0.75rem;">
                {{ $b->cancel_reason ?? 'User tidak menyertakan alasan spesifik.' }}
            </p>
        </div>
    @endif

    {{-- KONTROL LOGISTIK (Hanya Muncul jika Premium/VVIP & Bukan Status Rejected) --}}
    @if($b->shipping_method !== 'standard' && !in_array($b->status, ['rejected', 'cancelled']))
        <div class="mt-3 pt-2 border-top">
            <div class="mb-2 p-2 bg-soft-maroon rounded-3 border border-maroon border-opacity-10">
                <small class="text-muted d-block" style="font-size: 0.7rem;">Resi dari User:</small>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-maroon">{{ $b->receiver_resi ?? 'Belum Diinput' }}</span>
                    @if($b->receiver_resi)
                        <button class="btn btn-link btn-sm p-0 text-decoration-none" onclick="navigator.clipboard.writeText('{{ $b->receiver_resi }}'); alert('Resi disalin!')">
                            <i class="bi bi-clipboard small"></i>
                        </button>
                    @endif
                </div>
            </div>

            <form action="{{ route('admin.barter.update-logistic', [$b->id, 'receiver']) }}" method="POST">
                @csrf
                <select name="logistic_status" class="form-select form-select-sm rounded-pill mb-2 border-maroon border-opacity-25 shadow-none">
                    <option value="pending" {{ $b->receiver_logistic_status == 'pending' ? 'selected' : '' }}>Menunggu Kurir User</option>
                    <option value="at_warehouse" {{ $b->receiver_logistic_status == 'at_warehouse' ? 'selected' : '' }}>Tiba di Gudang</option>
                    <option value="qc_process" {{ $b->receiver_logistic_status == 'qc_process' ? 'selected' : '' }}>Sedang Proses QC</option>
                    <option value="shipped_to_sender" {{ $b->receiver_logistic_status == 'shipped_to_sender' ? 'selected' : '' }}>Lolos & Kirim ke Sender</option>
                </select>
                <div class="input-group input-group-sm mb-2 shadow-sm">
                    <span class="input-group-text bg-white border-maroon border-opacity-25 rounded-start-pill text-maroon small">RESI BALIK</span>
                    <input type="text" name="admin_resi" class="form-control border-maroon border-opacity-25 rounded-end-pill" 
                           placeholder="Input Resi Baru..." value="{{ $b->resi_from_admin_to_sender }}">
                </div>
                <button class="btn btn-dark btn-sm w-100 rounded-pill fw-bold">Update Logistik Receiver</button>
            </form>
        </div>
    @endif
</div>