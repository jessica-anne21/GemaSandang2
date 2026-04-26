@extends('layouts.main')

@section('content')
<div class="container my-5 py-5">
    <div class="row justify-content-center text-center mb-5">
        <div class="col-md-8">
            <h1 class="display-4 mb-3" style="font-family: 'Playfair Display', serif; color: var(--primary-color);">Hubungi Kami</h1>
            <p class="lead text-muted">
                Punya pertanyaan tentang produk atau pesanan? Jangan ragu untuk menghubungi kami.
            </p>
        </div>
    </div>

    <div class="row justify-content-center g-4">
        
        {{-- KARTU INSTAGRAM --}}
        <div class="col-md-5">
            <div class="card h-100 border-0 shadow-sm p-4 hover-lift" style="background: linear-gradient(135deg, #fff 0%, #fcfcfc 100%);">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="bi bi-instagram display-3 text-danger"></i>
                    </div>
                    <h3 class="card-title fw-bold mb-3">Instagram</h3>
                    <p class="text-muted mb-4">
                        Ikuti <strong>@gemasandang</strong> untuk update katalog terbaru.
                    </p>
                    
                    <a href="https://www.instagram.com/gemasandang/" target="_blank" class="btn btn-outline-danger btn-lg w-100 rounded-pill">
                        <i class="bi bi-instagram me-2"></i> Kunjungi Profil
                    </a>
                </div>
            </div>
        </div>

        {{-- KARTU WHATSAPP --}}
        <div class="col-md-5">
            <div class="card h-100 border-0 shadow-sm p-4 hover-lift" style="background: linear-gradient(135deg, #fff 0%, #fcfcfc 100%);">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="bi bi-whatsapp display-3 text-success"></i>
                    </div>
                    <h3 class="card-title fw-bold mb-3">WhatsApp</h3>
                    <p class="text-muted mb-4">
                        Butuh respon cepat? Chat admin kami untuk konfirmasi pesanan atau komplain.
                    </p>

                    <a href="https://wa.me/6282121349200?text=Halo%20Admin%20Gema%20Sandang,%20saya%20tertarik%20dengan%20produk%20Anda..." target="_blank" class="btn btn-outline-success btn-lg w-100 rounded-pill">
                        <i class="bi bi-whatsapp me-2"></i> Chat Admin
                    </a>
                </div>
            </div>
        </div>

    </div>

    <div class="mt-5 text-center">
        <hr class="w-50 mx-auto opacity-25">
        <p class="small text-muted mt-4">
            <strong>Jam Operasional Admin:</strong><br>
            Senin - Jumat: 09.00 - 17.00 WIB<br>
            Sabtu - Minggu: 10.00 - 15.00 WIB
        </p>
    </div>
</div>
@endsection

@section('styles')
<style>
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.1) !important;
    }
</style>
@endsection