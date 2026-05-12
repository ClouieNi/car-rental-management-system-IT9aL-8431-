@extends('layouts.app')

@section('title', 'Rentals')
@section('page-title', 'Rental Transactions')
@section('breadcrumb', 'Manage all rental bookings')

@section('content')

<!-- Header -->
<div class="page-header" style="margin-bottom: 16px;">
    <div>
        <h2 style="margin: 0;">All Rentals</h2>
        <small style="color: var(--text-muted);">{{ $rentals->total() }} total transaction{{ $rentals->total() === 1 ? '' : 's' }}</small>
    </div>
    @if(auth()->check() && auth()->user()->role === 'admin')
        <a href="{{ route('rentals.create') }}" class="btn btn-gold btn-sm">
            <i class="bi bi-plus-circle"></i> New Rental
        </a>
    @endif
</div>

<!-- Filter Bar -->
<div class="card" style="margin-bottom: 24px;">
    <form method="GET" class="filter-bar" style="flex-wrap: wrap;">
        <div class="filter-group" style="flex: 2;">
            <label>Search</label>
            <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                   placeholder="Customer, brand, plate...">
        </div>
        <div class="filter-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="">All</option>
                @foreach(['pending','approved','reserved','ongoing','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label>Payment</label>
            <select name="payment" class="form-control">
                <option value="">All</option>
                @foreach(['unpaid','partial','paid'] as $p)
                    <option value="{{ $p }}" {{ request('payment') === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label>Type</label>
            <select name="type" class="form-control">
                <option value="">All</option>
                <option value="self_drive" {{ request('type') === 'self_drive' ? 'selected' : '' }}>Self-drive</option>
                <option value="with_driver" {{ request('type') === 'with_driver' ? 'selected' : '' }}>With Driver</option>
            </select>
        </div>
        <button type="submit" class="btn btn-gold btn-sm">
            <i class="bi bi-funnel"></i> Filter
        </button>
        <a href="{{ route('rentals.index') }}" class="btn btn-secondary btn-sm">Reset</a>
    </form>
</div>

<!-- Table -->
<div class="card" style="padding: 0; overflow-x: auto;">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Vehicle</th>
                <th>Type</th>
                <th>Dates</th>
                <th style="text-align: right;">Total</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rentals as $rental)
            <tr>
                <td>{{ $loop->iteration + ($rentals->currentPage() - 1) * $rentals->perPage() }}</td>
                <td>
                    <strong>{{ $rental->customer?->name ?? $rental->customer_name }}</strong>
                    @if($rental->destination)
                        <br><small style="color: var(--text-dim);"><i class="bi bi-geo-alt"></i> {{ $rental->destination }}</small>
                    @endif
                </td>
                <td>
                    <strong>{{ $rental->car->brand }} {{ $rental->car->model }}</strong><br>
                    <small style="color: var(--text-dim);">{{ $rental->car->plate_number }}</small>
                </td>
                <td>
                    @if($rental->rental_type === 'self_drive')
                        <span class="badge badge-info" style="font-size: 10px;"><i class="bi bi-person"></i> Self</span>
                    @else
                        <span class="badge badge-warning" style="font-size: 10px;"><i class="bi bi-person-badge"></i> Driver</span>
                    @endif
                </td>
                <td>
                    <small>
                        {{ \Carbon\Carbon::parse($rental->start_date)->format('M d') }} —
                        {{ \Carbon\Carbon::parse($rental->end_date)->format('M d, Y') }}
                    </small>
                </td>
                <td style="text-align: right; font-weight: 600;">&#8369;{{ number_format($rental->total_cost, 2) }}</td>
                <td>
                    @php
                        $payColor = match($rental->payment_status ?? 'unpaid') {
                            'paid' => 'success',
                            'partial' => 'warning',
                            default => 'danger',
                        };
                    @endphp
                    <span class="badge badge-{{ $payColor }}">{{ ucfirst($rental->payment_status ?? 'Unpaid') }}</span>
                </td>
                <td><span class="badge badge-{{ $rental->status_color ?? $rental->status }}">{{ ucfirst($rental->status) }}</span></td>
                <td style="white-space: nowrap;">
                    <a href="{{ route('rentals.show', $rental) }}" class="btn btn-primary btn-sm" title="View">
                        <i class="bi bi-eye"></i>
                    </a>
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('rentals.edit', $rental) }}" class="btn btn-warning btn-sm" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('rentals.destroy', $rental) }}" method="POST"
                              style="display: inline;" onsubmit="return confirm('Delete this rental?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="9">
                    <div style="padding: 40px 20px; text-align: center; color: var(--text-muted);">
                        <i class="bi bi-inbox" style="font-size: 32px; display: block; margin-bottom: 8px; color: var(--gold);"></i>
                        No rentals found matching your filters.
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-container" style="margin-top: 24px;">
    {{ $rentals->links() }}
</div>

@endsection