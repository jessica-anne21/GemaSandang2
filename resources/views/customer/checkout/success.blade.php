@extends('layouts.main')

@section('content')
<div class="container my-5 py-5">
    
    @php
        $limitInMinutes = 24 * 60; 
        $expiryTime = $order->created_at->addMinutes($limitInMinutes);
        $remainingSeconds = now()->diffInSeconds($expiryTime, false);
    @endphp

    @if($order->status == 'menunggu_pembayaran' && !$order->bukti_bayar)
        
        @if($remainingSeconds <= 0)
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="card border-0 shadow-sm p-5">
                        <div class="mb-3"><i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i></div>
                        <h2 class="mb-3" style="font-family: 'Playfair Display', serif;">Waktu Habis</h2>
                        <p class="text-muted mb-4">Batas waktu pembayaran untuk pesanan #{{ $order->id }} telah berakhir.</p>
                        <a href="{{ route('shop') }}" class="btn btn-custom px-4">Kembali ke Toko</a>
                    </div>
                </div>
            </div>
        @else
            <div class="row justify-content-center mb-4">
                <div class="col-12 text-center">
                    <i class="bi bi-hourglass-split text-warning" style="font-size: 3rem;"></i>
                    <h2 class="mt-3" style="font-family: 'Playfair Display', serif;">Selesaikan Pembayaran</h2>
                    
                    <div class="mt-3 mb-2">
                        <p class="text-muted mb-1">Batas waktu pembayaran:</p>
                        <div id="countdown-display" class="badge bg-danger fs-5 px-4 py-2 rounded-pill shadow-sm">
                            @php
                                $h = floor($remainingSeconds / 3600);
                                $m = floor(($remainingSeconds % 3600) / 60);
                                $s = $remainingSeconds % 60;
                                echo sprintf('%02d:%02d:%02d', $h, $m, $s);
                            @endphp
                        </div>
                    </div>
                    <p class="text-muted small">Pesanan #{{ $order->id }} akan otomatis dibatalkan jika waktu habis.</p>
                </div>
            </div>

            @if($order->catatan_admin)
                <div class="row justify-content-center mb-4">
                    <div class="col-lg-10">
                        <div class="alert alert-danger border-0 shadow-sm d-flex align-items-center" role="alert">
                            <i class="bi bi-exclamation-triangle-fill fs-1 me-3"></i>
                            <div>
                                <h5 class="alert-heading fw-bold mb-1">Pembayaran Ditolak!</h5>
                                <p class="mb-0">Alasan: <strong>{{ $order->catatan_admin }}</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-4">
                                    <h5 class="mb-4" style="color: var(--primary-color);">1. Transfer Manual</h5>
                                    <div class="d-flex align-items-center justify-content-between p-3 border rounded bg-light mb-3">
                                        <div>
                                            <div class="fw-bold">BCA</div>
                                            <div class="small text-muted">Gema Sandang Official</div>
                                        </div>
                                        <div class="text-end">
                                            <h5 class="mb-0 fw-bold text-dark">3211026501</h5>
                                            <small class="text-primary" style="cursor: pointer;" onclick="navigator.clipboard.writeText('3211026501'); alert('Nomor rekening berhasil disalin!');">Salin</small>
                                        </div>
                                    </div>
                                    <div class="alert alert-warning mb-0">
                                        <small class="d-block text-muted">Total Bayar:</small>
                                        <h4 class="mb-0 fw-bold text-dark">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body p-4">
                                    <h5 class="mb-4" style="color: var(--primary-color);">2. Kirim Bukti</h5>
                                    <form action="{{ route('checkout.payment.upload', $order->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-4 text-center p-4 border border-dashed rounded bg-light">
                                            <i class="bi bi-cloud-upload display-4 text-muted mb-2"></i>
                                            <input type="file" name="bukti_bayar" class="form-control @error('bukti_bayar') is-invalid @enderror" required>
                                            @error('bukti_bayar')
                                                <div class="invalid-feedback text-start">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                        <button type="submit" class="btn btn-custom w-100 py-2">Kirim Bukti Pembayaran</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    @elseif($order->status == 'menunggu_konfirmasi' || $order->bukti_bayar)
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="card border-0 shadow-sm p-5">
                    <div class="mb-3"><i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i></div>
                    <h2 class="mb-3" style="font-family: 'Playfair Display', serif;">Bukti Terkirim!</h2>
                    <p class="text-muted mb-4">Pembayaran Anda sedang dalam proses verifikasi oleh tim Gema Sandang.</p>
                    <div class="d-grid gap-2 d-md-block">
                        <a href="{{ route('orders.index') }}" class="btn btn-custom px-4 me-md-2">Riwayat Pesanan</a>
                        <a href="{{ route('shop') }}" class="btn btn-outline-dark px-4">Belanja Lagi</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const timerElement = document.getElementById('countdown-display');
        
        if (timerElement) {
            const initialSeconds = parseInt("{{ $remainingSeconds ?? 0 }}");
            const deadline = new Date().getTime() + (initialSeconds * 1000);

            const countdownInterval = setInterval(() => {
                const now = new Date().getTime();
                const distance = deadline - now;

                if (distance > 0) {
                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                    timerElement.textContent = 
                        hours.toString().padStart(2, '0') + ":" + 
                        minutes.toString().padStart(2, '0') + ":" + 
                        seconds.toString().padStart(2, '0');
                } else {
                    clearInterval(countdownInterval);
                    timerElement.innerHTML = "EXPIRED";
                    setTimeout(() => { window.location.reload(); }, 1000);
                }
            }, 1000);
        }
    });
</script>
@endsection