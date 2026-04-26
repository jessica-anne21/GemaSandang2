<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        .email-card { font-family: 'Segoe UI', sans-serif; max-width: 500px; margin: auto; border: 1px solid #eee; border-radius: 15px; overflow: hidden; }
        .header { background-color: #800000; color: white; padding: 20px; text-align: center; }
        .body { padding: 30px; text-align: center; }
        .trend-img { width: 100%; border-radius: 10px; margin-bottom: 20px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .btn { background-color: #800000; color: white; padding: 12px 25px; text-decoration: none; border-radius: 50px; display: inline-block; font-weight: bold; }
    </style>
</head>
<body>
    <div class="email-card">
        <div class="header">
            <h1 style="margin:0;">GEMA SANDANG</h1>
            <p style="margin:0; font-size: 0.8rem;">Fashion Trend Aggregator</p>
        </div>
        <div class="body">
            <h2 style="color: #333;">Tren Fashion Baru Untukmu!</h2>
            <img src="{{ $trend->gambar }}" class="trend-img">
            <h3 style="color: #800000;">{{ $trend->judul }}</h3>
            <p style="color: #666; line-height: 1.6;">{{ $trend->style }} - Gaya terbaru dari {{ $trend->sumber }} sudah tersedia untuk dikurasi.</p>
            <br>
            <a href="{{ url('/') }}" class="btn">Lihat di Gema Sandang</a>
        </div>
        <div style="background: #f9f9f9; padding: 15px; text-align: center; font-size: 10px; color: #999;">
            Kamu menerima email ini karena terdaftar sebagai customer di Gema Sandang.
        </div>
    </div>
</body>
</html>