@extends('layouts.app')
@section('content')
<div class="hero">
    <h1>&#128663; Car Rental Management System</h1>
    <p>Manage your fleet and rental transactions simply and efficiently.</p>
    <div class="hero-actions">
        <a href="{{ route('cars.index') }}" class="btn btn-primary">View Cars</a>
        <a href="{{ route('rentals.index') }}" class="btn btn-success">View Rentals</a>
    </div>
</div>

<div class="module-cards">
    <div class="module-card">
        <h3>&#128663; Cars</h3>
        <p>Add, update, and remove cars from your rental fleet.</p>
        <a href="{{ route('cars.create') }}" class="btn btn-primary btn-sm">+ Add Car</a>
    </div>
    <div class="module-card">
        <h3>&#128197; Rentals</h3>
        <p>Record and manage customer rental transactions.</p>
        <a href="{{ route('rentals.create') }}" class="btn btn-success btn-sm">+ New Rental</a>
    </div>
</div>
@endsection