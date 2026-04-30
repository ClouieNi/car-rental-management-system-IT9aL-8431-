@extends('layouts.app')

@section('title', 'Suppliers')
@section('page-title', 'Vehicle Suppliers')
@section('breadcrumb', 'Manage vehicle ownership and commission partners')

@section('content')

<!-- Header -->
<div class="page-header" style="margin-bottom: 16px;">
    <div>
        <h2 style="margin: 0;">Suppliers</h2>
        <small style="color: var(--text-muted);">{{ $suppliers->total() }} supplier{{ $suppliers->total() === 1 ? '' : 's' }} registered</small>
    </div>
    <a href="{{ route('suppliers.create') }}" class="btn btn-gold btn-sm">
        <i class="bi bi-plus-circle"></i> New Supplier
    </a>
</div>

<!-- Filter Bar -->
<div class="card" style="margin-bottom: 24px;">
    <form method="GET" class="filter-bar" style="flex-wrap: wrap;">
        <div class="filter-group" style="flex: 2;">
            <label>Search</label>
            <input type="text" name="search" class="form-control" value="{{ request('search') }}"
                   placeholder="Name, contact, email, phone...">
        </div>
        <div class="filter-group">
            <label>Type</label>
            <select name="type" class="form-control">
                <option value="">All Types</option>
                <option value="company-owned" {{ request('type') === 'company-owned' ? 'selected' : '' }}>Company-Owned</option>
                <option value="partner-owned" {{ request('type') === 'partner-owned' ? 'selected' : '' }}>Partner-Owned</option>
            </select>
        </div>
        <div class="filter-group">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="">All</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <button type="submit" class="btn btn-gold btn-sm">
            <i class="bi bi-funnel"></i> Filter
        </button>
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary btn-sm">Reset</a>
    </form>
</div>

<!-- Table -->
<div class="card" style="padding: 0; overflow-x: auto;">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Contact Person</th>
                <th>Contact Info</th>
                <th style="text-align: center;">Vehicles</th>
                <th style="text-align: center;">Commission</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suppliers as $supplier)
            <tr>
                <td>
                    <strong>{{ $supplier->name }}</strong>
                    @if($supplier->address)
                        <br><small style="color: var(--text-dim);"><i class="bi bi-geo-alt"></i> {{ $supplier->address }}</small>
                    @endif
                </td>
                <td>
                    @if($supplier->isCompanyOwned())
                        <span class="badge badge-success"><i class="bi bi-building"></i> Company</span>
                    @else
                        <span class="badge badge-info"><i class="bi bi-handshake"></i> Partner</span>
                    @endif
                </td>
                <td>{{ $supplier->contact_person ?? '—' }}</td>
                <td>
                    @if($supplier->phone)
                        <small><i class="bi bi-telephone"></i> {{ $supplier->phone }}</small><br>
                    @endif
                    @if($supplier->email)
                        <small style="color: var(--text-dim);"><i class="bi bi-envelope"></i> {{ $supplier->email }}</small>
                    @endif
                    @if(!$supplier->phone && !$supplier->email)
                        <small style="color: var(--text-dim);">—</small>
                    @endif
                </td>
                <td style="text-align: center;">
                    <span style="font-family: 'Bebas Neue', sans-serif; font-size: 20px; color: var(--gold); letter-spacing: 1px;">
                        {{ $supplier->cars_count }}
                    </span>
                </td>
                <td style="text-align: center;">
                    @if($supplier->isPartnerOwned() && $supplier->commission_rate)
                        <span style="color: #38BDF8; font-weight: 600;">{{ $supplier->commission_rate }}%</span>
                    @else
                        <span style="color: var(--text-dim);">—</span>
                    @endif
                </td>
                <td>
                    @if($supplier->is_active)
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-secondary">Inactive</span>
                    @endif
                </td>
                <td style="white-space: nowrap;">
                    <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-primary btn-sm" title="View">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning btn-sm" title="Edit">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST"
                          style="display: inline;" onsubmit="return confirm('Delete this supplier?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm" title="Delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr class="empty-row">
                <td colspan="8">
                    <div style="padding: 40px 20px; text-align: center; color: var(--text-muted);">
                        <i class="bi bi-people" style="font-size: 32px; display: block; margin-bottom: 8px; color: var(--gold);"></i>
                        No suppliers found. <a href="{{ route('suppliers.create') }}" style="color: var(--gold);">Add one!</a>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-container" style="margin-top: 24px;">
    {{ $suppliers->links() }}
</div>

@endsection
