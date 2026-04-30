<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cars ni Bai — Premium Car Rental</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: #0A0A0A;
            color: #EDEDEC;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Background gradient glow */
        .bg-glow {
            position: fixed; top: -40%; left: 50%; transform: translateX(-50%);
            width: 800px; height: 800px;
            background: radial-gradient(circle, rgba(255,184,0,0.06) 0%, transparent 70%);
            pointer-events: none; z-index: 0;
        }

        /* Navigation */
        .nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 20px 48px;
            background: rgba(10,10,10,0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }
        .nav-brand {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 28px; letter-spacing: 3px;
            color: #FFB800; text-decoration: none;
        }
        .nav-brand span { color: #EDEDEC; font-size: 14px; letter-spacing: 1px; display: block; font-family: 'Poppins', sans-serif; font-weight: 300; margin-top: -4px; }
        .nav-links { display: flex; align-items: center; gap: 24px; }
        .nav-links a {
            color: #A1A09A; text-decoration: none; font-size: 13px; font-weight: 500;
            transition: color .2s;
        }
        .nav-links a:hover { color: #FFB800; }

        /* Hero */
        .hero {
            position: relative; z-index: 1;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 120px 48px 80px;
        }
        .hero-content {
            max-width: 1200px; width: 100%; margin: 0 auto;
            display: grid; grid-template-columns: 1fr 1fr; gap: 60px; align-items: center;
        }
        .hero-text h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 72px; letter-spacing: 4px; line-height: 1;
            margin-bottom: 8px;
        }
        .hero-text h1 .gold { color: #FFB800; }
        .hero-text .tagline {
            font-size: 18px; font-weight: 300; color: #A1A09A;
            margin-bottom: 32px; line-height: 1.6;
        }
        .hero-text .tagline strong { color: #EDEDEC; font-weight: 600; }
        .hero-actions { display: flex; gap: 16px; flex-wrap: wrap; }

        /* Buttons */
        .btn-hero {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 14px 32px;
            font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 600;
            border-radius: 8px; text-decoration: none;
            transition: all .2s; cursor: pointer; border: none;
        }
        .btn-hero.primary {
            background: #FFB800; color: #0A0A0A;
        }
        .btn-hero.primary:hover { background: #FFD04A; transform: translateY(-2px); box-shadow: 0 8px 30px rgba(255,184,0,0.25); }
        .btn-hero.outline {
            background: transparent; color: #EDEDEC;
            border: 1px solid rgba(255,255,255,0.15);
        }
        .btn-hero.outline:hover { border-color: #FFB800; color: #FFB800; transform: translateY(-2px); }

        /* Vehicle showcase */
        .hero-visual {
            position: relative;
            display: grid; grid-template-columns: 1fr 1fr; gap: 16px;
        }
        .vehicle-showcase {
            background: #161615;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 12px; overflow: hidden;
            transition: all .3s;
        }
        .vehicle-showcase:hover { border-color: rgba(255,184,0,0.3); transform: translateY(-4px); }
        .vehicle-showcase img {
            width: 100%; height: 140px; object-fit: cover;
            background: #1E1E1E;
        }
        .vehicle-showcase .info {
            padding: 14px 16px;
        }
        .vehicle-showcase .info .type {
            font-size: 10px; text-transform: uppercase; letter-spacing: 1px;
            color: #FFB800; font-weight: 600; margin-bottom: 4px;
        }
        .vehicle-showcase .info .name {
            font-size: 14px; font-weight: 600; color: #EDEDEC;
        }

        /* Features strip */
        .features {
            position: relative; z-index: 1;
            padding: 60px 48px;
            background: #111110;
            border-top: 1px solid rgba(255,255,255,0.04);
        }
        .features-grid {
            max-width: 1200px; margin: 0 auto;
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 32px;
        }
        .feature-item {
            text-align: center; padding: 24px;
        }
        .feature-item i {
            font-size: 28px; color: #FFB800; display: block; margin-bottom: 14px;
        }
        .feature-item h3 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 18px; letter-spacing: 1px; margin-bottom: 6px;
        }
        .feature-item p { font-size: 12px; color: #A1A09A; line-height: 1.5; }

        /* Admin login floating button */
        .admin-login {
            position: fixed; bottom: 32px; right: 32px; z-index: 200;
            display: inline-flex; align-items: center; gap: 8px;
            padding: 12px 24px;
            background: rgba(22,22,21,0.9);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 50px;
            color: #A1A09A; text-decoration: none;
            font-size: 12px; font-weight: 500;
            backdrop-filter: blur(20px);
            transition: all .2s;
        }
        .admin-login:hover {
            border-color: #FFB800; color: #FFB800;
            background: rgba(255,184,0,0.08);
        }
        .admin-login i { font-size: 16px; }

        /* Footer */
        .footer {
            position: relative; z-index: 1;
            text-align: center; padding: 32px 48px;
            color: #555; font-size: 12px;
            border-top: 1px solid rgba(255,255,255,0.04);
        }

        /* Responsive */
        @media (max-width: 900px) {
            .hero-content { grid-template-columns: 1fr; text-align: center; }
            .hero-text h1 { font-size: 48px; }
            .hero-actions { justify-content: center; }
            .hero-visual { max-width: 400px; margin: 0 auto; }
            .features-grid { grid-template-columns: repeat(2, 1fr); }
            .nav { padding: 16px 24px; }
            .hero { padding: 100px 24px 60px; }
        }
        @media (max-width: 500px) {
            .features-grid { grid-template-columns: 1fr; }
            .hero-visual { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div class="bg-glow"></div>

<!-- Navigation -->
<nav class="nav">
    <a href="/" class="nav-brand">
        CARS NI BAI
        <span>Rental Management</span>
    </a>
    <div class="nav-links">
        <a href="{{ route('quotes.request') }}">Get a Quote</a>
        @auth
            <a href="{{ url('/dashboard') }}" style="color: #FFB800;">Dashboard</a>
        @endauth
    </div>
</nav>

<!-- Hero Section -->
<section class="hero">
    <div class="hero-content">
        <div class="hero-text">
            <h1><span class="gold">PREMIUM</span><br>CAR RENTAL</h1>
            <p class="tagline">
                Self-drive &amp; chauffeured vehicles for every journey.<br>
                <strong>Affordable rates</strong>, well-maintained fleet, hassle-free booking.
            </p>
            <div class="hero-actions">
                <a href="{{ route('quotes.request') }}" class="btn-hero primary">
                    <i class="bi bi-send"></i> Get a Quote
                </a>
                <a href="#fleet" class="btn-hero outline">
                    <i class="bi bi-car-front"></i> View Fleet
                </a>
            </div>
        </div>
        <div class="hero-visual" id="fleet">
            <div class="vehicle-showcase">
                <img src="{{ asset('images/sedan.png') }}" alt="Sedan">
                <div class="info">
                    <div class="type">Sedan</div>
                    <div class="name">Comfortable &amp; Fuel-Efficient</div>
                </div>
            </div>
            <div class="vehicle-showcase">
                <img src="{{ asset('images/suv.png') }}" alt="SUV">
                <div class="info">
                    <div class="type">SUV</div>
                    <div class="name">Spacious &amp; Powerful</div>
                </div>
            </div>
            <div class="vehicle-showcase">
                <img src="{{ asset('images/mpv.png') }}" alt="MPV">
                <div class="info">
                    <div class="type">MPV</div>
                    <div class="name">Family &amp; Group Travel</div>
                </div>
            </div>
            <div class="vehicle-showcase">
                <img src="{{ asset('images/pickup.png') }}" alt="Pickup">
                <div class="info">
                    <div class="type">Pickup</div>
                    <div class="name">Rugged &amp; Versatile</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="features">
    <div class="features-grid">
        <div class="feature-item">
            <i class="bi bi-shield-check"></i>
            <h3>FULLY INSURED</h3>
            <p>All vehicles are comprehensively insured for your peace of mind.</p>
        </div>
        <div class="feature-item">
            <i class="bi bi-clock-history"></i>
            <h3>FLEXIBLE RENTAL</h3>
            <p>Daily, weekly, or monthly rental periods tailored to your needs.</p>
        </div>
        <div class="feature-item">
            <i class="bi bi-geo-alt"></i>
            <h3>SELF-DRIVE OR WITH DRIVER</h3>
            <p>Choose to drive yourself or book a professional driver.</p>
        </div>
        <div class="feature-item">
            <i class="bi bi-telephone"></i>
            <h3>24/7 SUPPORT</h3>
            <p>Round-the-clock assistance for any roadside emergency.</p>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    &copy; {{ date('Y') }} Cars ni Bai — Rental Management System
</footer>

<!-- Admin Login Button -->
<a href="{{ route('login') }}" class="admin-login">
    <i class="bi bi-lock"></i> Login as Admin
</a>

</body>
</html>
