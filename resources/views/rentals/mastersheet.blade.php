@extends('layouts.app')

@section('title', 'Mastersheet')
@section('page-title', 'Rental Mastersheet')
@section('breadcrumb', 'Complete rental transaction overview')

@section('content')

<!-- Filter Bar -->
<div class="card" style="margin-bottom: 24px;">
    <form method="GET" class="filter-bar" style="flex-wrap: wrap;">
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
                <option value="">All Status</option>
                @foreach(['pending','approved','reserved','ongoing','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label>Payment</label>
            <select name="payment_status" class="form-control">
                <option value="">All Payment</option>
                @foreach(['unpaid','partial','paid'] as $p)
                    <option value="{{ $p }}" {{ request('payment_status') === $p ? 'selected' : '' }}>{{ ucfirst($p) }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-group">
            <label>Supplier</label>
            <select name="supplier_id" class="form-control">
                <option value="">All Suppliers</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->name }} ({{ $supplier->isCompanyOwned() ? 'Company' : 'Partner' }})
                    </option>
                @endforeach
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
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px;">
        <div class="total-card">
            <div class="total-label">Company Revenue</div>
            <div class="total-value" style="color: #4ADE80;">₱{{ number_format($totals['company_revenue'], 2) }}</div>
        </div>
        <div class="total-card">
            <div class="total-label">Partner Revenue</div>
            <div class="total-value" style="color: #38BDF8;">₱{{ number_format($totals['partner_revenue'], 2) }}</div>
        </div>
        <div class="total-card">
            <div class="total-label">Commission Paid Out</div>
            <div class="total-value" style="color: #F87171;">₱{{ number_format($totals['total_commission'], 2) }}</div>
        </div>
        <div class="total-card" style="border-color: var(--gold);">
            <div class="total-label">Your Net Earnings</div>
            <div class="total-value" style="color: var(--gold);">₱{{ number_format($totals['net_revenue'], 2) }}</div>
        </div>
    </div>
    <div style="margin-top: 16px; padding: 12px 16px; background: rgba(255,184,0,0.05); border-radius: 6px; border: 1px solid rgba(255,184,0,0.1);">
        <small style="color: var(--text-muted); line-height: 1.5;">
            <i class="bi bi-info-circle" style="color: var(--gold); margin-right: 4px;"></i>
            <strong>Net Earnings</strong> = Company Revenue + (Partner Revenue − Commission Paid to Partners).
            For company-owned vehicles you keep 100%. For partner vehicles the partner's commission rate is deducted.
        </small>
    </div>
</div>

<!-- Transactions Table -->
<div class="card" style="padding: 0; overflow-x: auto;">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Car</th>
                <th>Supplier</th>
                <th>Dates</th>
                <th style="text-align: right;">Total Cost</th>
                <th style="text-align: center;">Commission Rate</th>
                <th style="text-align: right;">Commission Amt</th>
                <th style="text-align: right;">Net Earnings</th>
                <th>Payment</th>
                <th>Status</th>
            </tr>
            <!-- Filter Row -->
            <tr style="background: rgba(255,184,0,0.05);">
                <td style="padding: 8px;"></td>
                <td style="padding: 8px;">
                    <input type="text" name="customer_filter" placeholder="Filter customer..." value="{{ request('customer_filter') }}"
                           style="width: 100%; padding: 4px 8px; font-size: 12px; background: var(--dark-200); border: 1px solid var(--border); border-radius: 4px; color: var(--cream);">
                </td>
                <td style="padding: 8px;">
                    <input type="text" name="car_filter" placeholder="Filter car..." value="{{ request('car_filter') }}"
                           style="width: 100%; padding: 4px 8px; font-size: 12px; background: var(--dark-200); border: 1px solid var(--border); border-radius: 4px; color: var(--cream);">
                </td>
                <td style="padding: 8px;">
                    <select name="supplier_filter" style="width: 100%; padding: 4px 8px; font-size: 12px; background: var(--dark-200); border: 1px solid var(--border); border-radius: 4px; color: var(--cream);">
                        <option value="">All</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ request('supplier_filter') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </td>
                <td style="padding: 8px;"></td>
                <td style="padding: 8px;"></td>
                <td style="padding: 8px;"></td>
                <td style="padding: 8px;"></td>
                <td style="padding: 8px;"></td>
                <td style="padding: 8px;">
                    <select name="payment_filter" style="width: 100%; padding: 4px 8px; font-size: 12px; background: var(--dark-200); border: 1px solid var(--border); border-radius: 4px; color: var(--cream);">
                        <option value="">All</option>
                        <option value="paid" {{ request('payment_filter') === 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="partial" {{ request('payment_filter') === 'partial' ? 'selected' : '' }}>Partial</option>
                        <option value="unpaid" {{ request('payment_filter') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    </select>
                </td>
                <td style="padding: 8px;">
                    <select name="status_filter" style="width: 100%; padding: 4px 8px; font-size: 12px; background: var(--dark-200); border: 1px solid var(--border); border-radius: 4px; color: var(--cream);">
                        <option value="">All</option>
                        <option value="reserved" {{ request('status_filter') === 'reserved' ? 'selected' : '' }}>Reserved</option>
                        <option value="ongoing" {{ request('status_filter') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="completed" {{ request('status_filter') === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status_filter') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </td>
            </tr>
        </thead>
        <tbody>
            <script>
                // Auto-submit filter form when any filter input changes
                document.querySelectorAll('input[name^="customer_filter"], input[name^="car_filter"], select[name^="supplier_filter"], select[name^="payment_filter"], select[name^="status_filter"]').forEach(input => {
                    input.addEventListener('change', function() {
                        // Build URL with filter parameters
                        const params = new URLSearchParams(window.location.search);
                        if (this.value) {
                            params.set(this.name, this.value);
                        } else {
                            params.delete(this.name);
                        }
                        window.location.href = window.location.pathname + '?' + params.toString();
                    });
                });
            </script>
            @forelse($rentals as $rental)
            @php
                $isPartner = $rental->car && $rental->car->supplier && $rental->car->supplier->isPartnerOwned();
                $commissionRate = $isPartner ? ($rental->car->supplier->commission_rate ?? 0) : 0;
                $commissionAmt = $isPartner ? $rental->car->supplier->calculateCommission($rental->total_cost) : 0;
                $netEarnings = $rental->total_cost - $commissionAmt;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ $rental->customer_name }}</strong></td>
                <td>
                    <strong>{{ $rental->car->brand }} {{ $rental->car->model }}</strong><br>
                    <small style="color: var(--text-dim);">{{ $rental->car->plate_number }}</small>
                </td>
                <td>
                    @if($rental->car && $rental->car->supplier)
                        {{ $rental->car->supplier->name }}<br>
                        @if($isPartner)
                            <span class="badge badge-info" style="font-size: 10px;">Partner</span>
                        @else
                            <span class="badge badge-success" style="font-size: 10px;">Company</span>
                        @endif
                    @else
                        <small style="color: var(--text-dim);">—</small>
                    @endif
                </td>
                <td>
                    <small>{{ $rental->start_date->format('M d') }} – {{ $rental->end_date->format('M d, Y') }}</small>
                </td>
                <td style="text-align: right; font-weight: 600;">₱{{ number_format($rental->total_cost, 2) }}</td>
                <td style="text-align: center;">
                    @if($isPartner)
                        <span style="color: #38BDF8; font-weight: 600;">{{ $commissionRate }}%</span>
                    @else
                        <span style="color: var(--text-dim);">0%</span>
                    @endif
                </td>
                <td style="text-align: right;">
                    @if($commissionAmt > 0)
                        <span style="color: #F87171; font-weight: 600;">₱{{ number_format($commissionAmt, 2) }}</span>
                    @else
                        <span style="color: var(--text-dim);">₱0.00</span>
                    @endif
                </td>
                <td style="text-align: right;">
                    <span style="color: #4ADE80; font-weight: 700;">₱{{ number_format($netEarnings, 2) }}</span>
                </td>
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
                <td><span class="badge badge-{{ $rental->status_color }}">{{ ucfirst($rental->status) }}</span></td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="11">No rentals found for the selected criteria.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection
