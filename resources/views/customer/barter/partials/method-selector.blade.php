{{-- resources/views/customer/barter/partials/method-selector.blade.php --}}
<div class="text-center py-4 animate-fade-in">
    <div class="mb-4">
        <div class="rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" 
             style="width: 80px; height: 80px; background-color: #fdf0f0; color: #8b6262;">
            <i class="bi bi-truck fs-1"></i>
        </div>
    </div>
    <h4 class="fw-bold text-dark" style="font-family: 'Playfair Display';">Pilih Metode Pengiriman</h4>
    <p class="text-muted mx-auto mb-4" style="max-width: 500px;">
        Pilih metode yang paling nyaman untukmu. Ingat, khusus <strong>Trade Protection</strong>, barter baru berlanjut jika partner juga memilih metode yang sama.
    </p>

    <div class="row g-4 justify-content-center">
        {{-- Opsi 1: Standard --}}
        <div class="col-md-5">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center selection-card">
                <div class="mb-3 text-secondary">
                    <i class="bi bi-box-seam" style="font-size: 2.5rem;"></i>
                </div>
                <h5 class="fw-bold">Standard Delivery</h5>
                <p class="x-small text-muted mb-4">Kirim langsung ke partner. Tanpa biaya tambahan, namun risiko sepenuhnya ditanggung pengguna.</p>
                <form action="{{ route('barter.select-method', $barter->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="method" value="standard">
                    <button type="submit" class="btn btn-outline-dark rounded-pill px-4 fw-bold w-100 shadow-sm">
                        Pilih Standard
                    </button>
                </form>
            </div>
        </div>

        {{-- Opsi 2: Protection --}}
        <div class="col-md-5">
            <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center selection-card border-start border-4" style="border-color: #8b6262 !important;">
                <div class="mb-3" style="color: #8b6262;">
                    <i class="bi bi-shield-check" style="font-size: 2.5rem;"></i>
                </div>
                <h5 class="fw-bold" style="color: #8b6262;">Trade Protection</h5>
                <p class="x-small text-muted mb-2">Pemeriksaan kualitas oleh Admin. Lebih aman & terpercaya (Biaya layanan Rp 25.000).</p>
                
                <form action="{{ route('barter.select-method', $barter->id) }}" method="POST" id="protectionForm">
                    @csrf
                    <input type="hidden" name="method" value="protection">
                    
                    {{-- Checkbox S&K --}}
                    <div class="form-check text-start mb-3 mt-3 d-inline-block">
                        <input class="form-check-input" type="checkbox" id="termsCheck" required>
                        <label class="form-check-label x-small text-muted" for="termsCheck">
                            Saya menyetujui <a href="{{ route('barter.guide') }}" target="_blank" class="text-maroon fw-bold">Syarat & Ketentuan</a> layanan Protection.
                        </label>
                    </div>

                    <button type="submit" id="btnProtection" class="btn text-white rounded-pill px-4 fw-bold w-100 shadow" style="background-color: #8b6262;" disabled>
                        Pilih Protection
                    </button>
                    <small class="d-block mt-2 x-small italic text-maroon">*Menunggu persetujuan partner.</small>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Logic biar tombol Protection nggak bisa diklik kalau S&K belum diceklis
    const termsCheck = document.getElementById('termsCheck');
    const btnProtection = document.getElementById('btnProtection');

    termsCheck.addEventListener('change', function() {
        btnProtection.disabled = !this.checked;
    });
</script>

<style>
    .selection-card { transition: all 0.3s ease; }
    .selection-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important; }
    .text-maroon { color: #8b6262; }
</style>