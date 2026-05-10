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
        /* Custom file input */
        .file-upload-wrapper { position: relative; }
        .file-upload-wrapper input[type="file"] {
            position: absolute; inset: 0; width: 100%; height: 100%;
            opacity: 0; cursor: pointer; z-index: 2;
        }
        .file-upload-label {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 16px;
            background: rgba(10,10,10,0.6);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 8px;
            cursor: pointer;
            transition: all .2s;
        }
        .file-upload-label:hover, .file-upload-wrapper:focus-within .file-upload-label {
            border-color: #FFB800;
            background: rgba(10,10,10,0.9);
            box-shadow: 0 0 0 3px rgba(255,184,0,0.1);
        }
        .file-upload-btn {
            display: inline-flex; align-items: center; gap-6px; gap: 6px;
            padding: 6px 14px;
            background: #FFB800; color: #0A0A0A;
            border-radius: 6px; font-size: 12px; font-weight: 700;
            white-space: nowrap; flex-shrink: 0;
        }
        .file-upload-name {
            font-size: 13px; color: #A1A09A;
            overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
        }

        /* Date input styling - make calendar icon visible */
        .form-group input[type="date"] {
            position: relative;
            padding-right: 40px;
        }
        .form-group input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(0.5) sepia(1) saturate(5) hue-rotate(350deg);
            opacity: 0.8;
            cursor: pointer;
        }
        .form-group input[type="date"]::-webkit-calendar-picker-indicator:hover {
            opacity: 1;
            filter: invert(0.3) sepia(1) saturate(8) hue-rotate(350deg);
        }
        /* Custom calendar icon overlay */
        .date-input-wrap {
            position: relative;
        }
        .date-input-wrap .calendar-icon {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #FFB800;
            font-size: 16px;
            pointer-events: none;
            z-index: 2;
        }
        .form-group .helper {
            font-size: 11px; margin-top: 6px;
            display: flex; align-items: center; gap: 4px;
        }
        .helper.success { color: #4ADE80; }
        .helper.warn { color: #F59E0B; }

        /* ── Destination Combobox ────────────── */
        .combobox { position: relative; }
        .combobox input {
            padding-right: 42px !important;
        }
        .combo-toggle {
            position: absolute; top: 0; right: 0;
            height: 44px; width: 40px;
            background: transparent; border: none;
            color: #A1A09A; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px;
            transition: color .2s, transform .2s;
        }
        .combo-toggle:hover { color: #FFB800; }
        .combo-toggle.open i { transform: rotate(180deg); }
        .combo-toggle:disabled { cursor: not-allowed; opacity: 0.4; }

        .combo-panel {
            position: absolute; top: calc(100% + 4px); left: 0; right: 0;
            z-index: 50;
            background: #161615;
            border: 1px solid rgba(255,184,0,0.2);
            border-radius: 8px;
            max-height: 280px; overflow-y: auto;
            box-shadow: 0 12px 32px rgba(0,0,0,0.5);
            display: none;
            animation: comboFade .15s ease-out;
        }
        @keyframes comboFade { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: translateY(0); } }
        .combo-panel.visible { display: block; }
        .combo-panel::-webkit-scrollbar { width: 8px; }
        .combo-panel::-webkit-scrollbar-track { background: #0A0A0A; }
        .combo-panel::-webkit-scrollbar-thumb { background: rgba(255,184,0,0.2); border-radius: 4px; }
        .combo-panel::-webkit-scrollbar-thumb:hover { background: rgba(255,184,0,0.4); }

        .combo-section {
            font-size: 10px; font-weight: 600; letter-spacing: 1.5px;
            text-transform: uppercase; color: #FFB800;
            padding: 8px 14px 4px;
            background: rgba(255,184,0,0.04);
            border-bottom: 1px solid rgba(255,184,0,0.08);
        }
        .combo-option {
            display: flex; justify-content: space-between; align-items: center;
            padding: 10px 14px;
            font-size: 13px; color: #EDEDEC;
            cursor: pointer;
            border-left: 3px solid transparent;
            transition: background .12s, border-color .12s;
        }
        .combo-option:hover, .combo-option.active {
            background: rgba(255,184,0,0.1);
            border-left-color: #FFB800;
        }
        .combo-option .km {
            font-size: 11px; color: #777; font-weight: 500;
        }
        .combo-option.active .km { color: #FFB800; }
        .combo-empty {
            padding: 16px 14px; text-align: center;
            color: #777; font-size: 12px; font-style: italic;
        }

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

        <form method="POST" action="{{ route('quotes.request.store') }}" id="quote-form" enctype="multipart/form-data">
            @csrf

            <!-- Contact Info -->
            <div class="form-section">
                <h3><i class="bi bi-person"></i> YOUR CONTACT INFO</h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="guest_name">Full Name<span class="req">*</span></label>
                        <input type="text" id="guest_name" name="guest_name"
                               value="{{ old('guest_name', request('guest_name')) }}" required
                               placeholder="Juan Dela Cruz">
                    </div>
                    <div class="form-group">
                        <label for="guest_phone">Phone<span class="req">*</span></label>
                        <input type="text" id="guest_phone" name="guest_phone"
                               value="{{ old('guest_phone', request('guest_phone')) }}" required
                               placeholder="0917-xxx-xxxx">
                    </div>
                </div>
                <div class="form-row single">
                    <div class="form-group">
                        <label for="guest_email">Email<span class="req">*</span></label>
                        <input type="email" id="guest_email" name="guest_email"
                               value="{{ old('guest_email', request('guest_email')) }}" required
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
                        <div class="date-input-wrap">
                            <input type="date" id="start_date" name="start_date"
                                   value="{{ old('start_date') }}" required
                                   min="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date<span class="req">*</span></label>
                        <div class="date-input-wrap">
                            <input type="date" id="end_date" name="end_date"
                                   value="{{ old('end_date') }}" required
                                   min="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                </div>

                <!-- Davao City toggle -->
                <label class="davao-toggle">
                    <input type="checkbox" id="within_davao" {{ old('within_davao') || request('within_davao') ? 'checked' : '' }}>
                    <div>
                        <div class="label-main"><i class="bi bi-geo-alt-fill"></i> Within Davao City</div>
                        <div class="label-sub">Check this if your destination is inside Davao City — no distance surcharge applies.</div>
                    </div>
                </label>

                <div class="form-row">
                    <div class="form-group">
                        <label for="destination">Destination</label>
                        <div class="combobox" id="destination-combo">
                            <input type="text" id="destination" name="destination"
                                   value="{{ old('destination', request('destination')) }}"
                                   autocomplete="off"
                                   placeholder="Type or click ↓ to browse...">
                            <button type="button" class="combo-toggle" id="combo-toggle" tabindex="-1">
                                <i class="bi bi-chevron-down"></i>
                            </button>
                            <div class="combo-panel" id="combo-panel"></div>
                        </div>
                        <div class="helper" id="dest-helper" style="display:none;"></div>
                    </div>
                    <div class="form-group">
                        <label for="distance_km">Estimated Distance (km)</label>
                        <input type="number" id="distance_km" name="distance_km"
                               value="{{ old('distance_km', request('distance_km', 0)) }}" min="0"
                               placeholder="0" readonly>
                    </div>
                </div>
            </div>

            <!-- License ID -->
            <div class="form-section">
                <h3><i class="bi bi-card-image"></i> DRIVER'S LICENSE</h3>
                <div class="form-row single">
                    <div class="form-group">
                        <label for="license_file">Upload Driver's License / Valid ID<span class="req">*</span></label>
                        <div class="file-upload-wrapper">
                            <input type="file" id="license_file" name="license_file"
                                   accept="image/jpeg,image/png,application/pdf" required>
                            <div class="file-upload-label">
                                <span class="file-upload-btn"><i class="bi bi-upload"></i> Choose File</span>
                                <span class="file-upload-name" id="license-file-name">No file chosen</span>
                            </div>
                        </div>
                        <span style="font-size:11px; color:#A1A09A; margin-top:6px; display:block;"><i class="bi bi-info-circle"></i> Accepted: JPG, PNG, PDF — Max 5MB</span>
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
// Structured by region for the dropdown grouping
const DESTINATION_GROUPS = [
    { region: 'Davao del Norte', items: [
        ['Tagum City', 'tagum', 55], ['Panabo City', 'panabo', 31], ['Carmen', 'carmen', 38],
        ['Braulio Dujali', 'braulio dujali', 40], ['Asuncion', 'asuncion', 70],
        ['Kapalong', 'kapalong', 80], ['San Isidro', 'san isidro', 85],
        ['Talaingod', 'talaingod', 95], ['New Corella', 'new corella', 75],
        ['Santo Tomas', 'santo tomas', 45],
    ]},
    { region: 'Davao del Sur', items: [
        ['Digos City', 'digos', 46], ['Santa Cruz', 'santa cruz', 42],
        ['Hagonoy', 'hagonoy', 60], ['Bansalan', 'bansalan', 65],
        ['Magsaysay', 'magsaysay', 70], ['Matanao', 'matanao', 80],
        ['Padada', 'padada', 55], ['Sulop', 'sulop', 75],
        ['Kiblawan', 'kiblawan', 85], ['Malalag', 'malalag', 90],
    ]},
    { region: 'Davao Oriental', items: [
        ['Mati City', 'mati', 84], ['Lupon', 'lupon', 95],
        ['Baganga', 'baganga', 145], ['Caraga', 'caraga', 160],
        ['Boston', 'boston', 170], ['Cateel', 'cateel', 185],
        ['Manay', 'manay', 120], ['Tarragona', 'tarragona', 130],
        ['Governor Generoso', 'governor generoso', 110],
    ]},
    { region: 'Davao de Oro', items: [
        ['Nabunturan', 'nabunturan', 90], ['Compostela', 'compostela', 75],
        ['Maco', 'maco', 48], ['Mabini', 'mabini', 44],
        ['Pantukan', 'pantukan', 55], ['New Bataan', 'new bataan', 80],
        ['Monkayo', 'monkayo', 100], ['Montevista', 'montevista', 110],
        ['Laak', 'laak', 130], ['Maragusan', 'maragusan', 120],
    ]},
    { region: 'Davao Occidental', items: [
        ['Malita', 'malita', 145], ['Don Marcelino', 'don marcelino', 180],
        ['Jose Abad Santos', 'jose abad santos', 220],
        ['Sarangani (Davao Occ)', 'sarangani', 195],
        ['Santa Maria', 'santa maria', 160],
    ]},
    { region: 'Island Garden City of Samal', items: [
        ['Samal Island', 'samal', 26],
    ]},
    { region: 'North Cotabato', items: [
        ['Kidapawan City', 'kidapawan', 45], ['Makilala', 'makilala', 85],
        ['Kabacan', 'kabacan', 110], ['Magpet', 'magpet', 100],
        ['Mlang', 'mlang', 105], ['Matalam', 'matalam', 115],
        ['Arakan', 'arakan', 120], ['Antipas', 'antipas', 110],
        ['President Roxas', 'president roxas', 125],
        ['Pigkawayan', 'pigkawayan', 140], ['Pikit', 'pikit', 155],
        ['Midsayap', 'midsayap', 145], ['Alamada', 'alamada', 135],
        ['Libungan', 'libungan', 150], ['Banisilan', 'banisilan', 160],
        ['Aleosan', 'aleosan', 140],
    ]},
    { region: 'South Cotabato', items: [
        ['Koronadal City', 'koronadal', 100],
        ['General Santos City', 'general santos', 146],
        ['Surallah', 'surallah', 120], ['Tupi', 'tupi', 130],
        ['Polomolok', 'polomolok', 140], ['Norala', 'norala', 115],
        ['Banga', 'banga', 125], ['Lake Sebu', 'lake sebu', 145],
        ['Tampakan', 'tampakan', 140], ['Tantangan', 'tantangan', 120],
        ["T'boli", 'tboli', 155],
    ]},
    { region: 'Sultan Kudarat', items: [
        ['Tacurong City', 'tacurong', 130], ['Isulan', 'isulan', 140],
        ['Kalamansig', 'kalamansig', 195], ['Lebak', 'lebak', 180],
        ['Bagumbayan', 'bagumbayan', 160], ['Columbio', 'columbio', 165],
        ['Esperanza', 'esperanza', 150], ['Lambayong', 'lambayong', 155],
        ['Lutayan', 'lutayan', 170], ['Palimbang', 'palimbang', 190],
        ['President Quirino', 'president quirino', 145],
        ['Sen. Ninoy Aquino', 'sen ninoy aquino', 175],
    ]},
    { region: 'Sarangani', items: [
        ['Alabel', 'alabel', 135], ['Malungon', 'malungon', 90],
        ['Glan', 'glan', 175], ['Kiamba', 'kiamba', 160],
        ['Malapatan', 'malapatan', 165], ['Maasim', 'maasim', 155],
        ['Maitum', 'maitum', 150],
    ]},
    { region: 'BARMM / Cotabato', items: [
        ['Cotabato City', 'cotabato', 136], ['Kakar', 'kakar', 145],
        ['Datu Odin Sinsuat', 'datu odin sinsuat', 150],
        ['Sultan Kudarat (Maguindanao)', 'sultan kudarat', 155],
        ['Buluan', 'buluan', 160], ['Upi', 'upi', 180],
    ]},
    { region: 'Agusan del Norte', items: [
        ['Butuan City', 'butuan', 201], ['Cabadbaran City', 'cabadbaran', 215],
        ['Nasipit', 'nasipit', 210], ['Las Nieves', 'las nieves', 220],
        ['Magallanes', 'magallanes', 225], ['Tubay', 'tubay', 220],
    ]},
    { region: 'Agusan del Sur', items: [
        ['Prosperidad', 'prosperidad', 230], ['Bayugan City', 'bayugan', 240],
        ['Bunawan', 'bunawan', 255], ['Loreto', 'loreto', 270],
        ['Trento', 'trento', 220], ['Veruela', 'veruela', 260],
        ['La Paz', 'la paz', 245], ['San Francisco', 'san francisco', 250],
        ['Sibagat', 'sibagat', 265],
    ]},
    { region: 'Surigao del Norte', items: [
        ['Surigao City', 'surigao', 320], ['Dapa (Siargao)', 'dapa', 345],
        ['Mainit', 'mainit', 295], ['Malimono', 'malimono', 310],
        ['Placer', 'placer', 305], ['Tubod', 'tubod', 300],
    ]},
    { region: 'Surigao del Sur', items: [
        ['Tandag City', 'tandag', 270], ['Bislig City', 'bislig', 230],
        ['Cantilan', 'cantilan', 280], ['Carrascal', 'carrascal', 265],
        ['Cortes', 'cortes', 255], ['Hinatuan', 'hinatuan', 240],
        ['Lianga', 'lianga', 245], ['Lingig', 'lingig', 250],
    ]},
    { region: 'Bukidnon', items: [
        ['Malaybalay City', 'malaybalay', 170], ['Valencia City', 'valencia', 185],
        ['Manolo Fortich', 'manolo fortich', 195], ['Maramag', 'maramag', 180],
        ['Kibawe', 'kibawe', 175], ['Kalilangan', 'kalilangan', 185],
        ['Lantapan', 'lantapan', 180], ['Impasugong', 'impasugong', 195],
        ['San Fernando', 'san fernando', 190], ['Cabanglasan', 'cabanglasan', 200],
        ['Libona', 'libona', 205], ['Talakag', 'talakag', 210],
    ]},
    { region: 'Misamis Oriental', items: [
        ['Cagayan de Oro City', 'cagayan de oro', 175],
        ['El Salvador City', 'el salvador', 185], ['Gingoog City', 'gingoog', 230],
        ['Tagoloan', 'tagoloan', 180], ['Opol', 'opol', 180],
        ['Villanueva', 'villanueva', 182], ['Jasaan', 'jasaan', 185],
        ['Initao', 'initao', 195],
    ]},
    { region: 'Misamis Occidental', items: [
        ['Ozamiz City', 'ozamiz', 260], ['Oroquieta City', 'oroquieta', 290],
        ['Tangub City', 'tangub', 275], ['Calamba', 'calamba', 280],
        ['Jimenez', 'jimenez', 285],
    ]},
    { region: 'Lanao', items: [
        ['Iligan City', 'iligan', 171], ['Marawi City', 'marawi', 163],
        ['Bacolod (Lanao)', 'bacolod', 190], ['Kapatagan', 'kapatagan', 195],
    ]},
    { region: 'Camiguin', items: [
        ['Mambajao (Camiguin)', 'mambajao', 240],
    ]},
    { region: 'Zamboanga Peninsula', items: [
        ['Pagadian City', 'pagadian', 238], ['Dipolog City', 'dipolog', 330],
        ['Dapitan City', 'dapitan', 340], ['Zamboanga City', 'zamboanga', 390],
        ['Ipil', 'ipil', 370], ['Molave', 'molave', 265],
        ['Dumingag', 'dumingag', 280], ['Mahayag', 'mahayag', 270],
        ['Labangan', 'labangan', 255],
    ]},
];

// Flat list for typeahead (with synonyms)
const DESTINATIONS = [];
DESTINATION_GROUPS.forEach(g => {
    g.items.forEach(([name, key, km]) => {
        DESTINATIONS.push({ name, key, km, region: g.region });
    });
});
// Synonyms
const SYNONYMS = {
    'gensan': 'general santos', 'gen santos': 'general santos',
    'cdo': 'cagayan de oro', 'samal island': 'samal', 'igacos': 'samal',
    'siargao': 'dapa',
};

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

function normalizeKey(input) {
    return input.toLowerCase().trim()
        .replace(/\s+city$/, '')
        .replace(/\s+/g, ' ')
        .replace(/[.,']/g, '');
}

function lookupDistance(input) {
    if (!input) return null;
    let key = normalizeKey(input);
    if (SYNONYMS[key]) key = SYNONYMS[key];
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
    const toggleBtn = document.getElementById('combo-toggle');
    if ($davao.checked) {
        $destination.value = 'Within Davao City';
        $destination.disabled = true;
        if (toggleBtn) toggleBtn.disabled = true;
        $distance.value = 0;
        $distance.readOnly = true;
        $destHelper.style.display = 'flex';
        $destHelper.className = 'helper success';
        $destHelper.innerHTML = '<i class="bi bi-check-circle"></i> No distance surcharge';
        if (typeof closePanel === 'function') closePanel();
    } else {
        if ($destination.value === 'Within Davao City') $destination.value = '';
        $destination.disabled = false;
        if (toggleBtn) toggleBtn.disabled = false;
        $distance.value = 0;
        $distance.readOnly = true;
        $destHelper.style.display = 'none';
    }
}
$davao.addEventListener('change', applyDavaoToggle);

// ── Destination Combobox (autocomplete + dropdown) ────────────────────
const $comboToggle = document.getElementById('combo-toggle');
const $comboPanel = document.getElementById('combo-panel');
let activeIndex = -1;
let currentMatches = [];

function filterDestinations(query) {
    if (!query || !query.trim()) {
        // Return all, grouped by region
        return { grouped: true, groups: DESTINATION_GROUPS };
    }
    const q = normalizeKey(query);
    const prefix = [];
    const substring = [];
    DESTINATIONS.forEach(d => {
        const k = d.key;
        const n = d.name.toLowerCase();
        if (k.startsWith(q) || n.startsWith(q)) prefix.push(d);
        else if (k.includes(q) || n.includes(q)) substring.push(d);
    });
    return { grouped: false, items: [...prefix, ...substring] };
}

function renderPanel(query) {
    const result = filterDestinations(query);
    let html = '';
    if (result.grouped) {
        result.groups.forEach(g => {
            html += `<div class="combo-section">${g.region}</div>`;
            g.items.forEach(([name, key, km]) => {
                html += `<div class="combo-option" data-name="${name}" data-km="${km}">`
                     + `<span>${name}</span><span class="km">${km} km</span></div>`;
            });
        });
    } else if (result.items.length === 0) {
        html = '<div class="combo-empty">No matches. Type the destination manually.</div>';
    } else {
        result.items.forEach(d => {
            html += `<div class="combo-option" data-name="${d.name}" data-km="${d.km}">`
                 + `<span>${d.name} <small style="color:#555;">(${d.region})</small></span>`
                 + `<span class="km">${d.km} km</span></div>`;
        });
    }
    $comboPanel.innerHTML = html;
    activeIndex = -1;
    currentMatches = [...$comboPanel.querySelectorAll('.combo-option')];
}

function openPanel() {
    if ($davao.checked) return;
    renderPanel($destination.value);
    $comboPanel.classList.add('visible');
    $comboToggle.classList.add('open');
}
function closePanel() {
    $comboPanel.classList.remove('visible');
    $comboToggle.classList.remove('open');
    activeIndex = -1;
}
function setActive(i) {
    currentMatches.forEach(el => el.classList.remove('active'));
    if (i >= 0 && i < currentMatches.length) {
        activeIndex = i;
        currentMatches[i].classList.add('active');
        currentMatches[i].scrollIntoView({ block: 'nearest' });
    }
}
function selectOption(el) {
    const name = el.dataset.name;
    const km = parseInt(el.dataset.km, 10);
    $destination.value = name;
    $distance.value = km;
    $distance.readOnly = true;
    $destHelper.style.display = 'flex';
    $destHelper.className = 'helper success';
    $destHelper.innerHTML = `<i class="bi bi-check-circle"></i> ${km} km from Davao City`;
    closePanel();
}

$destination.addEventListener('focus', openPanel);
$destination.addEventListener('click', openPanel);
$comboToggle.addEventListener('click', () => {
    if ($comboPanel.classList.contains('visible')) closePanel();
    else { $destination.focus(); openPanel(); }
});

$destination.addEventListener('input', () => {
    if ($davao.checked) return;
    openPanel();
    // Also try direct lookup for the typed value
    const km = lookupDistance($destination.value);
    if (km !== null) {
        $distance.value = km;
        $distance.readOnly = true;
        $destHelper.style.display = 'flex';
        $destHelper.className = 'helper success';
        $destHelper.innerHTML = `<i class="bi bi-check-circle"></i> ${km} km from Davao City`;
    } else {
        $distance.value = 0;
        $distance.readOnly = false;
        $destHelper.style.display = 'flex';
        $destHelper.className = 'helper warn';
        $destHelper.innerHTML = '<i class="bi bi-exclamation-triangle"></i> Pick from the list or type distance manually';
    }
});

$destination.addEventListener('keydown', (e) => {
    if (!$comboPanel.classList.contains('visible')) {
        if (e.key === 'ArrowDown') { openPanel(); e.preventDefault(); }
        return;
    }
    if (e.key === 'ArrowDown') { setActive(Math.min(activeIndex + 1, currentMatches.length - 1)); e.preventDefault(); }
    else if (e.key === 'ArrowUp') { setActive(Math.max(activeIndex - 1, 0)); e.preventDefault(); }
    else if (e.key === 'Enter') {
        if (activeIndex >= 0) { selectOption(currentMatches[activeIndex]); e.preventDefault(); }
    }
    else if (e.key === 'Escape') { closePanel(); }
});

$comboPanel.addEventListener('mousedown', (e) => {
    const opt = e.target.closest('.combo-option');
    if (opt) { e.preventDefault(); selectOption(opt); }
});

document.addEventListener('click', (e) => {
    if (!document.getElementById('destination-combo').contains(e.target)) closePanel();
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

// File input — show selected filename
document.getElementById('license_file').addEventListener('change', function() {
    const nameEl = document.getElementById('license-file-name');
    nameEl.textContent = this.files.length ? this.files[0].name : 'No file chosen';
    nameEl.style.color = this.files.length ? '#EDEDEC' : '#A1A09A';
});
</script>

</body>
</html>
