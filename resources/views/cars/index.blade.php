@extends('layouts.app')
@section('content')
<div class="page-header">
    <h2>Cars</h2>
    @if(auth()->check() && auth()->user()->role === 'admin')
        <a href="{{ route('cars.create') }}" class="btn btn-primary btn-sm">+ Add Car</a>
    @endif
</div>
<div class="card">
    <table>
        <thead>
            <tr>
                <th>#</th><th>Plate No.</th><th>Brand</th><th>Model</th>
                <th>Supplier</th><th>Daily Rate</th><th>Status</th><th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cars as $car)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ $car->plate_number }}</strong></td>
                <td>{{ $car->brand }}</td>
                <td>{{ $car->model }}</td>
                <td>
                    @if($car->supplier)
                        {{ $car->supplier->name }}<br>
                        {!! $car->ownership_badge !!}
                    @else
                        <span class="badge badge-secondary">No Supplier</span>
                    @endif
                </td>
                <td>&#8369;{{ number_format($car->daily_rate, 2) }}</td>
                <td><span class="badge badge-{{ $car->status }}">{{ ucfirst($car->status) }}</span></td>
                <td style="display:flex; gap:6px;">
                    @if(auth()->check() && auth()->user()->role === 'admin')
                        <a href="{{ route('cars.edit', $car) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('cars.destroy', $car) }}" method="POST"
                              onsubmit="return confirm('Delete this car?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    @else
                        <span style="color:#9ca3af; font-size:0.85rem;">View only</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr class="empty-row"><td colspan="8">No cars found. Add one!</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection