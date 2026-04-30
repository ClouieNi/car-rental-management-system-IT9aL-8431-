@extends('layouts.app')

@section('title', 'Quotes')
@section('page-title', 'Quote Requests')
@section('breadcrumb', 'Manage customer quote requests')

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
.filter-group input[type="text"] {
    min-width: 200px;
}
</style>
@endpush

@section('content')

<div class="card">
    <form method="GET" class="filter-bar">
        <div class="filter-group">
            <label>Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Guest name or email...">
        </div>
        <div class="filter-group">
            <label>Status</label>
            <select name="status">
                <option value="">All</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="sent" {{ request('status') === 'sent' ? 'selected' : '' }}>Sent</option>
                <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Filter</button>
        <a href="{{ route('quotes.index') }}" class="btn btn-secondary btn-sm">Reset</a>
    </form>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Quote ID</th>
                <th>Guest</th>
                <th>Car</th>
                <th>Dates</th>
                <th>Total Estimate</th>
                <th>Status</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($quotes as $quote)
            <tr>
                <td><strong>{{ $quote->quote_id_display }}</strong></td>
                <td>
                    {{ $quote->guest_name }}<br>
                    <small style="color:#9ca3af;">{{ $quote->guest_email }}</small>
                </td>
                <td>
                    {{ $quote->car->brand }} {{ $quote->car->model }}<br>
                    <small style="color:#9ca3af;">{{ $quote->car->plate_number }}</small>
                </td>
                <td>
                    {{ $quote->start_date->format('M d') }} - {{ $quote->end_date->format('M d, Y') }}<br>
                    <small style="color:#9ca3af;">{{ $quote->days }} days</small>
                </td>
                <td>&#8369;{{ number_format($quote->total_estimate, 2) }}</td>
                <td>
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
                    <span class="badge badge-{{ $statusColor }}">{{ ucfirst($quote->status) }}</span>
                </td>
                <td>{{ $quote->created_at->format('M d, Y') }}</td>
                <td>
                    <a href="{{ route('quotes.show', $quote) }}" class="btn btn-primary btn-sm">View</a>
                </td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="8">No quotes found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-container">
    {{ $quotes->links() }}
</div>

@endsection
