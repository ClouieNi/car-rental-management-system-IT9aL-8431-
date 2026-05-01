<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quote Submitted — Cars ni Bai</title>

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
            display: flex; align-items: center; justify-content: center;
            padding: 24px;
        }
        a { text-decoration: none; }

        .bg-glow {
            position: fixed; top: -30%; left: 50%; transform: translateX(-50%);
            width: 700px; height: 700px;
            background: radial-gradient(circle, rgba(74,222,128,0.06) 0%, transparent 70%);
            pointer-events: none; z-index: 0;
        }

        .card {
            position: relative; z-index: 1;
            background: rgba(22,22,21,0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 16px;
            padding: 56px 48px;
            text-align: center;
            max-width: 480px; width: 100%;
            box-shadow: 0 24px 60px rgba(0,0,0,0.4);
        }

        .icon-wrap {
            width: 80px; height: 80px;
            background: rgba(74,222,128,0.1);
            border: 2px solid rgba(74,222,128,0.3);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
            color: #4ADE80; font-size: 38px;
            animation: pop .4s ease-out;
        }
        @keyframes pop {
            0% { transform: scale(.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 44px; letter-spacing: 3px; margin-bottom: 12px;
        }
        h1 .gold { color: #FFB800; }

        .message {
            color: #A1A09A; font-size: 15px; line-height: 1.6;
            margin-bottom: 32px;
        }
        .message strong { color: #EDEDEC; font-weight: 600; }

        .info-strip {
            background: rgba(255,184,0,0.05);
            border: 1px solid rgba(255,184,0,0.1);
            border-radius: 8px;
            padding: 14px 18px;
            margin-bottom: 32px;
            font-size: 13px; color: #A1A09A;
            display: flex; align-items: center; gap: 10px;
            text-align: left;
        }
        .info-strip i { color: #FFB800; font-size: 16px; flex-shrink: 0; }

        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 13px 28px;
            background: #FFB800; color: #0A0A0A;
            border-radius: 8px;
            font-size: 14px; font-weight: 600;
            transition: all .2s;
        }
        .btn:hover {
            background: #FFD04A;
            box-shadow: 0 8px 24px rgba(255,184,0,0.25);
            transform: translateY(-2px);
        }

        .footer-link {
            display: block; margin-top: 20px;
            color: #555; font-size: 12px;
        }
        .footer-link:hover { color: #FFB800; }
    </style>
</head>
<body>

<div class="bg-glow"></div>

<div class="card">
    <div class="icon-wrap"><i class="bi bi-check-lg"></i></div>

    <h1>QUOTE <span class="gold">SUBMITTED</span></h1>

    <p class="message">
        Thank you for your interest in <strong>Cars ni Bai</strong>!<br>
        We've received your quote request and will get back to you shortly.
    </p>

    <div class="info-strip">
        <i class="bi bi-clock"></i>
        <span>Our team will contact you within <strong style="color:#EDEDEC;">24 hours</strong> via the email or phone you provided.</span>
    </div>

    <a href="/login" class="btn">
        <i class="bi bi-arrow-left"></i> Back to Home
    </a>

    <a href="{{ route('quotes.request') }}" class="footer-link">
        Need to submit another quote? Click here
    </a>
</div>

</body>
</html>
