<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<nav style="background-color: #2d2d2d;">
    <div class="nav-container">
        {{-- Brand on the LEFT --}}
        <a href="/" class="nav-brand">🚗 Cars ni Bai</a>

        {{-- All links pushed to the RIGHT --}}
        <ul class="nav-links" style="margin-left: auto;">
            <li><a href="{{ route('cars.index') }}" class="{{ request()->is('cars*') ? 'active' : '' }}">Cars</a></li>
            <li><a href="{{ route('rentals.index') }}" class="{{ request()->is('rentals*') ? 'active' : '' }}">Rentals</a></li>
            <li><a href="{{ route('about') }}" class="{{ request()->is('about') ? 'active' : '' }}">About</a></li>

            @guest
                <li style="color:#555; padding:0 4px;">|</li>
                <li><a href="{{ route('login') }}" class="{{ request()->is('login') ? 'active' : '' }}">Login</a></li>
                <li><a href="{{ route('register') }}" class="{{ request()->is('register') ? 'active' : '' }}">Register</a></li>
            @else
                <li style="color:#555; padding:0 4px;">|</li>
<li style="display:flex; align-items:center; padding: 0 6px;">
    <span style="color:#ccc; font-size:0.9rem;">
        @if(auth()->user()->role === 'admin')
            <span style="color:#e74c3c; font-weight:600;">ADMIN</span>
        @else
            <span style="color:#3498db; font-weight:600;">USER</span>
        @endif
        - {{ auth()->user()->name }}
    </span>
</li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" style="display:inline; margin:0;">
                        @csrf
                        <button type="submit" style="background:#c0392b; border:none; color:white; cursor:pointer; font-size:0.8rem; padding:4px 12px; border-radius:4px; font-weight:600;">
                            Logout
                        </button>
                    </form>
                </li>
            @endguest
        </ul>
    </div>
</nav>

<style>
    .nav-links a {
        color: rgba(255,255,255,0.75);
        text-decoration: none;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 0.9rem;
        transition: background 0.2s;
        display: inline-block;
    }
    .nav-links a:hover { background: rgba(255,255,255,0.12) !important; color: #fff !important; }
    .nav-links a.active { background: rgba(255,255,255,0.18) !important; color: #fff !important; font-weight: 600; }
</style>

<div class="container">
    @if(session('success'))
        <div class="alert alert-success">&#10003; {{ session('success') }}</div>
    @endif
    @yield('content')
</div>

<footer>Car Rental Management System &copy; {{ date('Y') }}</footer>

</body>
</html>