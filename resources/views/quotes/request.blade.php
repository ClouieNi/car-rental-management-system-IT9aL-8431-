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
        .form-error {
            font-size: 12px; color: #F87171; margin-top: 6px;
            display: flex; align-items: center; gap: 4px;
        }

        .btn-submit {
            width: 100%; padding: 14px;
            background: #FFB800; color: #0A0A0A;
            border: none; border-radius: 8px;
            font-size: 14px; font-weight: 600; letter-spacing: .5px;
            font-family: inherit; cursor: pointer;
            transition: all .2s; margin-top: 24px;
            display: flex; align-items: center; justify-content: center; gap: 8px;
        }
        .btn-submit:hover {
            background: #FFD04A;
            box-shadow: 0 8px 24px rgba(255,184,0,0.25);
        }

        .alert-error {
            background: rgba(248,113,113,0.1);
            border: 1px solid rgba(248,113,113,0.2);
            color: #F87171;
            padding: 12px 14px; border-radius: 8px;
            font-size: 13px; margin-bottom: 18px;
        }
        .alert-error ul { padding-left: 18px; margin-top: 4px; }

        .footer {
            text-align: center; padding: 32px 24px;
            color: #555; font-size: 12px;
        }

        @media (max-width: 600px) {
            .nav { padding: 14px 20px; }
            .container { padding: 100px 18px 40px; }
            .header h1 { font-size: 40px; }
            .quote-card { padding: 24px 20px; }
            .form-row { grid-template-columns: 1fr; }
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
        <p>Tell us about your trip and we'll send you a personalized quote within 24 hours.</p>
    </div>

    <div class="quote-card">
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

        <form method="POST" action="{{ route('quotes.request.store') }}">
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
                                <option value="{{ $car->id }}" {{ old('car_id') == $car->id ? 'selected' : '' }}>
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
                <div class="form-row">
                    <div class="form-group">
                        <label for="destination">Destination</label>
                        <input type="text" id="destination" name="destination"
                               value="{{ old('destination') }}"
                               placeholder="Where are you going?">
                    </div>
                    <div class="form-group">
                        <label for="distance_km">Estimated Distance (km)</label>
                        <input type="number" id="distance_km" name="distance_km"
                               value="{{ old('distance_km') }}" min="0"
                               placeholder="0">
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

            <button type="submit" class="btn-submit">
                <i class="bi bi-send"></i> Submit Quote Request
            </button>
        </form>
    </div>
</div>

<footer class="footer">
    &copy; {{ date('Y') }} Cars ni Bai — Rental Management System
</footer>

</body>
</html>
