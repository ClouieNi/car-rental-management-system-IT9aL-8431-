@extends('layouts.app')

@section('title', 'Mastersheet')
@section('page-title', 'Rental Mastersheet')
@section('breadcrumb', 'Complete rental transaction overview')

@push('styles')
<style>
.filter-bar {
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
    flex-wrap: wrap;
    align-items: end;
}
.filter-group {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.filter-group label {
    font-size: 12px;
    color: var(--text-muted);
    font-weight: 500;
}
.filter-group input,
.filter-group select {
    padding: 8px 12px;
    background: var(--black-2);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-sm);
    color: var(--text-primary);
    font-size: 13px;
}
.totals-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
.total-card {
    background: var(--black-2);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-sm);
    padding: 16px;
}
.total-card.highlight {
    background: var(--gold-muted);
    border-color: var(--gold);
}
.total-label {
    font-size: 12px;
    color: var(--text-muted);
    margin-bottom: 4px;
}
.total-value {
    font-family: 'Bebas Neue', sans-serif;
    font-size: 24px;
    color: var(--text-primary);
}
.total-card.highlight .total-value {
    color: var(--gold);
}
@media (max-width: 900px) {
    .totals-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 500px) {
    .totals-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')

<div class="card">
    <form method="GET" class="filter-bar">
        <div class="filter-group">
            <label>From Date</label>
            <input type="date" name="from" value="{{ request('from') }}">
        </div>
        <div class="filter-group">
            <label>To Date</label>
            <input type="date" name="to" value="{{ request('to') }}">
        </div>
        <div class="filter-group">
            <label>Status</label>
            <select name="status">
                <option value="">All</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="documents_pending" {{ request('status') === 'documents_pending' ? 'selected' : '' }}>Documents Pending</option>
                <option value="documents_verified" {{ request('status') === 'documents_verified' ? 'selected' : '' }}>Documents Verified</option>
                <option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>Reserved</option>
                <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                <option value="return_pending" {{ request('status') === 'return_pending' ? 'selected' : '' }}>Return Pending</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('rentals.mastersheet') }}" class="btn btn-secondary btn-sm">Reset</a>
    </form>
</div>

<div class="totals-grid">
    <div class="total-card">
        <div class="total-label">Total Transactions</div>
        <div class="total-value">{{ $totals['count'] }}</div>
    </div>
    <div class="total-card highlight">
        <div class="total-label">Total Revenue</div>
        <div class="total-value">₱{{ number_format($totals['total_cost'], 2) }}</div>
    </div>
    <div class="total-card">
        <div class="total-label">Amount Paid</div>
        <div class="total-value">₱{{ number_format($totals['amount_paid'], 2) }}</div>
    </div>
    <div class="total-card">
        <div class="total-label">Outstanding Balance</div>
        <div class="total-value">₱{{ number_format($totals['balance'], 2) }}</div>
    </div>
</div>

<div class="card" style="margin-bottom: 24px;">
    <h3 style="font-size: 14px; color: var(--text-muted); margin-bottom: 16px;">Revenue Breakdown</h3>
    <div class="totals-grid" style="grid-template-columns: repeat(3, 1fr);">
        <div class="total-card">
            <div class="total-label">Company Revenue</div>
            <div class="total-value" style="font-size: 20px;">₱{{ number_format($totals['company_revenue'], 2) }}</div>
        </div>
        <div class="total-card">
            <div class="total-label">Partner Revenue</div>
            <div class="total-value" style="font-size: 20px;">₱{{ number_format($totals['partner_revenue'], 2) }}</div>
        </div>
        <div class="total-card">
            <div class="total-label">Total Commission</div>
            <div class="total-value" style="font-size: 20px; color: #F87171;">₱{{ number_format($totals['total_commission'], 2) }}</div>
        </div>
    </div>
    <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--border-subtle); text-align: right;">
        <strong style="color: var(--gold);">Net Revenue: ₱{{ number_format($totals['net_revenue'], 2) }}</strong>
    </div>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Car</th>
                <th>Supplier</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Total Cost</th>
                <th>Commission</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rentals as $rental)
            @php
                $commission = 0;
                if ($rental->car && $rental->car->supplier && $rental->car->supplier->isPartnerOwned()) {
                    $commission = $rental->car->supplier->calculateCommission($rental->total_cost);
                }
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $rental->customer_name }}</td>
                <td>
                    {{ $rental->car->brand }} {{ $rental->car->model }}<br>
                    <small style="color:#9ca3af;">{{ $rental->car->plate_number }}</small>
                </td>
                <td>
                    @if($rental->car && $rental->car->supplier)
                        {{ $rental->car->supplier->name }}<br>
                        @if($rental->car->supplier->isPartnerOwned())
                            <small style="color: #38BDF8;">{{ $rental->car->supplier->commission_rate }}% commission</small>
                        @else
                            <small style="color: #4ADE80;">Company</small>
                        @endif
                    @else
                        <small style="color:#9ca3af;">No Supplier</small>
                    @endif
                </td>
                <td>{{ $rental->start_date->format('M d, Y') }}</td>
                <td>{{ $rental->end_date->format('M d, Y') }}</td>
                <td>₱{{ number_format($rental->total_cost, 2) }}</td>
                <td>
                    @if($commission > 0)
                        <span style="color: #F87171;">₱{{ number_format($commission, 2) }}</span>
                    @else
                        <span style="color: #6B7280;">-</span>
                    @endif
                </td>
                <td><span class="badge badge-{{ $rental->status_color }}">{{ ucfirst($rental->status) }}</span></td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="9">No rentals found for the selected criteria.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
