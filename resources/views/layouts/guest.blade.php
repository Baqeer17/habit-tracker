<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'MahabBa')</title>

    <!-- PWA Manifest & Favicons -->
    <meta name="theme-color" content="#0F766E">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="MahabBa">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('icon.png') }}?v=2">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('icon.png') }}?v=2">
    <link rel="shortcut icon" href="{{ asset('icon.png') }}?v=2">
    <link rel="apple-touch-icon" href="{{ asset('icon.png') }}?v=2">
    <link rel="manifest" href="{{ asset('manifest.json') }}?v=3">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&family=Lora:ital,wght@0,400..700;1,400..700&family=Amiri:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- AOS Animation CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                        serif: ['Lora', 'serif'],
                        arabic: ['Amiri', 'serif'],
                    },
                    colors: {
                        mahabba: {
                            green: '#2D5A43',
                            light: '#F8FBFC',
                            cyan: '#E6F3F5',
                            dark: '#4D4D4D',
                            teal: '#0F766E'
                        }
                    },
                    keyframes: {
                        marqueeLeft: {
                            '0%': { transform: 'translateX(0%)' },
                            '100%': { transform: 'translateX(-50%)' },
                        },
                        marqueeRight: {
                            '0%': { transform: 'translateX(-50%)' },
                            '100%': { transform: 'translateX(0%)' },
                        }
                    },
                    animation: {
                        marqueeLeft: 'marqueeLeft 60s linear infinite',
                        marqueeRight: 'marqueeRight 60s linear infinite',
                    }
                }
            }
        }
    </script>

    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #F8FBFC;
            color: #374151; /* gray-700 */
        }
        h1, h2, h3, h4, h5, h6, .font-serif {
            font-family: 'Lora', serif;
        }
    </style>

    @stack('styles')
</head>
<body class="antialiased overflow-x-hidden">
    
    <main>
        @yield('content')
    </main>

    <!-- AOS Animation JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                once: true,
                offset: 100,
                easing: 'ease-out-cubic'
            });
        });
    </script>

    <!-- Service Worker for PWA -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register('/sw.js').catch(function(err) {
                    console.log('SW registration failed:', err);
                });
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
