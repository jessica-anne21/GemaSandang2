<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Gema Sandang') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    @vite(['resources/css/app.css'])

    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#ffffff">
    <link rel="apple-touch-icon" href="/icon-192.png">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Gema Sandang">
    <link rel="icon" type="image/png" href="/icon-192.png">
</head>
<body>

    <div id="app">
        @include('layouts.partials.navbar')

        <main>
            @yield('content')
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @vite(['resources/js/app.js']) 

    <script>
        if ("serviceWorker" in navigator) {
            window.addEventListener("load", function () {
                navigator.serviceWorker.register("/sw.js").then(function (reg) {
                    console.log("SW Registered:", reg.scope);
                }).catch(function (err) {
                    console.log("SW Failed:", err);
                });
            });
        }

        let deferredPrompt;
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            const a2hsBtn = document.getElementById('a2hs-btn');
            if (a2hsBtn) a2hsBtn.style.display = 'block';
        });

        async function addToHomeScreen() {
            if (!deferredPrompt) return;
            deferredPrompt.prompt();
            deferredPrompt = null;
            const a2hsBtn = document.getElementById('a2hs-btn');
            if (a2hsBtn) a2hsBtn.style.display = 'none';
        }
    </script>

    @yield('scripts')

    {{-- Tombol PWA --}}
    <button id="a2hs-btn" onclick="addToHomeScreen()" style="display:none; position:fixed; bottom:20px; right:20px; background:#800000; color:white; border:none; padding:12px 18px; border-radius:30px; box-shadow:0 4px 12px rgba(0,0,0,0.2); z-index:1000;">
        <i class="bi bi-phone"></i> Add to Home Screen
    </button>

</body>
</html>