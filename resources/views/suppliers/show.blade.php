@extends('layouts.app')

@section('title', $supplier->name)
@section('page-title', 'Supplier Details')
@section('breadcrumb', 'View supplier information and vehicles')

@section('content')

<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 20px;">
        <div>
            <h2 style="margin-bottom: 8px;">{{ $supplier->name }}</h2>
            <div style="display: flex; gap: 8px;">
                @if($supplier->isCompanyOwned())
                    <span class="badge badge-success">Company Owned</span>
                @else
                    <span class="badge badge-info">Partner Owned</span>
                @endif
                @if($supplier->is_active)
                    <span class="badge badge-success">Active</span>
                @else
                    <span class="badge badge-secondary">Inactive</span>
                @endif
            </div>
        </div>
        <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning btn-sm">Edit</a>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px;">
        <div>
            <h4 style="font-size: 12px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px;">Contact Information</h4>
            @if($supplier->contact_person)
                <p><strong>Contact:</strong> {{ $supplier->contact_person }}</p>
            @endif
            @if($supplier->phone)
                <p><strong>Phone:</strong> {{ $supplier->phone }}</p>
            @endif
            @if($supplier->email)
                <p><strong>Email:</strong> {{ $supplier->email }}</p>
            @endif
            @if($supplier->address)
                <p><strong>Address:</strong> {{ $supplier->address }}</p>
            @endif
        </div>
        <div>
            <h4 style="font-size: 12px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px;">Commission Details</h4>
            <p><strong>Commission Rate:</strong> {{ $supplier->commission_rate_display }}</p>
            <p><strong>Total Vehicles:</strong> {{ $supplier->cars->count() }}</p>
            <p><strong>Total Rentals:</strong> {{ $supplier->cars->sum('rentals_count') }}</p>
        </div>
    </div>

    @if($supplier->notes)
    <div style="background: var(--black-3); padding: 16px; border-radius: var(--radius-sm); margin-bottom: 24px;">
        <h4 style="font-size: 12px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 8px;">Notes</h4>
        <p style="margin: 0; color: var(--text-secondary);">{{ $supplier->notes }}</p>
    </div>
    @endif
</div>

<h3 style="margin: 24px 0 16px;">Assigned Vehicles</h3>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Plate</th>
                <th>Vehicle</th>
                <th>Year</th>
                <th>Daily Rate</th>
                <th>Status</th>
                <th>Rentals</th>
            </tr>
        </thead>
        <tbody>
            @forelse($supplier->cars as $car)
            <tr>
                <td><strong>{{ $car->plate_number }}</strong></td>
                <td>{{ $car->brand }} {{ $car->model }}</td>
                <td>{{ $car->year }}</td>
                <td>&#8369;{{ number_format($car->daily_rate, 2) }}</td>
                <td><span class="badge badge-{{ $car->status_badge_color }}">{{ ucfirst($car->status) }}</span></td>
                <td>{{ $car->rentals_count }}</td>
            </tr>
            @empty
            <tr class="empty-row"><td colspan="6">No vehicles assigned to this supplier.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div style="margin-top: 24px;">
    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Back to Suppliers</a>
</div>

@endsection
