@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Overview of Cars ni Bai rental operations')

@push('styles')
<style>
.stat-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
.charts-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-bottom: 24px;
}
.bottom-grid {
    display: grid;
    grid-template-columns: 1fr 360px;
    gap: 16px;
}
.recent-item {
    display: flex; align-items: center; gap: 14px;
    padding: 13px 0;
    border-bottom: 1px solid var(--border-subtle);
}
.recent-item:last-child { border-bottom: none; }
.recent-item-icon {
    width: 38px; height: 38px;
    background: var(--gold-muted);
    border-radius: var(--radius-sm);
    display: flex; align-items: center; justify-content: center;
    color: var(--gold); font-size: 16px; flex-shrink: 0;
}
.recent-item-info { flex: 1; }
.recent-item-name { font-weight: 600; font-size: 13.5px; }
.recent-item-sub  { font-size: 12px; color: var(--text-muted); margin-top: 1px; }
.recent-item-amount { font-family: 'Bebas Neue', sans-serif; font-size: 18px; color: var(--gold); }

.quick-action-btn {
    display: flex; align-items: center; gap: 12px;
    padding: 13px 16px;
    background: var(--black-3);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-sm);
    color: var(--text-primary);
    text-decoration: none;
    font-size: 13.5px; font-weight: 500;
    transition: all .15s;
    margin-bottom: 8px;
}
.quick-action-btn:hover {
    background: var(--gold-muted);
    border-color: var(--border);
    color: var(--gold);
    text-decoration: none;
}
.quick-action-btn i { font-size: 17px; width: 20px; }
.quick-action-btn.primary {
    background: var(--gold); border-color: var(--gold);
    color: var(--black); font-weight: 700;
}
.quick-action-btn.primary:hover { background: var(--gold-light); color: var(--black); }

@media (max-width: 1100px) {
    .stat-grid { grid-template-columns: repeat(2, 1fr); }
    .charts-grid, .bottom-grid { grid-template-columns: 1fr; }
}
@media (max-width: 600px) {
    .stat-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-label">Total Fleet</div>
        <div class="stat-value">{{ $stats['total_cars'] }}</div>
        <div class="stat-sub">{{ $stats['available_cars'] }} available</div>
        <i class="bi bi-car-front stat-icon"></i>
    </div>
    <div class="stat-card green">
        <div class="stat-label">Active Rentals</div>
        <div class="stat-value">{{ $stats['active_rentals'] }}</div>
        <div class="stat-sub">{{ $stats['active_rentals'] }} vehicle(s) out</div>
        <i class="bi bi-key stat-icon"></i>
    </div>
    <div class="stat-card">
        <div class="stat-label">Total Revenue</div>
        <div class="stat-value">₱{{ number_format($stats['total_revenue'], 0) }}</div>
        <div class="stat-sub">Paid transactions</div>
        <i class="bi bi-cash-coin stat-icon"></i>
    </div>
    <div class="stat-card red">
        <div class="stat-label">Pending Balance</div>
        <div class="stat-value">₱{{ number_format($stats['pending_balance'], 0) }}</div>
        <div class="stat-sub">To be collected</div>
        <i class="bi bi-hourglass-split stat-icon"></i>
    </div>
</div>

<div class="charts-grid">
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-bar-chart"></i> Fleet by Vehicle Type</div>
        </div>
        <div class="card-body">
            <canvas id="fleetChart" height="200"></canvas>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-pie-chart"></i> Vehicle Status</div>
        </div>
        <div class="card-body" style="display:flex;align-items:center;gap:24px;">
            <canvas id="statusChart" style="max-width:160px;max-height:160px;"></canvas>
            <div style="flex:1;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
                    <div style="width:12px;height:12px;border-radius:3px;background:#22C55E;"></div>
                    <span style="font-size:13px;color:var(--text-muted)">Available</span>
                    <span style="margin-left:auto;font-weight:700;color:#4ADE80;">{{ $stats['available_cars'] }}</span>
                </div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px;">
                    <div style="width:12px;height:12px;border-radius:3px;background:#38BDF8;"></div>
                    <span style="font-size:13px;color:var(--text-muted)">Rented</span>
                    <span style="margin-left:auto;font-weight:700;color:#7DD3FC;">{{ $stats['rented_cars'] }}</span>
                </div>
                <div style="display:flex;align-items:center;gap:8px;">
                    <div style="width:12px;height:12px;border-radius:3px;background:#F59E0B;"></div>
                    <span style="font-size:13px;color:var(--text-muted)">Maintenance</span>
                    <span style="margin-left:auto;font-weight:700;color:#FCD34D;">{{ $stats['maintenance_cars'] }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bottom-grid">
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="bi bi-clock-history"></i> Recent Transactions</div>
            <a href="{{ route('rentals.index') }}" class="btn btn-outline btn-sm">View All</a>
        </div>
        <div class="card-body" style="padding-top:0;padding-bottom:0;">
            @forelse($recentRentals as $rental)
                <div class="recent-item">
                    <div class="recent-item-icon"><i class="bi bi-person"></i></div>
                    <div class="recent-item-info">
                        <div class="recent-item-name">{{ $rental->customer_name }}</div>
                        <div class="recent-item-sub">
                            {{ $rental->car->brand ?? '' }} {{ $rental->car->model ?? '' }} &bull;
                            {{ $rental->start_date->format('M d') }} – {{ $rental->end_date->format('M d, Y') }}
                        </div>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;">
                        <div class="recent-item-amount">₱{{ number_format($rental->total_cost, 0) }}</div>
                        <span class="badge badge-{{ $rental->status_color }}">{{ ucfirst($rental->status) }}</span>
                    </div>
                </div>
            @empty
                <div style="padding:24px;text-align:center;color:var(--text-dim);">
                    <i class="bi bi-inbox" style="font-size:28px;display:block;margin-bottom:8px;"></i>
                    No transactions yet
                </div>
            @endforelse
        </div>
    </div>

    <div style="display:flex;flex-direction:column;gap:16px;">
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-lightning"></i> Quick Actions</div>
            </div>
            <div class="card-body">
                <a href="{{ route('rentals.create') }}" class="quick-action-btn primary">
                    <i class="bi bi-plus-circle-fill"></i> New Rental Booking
                </a>
                <a href="{{ route('cars.create') }}" class="quick-action-btn">
                    <i class="bi bi-car-front"></i> Add Vehicle
                </a>
                <a href="{{ route('rentals.calendar') }}" class="quick-action-btn">
                    <i class="bi bi-calendar3"></i> View Calendar
                </a>
                <a href="{{ route('rentals.mastersheet') }}" class="quick-action-btn">
                    <i class="bi bi-table"></i> Master Sheet
                </a>
                <a href="{{ route('quotes.index') }}" class="quick-action-btn">
                    <i class="bi bi-file-earmark-text"></i> Quote Requests
                    @if($stats['pending_quotes'] > 0)
                        <span style="margin-left:auto;background:var(--gold);color:var(--black);font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;">
                            {{ $stats['pending_quotes'] }}
                        </span>
                    @endif
                </a>
            </div>
        </div>

        @if($stats['pending_quotes'] > 0)
        <div class="alert alert-warning" style="margin-bottom:0;">
            <i class="bi bi-bell"></i>
            <strong>{{ $stats['pending_quotes'] }}</strong> pending quote(s) need attention.
            <a href="{{ route('quotes.index') }}" style="color:inherit;font-weight:700;margin-left:4px;">View →</a>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
const fleetData = @json($stats['fleet_by_type']);
new Chart(document.getElementById('fleetChart'), {
    type: 'bar',
    data: {
        labels: fleetData.map(d => d.label),
        datasets: [{
            data: fleetData.map(d => d.count),
            backgroundColor: '#FFB800',
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#888880' } },
            y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: '#888880', precision: 0 } }
        }
    }
});

new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Available', 'Rented', 'Maintenance'],
        datasets: [{
            data: [{{ $stats['available_cars'] }}, {{ $stats['rented_cars'] }}, {{ $stats['maintenance_cars'] }}],
            backgroundColor: ['#22C55E', '#38BDF8', '#F59E0B'],
            borderWidth: 0,
            hoverOffset: 4,
        }]
    },
    options: {
        responsive: true,
        cutout: '72%',
        plugins: { legend: { display: false } }
    }
});
</script>
@endpush