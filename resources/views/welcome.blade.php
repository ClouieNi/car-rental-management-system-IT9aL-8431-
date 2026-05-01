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
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Poppins', sans-serif;
            background: #0A0A0A; color: #EDEDEC;
            min-height: 100vh;
            overflow-x: hidden;
        }
        a { text-decoration: none; }

        /* Background gradient glow */
        .bg-glow {
            position: fixed; top: -40%; left: 30%;
            width: 800px; height: 800px;
            background: radial-gradient(circle, rgba(255,184,0,0.07) 0%, transparent 70%);
            pointer-events: none; z-index: 0;
        }
        .bg-glow-2 {
            position: fixed; bottom: -30%; right: -10%;
            width: 600px; height: 600px;
            background: radial-gradient(circle, rgba(56,189,248,0.04) 0%, transparent 70%);
            pointer-events: none; z-index: 0;
        }

        /* Top Nav */
        .nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 48px;
            background: rgba(10,10,10,0.7);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }
        .nav-brand {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 26px; letter-spacing: 3px; color: #FFB800;
        }
        .nav-brand span {
            color: #A1A09A; font-size: 11px; letter-spacing: 1px; display: block;
            font-family: 'Poppins', sans-serif; font-weight: 300; margin-top: -4px;
        }
        .nav-links { display: flex; align-items: center; gap: 28px; }
        .nav-links a {
            color: #A1A09A; font-size: 13px; font-weight: 500;
            transition: color .2s;
        }
        .nav-links a:hover { color: #FFB800; }
        .nav-links .cta {
            background: #FFB800; color: #0A0A0A;
            padding: 8px 18px; border-radius: 6px;
            font-weight: 600;
        }
        .nav-links .cta:hover { background: #FFD04A; color: #0A0A0A; }

        /* ── Hero Split ────────────────────────── */
        .hero-split {
            position: relative; z-index: 1;
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            padding-top: 70px;
        }

        /* Left side */
        .hero-left {
            display: flex; flex-direction: column; justify-content: center;
            padding: 60px 80px;
        }
        .hero-eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 6px 14px;
            background: rgba(255,184,0,0.1);
            border: 1px solid rgba(255,184,0,0.2);
            border-radius: 50px;
            color: #FFB800; font-size: 11px; font-weight: 600;
            letter-spacing: 1.5px; text-transform: uppercase;
            margin-bottom: 24px;
            width: fit-content;
        }
        .hero-eyebrow i { font-size: 12px; }
        .hero-left h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 84px; letter-spacing: 4px; line-height: .95;
            margin-bottom: 16px;
        }
        .hero-left h1 .gold { color: #FFB800; }
        .hero-left .tagline {
            font-size: 17px; font-weight: 300; color: #A1A09A;
            line-height: 1.6; margin-bottom: 36px; max-width: 480px;
        }
        .hero-left .tagline strong { color: #EDEDEC; font-weight: 600; }

        .hero-actions { display: flex; gap: 14px; margin-bottom: 28px; flex-wrap: wrap; }
        .btn-hero {
            display: inline-flex; align-items: center; gap: 10px;
            padding: 14px 28px;
            font-size: 14px; font-weight: 600;
            border-radius: 8px; border: none; cursor: pointer;
            transition: all .2s;
        }
        .btn-hero.primary {
            background: #FFB800; color: #0A0A0A;
        }
        .btn-hero.primary:hover {
            background: #FFD04A; transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(255,184,0,0.25);
        }
        .btn-hero.outline {
            background: transparent; color: #EDEDEC;
            border: 1px solid rgba(255,255,255,0.15);
        }
        .btn-hero.outline:hover { border-color: #FFB800; color: #FFB800; transform: translateY(-2px); }

        .signin-hint {
            font-size: 12px; color: #555;
            display: flex; align-items: center; gap: 8px;
        }
        .signin-hint i { color: #FFB800; }

        /* Right side - login card */
        .hero-right {
            display: flex; align-items: center; justify-content: center;
            padding: 60px 80px;
            background: linear-gradient(135deg, rgba(22,22,21,0.6) 0%, rgba(10,10,10,0.6) 100%);
            border-left: 1px solid rgba(255,255,255,0.04);
            position: relative;
        }
        .login-card {
            width: 100%; max-width: 400px;
            background: rgba(22,22,21,0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 16px;
            padding: 40px 36px;
            box-shadow: 0 24px 60px rgba(0,0,0,0.4);
        }
        .login-card .lock-badge {
            display: inline-flex; align-items: center; justify-content: center;
            width: 48px; height: 48px;
            background: rgba(255,184,0,0.1);
            border: 1px solid rgba(255,184,0,0.2);
            border-radius: 12px;
            color: #FFB800; font-size: 22px;
            margin-bottom: 18px;
        }
        .login-card h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 32px; letter-spacing: 2px; margin-bottom: 4px;
        }
        .login-card .subtitle {
            font-size: 13px; color: #A1A09A; margin-bottom: 28px;
        }

        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block; font-size: 11px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 1px;
            color: #A1A09A; margin-bottom: 8px;
        }
        .form-group .input-wrap { position: relative; }
        .form-group .input-wrap i.icon {
            position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            color: #555; font-size: 14px;
        }
        .form-group input[type="email"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 12px 14px 12px 42px;
            background: rgba(10,10,10,0.6);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 8px;
            color: #EDEDEC; font-size: 14px;
            font-family: inherit;
            transition: all .2s;
        }
        .form-group input:focus {
            outline: none;
            border-color: #FFB800;
            background: rgba(10,10,10,0.9);
            box-shadow: 0 0 0 3px rgba(255,184,0,0.1);
        }
        .form-group input::placeholder { color: #555; }
        .form-error {
            font-size: 12px; color: #F87171; margin-top: 6px;
            display: flex; align-items: center; gap: 4px;
        }

        .form-options {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 24px;
        }
        .remember {
            display: flex; align-items: center; gap: 8px;
            font-size: 12px; color: #A1A09A; cursor: pointer;
        }
        .remember input { accent-color: #FFB800; }
        .forgot-link {
            font-size: 12px; color: #A1A09A;
            transition: color .2s;
        }
        .forgot-link:hover { color: #FFB800; }

        .btn-signin {
            width: 100%;
            padding: 14px;
            background: #FFB800; color: #0A0A0A;
            border: none; border-radius: 8px;
            font-size: 14px; font-weight: 600;
            font-family: inherit;
            letter-spacing: 0.5px;
            cursor: pointer;
            transition: all .2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-signin:hover {
            background: #FFD04A;
            box-shadow: 0 8px 24px rgba(255,184,0,0.25);
        }

        .session-status {
            background: rgba(74,222,128,0.1);
            border: 1px solid rgba(74,222,128,0.2);
            color: #4ADE80;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 18px;
        }

        /* ── Quick Quote Combobox ─────────────────────── */
        .qq-combobox { position: relative; }
        .qq-combobox .input-wrap input { padding-right: 42px; }
        .qq-toggle {
            position: absolute; top: 0; right: 0;
            height: 42px; width: 38px;
            background: transparent; border: none;
            color: #A1A09A; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px;
            transition: color .2s, transform .2s;
        }
        .qq-toggle:hover { color: #FFB800; }
        .qq-toggle.open i { transform: rotate(180deg); }
        .qq-panel {
            position: absolute; top: calc(100% + 4px); left: 0; right: 0;
            z-index: 50;
            background: #161615;
            border: 1px solid rgba(255,184,0,0.2);
            border-radius: 8px;
            max-height: 220px; overflow-y: auto;
            box-shadow: 0 12px 32px rgba(0,0,0,0.5);
            display: none;
        }
        .qq-panel.visible { display: block; }
        .qq-panel::-webkit-scrollbar { width: 6px; }
        .qq-panel::-webkit-scrollbar-track { background: #0A0A0A; }
        .qq-panel::-webkit-scrollbar-thumb { background: rgba(255,184,0,0.2); border-radius: 4px; }
        .qq-section {
            font-size: 9px; font-weight: 600; letter-spacing: 1.5px;
            text-transform: uppercase; color: #FFB800;
            padding: 6px 12px 4px;
            background: rgba(255,184,0,0.04);
            border-bottom: 1px solid rgba(255,184,0,0.08);
        }
        .qq-option {
            display: flex; justify-content: space-between; align-items: center;
            padding: 9px 12px;
            font-size: 12px; color: #EDEDEC;
            cursor: pointer;
            border-left: 3px solid transparent;
        }
        .qq-option:hover, .qq-option.active {
            background: rgba(255,184,0,0.1);
            border-left-color: #FFB800;
        }
        .qq-option .km { font-size: 10px; color: #777; }
        .qq-empty {
            padding: 14px 12px; text-align: center;
            color: #777; font-size: 11px; font-style: italic;
        }
        .qq-helper {
            font-size: 11px; margin-top: 6px; min-height: 16px;
            display: flex; align-items: center; gap: 4px;
            color: #4ADE80;
        }
        .qq-admin-link {
            display: block; text-align: center; margin-top: 18px;
            color: #555; font-size: 11px;
        }
        .qq-admin-link:hover { color: #FFB800; }

        /* ── Fleet Showcase ────────────────────── */
        .section {
            position: relative; z-index: 1;
            padding: 80px 48px;
        }
        .section-header {
            max-width: 1200px; margin: 0 auto 48px;
            text-align: center;
        }
        .section-header .eyebrow {
            font-size: 11px; font-weight: 600; letter-spacing: 2px;
            text-transform: uppercase; color: #FFB800; margin-bottom: 8px;
        }
        .section-header h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 48px; letter-spacing: 3px; margin-bottom: 12px;
        }
        .section-header p { color: #A1A09A; font-size: 15px; max-width: 600px; margin: 0 auto; }

        .fleet-grid {
            max-width: 1200px; margin: 0 auto;
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px;
        }
        .vehicle-card {
            background: #161615;
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 14px; overflow: hidden;
            transition: all .3s;
        }
        .vehicle-card:hover {
            border-color: rgba(255,184,0,0.3);
            transform: translateY(-6px);
            box-shadow: 0 16px 40px rgba(0,0,0,0.3);
        }
        .vehicle-card img {
            width: 100%; height: 160px; object-fit: cover; background: #1E1E1E;
        }
        .vehicle-card .info { padding: 18px 20px; }
        .vehicle-card .type {
            font-size: 10px; text-transform: uppercase; letter-spacing: 1.5px;
            color: #FFB800; font-weight: 600; margin-bottom: 4px;
        }
        .vehicle-card .name { font-size: 15px; font-weight: 600; margin-bottom: 4px; }
        .vehicle-card .desc { font-size: 12px; color: #A1A09A; line-height: 1.5; }

        /* ── Features ──────────────────────────── */
        .features {
            background: #111110;
            border-top: 1px solid rgba(255,255,255,0.04);
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }
        .features-grid {
            max-width: 1200px; margin: 0 auto;
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px;
        }
        .feature-item { text-align: center; padding: 16px; }
        .feature-item i { font-size: 32px; color: #FFB800; display: block; margin-bottom: 14px; }
        .feature-item h3 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 18px; letter-spacing: 1.5px; margin-bottom: 6px;
        }
        .feature-item p { font-size: 12px; color: #A1A09A; line-height: 1.5; }

        /* ── Footer ────────────────────────────── */
        .footer {
            position: relative; z-index: 1;
            text-align: center; padding: 36px 48px;
            color: #555; font-size: 12px;
        }

        /* ── Responsive ────────────────────────── */
        @media (max-width: 1100px) {
            .hero-left { padding: 60px 40px; }
            .hero-right { padding: 60px 40px; }
            .hero-left h1 { font-size: 64px; }
        }
        @media (max-width: 900px) {
            .hero-split { grid-template-columns: 1fr; min-height: auto; padding-top: 70px; }
            .hero-right { border-left: none; border-top: 1px solid rgba(255,255,255,0.04); }
            .hero-left { padding: 40px 24px; text-align: center; align-items: center; }
            .hero-actions { justify-content: center; }
            .hero-eyebrow { margin-left: auto; margin-right: auto; }
            .nav { padding: 14px 24px; }
            .fleet-grid { grid-template-columns: repeat(2, 1fr); }
            .features-grid { grid-template-columns: repeat(2, 1fr); }
            .section { padding: 60px 24px; }
            .section-header h2 { font-size: 36px; }
            .hero-left h1 { font-size: 56px; }
        }
        @media (max-width: 500px) {
            .fleet-grid, .features-grid { grid-template-columns: 1fr; }
            .nav-links a:not(.cta) { display: none; }
            .hero-right { padding: 40px 20px; }
            .login-card { padding: 30px 24px; }
            .hero-left h1 { font-size: 44px; }
        }
    </style>
</head>
<body>

<div class="bg-glow"></div>
<div class="bg-glow-2"></div>

<!-- Navigation -->
<nav class="nav">
    <a href="/landing" class="nav-brand">
        CARS NI BAI
        <span>Rental Management</span>
    </a>
    <div class="nav-links">
        <a href="#fleet">Fleet</a>
        <a href="#features">Features</a>
        <a href="{{ route('login') }}" class="cta">
            <i class="bi bi-box-arrow-in-right"></i> Login
        </a>
    </div>
</nav>

<!-- Hero: Split Landing + Login -->
<section class="hero-split">
    <!-- Left: Branding -->
    <div class="hero-left">
        <div class="hero-eyebrow">
            <i class="bi bi-star-fill"></i> Premium Rental Service
        </div>
        <h1><span class="gold">DRIVE</span> WITH<br>CONFIDENCE</h1>
        <p class="tagline">
            Self-drive &amp; chauffeured vehicles for every journey.<br>
            <strong>Affordable rates</strong>, well-maintained fleet, hassle-free booking — all under one roof.
        </p>
        <div class="signin-hint">
            <i class="bi bi-arrow-right"></i> Get your instant quote on the right →
        </div>
    </div>

    <!-- Right: Quick Quote Form -->
    <div class="hero-right" id="quick-quote">
        <div class="login-card">
            <div class="lock-badge" style="color:#FFB800;"><i class="bi bi-send"></i></div>
            <h2>GET A QUOTE</h2>
            <p class="subtitle">Tell us where you're heading — we'll show your price instantly.</p>

            <form method="GET" action="{{ route('quotes.request') }}" id="quick-quote-form">
                <div class="form-group">
                    <label for="qq_name">Full Name</label>
                    <div class="input-wrap">
                        <i class="bi bi-person icon"></i>
                        <input id="qq_name" type="text" name="guest_name" required
                               placeholder="Juan Dela Cruz">
                    </div>
                </div>

                <div class="form-group">
                    <label for="qq_email">Email Address</label>
                    <div class="input-wrap">
                        <i class="bi bi-envelope icon"></i>
                        <input id="qq_email" type="email" name="guest_email" required
                               placeholder="you@example.com">
                    </div>
                </div>

                <div class="form-group">
                    <label for="qq_destination">Destination</label>
                    <div class="qq-combobox" id="qq-combo">
                        <div class="input-wrap">
                            <i class="bi bi-geo-alt icon"></i>
                            <input id="qq_destination" type="text" name="destination"
                                   autocomplete="off"
                                   placeholder="Type or click ↓ to browse...">
                        </div>
                        <button type="button" class="qq-toggle" id="qq-toggle" tabindex="-1">
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div class="qq-panel" id="qq-panel"></div>
                    </div>
                    <input type="hidden" name="distance_km" id="qq_distance" value="0">
                    <div class="qq-helper" id="qq-helper"></div>
                </div>

                <button type="submit" class="btn-signin">
                    Continue to Quote <i class="bi bi-arrow-right"></i>
                </button>
            </form>

            <a href="{{ route('login') }}" class="qq-admin-link">
                Are you an admin? Sign in here
            </a>
        </div>
    </div>
</section>

<!-- Fleet Showcase -->
<section class="section" id="fleet">
    <div class="section-header">
        <div class="eyebrow">Our Fleet</div>
        <h2>VEHICLES FOR EVERY JOURNEY</h2>
        <p>Choose from our carefully curated lineup — from city sedans to rugged pickups, all kept in pristine condition.</p>
    </div>
    <div class="fleet-grid">
        <div class="vehicle-card">
            <img src="{{ asset('images/sedan.png') }}" alt="Sedan">
            <div class="info">
                <div class="type">Sedan</div>
                <div class="name">Comfortable Daily Driver</div>
                <div class="desc">Fuel-efficient, smooth ride, perfect for city commutes and short trips.</div>
            </div>
        </div>
        <div class="vehicle-card">
            <img src="{{ asset('images/suv.png') }}" alt="SUV">
            <div class="info">
                <div class="type">SUV</div>
                <div class="name">Powerful &amp; Spacious</div>
                <div class="desc">High ground clearance and seating for the whole family. Ideal for adventures.</div>
            </div>
        </div>
        <div class="vehicle-card">
            <img src="{{ asset('images/mpv.png') }}" alt="MPV">
            <div class="info">
                <div class="type">MPV</div>
                <div class="name">Family &amp; Group Travel</div>
                <div class="desc">7–8 seater configuration with room for luggage. Perfect for road trips.</div>
            </div>
        </div>
        <div class="vehicle-card">
            <img src="{{ asset('images/pickup.png') }}" alt="Pickup">
            <div class="info">
                <div class="type">Pickup</div>
                <div class="name">Rugged &amp; Versatile</div>
                <div class="desc">Tow, haul, and handle any terrain. Built for work and weekend escapes.</div>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section class="section features" id="features">
    <div class="section-header">
        <div class="eyebrow">Why Choose Us</div>
        <h2>HASSLE-FREE RENTAL</h2>
        <p>We make car rental simple, transparent, and reliable.</p>
    </div>
    <div class="features-grid">
        <div class="feature-item">
            <i class="bi bi-shield-check"></i>
            <h3>FULLY INSURED</h3>
            <p>All vehicles comprehensively insured for your peace of mind.</p>
        </div>
        <div class="feature-item">
            <i class="bi bi-clock-history"></i>
            <h3>FLEXIBLE RENTAL</h3>
            <p>Daily, weekly, or monthly plans tailored to your needs.</p>
        </div>
        <div class="feature-item">
            <i class="bi bi-person-badge"></i>
            <h3>SELF OR DRIVER</h3>
            <p>Drive yourself or book a professional driver — your call.</p>
        </div>
        <div class="feature-item">
            <i class="bi bi-headset"></i>
            <h3>24/7 SUPPORT</h3>
            <p>Round-the-clock assistance for any roadside emergency.</p>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
    &copy; {{ date('Y') }} Cars ni Bai — Rental Management System
</footer>

<script>
// ── Destination data (driving km from Davao City) ─────────────────────────
const QQ_GROUPS = [
    { region: 'Davao del Norte', items: [['Tagum City',55],['Panabo City',31],['Carmen',38],['Braulio Dujali',40],['Asuncion',70],['Kapalong',80],['San Isidro',85],['Talaingod',95],['New Corella',75],['Santo Tomas',45]] },
    { region: 'Davao del Sur', items: [['Digos City',46],['Santa Cruz',42],['Hagonoy',60],['Bansalan',65],['Magsaysay',70],['Matanao',80],['Padada',55],['Sulop',75],['Kiblawan',85],['Malalag',90]] },
    { region: 'Davao Oriental', items: [['Mati City',84],['Lupon',95],['Baganga',145],['Caraga',160],['Boston',170],['Cateel',185],['Manay',120],['Tarragona',130],['Governor Generoso',110]] },
    { region: 'Davao de Oro', items: [['Nabunturan',90],['Compostela',75],['Maco',48],['Mabini',44],['Pantukan',55],['New Bataan',80],['Monkayo',100],['Montevista',110],['Laak',130],['Maragusan',120]] },
    { region: 'Davao Occidental', items: [['Malita',145],['Don Marcelino',180],['Jose Abad Santos',220],['Sarangani (Davao Occ)',195],['Santa Maria',160]] },
    { region: 'Samal Island', items: [['Samal Island',26]] },
    { region: 'North Cotabato', items: [['Kidapawan City',45],['Makilala',85],['Kabacan',110],['Magpet',100],['Mlang',105],['Matalam',115],['Arakan',120],['Antipas',110],['President Roxas',125],['Pigkawayan',140],['Pikit',155],['Midsayap',145],['Alamada',135],['Libungan',150],['Banisilan',160],['Aleosan',140]] },
    { region: 'South Cotabato', items: [['Koronadal City',100],['General Santos City',146],['Surallah',120],['Tupi',130],['Polomolok',140],['Norala',115],['Banga',125],['Lake Sebu',145],['Tampakan',140],['Tantangan',120],["T'boli",155]] },
    { region: 'Sultan Kudarat', items: [['Tacurong City',130],['Isulan',140],['Kalamansig',195],['Lebak',180],['Bagumbayan',160],['Columbio',165],['Esperanza',150],['Lambayong',155],['Lutayan',170],['Palimbang',190],['President Quirino',145],['Sen. Ninoy Aquino',175]] },
    { region: 'Sarangani', items: [['Alabel',135],['Malungon',90],['Glan',175],['Kiamba',160],['Malapatan',165],['Maasim',155],['Maitum',150]] },
    { region: 'BARMM / Cotabato', items: [['Cotabato City',136],['Kakar',145],['Datu Odin Sinsuat',150],['Sultan Kudarat (Maguindanao)',155],['Buluan',160],['Upi',180]] },
    { region: 'Agusan del Norte', items: [['Butuan City',201],['Cabadbaran City',215],['Nasipit',210],['Las Nieves',220],['Magallanes',225],['Tubay',220]] },
    { region: 'Agusan del Sur', items: [['Prosperidad',230],['Bayugan City',240],['Bunawan',255],['Loreto',270],['Trento',220],['Veruela',260],['La Paz',245],['San Francisco',250],['Sibagat',265]] },
    { region: 'Surigao del Norte', items: [['Surigao City',320],['Dapa (Siargao)',345],['Mainit',295],['Malimono',310],['Placer',305],['Tubod',300]] },
    { region: 'Surigao del Sur', items: [['Tandag City',270],['Bislig City',230],['Cantilan',280],['Carrascal',265],['Cortes',255],['Hinatuan',240],['Lianga',245],['Lingig',250]] },
    { region: 'Bukidnon', items: [['Malaybalay City',170],['Valencia City',185],['Manolo Fortich',195],['Maramag',180],['Kibawe',175],['Kalilangan',185],['Lantapan',180],['Impasugong',195],['San Fernando',190],['Cabanglasan',200],['Libona',205],['Talakag',210]] },
    { region: 'Misamis Oriental', items: [['Cagayan de Oro City',175],['El Salvador City',185],['Gingoog City',230],['Tagoloan',180],['Opol',180],['Villanueva',182],['Jasaan',185],['Initao',195]] },
    { region: 'Misamis Occidental', items: [['Ozamiz City',260],['Oroquieta City',290],['Tangub City',275],['Calamba',280],['Jimenez',285]] },
    { region: 'Lanao', items: [['Iligan City',171],['Marawi City',163],['Bacolod (Lanao)',190],['Kapatagan',195]] },
    { region: 'Camiguin', items: [['Mambajao (Camiguin)',240]] },
    { region: 'Zamboanga Peninsula', items: [['Pagadian City',238],['Dipolog City',330],['Dapitan City',340],['Zamboanga City',390],['Ipil',370],['Molave',265],['Dumingag',280],['Mahayag',270],['Labangan',255]] },
];
const QQ_FLAT = [];
QQ_GROUPS.forEach(g => g.items.forEach(([n,k]) => QQ_FLAT.push({ name:n, km:k, region:g.region })));

function qqNorm(s){ return s.toLowerCase().trim().replace(/\s+city$/,'').replace(/[.,']/g,'').replace(/\s+/g,' '); }

const $qqInput = document.getElementById('qq_destination');
const $qqDist = document.getElementById('qq_distance');
const $qqPanel = document.getElementById('qq-panel');
const $qqToggle = document.getElementById('qq-toggle');
const $qqHelper = document.getElementById('qq-helper');
let qqActive = -1;
let qqMatches = [];

function qqFilter(q) {
    if (!q || !q.trim()) return { grouped: true, groups: QQ_GROUPS };
    const nq = qqNorm(q);
    const pre = [], sub = [];
    QQ_FLAT.forEach(d => {
        const n = d.name.toLowerCase();
        if (qqNorm(d.name).startsWith(nq) || n.startsWith(nq)) pre.push(d);
        else if (n.includes(nq)) sub.push(d);
    });
    return { grouped: false, items: [...pre, ...sub] };
}

function qqRender(q) {
    const r = qqFilter(q);
    let html = '';
    if (r.grouped) {
        r.groups.forEach(g => {
            html += `<div class="qq-section">${g.region}</div>`;
            g.items.forEach(([n,k]) => {
                html += `<div class="qq-option" data-name="${n}" data-km="${k}"><span>${n}</span><span class="km">${k} km</span></div>`;
            });
        });
    } else if (r.items.length === 0) {
        html = '<div class="qq-empty">No matches found</div>';
    } else {
        r.items.forEach(d => {
            html += `<div class="qq-option" data-name="${d.name}" data-km="${d.km}"><span>${d.name} <small style="color:#555;">(${d.region})</small></span><span class="km">${d.km} km</span></div>`;
        });
    }
    $qqPanel.innerHTML = html;
    qqActive = -1;
    qqMatches = [...$qqPanel.querySelectorAll('.qq-option')];
}

function qqOpen(){ qqRender($qqInput.value); $qqPanel.classList.add('visible'); $qqToggle.classList.add('open'); }
function qqClose(){ $qqPanel.classList.remove('visible'); $qqToggle.classList.remove('open'); qqActive = -1; }
function qqSetActive(i) {
    qqMatches.forEach(el => el.classList.remove('active'));
    if (i >= 0 && i < qqMatches.length) {
        qqActive = i;
        qqMatches[i].classList.add('active');
        qqMatches[i].scrollIntoView({ block: 'nearest' });
    }
}
function qqSelect(el) {
    const name = el.dataset.name, km = parseInt(el.dataset.km, 10);
    $qqInput.value = name;
    $qqDist.value = km;
    $qqHelper.innerHTML = `<i class="bi bi-check-circle"></i> ${km} km from Davao City`;
    qqClose();
}

$qqInput.addEventListener('focus', qqOpen);
$qqInput.addEventListener('click', qqOpen);
$qqToggle.addEventListener('click', () => { $qqPanel.classList.contains('visible') ? qqClose() : ($qqInput.focus(), qqOpen()); });
$qqInput.addEventListener('input', () => {
    qqOpen();
    // Try direct match for typed value
    const nq = qqNorm($qqInput.value);
    const exact = QQ_FLAT.find(d => qqNorm(d.name) === nq);
    if (exact) {
        $qqDist.value = exact.km;
        $qqHelper.innerHTML = `<i class="bi bi-check-circle"></i> ${exact.km} km from Davao City`;
    } else {
        $qqDist.value = 0;
        $qqHelper.innerHTML = '';
    }
});
$qqInput.addEventListener('keydown', (e) => {
    if (!$qqPanel.classList.contains('visible')) {
        if (e.key === 'ArrowDown') { qqOpen(); e.preventDefault(); }
        return;
    }
    if (e.key === 'ArrowDown') { qqSetActive(Math.min(qqActive + 1, qqMatches.length - 1)); e.preventDefault(); }
    else if (e.key === 'ArrowUp') { qqSetActive(Math.max(qqActive - 1, 0)); e.preventDefault(); }
    else if (e.key === 'Enter' && qqActive >= 0) { qqSelect(qqMatches[qqActive]); e.preventDefault(); }
    else if (e.key === 'Escape') { qqClose(); }
});
$qqPanel.addEventListener('mousedown', (e) => {
    const opt = e.target.closest('.qq-option');
    if (opt) { e.preventDefault(); qqSelect(opt); }
});
document.addEventListener('click', (e) => {
    if (!document.getElementById('qq-combo').contains(e.target)) qqClose();
});
</script>

</body>
</html>
