<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Get a Quote — Cars ni Bai</title>

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
        }
        a { text-decoration: none; }
        .bg-glow {
            position: fixed; top: -30%; left: 50%; transform: translateX(-50%);
            width: 700px; height: 700px;
            background: radial-gradient(circle, rgba(255,184,0,0.06) 0%, transparent 70%);
            pointer-events: none; z-index: 0;
        }
        .nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 18px 48px;
            background: rgba(10,10,10,0.7); backdrop-filter: blur(20px);
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
        .back-link {
            color: #A1A09A; font-size: 13px; font-weight: 500;
            display: inline-flex; align-items: center; gap: 6px;
        }
        .back-link:hover { color: #FFB800; }

        .container {
            position: relative; z-index: 1;
            max-width: 760px; margin: 0 auto;
            padding: 120px 24px 60px;
        }
        .header { text-align: center; margin-bottom: 36px; }
        .header .eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 6px 14px;
            background: rgba(255,184,0,0.1);
            border: 1px solid rgba(255,184,0,0.2);
            border-radius: 50px;
            color: #FFB800; font-size: 11px; font-weight: 600;
            letter-spacing: 1.5px; text-transform: uppercase;
            margin-bottom: 16px;
        }
        .header h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 56px; letter-spacing: 3px; line-height: 1; margin-bottom: 10px;
        }
        .header h1 .gold { color: #FFB800; }
        .header p { color: #A1A09A; font-size: 15px; max-width: 520px; margin: 0 auto; }

        .quote-card {
            background: rgba(22,22,21,0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 16px;
            padding: 36px 36px;
            box-shadow: 0 24px 60px rgba(0,0,0,0.4);
        }
        .form-section {
            padding-bottom: 24px; margin-bottom: 24px;
            border-bottom: 1px solid rgba(255,255,255,0.04);
        }
        .form-section:last-of-type { border-bottom: none; padding-bottom: 0; margin-bottom: 0; }
        .form-section h3 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 18px; letter-spacing: 2px;
            color: #FFB800; margin-bottom: 20px;
            display: flex; align-items: center; gap: 8px;
        }
        .form-row {
            display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;
        }
        .form-row.single { grid-template-columns: 1fr; }
        .form-group { display: flex; flex-direction: column; }
        .form-group.full { grid-column: 1 / -1; }
        .form-group label {
            font-size: 11px; font-weight: 600; text-transform: uppercase;
            letter-spacing: 1px; color: #A1A09A; margin-bottom: 8px;
        }
        .form-group label .req { color: #F87171; margin-left: 2px; }
        .form-group input, .form-group select, .form-group textarea {
            padding: 12px 14px;
            background: rgba(10,10,10,0.6);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 8px;
            color: #EDEDEC; font-size: 14px;
            font-family: inherit;
            transition: all .2s;
        }
        .form-group textarea { resize: vertical; min-height: 90px; }
        .form-group input:focus, .form-group select:focus, .form-group textarea:focus {
            outline: none; border-color: #FFB800;
            background: rgba(10,10,10,0.9);
            box-shadow: 0 0 0 3px rgba(255,184,0,0.1);
        }
        .form-group input::placeholder, .form-group textarea::placeholder { color: #555; }
        .form-group input:disabled, .form-group input[readonly] {
            background: rgba(10,10,10,0.3);
            color: #777; cursor: not-allowed;
            border-color: rgba(255,255,255,0.04);
        }
        .form-group .helper {
            font-size: 11px; margin-top: 6px;
            display: flex; align-items: center; gap: 4px;
        }
        .helper.success { color: #4ADE80; }
        .helper.warn { color: #F59E0B; }

        /* Davao City checkbox row */
        .davao-toggle {
            display: flex; align-items: center; gap: 12px;
            padding: 14px 16px;
            background: rgba(255,184,0,0.05);
            border: 1px solid rgba(255,184,0,0.15);
            border-radius: 8px;
            margin-bottom: 18px;
            cursor: pointer; user-select: none;
        }
        .davao-toggle input { accent-color: #FFB800; width: 18px; height: 18px; cursor: pointer; }
        .davao-toggle .label-main { font-size: 14px; font-weight: 600; color: #EDEDEC; }
        .davao-toggle .label-sub { font-size: 11px; color: #A1A09A; }

        .btn-submit, .btn-primary, .btn-outline {
            width: 100%; padding: 14px;
            border-radius: 8px;
            font-size: 14px; font-weight: 600; letter-spacing: .5px;
            font-family: inherit; cursor: pointer;
            transition: all .2s;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            border: none; text-decoration: none;
        }
        .btn-submit, .btn-primary {
            background: #FFB800; color: #0A0A0A;
        }
        .btn-submit:hover, .btn-primary:hover {
            background: #FFD04A;
            box-shadow: 0 8px 24px rgba(255,184,0,0.25);
        }
        .btn-outline {
            background: transparent; color: #EDEDEC;
            border: 1px solid rgba(255,255,255,0.15);
        }
        .btn-outline:hover { border-color: #FFB800; color: #FFB800; }
        .btn-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-top: 24px; }

        .alert-error {
            background: rgba(248,113,113,0.1);
            border: 1px solid rgba(248,113,113,0.2);
            color: #F87171;
            padding: 12px 14px; border-radius: 8px;
            font-size: 13px; margin-bottom: 18px;
        }
        .alert-error ul { padding-left: 18px; margin-top: 4px; }

        /* ── Breakdown Panel ─────────────────── */
        .breakdown-card {
            background: linear-gradient(135deg, rgba(255,184,0,0.06) 0%, rgba(22,22,21,0.85) 100%);
            border: 1px solid rgba(255,184,0,0.2);
            border-radius: 16px;
            padding: 36px 36px;
            margin-top: 24px;
            box-shadow: 0 24px 60px rgba(0,0,0,0.4);
            display: none;
            animation: slideUp .4s ease-out;
        }
        @keyframes slideUp { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }
        .breakdown-card.visible { display: block; }

        .breakdown-card h2 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 32px; letter-spacing: 3px; margin-bottom: 6px;
        }
        .breakdown-card .subtitle {
            font-size: 13px; color: #A1A09A; margin-bottom: 28px;
        }

        .summary-section {
            padding: 18px 0;
            border-bottom: 1px dashed rgba(255,255,255,0.06);
        }
        .summary-row {
            display: flex; justify-content: space-between; align-items: center;
            font-size: 13px; padding: 4px 0;
        }
        .summary-row .label { color: #A1A09A; }
        .summary-row .value { color: #EDEDEC; font-weight: 500; }

        .pricing-section {
            padding: 18px 0;
            border-bottom: 1px dashed rgba(255,255,255,0.06);
        }
        .pricing-row {
            display: flex; justify-content: space-between; align-items: baseline;
            padding: 8px 0;
        }
        .pricing-row .label { color: #A1A09A; font-size: 13px; }
        .pricing-row .label small { color: #555; font-size: 11px; display: block; }
        .pricing-row .value { font-size: 16px; color: #EDEDEC; font-weight: 600; }

        .total-row {
            display: flex; justify-content: space-between; align-items: center;
            padding: 18px 0 6px;
        }
        .total-row .label {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 22px; letter-spacing: 2px; color: #EDEDEC;
        }
        .total-row .value {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 38px; letter-spacing: 1px; color: #FFB800;
        }

        .edit-link {
            display: inline-flex; align-items: center; gap: 4px;
            color: #A1A09A; font-size: 12px; margin-top: 10px;
            cursor: pointer;
        }
        .edit-link:hover { color: #FFB800; }

        .footer {
            text-align: center; padding: 32px 24px;
            color: #555; font-size: 12px;
        }

        @media (max-width: 600px) {
            .nav { padding: 14px 20px; }
            .container { padding: 100px 18px 40px; }
            .header h1 { font-size: 40px; }
            .quote-card, .breakdown-card { padding: 24px 20px; }
            .form-row { grid-template-columns: 1fr; }
            .btn-row { grid-template-columns: 1fr; }
            .total-row .value { font-size: 30px; }
        }
    </style>
</head>
<body>

<div class="bg-glow"></div>

<nav class="nav">
    <a href="/login" class="nav-brand">
        CARS NI BAI
        <span>Rental Management</span>
    </a>
    <a href="/login" class="back-link">
        <i class="bi bi-arrow-left"></i> Back to Home
    </a>
</nav>

<div class="container">
    <div class="header">
        <div class="eyebrow"><i class="bi bi-send"></i> Quote Request</div>
        <h1>GET A <span class="gold">QUOTE</span></h1>
        <p>Tell us about your trip and we'll show you the price instantly. No commitment until you book.</p>
    </div>

    <div class="quote-card" id="quote-form-card">
        @if($errors->any())
            <div class="alert-error">
                <strong>Please fix the following:</strong>
                <ul>
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('quotes.request.store') }}" id="quote-form">
            @csrf

            <!-- Contact Info -->
            <div class="form-section">
                <h3><i class="bi bi-person"></i> YOUR CONTACT INFO</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="guest_name">Full Name<span class="req">*</span></label>
                        <input type="text" id="guest_name" name="guest_name"
                               value="{{ old('guest_name') }}" required
                               placeholder="Juan Dela Cruz">
                    </div>
                    <div class="form-group">
                        <label for="guest_phone">Phone<span class="req">*</span></label>
                        <input type="text" id="guest_phone" name="guest_phone"
                               value="{{ old('guest_phone') }}" required
                               placeholder="0917-xxx-xxxx">
                    </div>
                </div>
                <div class="form-row single">
                    <div class="form-group">
                        <label for="guest_email">Email<span class="req">*</span></label>
                        <input type="email" id="guest_email" name="guest_email"
                               value="{{ old('guest_email') }}" required
                               placeholder="you@example.com">
                    </div>
                </div>
            </div>

            <!-- Vehicle & Type -->
            <div class="form-section">
                <h3><i class="bi bi-car-front"></i> VEHICLE &amp; TYPE</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="car_id">Vehicle<span class="req">*</span></label>
                        <select id="car_id" name="car_id" required>
                            <option value="">— Select a vehicle —</option>
                            @foreach($cars as $car)
                                <option value="{{ $car->id }}"
                                        data-rate="{{ $car->daily_rate }}"
                                        data-name="{{ $car->brand }} {{ $car->model }}"
                                        {{ old('car_id') == $car->id ? 'selected' : '' }}>
                                    {{ $car->brand }} {{ $car->model }} ({{ ucfirst($car->vehicle_type) }})
                                    — ₱{{ number_format($car->daily_rate, 0) }}/day
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="rental_type">Rental Type<span class="req">*</span></label>
                        <select id="rental_type" name="rental_type" required>
                            <option value="">— Select —</option>
                            <option value="self_drive" {{ old('rental_type') === 'self_drive' ? 'selected' : '' }}>Self-drive</option>
                            <option value="with_driver" {{ old('rental_type') === 'with_driver' ? 'selected' : '' }}>With Driver</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Dates & Distance -->
            <div class="form-section">
                <h3><i class="bi bi-calendar-event"></i> DATES &amp; DESTINATION</h3>

                <div class="form-row">
                    <div class="form-group">
                        <label for="start_date">Start Date<span class="req">*</span></label>
                        <input type="date" id="start_date" name="start_date"
                               value="{{ old('start_date') }}" required
                               min="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date<span class="req">*</span></label>
                        <input type="date" id="end_date" name="end_date"
                               value="{{ old('end_date') }}" required
                               min="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <!-- Davao City toggle -->
                <label class="davao-toggle">
                    <input type="checkbox" id="within_davao" {{ old('within_davao') ? 'checked' : '' }}>
                    <div>
                        <div class="label-main"><i class="bi bi-geo-alt-fill"></i> Within Davao City</div>
                        <div class="label-sub">Check this if your destination is inside Davao City — no distance surcharge applies.</div>
                    </div>
                </label>

                <div class="form-row">
                    <div class="form-group">
                        <label for="destination">Destination</label>
                        <input type="text" id="destination" name="destination"
                               value="{{ old('destination') }}"
                               placeholder="e.g. Mati City, Tagum, General Santos">
                        <div class="helper" id="dest-helper" style="display:none;"></div>
                    </div>
                    <div class="form-group">
                        <label for="distance_km">Estimated Distance (km)</label>
                        <input type="number" id="distance_km" name="distance_km"
                               value="{{ old('distance_km', 0) }}" min="0"
                               placeholder="0" readonly>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="form-section">
                <h3><i class="bi bi-chat-left-text"></i> ADDITIONAL NOTES</h3>
                <div class="form-row single">
                    <div class="form-group">
                        <label for="guest_notes">Notes (optional)</label>
                        <textarea id="guest_notes" name="guest_notes" rows="3"
                                  placeholder="Anything else we should know?">{{ old('guest_notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Calculate Quote button (non-submitting) -->
            <button type="button" class="btn-submit" id="btn-calculate">
                <i class="bi bi-calculator"></i> Get Quote
            </button>
        </form>
    </div>

    <!-- Quote Breakdown Card -->
    <div class="breakdown-card" id="breakdown-card">
        <h2>YOUR <span style="color:#FFB800;">QUOTE</span></h2>
        <p class="subtitle">Review the details below. You can book now or come back later.</p>

        <div class="summary-section">
            <div class="summary-row"><span class="label">Customer</span><span class="value" id="sum-name">—</span></div>
            <div class="summary-row"><span class="label">Vehicle</span><span class="value" id="sum-vehicle">—</span></div>
            <div class="summary-row"><span class="label">Rental Type</span><span class="value" id="sum-type">—</span></div>
            <div class="summary-row"><span class="label">Period</span><span class="value" id="sum-period">—</span></div>
            <div class="summary-row"><span class="label">Destination</span><span class="value" id="sum-destination">—</span></div>
        </div>

        <div class="pricing-section">
            <div class="pricing-row">
                <div class="label">
                    Base Cost
                    <small id="base-detail">— rate × — days</small>
                </div>
                <div class="value" id="base-cost">₱0.00</div>
            </div>
            <div class="pricing-row">
                <div class="label">
                    Distance Surcharge
                    <small id="surcharge-detail">₱100 per 20 km outside Davao City</small>
                </div>
                <div class="value" id="surcharge-cost">₱0.00</div>
            </div>
        </div>

        <div class="total-row">
            <span class="label">TOTAL</span>
            <span class="value" id="total-cost">₱0.00</span>
        </div>

        <div class="btn-row">
            <button type="button" class="btn-primary" id="btn-book">
                <i class="bi bi-check-circle"></i> Book this Quote
            </button>
            <a href="/login" class="btn-outline">
                <i class="bi bi-x-circle"></i> Maybe Later
            </a>
        </div>

        <div style="text-align:center;">
            <span class="edit-link" id="btn-edit"><i class="bi bi-pencil"></i> Edit Details</span>
        </div>
    </div>
</div>

<footer class="footer">
    &copy; {{ date('Y') }} Cars ni Bai — Rental Management System
</footer>

<script>
// ── Davao Distance Reference Table (driving km from Davao City) ────────────
const DAVAO_DISTANCES = {
    // Davao del Norte
    'tagum': 55, 'panabo': 31, 'carmen': 38, 'braulio dujali': 40, 'asuncion': 70,
    'kapalong': 80, 'san isidro': 85, 'talaingod': 95, 'new corella': 75, 'santo tomas': 45,
    // Davao del Sur
    'digos': 46, 'santa cruz': 42, 'hagonoy': 60, 'bansalan': 65, 'magsaysay': 70,
    'matanao': 80, 'padada': 55, 'sulop': 75, 'kiblawan': 85, 'malalag': 90,
    // Davao Oriental
    'mati': 84, 'lupon': 95, 'baganga': 145, 'caraga': 160, 'boston': 170,
    'cateel': 185, 'manay': 120, 'tarragona': 130, 'governor generoso': 110,
    // Davao de Oro
    'nabunturan': 90, 'compostela': 75, 'maco': 48, 'mabini': 44, 'pantukan': 55,
    'new bataan': 80, 'monkayo': 100, 'montevista': 110, 'laak': 130, 'maragusan': 120,
    // Davao Occidental
    'malita': 145, 'don marcelino': 180, 'jose abad santos': 220, 'sarangani': 195,
    'santa maria': 160,
    // Samal
    'samal': 26, 'samal island': 26, 'igacos': 26,
    // North Cotabato
    'kidapawan': 45, 'makilala': 85, 'kabacan': 110, 'magpet': 100, 'mlang': 105,
    'matalam': 115, 'arakan': 120, 'antipas': 110, 'president roxas': 125,
    'pigkawayan': 140, 'pikit': 155, 'midsayap': 145, 'alamada': 135, 'libungan': 150,
    'banisilan': 160, 'aleosan': 140,
    // South Cotabato
    'koronadal': 100, 'general santos': 146, 'gensan': 146, 'gen santos': 146,
    'surallah': 120, 'tupi': 130, 'polomolok': 140, 'norala': 115, 'banga': 125,
    'lake sebu': 145, 'tampakan': 140, 'tantangan': 120, 'tboli': 155, "t'boli": 155,
    // Sultan Kudarat
    'tacurong': 130, 'isulan': 140, 'kalamansig': 195, 'lebak': 180, 'bagumbayan': 160,
    'columbio': 165, 'esperanza': 150, 'lambayong': 155, 'lutayan': 170, 'palimbang': 190,
    'president quirino': 145, 'sen ninoy aquino': 175, 'senator ninoy aquino': 175,
    // Sarangani
    'alabel': 135, 'malungon': 90, 'glan': 175, 'kiamba': 160, 'malapatan': 165,
    'maasim': 155, 'maitum': 150,
    // BARMM / Cotabato
    'cotabato': 136, 'kakar': 145, 'datu odin sinsuat': 150, 'sultan kudarat': 155,
    'buluan': 160, 'upi': 180,
    // CARAGA - Agusan del Norte
    'butuan': 201, 'cabadbaran': 215, 'nasipit': 210, 'las nieves': 220,
    'magallanes': 225, 'remedios t romualdez': 230, 'tubay': 220,
    // Agusan del Sur
    'prosperidad': 230, 'bayugan': 240, 'bunawan': 255, 'loreto': 270, 'trento': 220,
    'veruela': 260, 'la paz': 245, 'san francisco': 250, 'sibagat': 265,
    // Surigao del Norte
    'surigao': 320, 'dapa': 345, 'siargao': 345, 'mainit': 295, 'malimono': 310,
    'placer': 305, 'tubod': 300,
    // Surigao del Sur
    'tandag': 270, 'bislig': 230, 'cantilan': 280, 'carrascal': 265, 'cortes': 255,
    'hinatuan': 240, 'lianga': 245, 'lingig': 250,
    // Bukidnon
    'malaybalay': 170, 'valencia': 185, 'manolo fortich': 195, 'maramag': 180,
    'kibawe': 175, 'kalilangan': 185, 'lantapan': 180, 'impasugong': 195,
    'san fernando': 190, 'cabanglasan': 200, 'libona': 205, 'talakag': 210,
    // Misamis Oriental
    'cagayan de oro': 175, 'cdo': 175, 'el salvador': 185, 'gingoog': 230,
    'tagoloan': 180, 'opol': 180, 'villanueva': 182, 'jasaan': 185, 'initao': 195,
    // Misamis Occidental
    'ozamiz': 260, 'oroquieta': 290, 'tangub': 275, 'calamba': 280, 'jimenez': 285,
    // Lanao
    'iligan': 171, 'bacolod': 190, 'kapatagan': 195, 'marawi': 163,
    // Camiguin
    'mambajao': 240, 'camiguin': 240,
    // Zamboanga Peninsula
    'pagadian': 238, 'dipolog': 330, 'dapitan': 340, 'zamboanga': 390,
    'ipil': 370, 'molave': 265, 'dumingag': 280, 'mahayag': 270, 'labangan': 255,
};

function lookupDistance(input) {
    if (!input) return null;
    let key = input.toLowerCase().trim()
        .replace(/\s+city$/, '')
        .replace(/\s+/g, ' ')
        .replace(/[.,]/g, '');
    return DAVAO_DISTANCES[key] ?? null;
}

// ── DOM elements ───────────────────────────────────────────────────────────
const $davao = document.getElementById('within_davao');
const $destination = document.getElementById('destination');
const $distance = document.getElementById('distance_km');
const $destHelper = document.getElementById('dest-helper');
const $btnCalc = document.getElementById('btn-calculate');
const $btnEdit = document.getElementById('btn-edit');
const $btnBook = document.getElementById('btn-book');
const $form = document.getElementById('quote-form');
const $formCard = document.getElementById('quote-form-card');
const $breakdown = document.getElementById('breakdown-card');

// ── Davao toggle ───────────────────────────────────────────────────────────
function applyDavaoToggle() {
    if ($davao.checked) {
        $destination.value = 'Within Davao City';
        $destination.disabled = true;
        $distance.value = 0;
        $distance.readOnly = true;
        $destHelper.style.display = 'flex';
        $destHelper.className = 'helper success';
        $destHelper.innerHTML = '<i class="bi bi-check-circle"></i> No distance surcharge';
    } else {
        if ($destination.value === 'Within Davao City') $destination.value = '';
        $destination.disabled = false;
        $distance.value = 0;
        $distance.readOnly = true;
        $destHelper.style.display = 'none';
    }
}
$davao.addEventListener('change', applyDavaoToggle);

// ── Destination → distance auto-fill ───────────────────────────────────────
$destination.addEventListener('input', () => {
    if ($davao.checked) return;
    const km = lookupDistance($destination.value);
    if (km !== null) {
        $distance.value = km;
        $distance.readOnly = true;
        $destHelper.style.display = 'flex';
        $destHelper.className = 'helper success';
        $destHelper.innerHTML = `<i class="bi bi-check-circle"></i> Distance auto-filled: ${km} km from Davao City`;
    } else {
        $distance.value = 0;
        $distance.readOnly = false;
        $destHelper.style.display = 'flex';
        $destHelper.className = 'helper warn';
        $destHelper.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Destination not in our reference table — please type the distance manually';
    }
});

// ── Calculate Quote ────────────────────────────────────────────────────────
function pesos(n) {
    return '₱' + Number(n).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

$btnCalc.addEventListener('click', () => {
    // Validate required HTML5 fields
    if (!$form.checkValidity()) {
        $form.reportValidity();
        return;
    }

    const carSelect = document.getElementById('car_id');
    const carOpt = carSelect.options[carSelect.selectedIndex];
    const rate = parseFloat(carOpt.dataset.rate || 0);
    const carName = carOpt.dataset.name || '—';

    const start = new Date(document.getElementById('start_date').value);
    const end = new Date(document.getElementById('end_date').value);
    const days = Math.max(1, Math.round((end - start) / 86400000));

    const km = parseInt($distance.value || 0, 10);
    const surcharge = Math.floor(km / 20) * 100;
    const base = rate * days;
    const total = base + surcharge;

    const rentalType = document.getElementById('rental_type').value === 'self_drive' ? 'Self-drive' : 'With Driver';
    const startStr = start.toLocaleDateString('en-PH', { month:'short', day:'numeric', year:'numeric' });
    const endStr = end.toLocaleDateString('en-PH', { month:'short', day:'numeric', year:'numeric' });
    const dest = $davao.checked ? 'Within Davao City' :
        ($destination.value ? `${$destination.value} (${km} km)` : '—');

    document.getElementById('sum-name').textContent = document.getElementById('guest_name').value;
    document.getElementById('sum-vehicle').textContent = carName;
    document.getElementById('sum-type').textContent = rentalType;
    document.getElementById('sum-period').textContent = `${startStr} – ${endStr} (${days} day${days===1?'':'s'})`;
    document.getElementById('sum-destination').textContent = dest;

    document.getElementById('base-detail').textContent = `${pesos(rate)} × ${days} day${days===1?'':'s'}`;
    document.getElementById('base-cost').textContent = pesos(base);
    document.getElementById('surcharge-cost').textContent = pesos(surcharge);
    document.getElementById('total-cost').textContent = pesos(total);

    $breakdown.classList.add('visible');
    $breakdown.scrollIntoView({ behavior: 'smooth', block: 'start' });
});

// ── Edit & Book ────────────────────────────────────────────────────────────
$btnEdit.addEventListener('click', () => {
    $breakdown.classList.remove('visible');
    $formCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
});

$btnBook.addEventListener('click', () => {
    // If destination was disabled (Within Davao City), re-enable so it submits
    if ($destination.disabled) $destination.disabled = false;
    $form.submit();
});

// Initialize state on load
applyDavaoToggle();
</script>

</body>
</html>
