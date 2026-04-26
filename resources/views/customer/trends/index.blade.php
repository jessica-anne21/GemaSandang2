@extends('layouts.main')

@section('content')
{{-- WRAPPER UTAMA --}}
<div style="width: 100%; display: flex; flex-direction: column; align-items: center; background-color: #fdf5f5; min-height: 100vh; padding-bottom: 80px;">
    
    {{-- INNER CONTAINER --}}
    <div style="width: 100%; max-width: 1140px; padding: 0 20px;">
        
        {{-- HEADER SECTION --}}
        <header style="padding: 80px 0 50px; text-align: center;">
            <h1 style="font-family: 'Playfair Display', serif; font-weight: 800; color: #8b6262; font-size: 3.8rem; margin-bottom: 10px;">
                Tren Fashion
            </h1>
            <p style="letter-spacing: 4px; color: #a58b8b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase;">
                DARI KOLEKSI ZARA & UNIQLO
            </p>
        </header>

        {{-- GRID TRENDS --}}
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4" style="justify-content: center;">
            @forelse($trends as $trend)
                <div class="col">
                    <div style="background: #fff; border-radius: 25px; overflow: hidden; box-shadow: 0 10px 30px rgba(139, 98, 98, 0.05); height: 100%; transition: 0.3s; border: none;">
                        
                        {{-- IMAGE AREA --}}
                        <div style="width: 100%; height: 380px; overflow: hidden; background-color: #f8f9fa;">
                            <a href="{{ route('trends.show', $trend->id) }}">
                                <img src="{{ $trend->gambar }}" 
                                     style="width: 100%; height: 100%; object-fit: cover; object-position: top;"
                                     onerror="this.src='https://placehold.co/400x600?text=Fashion+Collection';">
                            </a>
                        </div>

                        {{-- INFO AREA --}}
                        <div style="padding: 20px; text-align: left;">
                            <a href="{{ route('trends.show', $trend->id) }}" 
                               style="font-size: 1rem; font-weight: 700; color: #333; margin-bottom: 12px; display: block; text-decoration: none; line-height: 1.4;">
                                {{ \Illuminate\Support\Str::limit($trend->judul, 45) }}
                            </a>
                            
                            {{-- STATS --}}
                            <div style="display: flex; gap: 20px; font-size: 0.85rem; border-top: 1px solid #f8f1f1; padding-top: 15px;">
                                <div style="display: flex; align-items: center; gap: 6px; color: #8b6262; font-weight: 600;">
                                    <i class="bi bi-heart-fill btn-like-main" 
                                       data-id="{{ $trend->id }}" 
                                       style="cursor: pointer; font-size: 1.1rem;"></i>
                                    <span id="score-{{ $trend->id }}">{{ $trend->skor_popularitas }}</span>
                                </div>
                                
                                <div style="display: flex; align-items: center; gap: 6px; color: #999; font-weight: 600;">
                                    <i class="bi bi-chat-dots-fill"></i>
                                    <span>{{ $trend->comments_count ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div style="width: 100%; text-align: center; padding: 50px;">
                    <div style="background: white; padding: 50px; border-radius: 30px;">
                        <p style="color: #999;">Belum ada koleksi tren tersedia.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- SCRIPT AJAX LIKE --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.btn-like-main').on('click', function() {
        let trendId = $(this).data('id');
        let heartIcon = $(this);
        let scoreSpan = $('#score-' + trendId);

        $.ajax({
            url: '/trends/' + trendId + '/like',
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if(response.success) {
                    scoreSpan.text(response.new_score);
                    if(heartIcon.css('color') === 'rgb(220, 53, 69)') {
                        heartIcon.css('color', '#8b6262');
                    } else {
                        heartIcon.css('color', '#dc3545');
                    }
                }
            }
        });
    });
});
</script>
@endsection