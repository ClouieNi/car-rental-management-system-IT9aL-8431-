@extends('layouts.app')

@section('title', 'Rental ' . $rental->rental_id_display)
@section('page-title', 'Rental Details')
@section('breadcrumb', 'View and manage rental transaction')

@push('styles')
<style>
.rental-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 24px;
}
.rental-id {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 36px;
    color: var(--gold);
    margin-bottom: 8px;
}
.rental-meta {
    display: flex;
    gap: 12px;
    align-items: center;
}
.status-timeline {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 24px;
    padding: 16px;
    background: var(--black-2);
    border-radius: var(--radius-sm);
    overflow-x: auto;
}
.timeline-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    min-width: 80px;
}
.timeline-step .dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: var(--border-subtle);
}
.timeline-step.active .dot {
    background: var(--gold);
}
.timeline-step.completed .dot {
    background: #22C55E;
}
.timeline-step span {
    font-size: 11px;
    color: var(--text-muted);
    text-align: center;
}
.timeline-step.active span {
    color: var(--gold);
}
.timeline-arrow {
    color: var(--border-subtle);
    font-size: 12px;
}
.rental-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}
.rental-section {
    background: var(--black-2);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-sm);
    padding: 20px;
}
.rental-section h3 {
    font-size: 14px;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 16px;
}
.info-row {
    display: flex;
    justify-content: space-between;
    padding: 10px 0;
    border-bottom: 1px solid var(--border-subtle);
}
.info-row:last-child {
    border-bottom: none;
}
.info-label {
    color: var(--text-muted);
    font-size: 13px;
}
.info-value {
    font-weight: 500;
    font-size: 13px;
}
.info-value.price {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 16px;
    color: var(--gold);
}
.document-status {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 12px;
}
.document-actions {
    display: flex;
    gap: 8px;
    margin-top: 12px;
}
.action-buttons {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid var(--border-subtle);
}
@media (max-width: 768px) {
    .rental-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')

<div class="rental-header">
    <div>
        <div class="rental-id">{{ $rental->rental_id_display }}</div>
        <div class="rental-meta">
            <span class="badge badge-{{ $rental->status_color }}">{{ ucfirst($rental->status) }}</span>
            <span style="color: var(--text-muted);">|</span>
            <span style="color: var(--text-muted);">{{ $rental->created_at->format('M d, Y') }}</span>
        </div>
    </div>
    @if($rental->status !== 'completed' && $rental->status !== 'cancelled')
    <div>
        <span style="color: var(--text-muted); font-size: 13px;">Updated {{ $rental->updated_at->diffForHumans() }}</span>
    </div>
    @endif
</div>

<div class="status-timeline">
    @php
        $steps = [
            'pending' => ['label' => 'Pending', 'completed' => true],
            'approved' => ['label' => 'Approved', 'completed' => in_array($rental->status, ['approved', 'documents_pending', 'documents_verified', 'reserved', 'ongoing', 'return_pending', 'completed'])],
            'documents' => ['label' => 'Documents', 'completed' => in_array($rental->status, ['documents_verified', 'reserved', 'ongoing', 'return_pending', 'completed'])],
            'reserved' => ['label' => 'Reserved', 'completed' => in_array($rental->status, ['reserved', 'ongoing', 'return_pending', 'completed'])],
            'ongoing' => ['label' => 'Ongoing', 'completed' => in_array($rental->status, ['ongoing', 'return_pending', 'completed'])],
            'return' => ['label' => 'Returned', 'completed' => in_array($rental->status, ['return_pending', 'completed'])],
            'completed' => ['label' => 'Complete', 'completed' => $rental->status === 'completed'],
        ];
        $currentStep = match($rental->status) {
            'pending' => 'pending',
            'approved', 'documents_pending' => 'documents',
            'documents_verified' => 'documents',
            'reserved' => 'reserved',
            'ongoing' => 'ongoing',
            'return_pending' => 'return',
            'completed' => 'completed',
            default => 'pending',
        };
    @endphp
    
    @foreach($steps as $key => $step)
        <div class="timeline-step {{ $step['completed'] ? 'completed' : '' }} {{ $currentStep === $key ? 'active' : '' }}">
            <div class="dot"></div>
            <span>{{ $step['label'] }}</span>
        </div>
        @if(!$loop->last)
            <div class="timeline-arrow"><i class="bi bi-chevron-right"></i></div>
        @endif
    @endforeach
</div>

<div class="rental-grid">
    <div class="rental-section">
        <h3>Customer Information</h3>
        <div class="info-row">
            <span class="info-label">Name</span>
            <span class="info-value">{{ $rental->customer_name }}</span>
        </div>
        @if($rental->customer)
        <div class="info-row">
            <span class="info-label">Email</span>
            <span class="info-value">{{ $rental->customer->email }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Phone</span>
            <span class="info-value">{{ $rental->customer->phone ?? 'N/A' }}</span>
        </div>
        @endif
        @if($rental->driver)
        <div class="info-row">
            <span class="info-label">Driver</span>
            <span class="info-value">{{ $rental->driver->user?->name ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">License</span>
            <span class="info-value">{{ $rental->driver->license_number }}</span>
        </div>
        @endif
    </div>

    <div class="rental-section">
        <h3>Vehicle Information</h3>
        <div class="info-row">
            <span class="info-label">Car</span>
            <span class="info-value">{{ $rental->car->brand }} {{ $rental->car->model }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Plate Number</span>
            <span class="info-value">{{ $rental->car->plate_number }}</span>
        </div>
        @if($rental->car->supplier)
        <div class="info-row">
            <span class="info-label">Supplier</span>
            <span class="info-value">
                {{ $rental->car->supplier->name }}
                @if($rental->car->supplier->isPartnerOwned())
                    <small>({{ $rental->car->supplier->commission_rate }}% commission)</small>
                @endif
            </span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Rental Type</span>
            <span class="info-value">{{ ucfirst(str_replace('_', ' ', $rental->rental_type)) }}</span>
        </div>
        @if($rental->destination)
        <div class="info-row">
            <span class="info-label">Destination</span>
            <span class="info-value">{{ $rental->destination }}</span>
        </div>
        @endif
    </div>

    <div class="rental-section">
        <h3>Rental Dates</h3>
        <div class="info-row">
            <span class="info-label">Start Date</span>
            <span class="info-value">{{ $rental->start_date->format('M d, Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">End Date</span>
            <span class="info-value">{{ $rental->end_date->format('M d, Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Duration</span>
            <span class="info-value">{{ $rental->duration_days }} days</span>
        </div>
        @if($rental->vehicle_released_at)
        <div class="info-row">
            <span class="info-label">Released</span>
            <span class="info-value">{{ $rental->vehicle_released_at->format('M d, Y h:i A') }}</span>
        </div>
        @endif
    </div>

    <div class="rental-section">
        <h3>Cost Breakdown</h3>
        <div class="info-row">
            <span class="info-label">Base Cost</span>
            <span class="info-value price">&#8369;{{ number_format($rental->total_cost - $rental->distance_surcharge, 2) }}</span>
        </div>
        @if($rental->distance_surcharge > 0)
        <div class="info-row">
            <span class="info-label">Distance Surcharge</span>
            <span class="info-value price">&#8369;{{ number_format($rental->distance_surcharge, 2) }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Total Cost</span>
            <span class="info-value price">&#8369;{{ number_format($rental->total_cost, 2) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Amount Paid</span>
            <span class="info-value" style="color: #4ADE80;">&#8369;{{ number_format($rental->amount_paid, 2) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Balance</span>
            <span class="info-value {{ $rental->balance > 0 ? 'text-danger' : '' }}">&#8369;{{ number_format($rental->balance, 2) }}</span>
        </div>
        @if($rental->rentalReturn)
        <div class="info-row">
            <span class="info-label">Damage Fee</span>
            <span class="info-value text-danger">&#8369;{{ number_format($rental->getTotalDamageFee(), 2) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Final Total</span>
            <span class="info-value price">&#8369;{{ number_format($rental->getFinalTotal(), 2) }}</span>
        </div>
        @endif
    </div>
</div>

<div class="rental-section" style="margin-top: 24px;">
    <h3>Documents & Verification</h3>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        <div>
            <div class="document-status">
                <span class="info-label">Contract:</span>
                <span class="badge badge-{{ $rental->document_status_color }}">{{ ucfirst($rental->contract_status) }}</span>
            </div>
            @if($rental->contract_file_path)
                <a href="{{ route('rentals.download-contract', $rental) }}" class="btn btn-sm btn-secondary">
                    <i class="bi bi-download"></i> Download Contract
                </a>
            @endif
            @if($rental->contract_verified_at)
                <small style="color: var(--text-muted); display: block; margin-top: 8px;">
                    Verified by {{ $rental->contractVerifiedBy?->name ?? 'Staff' }} 
                    on {{ $rental->contract_verified_at->format('M d, Y') }}
                </small>
            @endif
        </div>
        
        <div>
            <div class="document-status">
                <span class="info-label">ID Verification:</span>
                <span class="badge badge-{{ $rental->id_status_color }}">{{ ucfirst($rental->id_status) }}</span>
            </div>
            @if($rental->id_file_path)
                <a href="{{ route('rentals.download-id', $rental) }}" class="btn btn-sm btn-secondary">
                    <i class="bi bi-download"></i> Download ID
                </a>
            @endif
            @if($rental->id_verified_at)
                <small style="color: var(--text-muted); display: block; margin-top: 8px;">
                    Verified by {{ $rental->idVerifiedBy?->name ?? 'Staff' }} 
                    on {{ $rental->id_verified_at->format('M d, Y') }}
                </small>
            @endif
        </div>
    </div>
</div>

@if($rental->rentalReturn)
<div class="rental-section" style="margin-top: 24px;">
    <h3>Return Inspection</h3>
    <div class="rental-grid">
        <div>
            <div class="info-row">
                <span class="info-label">Returned At</span>
                <span class="info-value">{{ $rental->rentalReturn->returned_at->format('M d, Y h:i A') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Returned By</span>
                <span class="info-value">{{ $rental->rentalReturn->returnedBy?->name ?? 'Staff' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Fuel Level</span>
                <span class="info-value">{{ ucfirst($rental->rentalReturn->fuel_level) }}</span>
            </div>
        </div>
        <div>
            <div class="info-row">
                <span class="info-label">Damaged Panels</span>
                <span class="info-value {{ $rental->rentalReturn->damage_panels > 0 ? 'text-danger' : '' }}">
                    {{ $rental->rentalReturn->damage_panels }}
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">Damage Rate</span>
                <span class="info-value">&#8369;{{ number_format($rental->rentalReturn->damage_rate_per_panel, 2) }}/panel</span>
            </div>
            <div class="info-row">
                <span class="info-label">Damage Fee</span>
                <span class="info-value price">&#8369;{{ number_format($rental->rentalReturn->damage_fee, 2) }}</span>
            </div>
        </div>
    </div>
    @if($rental->rentalReturn->damage_description)
    <div style="margin-top: 16px; padding: 12px; background: var(--black-3); border-radius: var(--radius-sm);">
        <strong>Damage Description:</strong><br>
        <span style="color: var(--text-secondary);">{{ $rental->rentalReturn->damage_description }}</span>
    </div>
    @endif
</div>
@endif

<div class="action-buttons">
    @if($rental->status === 'pending')
        <form method="POST" action="{{ route('rentals.approve', $rental) }}" style="display: inline;">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-success">Approve Booking</button>
        </form>
        <form method="POST" action="{{ route('rentals.reject', $rental) }}" style="display: inline;">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-danger">Reject Booking</button>
        </form>
    @endif
    
    @if(in_array($rental->status, ['approved', 'documents_pending']))
        <a href="{{ route('rentals.documents', $rental) }}" class="btn btn-primary">
            <i class="bi bi-files"></i> Manage Documents
        </a>
    @endif
    
    @if($rental->canBeReleased())
        <form method="POST" action="{{ route('rentals.release', $rental) }}" style="display: inline;">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-success" onclick="return confirm('Confirm vehicle release?')">
                <i class="bi bi-key"></i> Release Vehicle
            </button>
        </form>
    @endif
    
    @if($rental->status === 'ongoing')
        <a href="{{ route('rentals.return-form', $rental) }}" class="btn btn-warning">
            <i class="bi bi-arrow-return-left"></i> Process Return
        </a>
    @endif
    
    @if($rental->status === 'return_pending')
        <a href="{{ route('rentals.return-form', $rental) }}" class="btn btn-success">
            <i class="bi bi-check-circle"></i> Complete Return
        </a>
    @endif
    
    <a href="{{ route('rentals.index') }}" class="btn btn-secondary">Back to Rentals</a>
</div>

@endsection
