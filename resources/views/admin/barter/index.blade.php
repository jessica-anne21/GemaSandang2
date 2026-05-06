@extends('layouts.admin')

@section('content')
<div class="container-fluid p-4" style="background-color: #f8f9fa; min-height: 100vh;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold m-0" style="color: #800000; font-family: 'Playfair Display', serif;">Monitoring Barter</h3>
            <p class="text-muted small mb-0">Kelola antrean Quality Control dan logistik Gema Sandang.</p>
        </div>
        <span class="badge bg-soft-maroon text-maroon px-3 py-2 rounded-pill shadow-sm border border-maroon border-opacity-10">Total: {{ $barters->total() }} Transaksi</span>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">
                    <tr>
                        <th class="ps-4">ID Transaksi</th>
                        <th>Barang & Para Pihak</th>
                        <th>Metode</th>
                        <th>Status Bayar (S | R)</th>
                        <th>Status Barter</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($barters as $b)
                    <tr class="{{ in_array($b->status, ['cancelled', 'rejected_qc']) ? 'table-light opacity-75' : '' }}">
                        <td class="ps-4 fw-bold text-muted">#{{ str_pad($b->id, 5, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div style="max-width: 140px;">
                                    <span class="fw-bold d-block text-dark text-truncate" title="{{ $b->offeredItem->nama_barang }}">{{ $b->offeredItem->nama_barang }}</span>
                                    <small class="text-muted"><i class="bi bi-person me-1"></i>{{ explode(' ', $b->sender->name)[0] }}</small>
                                </div>
                                <i class="bi bi-arrow-left-right text-maroon opacity-50"></i>
                                <div style="max-width: 140px;">
                                    <span class="fw-bold d-block text-dark text-truncate" title="{{ $b->requestedItem->nama_barang }}">{{ $b->requestedItem->nama_barang }}</span>
                                    <small class="text-muted"><i class="bi bi-person me-1"></i>{{ explode(' ', $b->receiver->name)[0] }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($b->shipping_method !== 'standard')
                                <span class="badge rounded-pill bg-info text-dark shadow-sm" style="font-size: 0.65rem;"><i class="bi bi-shield-check me-1"></i>{{ strtoupper($b->method_selection) }}</span>
                            @else
                                <span class="badge rounded-pill bg-secondary text-white shadow-sm" style="font-size: 0.65rem;">STANDARD</span>
                            @endif
                        </td>
                        <td>
                            @if($b->shipping_method !== 'standard')
                                <div class="d-flex gap-2 align-items-center">
                                    <i class="bi bi-person-circle {{ $b->sender_payment_status == 'paid' ? 'text-success' : ($b->sender_payment_proof ? 'text-warning animate-pulse' : 'text-muted') }}" title="Sender Payment"></i>
                                    <span class="text-muted">|</span>
                                    <i class="bi bi-person-circle {{ $b->receiver_payment_status == 'paid' ? 'text-success' : ($b->receiver_payment_proof ? 'text-warning animate-pulse' : 'text-muted') }}" title="Receiver Payment"></i>
                                </div>
                            @else
                                <span class="text-muted small">-</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusClass = 'bg-primary';
                                if($b->status == 'completed') $statusClass = 'bg-success';
                                elseif($b->status == 'pending') $statusClass = 'bg-warning text-dark';
                                elseif(in_array($b->status, ['cancelled', 'rejected', 'rejected_qc'])) $statusClass = 'bg-danger';
                            @endphp
                            <span class="badge {{ $statusClass }} px-3 py-1 rounded-pill" style="font-size: 0.65rem;">
                                {{ strtoupper($b->status) }}
                            </span>
                        </td>
                        <td class="text-end pe-4">
                            <button class="btn btn-sm btn-dark rounded-pill px-3 fw-bold shadow-sm" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBarter{{ $b->id }}">
                                <i class="bi bi-gear-fill me-1"></i> Kelola
                            </button>
                        </td>
                    </tr>

                    {{-- OFFCANVAS DETAIL --}}
                    <div class="offcanvas offcanvas-end border-0 shadow-lg" tabindex="-1" id="offcanvasBarter{{ $b->id }}" style="width: 550px; border-radius: 1.5rem 0 0 1.5rem;">
                        <div class="offcanvas-header bg-light p-4">
                            <div>
                                <h5 class="offcanvas-title fw-bold mb-0" style="color: #800000; font-family: 'Playfair Display';">Rincian Transaksi #{{ $b->id }}</h5>
                                <small class="text-muted small">{{ $b->created_at->format('d F Y, H:i') }}</small>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                        </div>
                        <div class="offcanvas-body p-4">

                            {{-- INFO BARANG & USER --}}
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <div class="p-3 rounded-4 bg-light border text-center h-100 shadow-sm">
                                        <label class="badge bg-dark rounded-pill mb-2" style="font-size: 0.6rem;">SENDER (PENGIRIM)</label>
                                        <img src="{{ asset('storage/' . ($b->offeredItem->foto_barang ?? 'default.jpg')) }}" class="rounded-4 mb-2 shadow-sm w-100" style="height: 110px; object-fit: cover; border: 2px solid white;">
                                        <div class="fw-bold small text-dark text-truncate">{{ $b->offeredItem->nama_barang }}</div>
                                        <div class="p-2 bg-white rounded-3 border text-start mt-2">
                                            <p class="mb-0 x-small fw-bold text-dark">{{ $b->sender->name }}</p>
                                            <p class="mb-1 x-small text-muted" style="line-height: 1.2;">{{ $b->sender->alamat ?? 'Alamat belum diatur' }}</p>
                                            <small class="text-maroon fw-bold"><i class="bi bi-phone me-1"></i>0{{ $b->sender->nomor_hp }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 rounded-4 bg-light border text-center h-100 shadow-sm">
                                        <label class="badge bg-maroon rounded-pill mb-2" style="font-size: 0.6rem;">RECEIVER (PENERIMA)</label>
                                        <img src="{{ asset('storage/' . ($b->requestedItem->foto_barang ?? 'default.jpg')) }}" class="rounded-4 mb-2 shadow-sm w-100" style="height: 110px; object-fit: cover; border: 2px solid white;">
                                        <div class="fw-bold small text-dark text-truncate">{{ $b->requestedItem->nama_barang }}</div>
                                        <div class="p-2 bg-white rounded-3 border text-start mt-2">
                                            <p class="mb-0 x-small fw-bold text-dark">{{ $b->receiver->name }}</p>
                                            <p class="mb-1 x-small text-muted" style="line-height: 1.2;">{{ $b->receiver->alamat ?? 'Alamat belum diatur' }}</p>
                                            <small class="text-maroon fw-bold"><i class="bi bi-phone me-1"></i>0{{ $b->receiver->nomor_hp }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- KONTROL BERDASARKAN STATUS & METODE --}}
                            @if(!in_array($b->status, ['rejected_qc', 'cancelled', 'completed', 'rejected']))
                                
                                {{-- QC REJECT HANYA UNTUK NON-STANDARD --}}
                                @if($b->shipping_method !== 'standard')
                                    <div class="p-3 rounded-4 bg-soft-maroon border border-maroon border-opacity-25 mb-4 shadow-sm">
                                        <h6 class="fw-bold text-maroon small mb-3 text-uppercase"><i class="bi bi-shield-exclamation me-2"></i>Quality Control (Reject)</h6>
                                        <form action="{{ route('admin.barter.reject-qc', $b->id) }}" method="POST">
                                            @csrf
                                            <div class="mb-2">
                                                <select name="rejected_user_id" class="form-select form-select-sm rounded-pill shadow-none" required>
                                                    <option value="" disabled selected>Pilih Barang yang Gagal QC</option>
                                                    <option value="{{ $b->sender_id }}">Barang {{ explode(' ', $b->sender->name)[0] }}</option>
                                                    <option value="{{ $b->receiver_id }}">Barang {{ explode(' ', $b->receiver->name)[0] }}</option>
                                                </select>
                                            </div>
                                            <div class="mb-2">
                                                <textarea name="admin_note" class="form-control rounded-3 small shadow-none" rows="2" placeholder="Alasan gagal QC..." required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-danger btn-sm w-100 rounded-pill fw-bold shadow-sm" onclick="return confirm('Yakin ingin menolak barter ini?')">
                                                Batalkan & Proses Retur
                                            </button>
                                        </form>
                                    </div>
                                @endif

                                {{-- PARTIAL FORM LOGISTIK --}}
                                @include('admin.barter.partials.logistic-forms', ['b' => $b])

                            @else
                                {{-- STATUS AKHIR & ALASAN --}}
                                <div class="alert alert-dark rounded-4 small py-3 text-center border-0 shadow-sm">
                                    <i class="bi bi-info-circle me-1"></i> Transaksi: <strong>{{ strtoupper($b->status) }}</strong>
                                    @if($b->admin_note || $b->cancel_reason)
                                        <div class="mt-2 p-2 bg-white rounded-3 text-danger italic" style="font-size: 0.7rem;">
                                            "{{ $b->admin_note ?? $b->cancel_reason }}"
                                        </div>
                                    @endif
                                </div>
                            @endif

                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .bg-soft-maroon { background-color: #fff0f0; }
    .text-maroon { color: #800000; }
    .bg-maroon { background-color: #800000; }
    .x-small { font-size: 0.65rem; }
    .animate-pulse { animation: pulse 1.8s infinite; }
    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
</style>
@endsection