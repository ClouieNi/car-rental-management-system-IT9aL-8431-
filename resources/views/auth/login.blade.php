<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In — Cars ni Bai</title>

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

        .login-card {
            position: relative; z-index: 1;
            width: 100%; max-width: 420px;
            background: rgba(22,22,21,0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 16px;
            padding: 44px 40px;
            box-shadow: 0 24px 60px rgba(0,0,0,0.4);
        }
        .login-card .lock-badge {
            display: inline-flex; align-items: center; justify-content: center;
            width: 52px; height: 52px;
            background: rgba(255,184,0,0.1);
            border: 1px solid rgba(255,184,0,0.2);
            border-radius: 12px;
            color: #FFB800; font-size: 24px; margin-bottom: 18px;
        }
        .login-card h1 {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 38px; letter-spacing: 3px; margin-bottom: 4px;
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
        .form-group input {
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
            outline: none; border-color: #FFB800;
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
        .forgot-link { font-size: 12px; color: #A1A09A; transition: color .2s; }
        .forgot-link:hover { color: #FFB800; }

        .btn-signin {
            width: 100%; padding: 14px;
            background: #FFB800; color: #0A0A0A;
            border: none; border-radius: 8px;
            font-size: 14px; font-weight: 600; letter-spacing: 0.5px;
            font-family: inherit; cursor: pointer;
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
            padding: 10px 14px; border-radius: 8px;
            font-size: 13px; margin-bottom: 18px;
        }

        .footer-link {
            display: block; text-align: center; margin-top: 18px;
            color: #555; font-size: 12px;
        }
        .footer-link:hover { color: #FFB800; }

        @media (max-width: 500px) {
            .nav { padding: 14px 20px; }
            .login-card { padding: 32px 24px; }
            .login-card h1 { font-size: 32px; }
        }
    </style>
</head>
<body>

<div class="bg-glow"></div>

<nav class="nav">
    <a href="/" class="nav-brand">
        CARS NI BAI
        <span>Rental Management</span>
    </a>
    <a href="/" class="back-link">
        <i class="bi bi-arrow-left"></i> Back to Home
    </a>
</nav>

<div class="login-card">
    <div class="lock-badge"><i class="bi bi-shield-lock"></i></div>
    <h1>SIGN IN</h1>
    <p class="subtitle">Login to access the management dashboard</p>

    @if(session('status'))
        <div class="session-status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label for="email">Email Address</label>
            <div class="input-wrap">
                <i class="bi bi-envelope icon"></i>
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       placeholder="you@example.com" required autofocus autocomplete="username">
            </div>
            @error('email')
                <div class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <div class="input-wrap">
                <i class="bi bi-key icon"></i>
                <input id="password" type="password" name="password"
                       placeholder="••••••••" required autocomplete="current-password">
            </div>
            @error('password')
                <div class="form-error"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
            @enderror
        </div>

        <div class="form-options">
            <label class="remember">
                <input type="checkbox" name="remember"> Remember me
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-link">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="btn-signin">
            <i class="bi bi-box-arrow-in-right"></i> Sign In
        </button>
    </form>

    <a href="{{ route('quotes.request') }}" class="footer-link">
        Just a customer? Get a quote instead →
    </a>
</div>

</body>
</html>
