@extends('layouts.main')

@section('content')
<div style="background-color: #fdf5f5; min-height: 100vh; font-family: 'Inter', sans-serif;">
    <div style="max-width: 1200px; margin: 0 auto; padding: 60px 20px;">
        
        <div class="row g-5">
            {{-- LEFT SIDE: TEXT & COMMENTS --}}
            <div class="col-lg-7">
                <div style="font-size: 0.9rem; font-weight: 800; color: #333; margin-bottom: 10px; letter-spacing: 2px; text-transform: uppercase;">
                    {{ $trend->sumber }}
                </div>

                {{-- LINK SUMBER ASLI --}}
        @if($trend->link_sumber)
            <a href="{{ $trend->link_sumber }}" target="_blank" 
               style="text-decoration: none; font-size: 0.75rem; font-weight: 700; color: #8b6262; border: 1px solid #8b6262; padding: 5px 15px; border-radius: 50px; transition: 0.3s;"
               onmouseover="this.style.backgroundColor='#8b6262'; this.style.color='white';"
               onmouseout="this.style.backgroundColor='transparent'; this.style.color='#8b6262';">
                <i class="bi bi-box-arrow-up-right me-1"></i> Lihat di {{ $trend->sumber }}
            </a>
        @endif
                
                <h1 style="font-family: 'Playfair Display', serif; font-size: clamp(2.5rem, 5vw, 4rem); color: #8b6262; font-weight: 800; line-height: 1.1; margin-bottom: 35px;">
                    {{ $trend->judul }}
                </h1>

                <div style="background: rgba(255, 255, 255, 0.5); border-radius: 20px; padding: 40px; margin-bottom: 35px; min-height: 250px; border: 1px solid rgba(139, 98, 98, 0.1);">
                    <p style="font-size: 1.1rem; line-height: 1.9; color: #555; text-align: justify; margin-bottom: 0;">
                        {{ $trend->deskripsi }}
                    </p>
                </div>

                {{-- LOGIKA LIKE & STATS --}}
                <div style="display: flex; gap: 25px; margin-bottom: 40px; font-weight: 700; color: #8b6262; font-size: 1.1rem; align-items: center;">
                    <span style="cursor: pointer; transition: 0.3s;" class="btn-like-detail" data-id="{{ $trend->id }}">
                        <i id="heart-icon-detail" class="bi bi-heart-fill me-2" style="font-size: 1.4rem;"></i> 
                        <span id="score-detail">{{ $trend->skor_popularitas }}</span> likes
                    </span>
                    <span><i class="bi bi-chat-dots-fill me-2" style="font-size: 1.4rem;"></i> {{ count($comments) }} comments</span>
                </div>

                <h3 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; color: #8b6262; margin-bottom: 25px; font-weight: 700;">Komentar</h3>
                
                <div style="margin-bottom: 40px;">
                    @forelse($comments as $comment)
                        <div style="display: flex; align-items: flex-start; gap: 15px; margin-bottom: 20px;">
                            <a href="{{ url('/profile/'.$comment->author_id) }}" style="text-decoration: none;">
                                <div style="width: 50px; height: 50px; background: #8b6262; border-radius: 50%; flex-shrink: 0; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.2rem;">
                                    {{ strtoupper(substr($comment->name ?? 'U', 0, 1)) }}
                                </div>
                            </a>
                            <div style="background: white; padding: 18px 25px; border-radius: 0 20px 20px 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.03); flex-grow: 1; border: 1px solid #f1f1f1;">
                                <div style="font-weight: 800; margin-bottom: 5px;">
                                    <a href="{{ url('/profile/'.$comment->author_id) }}" style="color: #8b6262; text-decoration: none;">
                                        @<span>{{ $comment->username ?? 'user' }}</span>
                                    </a>
                                </div>
                                <div style="font-size: 0.95rem; color: #666; line-height: 1.5;">{{ $comment->isi_komentar }}</div>
                            </div>
                        </div>
                    @empty
                        <p style="color: #999; font-style: italic;">Belum ada diskusi!</p>
                    @endforelse
                </div>

                <form action="{{ route('comments.store', $trend->id) }}" method="POST">
                    @csrf
                    <div style="background: white; border-radius: 18px; border: 2px solid #eee; overflow: hidden; display: flex; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                        <input type="text" name="isi_komentar" placeholder="Tulis pendapatmu.." required 
                               style="border: none; padding: 20px 25px; flex-grow: 1; outline: none; font-size: 1rem;">
                        <button type="submit" style="background: #8b6262; color: white; border: none; padding: 0 35px; font-weight: 800; text-transform: uppercase;">Kirim</button>
                    </div>
                </form>
            </div>

            {{-- RIGHT SIDE IMAGE --}}
            <div class="col-lg-5">
                <div style="position: sticky; top: 100px;">
                    <img src="{{ $trend->gambar }}" style="width: 100%; border-radius: 30px; box-shadow: 0 30px 60px rgba(139, 98, 98, 0.2); object-fit: cover; height: 750px; border: 8px solid white;">
                </div>
            </div>
        </div>

        {{-- RECOMMENDATION SECTION --}}
        <section style="background-color: #8b6262; border-radius: 40px; padding: 60px 50px; margin-top: 100px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 50px; color: white;">
                <h2 style="font-family: 'Playfair Display', serif; font-size: 2.8rem; font-weight: 800;">Get The Look</h2>
                <a href="/shop" style="background: white; color: #8b6262; border-radius: 50px; padding: 12px 30px; font-weight: 800; text-decoration: none;">Lihat Semua</a>
            </div>
            <div class="row row-cols-1 row-cols-md-4 g-4">
                @foreach($recommendations as $prod)
                <div class="col">
                    <div style="background: white; border-radius: 25px; overflow: hidden; height: 100%; box-shadow: 0 15px 35px rgba(0,0,0,0.1);">
                        <img src="{{ asset('storage/' . $prod->foto_produk) }}" style="height: 280px; object-fit: cover; width: 100%;">
                        <div style="padding: 25px;">
                            <small style="font-weight: 800; color: #aaa; text-transform: uppercase;">{{ $prod->category->nama_kategori ?? 'COLLECTION' }}</small>
                            <h6 style="font-weight: 800; margin: 10px 0;">{{ $prod->nama_produk }}</h6>
                            <p style="color: #600000; font-weight: 800;">Rp {{ number_format($prod->harga, 0, ',', '.') }}</p>
                            <a href="{{ url('/produk/'.$prod->id) }}" style="background: #8b6262; color: white; border-radius: 50px; width: 100%; display: block; text-align: center; text-decoration: none; padding: 10px; font-weight: 800;">Lihat Detail</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </div>
</div>

{{-- SCRIPT AJAX LIKE --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.btn-like-detail').on('click', function() {
        let trendId = $(this).data('id');
        let scoreSpan = $('#score-detail');
        let heartIcon = $('#heart-icon-detail');

        $.ajax({
            url: '/trends/' + trendId + '/like',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if(response.success) {
                    scoreSpan.text(response.new_score);
                    if(response.status === 'liked') {
                        heartIcon.css('color', '#dc3545');
                    } else {
                        heartIcon.css('color', 'inherit');
                    }
                }
            },
            error: function(xhr) {
                if(xhr.status === 401) alert('Login dulu ya!');
            }
        });
    });
});
</script>
@endsection