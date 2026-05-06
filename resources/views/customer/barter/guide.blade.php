@extends('layouts.main')

@section('content')
<div class="container py-5" style="background-color: #fdf5f5; min-height: 100vh;">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="text-center mb-5">
                <h2 class="fw-bold" style="font-family: 'Playfair Display'; color: #800000;">Panduan & FAQ Barter</h2>
                <p class="text-muted">Segala hal yang perlu kamu ketahui tentang barter di Gema Sandang.</p>
            </div>

            <div class="accordion border-0 shadow-sm rounded-4 overflow-hidden" id="barterGuide">
                
                {{-- Langkah Barter --}}
                <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                        <button class="accordion-button fw-bold text-maroon" type="button" data-bs-toggle="collapse" data-bs-target="#stepByStep">
                            <i class="bi bi-list-ol me-2"></i> Langkah-Langkah Barter
                        </button>
                    </h2>
                    <div id="stepByStep" class="accordion-collapse collapse show" data-bs-parent="#barterGuide">
                        <div class="accordion-body bg-white small">
                            <ol>
                                <li><strong>Pilih Barang:</strong> Cari barang di Barter Area dan ajukan penawaran dengan barang milikmu.</li>
                                <li><strong>Tunggu Respon:</strong> Partner akan menerima atau menolak penawaranmu (Verifikasi lewat OTP).</li>
                                <li><strong>Pilih Metode:</strong> Pilih <em>Standard</em> (Kirim langsung) atau <em>Trade Protection</em> (Lewat QC Admin).</li>
                                <li><strong>Kirim Barang:</strong> Input nomor resi di halaman Tracking.</li>
                                <li><strong>Konfirmasi:</strong> Klik 'Barang Diterima' jika paket sudah sampai dan sesuai.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- Penjelasan Status --}}
                <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold text-maroon" type="button" data-bs-toggle="collapse" data-bs-target="#statusMeaning">
                            <i class="bi bi-info-square me-2"></i> Apa Arti Status Barter?
                        </button>
                    </h2>
                    <div id="statusMeaning" class="accordion-collapse collapse" data-bs-parent="#barterGuide">
                        <div class="accordion-body bg-white small">
                            <ul class="list-unstyled">
                                <li class="mb-2"><span class="badge bg-warning text-dark">PENDING</span>: Menunggu partner menerima penawaranmu.</li>
                                <li class="mb-2"><span class="badge bg-success">ACCEPTED</span>: Barter disetujui, silakan pilih metode pengiriman.</li>
                                <li class="mb-2"><span class="badge bg-info text-dark">ON GOING</span>: Barang dalam proses pengiriman atau QC.</li>
                                <li class="mb-2"><span class="badge bg-danger">REJECTED_QC</span>: Admin membatalkan barter karena barang tidak lolos pemeriksaan.</li>
                                <li class="mb-2"><span class="badge bg-secondary">CANCELLED</span>: Barter dibatalkan oleh salah satu pihak sebelum dikirim.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Panduan Chat --}}
                <div class="accordion-item border-0 border-bottom">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed fw-bold text-maroon" type="button" data-bs-toggle="collapse" data-bs-target="#chatGuide">
                            <i class="bi bi-chat-dots me-2"></i> Panduan Chat dengan Partner
                        </button>
                    </h2>
                    <div id="chatGuide" class="accordion-collapse collapse" data-bs-parent="#barterGuide">
                        <div class="accordion-body bg-white small">
                            <p>Fitur chat akan terbuka otomatis setelah partner <strong>Menerima (Accept)</strong> tawaranmu.</p>
                            <ul>
                                <li>Gunakan bahasa yang sopan dan santun.</li>
                                <li>Tanyakan detail kondisi barang lebih lanjut jika perlu.</li>
                                <li>Jangan memberikan data pribadi yang sensitif di luar kebutuhan pengiriman.</li>
                                <li>Segala transaksi uang di luar biaya layanan Gema Sandang bukan tanggung jawab kami.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Gagal QC --}}
<div class="accordion-item border-0">
    <h2 class="accordion-header">
        <button class="accordion-button collapsed fw-bold text-maroon" type="button" data-bs-toggle="collapse" data-bs-target="#qcFail">
            <i class="bi bi-shield-x me-2"></i> Bagaimana Jika Barang Gagal QC Admin?
        </button>
    </h2>
    <div id="qcFail" class="accordion-collapse collapse" data-bs-parent="#barterGuide">
        <div class="accordion-body bg-white small text-muted">
            Jika kamu menggunakan <strong>Trade Protection</strong> dan Admin menemukan barang tidak layak atau tidak sesuai deskripsi:
            <ol class="mt-2">
                <li>Admin akan mengubah status transaksi menjadi <strong>Rejected QC</strong>.</li>
                <li>Admin akan mencantumkan alasan penolakan secara detail untuk kedua belah pihak.</li>
                <li class="text-danger fw-bold">Pihak yang menyebabkan Gagal QC:</li>
                <ul class="mb-2">
                    <li>Barang akan dikirim kembali ke alamatmu.</li>
                    <li>Biaya layanan Rp 25.000 <strong>hangus</strong> (digunakan untuk biaya operasional & membantu kompensasi pihak lawan).</li>
                </ul>
                <li class="text-success fw-bold">Pihak yang dirugikan (Korban):</li>
                <ul>
                    <li>Barang milikmu akan dikirim kembali ke alamatmu secara <strong>Gratis</strong> (Tanpa biaya ongkir retur).</li>
                    <li>Biaya layanan Rp 25.000 milikmu akan dikembalikan.</li>
                </ul>
            </ol>
            <p class="mt-2 mb-0 x-small italic text-maroon">＊Kebijakan ini dibuat untuk menjaga integritas dan kualitas barang di ekosistem Gema Sandang.</p>
        </div>
    </div>
</div>
            </div>

        </div>
    </div>
</div>

<style>
    .text-maroon { color: #800000; }
    .btn-maroon { background-color: #800000; color: white; border: none; }
    .btn-maroon:hover { background-color: #600000; color: white; }
    .accordion-button:not(.collapsed) {
        background-color: #fff0f0;
        color: #800000;
        box-shadow: none;
    }
    .accordion-button:focus { box-shadow: none; border-color: rgba(128,0,0,.125); }
    .accordion-item { border-radius: 15px !important; }
</style>
@endsection