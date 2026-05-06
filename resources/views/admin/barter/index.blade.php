@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0" style="color: #800000; font-family: 'Playfair Display', serif;">Monitoring Barter</h3>
        <span class="badge bg-soft-maroon text-maroon px-3 py-2 rounded-pill shadow-sm">Total: {{ $barters->total() }} Transaksi</span>
    </div>

    {{-- Alert Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">ID Transaksi</th>
                        <th>Para Pihak</th>
                        <th>Metode</th>
                        <th>Status Pembayaran (S | R)</th>
                        <th>Status Barter</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barters as $b)
                    <tr>
                        <td class="ps-4 fw-bold text-muted">#{{ str_pad($b->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold text-dark">{{ explode(' ', $b->sender->name)[0] }}</span>
                                <i class="bi bi-arrow-left-right text-muted small"></i>
                                <span class="fw-bold text-dark">{{ explode(' ', $b->receiver->name)[0] }}</span>
                            </div>
                        </td>
                        <td>
                            @if($b->method_selection == 'protection')
                                <span class="badge rounded-pill bg-info text-dark" style="font-size: 0.7rem;"><i class="bi bi-shield-check me-1"></i>Trade Protection</span>
                            @else
                                <span class="badge rounded-pill bg-secondary" style="font-size: 0.7rem;">Standard</span>
                            @endif
                        </td>
                        <td>
                            @if($b->method_selection == 'protection')
                                <div class="d-flex gap-2">
                                    {{-- Status Sender --}}
                                    @if($b->sender_payment_status == 'paid')
                                        <i class="bi bi-person-check-fill text-success" title="Sender Paid"></i>
                                    @elseif($b->sender_payment_proof)
                                        <i class="bi bi-person-exclamation text-warning animate-pulse" title="Sender Waiting Verification"></i>
                                    @else
                                        <i class="bi bi-person-x text-muted" title="Sender Not Paid"></i>
                                    @endif

                                    <span class="text-muted">|</span>

                                    {{-- Status Receiver --}}
                                    @if($b->receiver_payment_status == 'paid')
                                        <i class="bi bi-person-check-fill text-success" title="Receiver Paid"></i>
                                    @elseif($b->receiver_payment_proof)
                                        <i class="bi bi-person-exclamation text-warning animate-pulse" title="Receiver Waiting Verification"></i>
                                    @else
                                        <i class="bi bi-person-x text-muted" title="Receiver Not Paid"></i>
                                    @endif
                                </div>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $b->status == 'completed' ? 'bg-success' : ($b->status == 'pending' ? 'bg-warning text-dark' : 'bg-primary') }}" style="font-size: 0.7rem;">
                                {{ strtoupper($b->status) }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-bold shadow-sm" 
                                    data-bs-toggle="offcanvas" 
                                    data-bs-target="#offcanvasBarter{{ $b->id }}">
                                <i class="bi bi-search me-1"></i> Detail
                            </button>
                        </td>
                    </tr>

                    {{-- OFFCANVAS DETAIL (PANEL SAMPING) --}}
                    <div class="offcanvas offcanvas-end border-0 shadow-lg" tabindex="-1" id="offcanvasBarter{{ $b->id }}" style="width: 500px; border-radius: 2rem 0 0 2rem;">
                        <div class="offcanvas-header bg-light p-4">
                            <h5 class="offcanvas-title fw-bold" style="color: #800000;">Rincian Barter #{{ $b->id }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                        </div>
                        <div class="offcanvas-body p-4">
                            {{-- Info User & Items --}}
                            <div class="mb-4 text-center">
                                <div class="row align-items-center g-2">
                                    <div class="col-5">
                                        <img src="{{ asset('storage/' . $b->offeredItem->foto_barang) }}" class="rounded-3 mb-2 shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
                                        <div class="small fw-bold">{{ $b->sender->name }}</div>
                                    </div>
                                    <div class="col-2">
                                        <i class="bi bi-arrow-left-right fs-3 text-maroon"></i>
                                    </div>
                                    <div class="col-5">
                                        <img src="{{ asset('storage/' . $b->requestedItem->foto_barang) }}" class="rounded-3 mb-2 shadow-sm" style="width: 80px; height: 80px; object-fit: cover;">
                                        <div class="small fw-bold">{{ $b->receiver->name }}</div>
                                    </div>
                                </div>
                            </div>

                            {{-- VERIFIKASI PEMBAYARAN (Hanya jika Protection) --}}
                            @if($b->method_selection == 'protection')
                            <label class="small text-muted text-uppercase fw-bold mb-3 d-block border-bottom pb-2">Verifikasi Pembayaran (Split Bill)</label>
                            
                            <div class="row g-3 mb-4">
                                {{-- KOLOM SENDER --}}
                                <div class="col-6 text-center">
                                    <div class="p-3 rounded-4 bg-light border h-100">
                                        <p class="small fw-bold mb-2">Sender: {{ explode(' ', $b->sender->name)[0] }}</p>
                                        @if($b->sender_payment_proof)
                                            <img src="{{ asset('storage/' . $b->sender_payment_proof) }}" class="img-fluid rounded-3 mb-3 shadow-sm border img-zoomable" onclick="window.open(this.src)">
                                            @if($b->sender_payment_status != 'paid')
                                                <div class="d-grid gap-2">
                                                    <form action="{{ route('admin.barter.verify-payment', [$b->id, 'sender']) }}" method="POST">@csrf<button type="submit" class="btn btn-success btn-sm w-100 rounded-pill fw-bold">Approve</button></form>
                                                    <form action="{{ route('admin.barter.reject-payment', [$b->id, 'sender']) }}" method="POST">@csrf<button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-pill fw-bold">Reject</button></form>
                                                </div>
                                            @else
                                                <span class="badge bg-success w-100 rounded-pill"><i class="bi bi-check-circle me-1"></i>Verified</span>
                                            @endif
                                        @else
                                            <div class="text-center py-4 text-muted"><i class="bi bi-hourglass small"></i><p style="font-size: 0.65rem;">Belum Upload</p></div>
                                        @endif
                                    </div>
                                </div>

                                {{-- KOLOM RECEIVER --}}
                                <div class="col-6 text-center">
                                    <div class="p-3 rounded-4 bg-light border h-100">
                                        <p class="small fw-bold mb-2">Receiver: {{ explode(' ', $b->receiver->name)[0] }}</p>
                                        @if($b->receiver_payment_proof)
                                            <img src="{{ asset('storage/' . $b->receiver_payment_proof) }}" class="img-fluid rounded-3 mb-3 shadow-sm border img-zoomable" onclick="window.open(this.src)">
                                            @if($b->receiver_payment_status != 'paid')
                                                <div class="d-grid gap-2">
                                                    <form action="{{ route('admin.barter.verify-payment', [$b->id, 'receiver']) }}" method="POST">@csrf<button type="submit" class="btn btn-success btn-sm w-100 rounded-pill fw-bold">Approve</button></form>
                                                    <form action="{{ route('admin.barter.reject-payment', [$b->id, 'receiver']) }}" method="POST">@csrf<button type="submit" class="btn btn-outline-danger btn-sm w-100 rounded-pill fw-bold">Reject</button></form>
                                                </div>
                                            @else
                                                <span class="badge bg-success w-100 rounded-pill"><i class="bi bi-check-circle me-1"></i>Verified</span>
                                            @endif
                                        @else
                                            <div class="text-center py-4 text-muted"><i class="bi bi-hourglass small"></i><p style="font-size: 0.65rem;">Belum Upload</p></div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- MANAJEMEN LOGISTIK GUDANG (Hanya jika Keduanya Sudah Bayar) --}}
                            @if($b->sender_payment_status == 'paid' && $b->receiver_payment_status == 'paid')
                            <label class="small text-muted text-uppercase fw-bold mb-3 d-block border-bottom pb-2">Manajemen Logistik & QC</label>
                            
                            {{-- Update Barang dari Sender (Akan dikirim ke Receiver) --}}
                            <div class="p-3 bg-light rounded-4 mb-3 border">
                                <p class="small fw-bold mb-2 text-dark">Barang dari {{ explode(' ', $b->sender->name)[0] }} (QC Process)</p>
                                <form action="{{ route('admin.barter.update-logistic', [$b->id, 'sender']) }}" method="POST">
                                    @csrf
                                    <select name="logistic_status" class="form-select form-select-sm rounded-pill mb-2 shadow-sm">
                                        <option value="pending" {{ $b->sender_logistic_status == 'pending' ? 'selected' : '' }}>Menunggu Kurir User</option>
                                        <option value="at_warehouse" {{ $b->sender_logistic_status == 'at_warehouse' ? 'selected' : '' }}>Tiba di Gudang Gema Sandang</option>
                                        <option value="qc_process" {{ $b->sender_logistic_status == 'qc_process' ? 'selected' : '' }}>Sedang Proses QC (Pengecekan)</option>
                                        <option value="shipped_to_receiver" {{ $b->sender_logistic_status == 'shipped_to_receiver' ? 'selected' : '' }}>Lolos QC & Kirim ke Penerima</option>
                                    </select>
                                    <input type="text" name="admin_resi" class="form-control form-control-sm rounded-pill shadow-sm" placeholder="Resi Baru (Admin -> Receiver)" value="{{ $b->resi_from_admin_to_receiver }}">
                                    <button class="btn btn-dark btn-sm w-100 rounded-pill mt-2 fw-bold shadow-sm">Update Logistik Sender</button>
                                </form>
                            </div>

                            {{-- Update Barang dari Receiver (Akan dikirim ke Sender) --}}
                            <div class="p-3 bg-light rounded-4 mb-3 border">
                                <p class="small fw-bold mb-2 text-dark">Barang dari {{ explode(' ', $b->receiver->name)[0] }} (QC Process)</p>
                                <form action="{{ route('admin.barter.update-logistic', [$b->id, 'receiver']) }}" method="POST">
                                    @csrf
                                    <select name="logistic_status" class="form-select form-select-sm rounded-pill mb-2 shadow-sm">
                                        <option value="pending" {{ $b->receiver_logistic_status == 'pending' ? 'selected' : '' }}>Menunggu Kurir User</option>
                                        <option value="at_warehouse" {{ $b->receiver_logistic_status == 'at_warehouse' ? 'selected' : '' }}>Tiba di Gudang Gema Sandang</option>
                                        <option value="qc_process" {{ $b->receiver_logistic_status == 'qc_process' ? 'selected' : '' }}>Sedang Proses QC (Pengecekan)</option>
                                        <option value="shipped_to_sender" {{ $b->receiver_logistic_status == 'shipped_to_sender' ? 'selected' : '' }}>Lolos QC & Kirim ke Pengirim</option>
                                    </select>
                                    <input type="text" name="admin_resi" class="form-control form-control-sm rounded-pill shadow-sm" placeholder="Resi Baru (Admin -> Sender)" value="{{ $b->resi_from_admin_to_sender }}">
                                    <button class="btn btn-dark btn-sm w-100 rounded-pill mt-2 fw-bold shadow-sm">Update Logistik Receiver</button>
                                </form>
                            </div>
                            @else
                                <div class="alert alert-secondary rounded-4 py-3 small text-center">
                                    <i class="bi bi-lock-fill me-1"></i> Manajemen logistik terbuka setelah <strong>kedua user</strong> melunasi biaya proteksi.
                                </div>
                            @endif
                            @endif

                            {{-- Resi User (Info Kurir) --}}
                            @if($b->terms_accepted)
                            <div class="mt-4 pt-3 border-top">
                                <label class="small text-muted text-uppercase fw-bold mb-2 d-block">Resi Pengiriman User</label>
                                <div class="p-3 bg-light rounded-4 border">
                                    <div class="small mb-2 d-flex justify-content-between">
                                        <span>Resi {{ explode(' ', $b->sender->name)[0] }}:</span>
                                        <span class="fw-bold">{{ $b->sender_resi ?? 'Belum Input' }}</span>
                                    </div>
                                    <div class="small d-flex justify-content-between">
                                        <span>Resi {{ explode(' ', $b->receiver->name)[0] }}:</span>
                                        <span class="fw-bold">{{ $b->receiver_resi ?? 'Belum Input' }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-3 bg-light border-top">
            {{ $barters->links() }}
        </div>
    </div>
</div>

<style>
    .bg-soft-maroon { background-color: #fff0f0; }
    .text-maroon { color: #800000; }
    .img-zoomable:hover { transform: scale(1.05); transition: 0.3s; cursor: pointer; }
    .animate-pulse { animation: pulse 1.5s infinite; }
    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.2); opacity: 0.7; }
        100% { transform: scale(1); opacity: 1; }
    }
</style>
@endsection