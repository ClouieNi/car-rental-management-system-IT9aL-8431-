<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Cars ni Bai') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

        <!-- Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            
            body {
                font-family: 'Poppins', sans-serif;
                background: #0A0A0A;
                color: #EDEDEC;
                min-height: 100vh;
                overflow-x: hidden;
            }

            /* Background gradient glows */
            .bg-glow {
                position: fixed; top: -40%; left: 30%;
                width: 800px; height: 800px;
                background: radial-gradient(circle, rgba(255,184,0,0.07) 0%, transparent 70%);
                pointer-events: none; z-index: 0;
                animation: float-slow 20s ease-in-out infinite;
            }
            .bg-glow-2 {
                position: fixed; bottom: -30%; right: -10%;
                width: 600px; height: 600px;
                background: radial-gradient(circle, rgba(56,189,248,0.04) 0%, transparent 70%);
                pointer-events: none; z-index: 0;
                animation: float 25s ease-in-out infinite;
            }

            /* Floating lights */
            .floating-light {
                position: fixed;
                border-radius: 50%;
                filter: blur(60px);
                pointer-events: none;
                z-index: 0;
            }
            .light-1 {
                width: 180px; height: 180px;
                background: rgba(255,184,0,0.08);
                top: 15%; left: 5%;
                animation: float 12s ease-in-out infinite;
            }
            .light-2 {
                width: 120px; height: 120px;
                background: rgba(255,184,0,0.06);
                top: 45%; right: 8%;
                animation: float-slow 16s ease-in-out infinite;
            }
            .light-3 {
                width: 200px; height: 200px;
                background: rgba(255,140,0,0.05);
                bottom: 20%; left: 25%;
                animation: float 20s ease-in-out infinite;
            }
            .light-4 {
                width: 150px; height: 150px;
                background: rgba(255,184,0,0.07);
                top: 25%; right: 35%;
                animation: float 14s ease-in-out infinite 2s;
            }
            .light-5 {
                width: 100px; height: 100px;
                background: rgba(255,200,0,0.09);
                bottom: 35%; right: 5%;
                animation: float-slow 18s ease-in-out infinite 1s;
            }

            @keyframes float {
                0%, 100% { transform: translate(0, 0) scale(1); }
                25% { transform: translate(20px, -30px) scale(1.1); }
                50% { transform: translate(-30px, -20px) scale(1); }
                75% { transform: translate(-10px, 20px) scale(0.9); }
            }
            @keyframes float-slow {
                0%, 100% { transform: translate(0, 0) scale(1); }
                33% { transform: translate(-40px, 20px) scale(1.15); }
                66% { transform: translate(30px, -30px) scale(0.95); }
            }
        </style>
    </head>
    <body class="font-sans text-cream antialiased">
        {{-- Animated Background --}}
        <div class="bg-glow"></div>
        <div class="bg-glow-2"></div>
        <div class="floating-light light-1"></div>
        <div class="floating-light light-2"></div>
        <div class="floating-light light-3"></div>
        <div class="floating-light light-4"></div>
        <div class="floating-light light-5"></div>

        {{-- Content --}}
        <div class="relative z-10 min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/" class="flex flex-col items-center">
                    <div class="font-display text-3xl tracking-wider text-gold">Cars ni Bai</div>
                    <div class="text-xs text-gray-500 uppercase tracking-widest">Rental Management</div>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-dark-100/90 backdrop-blur-sm border border-white/10 shadow-xl overflow-hidden rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
