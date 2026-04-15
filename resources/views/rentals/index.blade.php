@extends('layouts.app')
@section('content')
<div class="page-header">
    <h2>Rentals</h2>
    <a href="{{ route('rentals.create') }}" class="btn btn-success btn-sm">+ New Rental</a>
</div>
<div class="card">
    <table>
        <thead>
            <tr>
                <th>#</th><th>Customer</th><th>Car</th><th>Start</th>
                <th>End</th><th>Total</th><th>Status</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rentals as $rental)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $rental->customer_name }}</td>
                <td>
                    {{ $rental->car->brand }} {{ $rental->car->model }}<br>
                    <small style="color:#9ca3af;">{{ $rental->car->plate_number }}</small>
                </td>
                <td>{{ $rental->start_date }}</td>
                <td>{{ $rental->end_date }}</td>
                <td>&#8369;{{ number_format($rental->total_cost, 2) }}</td>
                <td><span class="badge badge-{{ $rental->status }}">{{ ucfirst($rental->status) }}</span></td>
                <td style="display:flex; gap:6px;">
                    <a href="{{ route('rentals.edit', $rental) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('rentals.destroy', $rental) }}" method="POST"
                          onsubmit="return confirm('Delete this rental?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr class="empty-row"><td colspan="8">No rentals found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection