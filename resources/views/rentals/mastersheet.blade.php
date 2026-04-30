@extends('layouts.app')

@section('title', 'Mastersheet')
@section('page-title', 'Rental Mastersheet')
@section('breadcrumb', 'Complete rental transaction overview')

@section('content')

<!-- Filter Bar -->
<div class="card" style="margin-bottom: 24px;">
    <form method="GET" class="filter-bar">
        <div class="filter-group">
            <label>From Date</label>
            <input type="text" name="from" class="form-control datepicker" value="{{ request('from') }}" placeholder="Start date">
        </div>
        <div class="filter-group">
            <label>To Date</label>
            <input type="text" name="to" class="form-control datepicker" value="{{ request('to') }}" placeholder="End date">
        </div>
        <div class="filter-group">
            <label>Status</label>
            <select name="status" class="form-control">
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
        <button type="submit" class="btn btn-gold btn-sm">Filter</button>
        <a href="{{ route('rentals.mastersheet') }}" class="btn btn-secondary btn-sm">Reset</a>
    </form>
</div>

<!-- Summary Stats -->
<div class="totals-grid" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-label">Total Transactions</div>
        <div class="stat-value">{{ $totals['count'] }}</div>
        <i class="bi bi-receipt stat-icon"></i>
    </div>
    <div class="stat-card" style="border-color: var(--gold);">
        <div class="stat-label">Total Revenue</div>
        <div class="stat-value" style="color: var(--gold);">₱{{ number_format($totals['total_cost'], 2) }}</div>
        <i class="bi bi-currency-dollar stat-icon"></i>
    </div>
    <div class="stat-card green">
        <div class="stat-label">Amount Paid</div>
        <div class="stat-value">₱{{ number_format($totals['amount_paid'], 2) }}</div>
        <i class="bi bi-check-circle stat-icon"></i>
    </div>
    <div class="stat-card red">
        <div class="stat-label">Outstanding Balance</div>
        <div class="stat-value">₱{{ number_format($totals['balance'], 2) }}</div>
        <i class="bi bi-exclamation-circle stat-icon"></i>
    </div>
</div>

<!-- Revenue Breakdown -->
<div class="card" style="margin-bottom: 24px;">
    <h3 style="font-size: 14px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 20px;">
        <i class="bi bi-pie-chart" style="color: var(--gold); margin-right: 6px;"></i> Revenue Breakdown
    </h3>
    <div class="grid-3" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;">
        <div class="total-card">
            <div class="total-label">Company Revenue</div>
            <div class="total-value" style="color: #4ADE80;">₱{{ number_format($totals['company_revenue'], 2) }}</div>
        </div>
        <div class="total-card">
            <div class="total-label">Partner Revenue</div>
            <div class="total-value" style="color: #38BDF8;">₱{{ number_format($totals['partner_revenue'], 2) }}</div>
        </div>
        <div class="total-card">
            <div class="total-label">Total Commission</div>
            <div class="total-value" style="color: #F87171;">₱{{ number_format($totals['total_commission'], 2) }}</div>
        </div>
    </div>
    <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border-subtle); display: flex; justify-content: flex-end; align-items: center; gap: 8px;">
        <span style="font-size: 13px; color: var(--text-muted);">Net Revenue:</span>
        <span style="font-family: 'Bebas Neue', sans-serif; font-size: 28px; color: var(--gold); letter-spacing: 1px;">₱{{ number_format($totals['net_revenue'], 2) }}</span>
    </div>
</div>

<!-- Transactions Table -->
<div class="card" style="padding: 0;">
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
                        <span style="color: #6B7280;">—</span>
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
