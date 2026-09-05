<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- PWA Manifest & Favicons -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('icon.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('icon.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('icon.png') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('icon.png') }}?v=2">
    <link rel="manifest" href="{{ asset('manifest.json') }}?v=2">

    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- DotLottie Player -->
    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800;900&family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">

    <!-- Tailwind CDN with class-based dark mode config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Theme Detection -->
    <script>
        (function() {
            const theme = localStorage.getItem('theme') || 'auto';
            const isDark = theme === 'dark' || (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches);
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
    </script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ══ LOADING SCREEN ══ */
        #loading-screen {
            display: none; /* JS will show it only on first visit */
            position: fixed;
            inset: 0;
            z-index: 99999;
            background: rgba(248, 251, 252, 0.95);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            box-sizing: border-box;
            transition: opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            user-select: none;
            -webkit-user-select: none;
            touch-action: none;
        }
        .dark #loading-screen {
            background: rgba(18, 18, 18, 0.96);
        }
        #loading-screen.visible {
            display: flex;
        }
        #loading-screen.fade-out {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        .loading-content-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 100%;
            max-height: 100%;
            gap: 1rem;
        }
        .loading-lottie-wrap {
            width: clamp(160px, 45vw, 280px);
            height: clamp(160px, 45vw, 280px);
            max-width: min(75vw, 42vh);
            max-height: min(75vw, 42vh);
            aspect-ratio: 1 / 1;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .loading-lottie-wrap dotlottie-player {
            display: block;
            width: 100%;
            height: 100%;
            max-width: 100%;
            max-height: 100%;
        }
        .loading-brand-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }
        .loading-brand-title {
            font-size: 1.125rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            color: #2D5A43;
            font-family: 'Poppins', sans-serif;
        }
        .dark .loading-brand-title {
            color: #4ade80;
        }
        .loading-progress-bar {
            width: 100px;
            height: 3px;
            background: rgba(45, 90, 67, 0.15);
            border-radius: 9999px;
            overflow: hidden;
            position: relative;
        }
        .dark .loading-progress-bar {
            background: rgba(255, 255, 255, 0.12);
        }
        .loading-progress-fill {
            height: 100%;
            width: 40%;
            background: linear-gradient(90deg, #2D5A43, #0F766E);
            border-radius: 9999px;
            animation: loadingIndeterminate 1.4s cubic-bezier(0.65, 0.815, 0.735, 0.395) infinite;
        }
        .dark .loading-progress-fill {
            background: linear-gradient(90deg, #4ade80, #2dd4bf);
        }
        @keyframes loadingIndeterminate {
            0% {
                transform: translateX(-100%) scaleX(0.2);
            }
            50% {
                transform: translateX(100%) scaleX(1);
            }
            100% {
                transform: translateX(250%) scaleX(0.2);
            }
        }
        @media (max-height: 500px) {
            .loading-content-wrap {
                gap: 0.5rem;
            }
            .loading-lottie-wrap {
                width: clamp(100px, 32vh, 150px);
                height: clamp(100px, 32vh, 150px);
            }
            .loading-brand-title {
                font-size: 0.875rem;
            }
            .loading-progress-bar {
                width: 75px;
                height: 2px;
            }
        }
        /* ══ END LOADING SCREEN ══ */
    </style>

    <style>
        /* Shared Dashboard Styles */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        
        body { 
            background: #f3f4f6; 
            display: flex; 
            height: 100vh; 
            overflow: hidden; 
            transition: background 0.3s ease;
        }

        .dark body {
            background: #121212;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 250px;
            background: #dce6e9; /* Light mode sidebar */
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
            z-index: 100;
            height: 100vh;
            overflow-y: auto;
            position: sticky;
            top: 0;
            border-right: 1px solid #bdc3c7;
        }

        .dark .sidebar {
            background: #1a2f23; /* Dark Forest Green for dark mode sidebar */
            border-right-color: #2d3436;
        }
        .sidebar::-webkit-scrollbar { width: 0; }

        .logo h2 { 
            color: #d4a373; 
            font-size: 28px; 
            margin-bottom: 5px; 
            font-weight: 700; 
            line-height: 1.1; 
            font-family: 'Lora', serif;
        }
        .logo-sub { color: #1E3A2B; font-size: 14px; margin-bottom: 35px; letter-spacing: 2px; font-weight: 600; }
        .dark .logo-sub { color: #f3f4f6; } /* Light text for sub-logo in dark mode */
        
        .menu a {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #636e72;
            padding: 12px 15px;
            margin-bottom: 10px;
            border-radius: 12px;
            transition: 0.3s;
            font-size: 14px;
            font-weight: 600;
        }
        .menu a.active, .menu a:hover { 
            background: rgba(148, 163, 184, 0.2); 
            color: #2d3436; 
        }
        .dark .menu a { color: #94a3b8; }
        .dark .menu a.active, .dark .menu a:hover { 
            background: rgba(255, 255, 255, 0.05); 
            color: #f3f4f6; 
        }
        .menu a i { margin-right: 15px; width: 20px; text-align: center; }
        
        .logout-btn { border-top: 1px solid #bdc3c7; margin-top: 20px; padding-top: 20px; }
        .logout-btn button {
            background: none; border: none; cursor: pointer;
            display: flex; align-items: center; color: #ef4444; font-weight: 700;
            width: 100%; padding: 12px 15px; text-align: left;
            font-size: 14px;
        }

        /* --- MAIN CONTENT --- */
        .main-content {
            flex: 1;
            padding: 30px 40px; 
            overflow-y: auto;
            position: relative;
            background-image: radial-gradient(circle at 2px 2px, #bdc3c7 1px, transparent 0);
            background-size: 40px 40px;
            transition: background 0.3s ease;
        }

        .dark .main-content {
            background-color: #121212;
            background-image: radial-gradient(circle at 2px 2px, #2d3436 1px, transparent 0);
        }

        @media (max-width: 768px) {
            body { height: auto; overflow: auto; display: block; }
            .sidebar {
                position: fixed; left: 0; height: 100dvh;
                transform: translateX(-100%);
                box-shadow: 5px 0 15px rgba(0,0,0,0.1);
            }
            .sidebar.active { transform: translateX(0); }
            .hamburger-btn { display: block; }
            .main-content { width: 100%; height: auto; overflow: visible; padding: 80px 20px 40px; }
        }

        .hamburger-btn {
            display: none; position: absolute; top: 20px; left: 20px; z-index: 102;
            background: white; padding: 10px; border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1); border: none; cursor: pointer;
        }

        /* --- GLOBAL MODAL STYLES --- */
        .modal-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.5); backdrop-filter: blur(8px);
            display: none; justify-content: center; align-items: center; z-index: 1000;
            padding: 20px;
        }
        .modal-box {
            background: white; padding: 35px; border-radius: 30px; 
            max-width: 400px; width: 100%; text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.2);
            animation: modalAppear 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .dark .modal-box { background: #1e1e1e; color: #f3f4f6; border: 1px solid #2d3436; }
        .modal-box h3 { font-size: 20px; font-weight: 800; margin-bottom: 10px; color: #2d3436; }
        .dark .modal-box h3 { color: #f3f4f6; }
        .modal-box p { font-size: 14px; color: #636e72; margin-bottom: 25px; line-height: 1.6; }
        .dark .modal-box p { color: #94a3b8; }
        .modal-buttons { display: flex; gap: 15px; justify-content: center; }
        
        .btn-setuju, .btn-nanti {
            padding: 12px 25px; border-radius: 15px; font-weight: 700; font-size: 14px; cursor: pointer; transition: 0.3s;
            border: none; flex: 1;
        }
        .btn-setuju { background: #0F766E; color: white; box-shadow: 0 4px 15px rgba(15, 118, 110, 0.3); }
        .btn-setuju:hover { background: #0c5e58; transform: translateY(-2px); }
        .btn-nanti { background: #f3f4f6; color: #636e72; }
        .dark .btn-nanti { background: #2d3436; color: #94a3b8; }
        .btn-nanti:hover { background: #e5e7eb; }
        .dark .btn-nanti:hover { background: #38414a; }

        /* ── MOBILE BOTTOM NAV ── */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0; left: 0; right: 0;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            border-top: 1px solid #e5e7eb;
            z-index: 200;
            padding: 8px 4px;
            padding-bottom: max(8px, env(safe-area-inset-bottom));
        }
        .dark .mobile-bottom-nav {
            background: rgba(18,18,18,0.95);
            border-top-color: #1f2937;
        }
        .mobile-bottom-nav-inner {
            display: flex;
            justify-content: space-around;
            align-items: center;
            max-width: 480px;
            margin: 0 auto;
        }
        .mobile-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3px;
            padding: 6px 10px;
            border-radius: 12px;
            text-decoration: none;
            color: #9ca3af;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            transition: all 0.2s;
            min-width: 52px;
            border: none;
            background: transparent;
            cursor: pointer;
        }
        .mobile-nav-item i { font-size: 18px; }
        .mobile-nav-item.active, .mobile-nav-item:hover { color: #0F766E; }
        .dark .mobile-nav-item { color: #6b7280; }
        .dark .mobile-nav-item.active, .dark .mobile-nav-item:hover { color: #2dd4bf; }
        .mobile-nav-item.active i { transform: scale(1.15); }

        /* ── MORE MENU DRAWER ── */
        .more-drawer {
            display: none;
            position: fixed;
            bottom: 72px; left: 0; right: 0;
            background: rgba(255,255,255,0.97);
            backdrop-filter: blur(20px);
            border-top: 1px solid #e5e7eb;
            border-radius: 24px 24px 0 0;
            z-index: 199;
            padding: 20px 16px 8px;
            box-shadow: 0 -8px 32px rgba(0,0,0,0.12);
        }
        .dark .more-drawer {
            background: rgba(24,24,24,0.97);
            border-top-color: #1f2937;
        }
        .more-drawer.open { display: block; }
        .more-drawer-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }
        .more-drawer-item {
            display: flex; flex-direction: column; align-items: center;
            gap: 6px; padding: 12px 8px; border-radius: 16px;
            text-decoration: none; color: #374151;
            font-size: 10px; font-weight: 700; letter-spacing: 0.03em;
            text-transform: uppercase; transition: all 0.2s;
            background: #f9fafb; border: 1px solid #f3f4f6;
        }
        .dark .more-drawer-item { color: #d1d5db; background: #1e1e1e; border-color: #2d3436; }
        .more-drawer-item i { font-size: 20px; color: #0F766E; }
        .dark .more-drawer-item i { color: #2dd4bf; }
        .more-drawer-item:hover { background: #f0fdf4; border-color: #0F766E; color: #0F766E; }
        .dark .more-drawer-item:hover { background: rgba(45,212,191,0.08); }
        .more-drawer-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.3);
            z-index: 198;
        }
        .more-drawer-overlay.open { display: block; }

        @media (max-width: 768px) {
            body { height: auto; overflow: auto; display: block; }
            .sidebar {
                position: fixed; left: 0; height: 100dvh;
                transform: translateX(-100%);
                box-shadow: 5px 0 15px rgba(0,0,0,0.1);
            }
            .sidebar.active { transform: translateX(0); }
            .hamburger-btn { display: none !important; }
            .main-content {
                width: 100%; height: auto; overflow: visible;
                padding: 20px 14px 90px;
            }
            .mobile-bottom-nav { display: block; }
        }

        /* Extra small phones */
        @media (max-width: 380px) {
            .main-content { padding: 16px 12px 88px; }
            .mobile-nav-item { font-size: 8px; min-width: 46px; padding: 5px 6px; }
            .mobile-nav-item i { font-size: 16px; }
        }

        @keyframes modalAppear {
            from { opacity: 0; transform: scale(0.9) translateY(20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
    </style>
    @stack('styles')
</head>
<body class="transition-all duration-300">

    {{-- ═══════════ LOADING SCREEN (All Devices, First Visit) ═══════════ --}}
    <div id="loading-screen" role="status" aria-live="polite">
        <div class="loading-content-wrap">
            <div class="loading-lottie-wrap">
                <dotlottie-player
                    id="lottiePlayer"
                    src="{{ asset('assets/loading.lottie') }}"
                    autoplay
                    loop
                    speed="1.7"
                    direction="1"
                    mode="bounce"
                ></dotlottie-player>
            </div>
            <div class="loading-brand-wrap">
                <span class="loading-brand-title">MahabBa</span>
                <div class="loading-progress-bar">
                    <div class="loading-progress-fill"></div>
                </div>
            </div>
        </div>
    </div>

    <div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 bg-black/50 z-50 hidden" onclick="toggleSidebar()"></div>

    <button class="hamburger-btn" onclick="toggleSidebar()">
        <i class="fas fa-bars fa-lg" style="color: var(--text-primary);"></i>
    </button>

    <x-sidebar />

    <div class="main-content">
        @yield('content')
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const hamburger = document.querySelector('.hamburger-btn');
            sidebar.classList.toggle('active');
            overlay.classList.toggle('hidden');
            if (hamburger) hamburger.style.display = sidebar.classList.contains('active') ? 'none' : 'block';
        }
    </script>

    {{-- ═══════════ MOBILE BOTTOM NAVIGATION ═══════════ --}}
    @php
        $currentRoute = request()->route() ? request()->route()->getName() : '';
    @endphp

    {{-- More Drawer Overlay --}}
    <div class="more-drawer-overlay" id="moreDrawerOverlay" onclick="closeMoreDrawer()"></div>

    {{-- More Drawer --}}
    <div class="more-drawer" id="moreDrawer">
        <p style="font-size:10px;font-weight:900;letter-spacing:0.15em;text-transform:uppercase;color:#9ca3af;margin-bottom:12px;text-align:center;">Menu Lainnya</p>
        <div class="more-drawer-grid">
            <a href="{{ route('shalat-sunnah.halaman-sunnah') }}" class="more-drawer-item">
                <i class="fas fa-star-and-crescent"></i>
                <span>Sunnah</span>
            </a>
            <a href="{{ route('tracker.index') }}" class="more-drawer-item">
                <i class="fas fa-chart-line"></i>
                <span>Tracker</span>
            </a>
            <a href="{{ route('zakat.index') }}" class="more-drawer-item">
                <i class="fa-solid fa-scale-balanced"></i>
                <span>Zakat</span>
            </a>
            <a href="{{ route('hadith.index') }}" class="more-drawer-item">
                <i class="fas fa-book-reader"></i>
                <span>Hadis</span>
            </a>
            <a href="{{ route('settings.index') }}" class="more-drawer-item">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </a>
            <a href="{{ route('profile.index') }}" class="more-drawer-item">
                <i class="fas fa-user-circle"></i>
                <span>Profil</span>
            </a>
            <form action="{{ route('logout') }}" method="POST" style="display:contents;">
                @csrf
                <button type="submit" class="more-drawer-item" style="border:none;cursor:pointer; color:#ef4444;">
                    <i class="fas fa-sign-out-alt" style="color:#ef4444;"></i>
                    <span style="color:#ef4444;">Logout</span>
                </button>
            </form>
        </div>
        <div style="height:8px;"></div>
    </div>

    {{-- Bottom Nav Bar --}}
    <nav class="mobile-bottom-nav">
        <div class="mobile-bottom-nav-inner">
            <a href="{{ route('dashboard') }}"
               class="mobile-nav-item {{ Str::startsWith($currentRoute, 'dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('prayers.index') }}"
               class="mobile-nav-item {{ Str::startsWith($currentRoute, 'prayers') ? 'active' : '' }}">
                <i class="fas fa-hands-praying"></i>
                <span>Shalat</span>
            </a>
            <a href="{{ route('quran.index') }}"
               class="mobile-nav-item {{ Str::startsWith($currentRoute, 'quran') ? 'active' : '' }}">
                <i class="fas fa-book-open"></i>
                <span>Qur'an</span>
            </a>
            <a href="{{ route('dzikir.index') }}"
               class="mobile-nav-item {{ Str::startsWith($currentRoute, 'dzikir') ? 'active' : '' }}">
                <i class="fas fa-mosque"></i>
                <span>Dzikir</span>
            </a>
            <button type="button" class="mobile-nav-item" onclick="toggleMoreDrawer()" id="moreNavBtn">
                <i class="fas fa-grid-2"></i>
                <span>Lainnya</span>
            </button>
        </div>
    </nav>

    <script>
        function toggleMoreDrawer() {
            const drawer = document.getElementById('moreDrawer');
            const overlay = document.getElementById('moreDrawerOverlay');
            const btn = document.getElementById('moreNavBtn');
            const isOpen = drawer.classList.contains('open');
            drawer.classList.toggle('open');
            overlay.classList.toggle('open');
            btn.classList.toggle('active', !isOpen);
        }
        function closeMoreDrawer() {
            document.getElementById('moreDrawer').classList.remove('open');
            document.getElementById('moreDrawerOverlay').classList.remove('open');
            document.getElementById('moreNavBtn').classList.remove('active');
        }
        // Close drawer on navigation
        document.querySelectorAll('.more-drawer-item').forEach(el => {
            el.addEventListener('click', closeMoreDrawer);
        });
    </script>

    @stack('scripts')

    <script>
        (function () {
            var STORAGE_KEY = 'mahabba_visited';
            var screen = document.getElementById('loading-screen');
            if (!screen) return;

            // Show only on the very first visit per session
            if (!sessionStorage.getItem(STORAGE_KEY)) {
                sessionStorage.setItem(STORAGE_KEY, '1');
                screen.classList.add('visible');

                var MIN_MS = 2000;
                var MAX_TIMEOUT_MS = 4000; // Safety fallback
                var start = Date.now();
                var hidden = false;

                function hideLoader() {
                    if (hidden) return;
                    hidden = true;
                    var elapsed = Date.now() - start;
                    var delay = Math.max(0, MIN_MS - elapsed);
                    setTimeout(function () {
                        screen.classList.add('fade-out');
                        setTimeout(function () {
                            if (screen.parentNode) {
                                screen.remove();
                            }
                        }, 500);
                    }, delay);
                }

                // Fallback timeout in case window onload is delayed
                setTimeout(hideLoader, MAX_TIMEOUT_MS);

                if (document.readyState === 'complete') {
                    hideLoader();
                } else {
                    window.addEventListener('load', hideLoader);
                }
            } else {
                // Not first visit — remove immediately without showing
                screen.remove();
            }
        })();
    </script>

    <!-- Service Worker & Web Push Registration -->
    <script>
        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding)
                .replace(/\-/g, '+')
                .replace(/_/g, '/');

            const rawData = window.atob(base64);
            const outputArray = new Uint8Array(rawData.length);

            for (let i = 0; i < rawData.length; ++i) {
                outputArray[i] = rawData.charCodeAt(i);
            }
            return outputArray;
        }

        if ('serviceWorker' in navigator && 'PushManager' in window) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js').then(function(registration) {
                    console.log('SW ter-register dengan scope:', registration.scope);
                    
                    // Coba subscribe push
                    subscribeUserToPush(registration);
                }).catch(function(err) {
                    console.log('SW registration gagal:', err);
                });
            });
        }

        function subscribeUserToPush(registration) {
            const vapidPublicKey = '{{ env("VAPID_PUBLIC_KEY") }}';
            if(!vapidPublicKey) return;

            const convertedVapidKey = urlBase64ToUint8Array(vapidPublicKey);

            // Kita tunggu permission dulu, kalau denied langsung keluar
            if (Notification.permission === 'denied') return;

            registration.pushManager.subscribe({
                userVisibleOnly: true,
                applicationServerKey: convertedVapidKey
            })
            .then(function(subscription) {
                // Kirim subscription ke server (SettingsController@savePushSubscription)
                sendSubscriptionToServer(subscription);
            })
            .catch(function(err) {
                console.log('Push subscription gagal: ', err);
            });
        }

        function sendSubscriptionToServer(subscription) {
            fetch('/settings/push-subscription', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(subscription)
            })
            .then(res => res.json())
            .then(data => console.log('Subscription saved on server.', data))
            .catch(err => console.error('Subscription error', err));
        }
    </script>
</body>
</html>
