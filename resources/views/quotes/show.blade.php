@extends('layouts.app')

@section('title', 'Quote ' . $quote->quote_id_display)
@section('page-title', 'Quote Details')
@section('breadcrumb', 'View and manage quote request')

@push('styles')
<style>
.quote-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}
.quote-id {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 32px;
    color: var(--gold);
}
.quote-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}
.quote-section {
    background: var(--black-2);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-sm);
    padding: 20px;
}
.quote-section h3 {
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
.status-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.status-form {
    margin-top: 20px;
}
.status-form select {
    width: 100%;
    padding: 10px 12px;
    background: var(--black-2);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-sm);
    color: var(--text-primary);
    font-size: 13px;
    margin-bottom: 12px;
}
.status-form textarea {
    width: 100%;
    padding: 10px 12px;
    background: var(--black-2);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-sm);
    color: var(--text-primary);
    font-size: 13px;
    min-height: 80px;
    resize: vertical;
    margin-bottom: 12px;
}
.notes-box {
    background: var(--black-3);
    border-radius: var(--radius-sm);
    padding: 12px;
    font-size: 13px;
    color: var(--text-secondary);
    margin-top: 12px;
}
@media (max-width: 768px) {
    .quote-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@section('content')

<div class="quote-header">
    <div class="quote-id">{{ $quote->quote_id_display }}</div>
    @php
        $statusColor = match($quote->status) {
            'pending' => 'warning',
            'sent' => 'info',
            'accepted' => 'success',
            'rejected' => 'danger',
            'expired' => 'secondary',
            default => 'secondary',
        };
    @endphp
    <span class="badge badge-{{ $statusColor }}" style="font-size: 14px; padding: 6px 16px;">
        {{ ucfirst($quote->status) }}
    </span>
</div>

<div class="quote-grid">
    <div class="quote-section">
        <h3>Guest Information</h3>
        <div class="info-row">
            <span class="info-label">Name</span>
            <span class="info-value">{{ $quote->guest_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Email</span>
            <span class="info-value">{{ $quote->guest_email }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Phone</span>
            <span class="info-value">{{ $quote->guest_phone ?? 'N/A' }}</span>
        </div>
    </div>

    <div class="quote-section">
        <h3>Vehicle Details</h3>
        <div class="info-row">
            <span class="info-label">Car</span>
            <span class="info-value">{{ $quote->car->brand }} {{ $quote->car->model }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Plate Number</span>
            <span class="info-value">{{ $quote->car->plate_number }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Daily Rate</span>
            <span class="info-value">&#8369;{{ number_format($quote->car->daily_rate, 2) }}</span>
        </div>
    </div>

    <div class="quote-section">
        <h3>Rental Details</h3>
        <div class="info-row">
            <span class="info-label">Rental Type</span>
            <span class="info-value">{{ ucfirst($quote->rental_type) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Destination</span>
            <span class="info-value">{{ $quote->destination ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Distance</span>
            <span class="info-value">{{ $quote->distance_km ? $quote->distance_km . ' km' : 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Duration</span>
            <span class="info-value">{{ $quote->start_date->format('M d, Y') }} - {{ $quote->end_date->format('M d, Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Days</span>
            <span class="info-value">{{ $quote->days }}</span>
        </div>
    </div>

    <div class="quote-section">
        <h3>Cost Breakdown</h3>
        <div class="info-row">
            <span class="info-label">Base Cost</span>
            <span class="info-value">&#8369;{{ number_format($quote->base_cost, 2) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Distance Surcharge</span>
            <span class="info-value">&#8369;{{ number_format($quote->distance_surcharge, 2) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total Estimate</span>
            <span class="info-value price">&#8369;{{ number_format($quote->total_estimate, 2) }}</span>
        </div>
    </div>
</div>

@if($quote->guest_notes)
<div class="quote-section" style="margin-top: 24px;">
    <h3>Guest Notes</h3>
    <div class="notes-box">{{ $quote->guest_notes }}</div>
</div>
@endif

@if($quote->admin_remarks)
<div class="quote-section" style="margin-top: 24px;">
    <h3>Admin Remarks</h3>
    <div class="notes-box">{{ $quote->admin_remarks }}</div>
</div>
@endif

@if($quote->expires_at)
<div class="quote-section" style="margin-top: 24px;">
    <h3>Expiration</h3>
    <div class="info-row">
        <span class="info-label">Quote Expires At</span>
        <span class="info-value {{ $quote->expires_at->isPast() ? 'text-danger' : '' }}">
            {{ $quote->expires_at->format('M d, Y h:i A') }}
        </span>
    </div>
</div>
@endif

@if(auth()->check() && auth()->user()->role === 'admin')
<div class="quote-section" style="margin-top: 24px;">
    <h3>Update Status</h3>
    <form method="POST" action="{{ route('quotes.update-status', $quote) }}" class="status-form">
        @csrf
        @method('PATCH')
        <select name="status" required>
            <option value="pending" {{ $quote->status === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="sent" {{ $quote->status === 'sent' ? 'selected' : '' }}>Sent</option>
            <option value="accepted" {{ $quote->status === 'accepted' ? 'selected' : '' }}>Accepted</option>
            <option value="rejected" {{ $quote->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
            <option value="expired" {{ $quote->status === 'expired' ? 'selected' : '' }}>Expired</option>
        </select>
        <textarea name="admin_remarks" placeholder="Add admin remarks (optional)...">{{ $quote->admin_remarks }}</textarea>
        <button type="submit" class="btn btn-primary">Update Status</button>
    </form>
</div>
@endif

<div style="margin-top: 24px; display: flex; gap: 12px;">
    <a href="{{ route('quotes.index') }}" class="btn btn-secondary">Back to Quotes</a>
</div>

@endsection
