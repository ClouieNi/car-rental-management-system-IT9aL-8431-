@extends('layouts.app')

@section('title', 'Suppliers')
@section('page-title', 'Vehicle Suppliers')
@section('breadcrumb', 'Manage vehicle ownership and commission partners')

@section('content')

<div class="page-header">
    <h2>Suppliers</h2>
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-sm">+ New Supplier</a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Type</th>
                <th>Commission</th>
                <th>Vehicles</th>
                <th>Contact</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($suppliers as $supplier)
            <tr>
                <td><strong>{{ $supplier->name }}</strong></td>
                <td>
                    @if($supplier->isCompanyOwned())
                        <span class="badge badge-success">Company</span>
                    @else
                        <span class="badge badge-info">Partner</span>
                    @endif
                </td>
                <td>{{ $supplier->commission_rate_display }}</td>
                <td>{{ $supplier->cars_count }}</td>
                <td>
                    @if($supplier->phone)
                        <small>{{ $supplier->phone }}</small><br>
                    @endif
                    @if($supplier->email)
                        <small style="color:#9ca3af;">{{ $supplier->email }}</small>
                    @endif
                </td>
                <td>
                    @if($supplier->is_active)
                        <span class="badge badge-success">Active</span>
                    @else
                        <span class="badge badge-secondary">Inactive</span>
                    @endif
                </td>
                <td style="display:flex; gap:6px;">
                    <a href="{{ route('suppliers.show', $supplier) }}" class="btn btn-primary btn-sm">View</a>
                    <a href="{{ route('suppliers.edit', $supplier) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST"
                          onsubmit="return confirm('Delete this supplier?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr class="empty-row"><td colspan="7">No suppliers found. Add one!</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="pagination-container">
    {{ $suppliers->links() }}
</div>

@endsection
