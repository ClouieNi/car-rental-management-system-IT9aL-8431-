@extends('layouts.app')

@section('title', 'Fleet Management')
@section('page-title', 'Vehicles')
@section('breadcrumb', 'Manage your vehicle fleet')

@section('content')

<div class="card" style="margin-bottom: 24px;">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
        <h2 style="font-family: 'Bebas Neue', sans-serif; font-size: 22px; letter-spacing: 1px; color: var(--text-primary);">Fleet Overview</h2>
        @if(auth()->check() && auth()->user()->role === 'admin')
            <a href="{{ route('cars.create') }}" class="btn btn-gold btn-sm">+ Add Vehicle</a>
        @endif
    </div>
    <form method="GET" class="filter-bar" style="margin-bottom: 0;">
        <div class="filter-group">
            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Plate, brand...">
        </div>
        <div class="filter-group">
            <select name="type" class="form-control">
                <option value="">All Types</option>
                @foreach(['sedan','suv','mpv','pickup','van','other'] as $t)
                    <option value="{{ $t }}" {{ request('type') == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <select name="status" class="form-control">
                <option value="">All Status</option>
                @foreach(['available','rented','maintenance'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-gold btn-sm">Filter</button>
        <a href="{{ route('cars.index') }}" class="btn btn-secondary btn-sm">Reset</a>
    </form>
</div>

@if($cars->count() > 0)
<div class="vehicle-grid">
    @foreach($cars as $car)
    <div class="vehicle-card">
        <div class="vehicle-card-img">
            <img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}">
            <span class="status-tag {{ $car->status }}">{{ ucfirst($car->status) }}</span>
        </div>
        <div class="vehicle-card-body">
            <h3>{{ $car->brand }}</h3>
            <div class="vehicle-meta">{{ $car->plate_number }} &middot; {{ ucfirst($car->vehicle_type ?? 'Sedan') }} @if($car->year)&middot; {{ $car->year }}@endif</div>
            <div class="vehicle-price">&#8369;{{ number_format($car->daily_rate, 0) }}/day</div>
            <div class="vehicle-supplier">
                @if($car->supplier)
                    <i class="bi bi-{{ $car->supplier->isCompanyOwned() ? 'building' : 'heart' }}"></i>
                    {{ $car->supplier->isCompanyOwned() ? 'Company Owned' : $car->supplier->name }}
                @else
                    <span style="color: var(--text-dim);">No Supplier</span>
                @endif
            </div>
        </div>
        @if(auth()->check() && auth()->user()->role === 'admin')
        <div class="vehicle-card-actions">
            <a href="{{ route('cars.edit', $car) }}" class="edit-btn"><i class="bi bi-pencil"></i> Edit</a>
            <form action="{{ route('cars.destroy', $car) }}" method="POST" onsubmit="return confirm('Delete this vehicle?')" style="display:contents;">
                @csrf @method('DELETE')
                <button type="submit" class="delete-btn"><i class="bi bi-trash"></i></button>
            </form>
        </div>
        @endif
    </div>
    @endforeach
</div>

<div style="margin-top: 24px;">
    {{ $cars->links() }}
</div>
@else
<div class="card" style="text-align: center; padding: 48px 20px; color: var(--text-dim);">
    <i class="bi bi-car-front" style="font-size: 40px; display: block; margin-bottom: 12px; opacity: 0.3;"></i>
    <p style="font-size: 14px; margin-bottom: 16px;">No vehicles found. Add your first vehicle!</p>
    @if(auth()->check() && auth()->user()->role === 'admin')
        <a href="{{ route('cars.create') }}" class="btn btn-gold">+ Add Vehicle</a>
    @endif
</div>
@endif

@endsection