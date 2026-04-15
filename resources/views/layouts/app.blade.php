<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Rental</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<nav style="background-color: #2d2d2d; padding: 0.75rem 2rem; display: flex; align-items: center; justify-content: space-between;">
    <a class="brand" href="{{ route('home') }}" style="color: #ffffff; font-size: 1.2rem; font-weight: 700; text-decoration: none;">&#128663; Cars ni Bai</a>
    <ul style="list-style: none; display: flex; gap: 0.25rem; margin: 0; padding: 0;">
        <li><a href="{{ route('home') }}"          style="color: #cccccc; text-decoration: none; padding: 0.4rem 0.9rem; border-radius: 6px; font-size: 0.92rem;" class="{{ request()->routeIs('home')      ? 'active' : '' }}">Home</a></li>
        <li><a href="{{ route('cars.index') }}"    style="color: #cccccc; text-decoration: none; padding: 0.4rem 0.9rem; border-radius: 6px; font-size: 0.92rem;" class="{{ request()->routeIs('cars.*')    ? 'active' : '' }}">Cars</a></li>
        <li><a href="{{ route('rentals.index') }}" style="color: #cccccc; text-decoration: none; padding: 0.4rem 0.9rem; border-radius: 6px; font-size: 0.92rem;" class="{{ request()->routeIs('rentals.*') ? 'active' : '' }}">Rentals</a></li>
        <li><a href="{{ route('about') }}"         style="color: #cccccc; text-decoration: none; padding: 0.4rem 0.9rem; border-radius: 6px; font-size: 0.92rem;" class="{{ request()->routeIs('about')     ? 'active' : '' }}">About</a></li>
    </ul>
</nav>

<style>
    nav a:hover { background-color: #444444 !important; color: #ffffff !important; }
    nav a.active { background-color: #555555 !important; color: #ffffff !important; }
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