@extends('layouts.app')

@section('title', $car->display_name)
@section('page-title', 'Vehicle Details')
@section('breadcrumb', $car->brand . ' ' . $car->model . ' — ' . $car->plate_number)

@section('content')

<div style="max-width: 900px;">
    <div class="card" style="margin-bottom: 24px;">
        <div style="display: flex; gap: 24px; flex-wrap: wrap;">
            <div style="width: 280px; height: 200px; background: var(--black-3); border-radius: var(--radius-sm); overflow: hidden; flex-shrink: 0;">
                <img src="{{ $car->image_url }}" alt="{{ $car->brand }} {{ $car->model }}" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            <div style="flex: 1; min-width: 240px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 12px;">
                    <h2 style="font-family: 'Bebas Neue', sans-serif; font-size: 32px; letter-spacing: 2px; line-height: 1;">{{ $car->brand }} {{ $car->model }}</h2>
                    <span class="badge badge-{{ $car->status_badge_color }}">{{ ucfirst($car->status) }}</span>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 16px;">
                    <div>
                        <div class="form-label" style="margin-bottom: 2px;">Plate Number</div>
                        <div style="font-size: 14px; font-weight: 600; color: var(--text-primary);">{{ $car->plate_number }}</div>
                    </div>
                    <div>
                        <div class="form-label" style="margin-bottom: 2px;">Year</div>
                        <div style="font-size: 14px; font-weight: 600; color: var(--text-primary);">{{ $car->year ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="form-label" style="margin-bottom: 2px;">Type</div>
                        <div style="font-size: 14px; font-weight: 600; color: var(--text-primary);">{{ ucfirst($car->vehicle_type ?? '—') }}</div>
                    </div>
                    <div>
                        <div class="form-label" style="margin-bottom: 2px;">Transmission</div>
                        <div style="font-size: 14px; font-weight: 600; color: var(--text-primary);">{{ ucfirst($car->transmission ?? '—') }}</div>
                    </div>
                    <div>
                        <div class="form-label" style="margin-bottom: 2px;">Fuel</div>
                        <div style="font-size: 14px; font-weight: 600; color: var(--text-primary);">{{ ucfirst($car->fuel_type ?? '—') }}</div>
                    </div>
                    <div>
                        <div class="form-label" style="margin-bottom: 2px;">Seats</div>
                        <div style="font-size: 14px; font-weight: 600; color: var(--text-primary);">{{ $car->seating_capacity ?? '—' }}</div>
                    </div>
                </div>
                <div style="display: flex; align-items: baseline; gap: 8px; margin-bottom: 12px;">
                    <span style="font-family: 'Bebas Neue', sans-serif; font-size: 28px; color: var(--gold); letter-spacing: 1px;">&#8369;{{ number_format($car->daily_rate, 2) }}</span>
                    <span style="font-size: 13px; color: var(--text-muted);">/ day</span>
                </div>
                <div style="font-size: 12px; color: var(--text-muted); display: flex; align-items: center; gap: 6px;">
                    @if($car->supplier)
                        <i class="bi bi-{{ $car->supplier->isCompanyOwned() ? 'building' : 'heart' }}" style="color: var(--gold);"></i>
                        {{ $car->supplier->name }} ({{ $car->supplier->isCompanyOwned() ? 'Company' : 'Partner' }})
                    @else
                        No Supplier
                    @endif
                </div>
            </div>
        </div>
        @if($car->description)
            <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border-subtle); font-size: 13px; color: var(--text-muted);">
                {{ $car->description }}
            </div>
        @endif
    </div>

    <div style="display: flex; gap: 10px; margin-bottom: 24px;">
        @if(auth()->check() && auth()->user()->role === 'admin')
            <a href="{{ route('cars.edit', $car) }}" class="btn btn-gold"><i class="bi bi-pencil"></i> Edit Vehicle</a>
            <form action="{{ route('cars.destroy', $car) }}" method="POST" onsubmit="return confirm('Delete this vehicle?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Delete</button>
            </form>
        @endif
        <a href="{{ route('cars.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Back to Fleet</a>
    </div>

    @if($car->rentals->count() > 0)
    <div class="card" style="padding: 0;">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-clock-history"></i> Recent Rentals</div>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Start</th>
                    <th>End</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($car->rentals as $rental)
                <tr>
                    <td>{{ $rental->customer_name }}</td>
                    <td>{{ $rental->start_date->format('M d, Y') }}</td>
                    <td>{{ $rental->end_date->format('M d, Y') }}</td>
                    <td>&#8369;{{ number_format($rental->total_cost, 2) }}</td>
                    <td><span class="badge badge-{{ $rental->status_color }}">{{ ucfirst($rental->status) }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endsection
