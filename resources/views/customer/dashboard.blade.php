@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">My Bookings</h1>

    <!-- Book New Vehicle Button -->
    <div class="mb-8">
        <a href="{{ route('book.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition">
            + Book a New Vehicle
        </a>
    </div>

    <!-- Pending Approvals -->
    @if(isset($pending) && $pending->count() > 0)
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Pending Approval ({{ $pending->count() }})</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($pending as $rental)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <span class="font-semibold text-gray-800">{{ $rental->car->brand }} {{ $rental->car->model }}</span>
                            <span class="px-2 py-1 bg-yellow-200 text-yellow-900 text-xs font-semibold rounded">Pending</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">{{ $rental->start_date->format('M d') }} - {{ $rental->end_date->format('M d, Y') }}</p>
                        <p class="text-lg font-bold text-gray-800 mb-3">${{ number_format($rental->total_cost, 2) }}</p>
                        <a href="{{ route('customer.rental-show', $rental) }}" class="text-blue-600 hover:underline text-sm">View Details</a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Approved Bookings -->
    @if(isset($approved) && $approved->count() > 0)
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Approved Bookings ({{ $approved->count() }})</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($approved as $rental)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <span class="font-semibold text-gray-800">{{ $rental->car->brand }} {{ $rental->car->model }}</span>
                            <span class="px-2 py-1 bg-green-200 text-green-900 text-xs font-semibold rounded">Approved</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">{{ $rental->start_date->format('M d') }} - {{ $rental->end_date->format('M d, Y') }}</p>
                        <p class="text-lg font-bold text-gray-800 mb-3">${{ number_format($rental->total_cost, 2) }}</p>
                        @if($rental->payment_status === 'unpaid')
                            <p class="text-xs text-orange-600 font-semibold mb-2">Payment due</p>
                        @endif
                        <a href="{{ route('customer.rental-show', $rental) }}" class="text-blue-600 hover:underline text-sm">View Details</a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Ongoing Rentals -->
    @if(isset($ongoing) && $ongoing->count() > 0)
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Currently Renting ({{ $ongoing->count() }})</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($ongoing as $rental)
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <span class="font-semibold text-gray-800">{{ $rental->car->brand }} {{ $rental->car->model }}</span>
                            <span class="px-2 py-1 bg-blue-200 text-blue-900 text-xs font-semibold rounded">Ongoing</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">{{ $rental->start_date->format('M d') }} - {{ $rental->end_date->format('M d, Y') }}</p>
                        <p class="text-lg font-bold text-gray-800 mb-3">${{ number_format($rental->total_cost, 2) }}</p>
                        <a href="{{ route('customer.rental-show', $rental) }}" class="text-blue-600 hover:underline text-sm">View Details</a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Completed Rentals -->
    @if(isset($completed) && $completed->count() > 0)
        <div class="mb-8">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Completed Rentals ({{ $completed->count() }})</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($completed as $rental)
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <div class="flex justify-between items-start mb-2">
                            <span class="font-semibold text-gray-800">{{ $rental->car->brand }} {{ $rental->car->model }}</span>
                            <span class="px-2 py-1 bg-gray-300 text-gray-900 text-xs font-semibold rounded">Completed</span>
                        </div>
                        <p class="text-sm text-gray-600 mb-2">{{ $rental->start_date->format('M d') }} - {{ $rental->end_date->format('M d, Y') }}</p>
                        <p class="text-lg font-bold text-gray-800 mb-3">${{ number_format($rental->total_cost, 2) }}</p>
                        <a href="{{ route('customer.rental-show', $rental) }}" class="text-blue-600 hover:underline text-sm">View Details</a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- No Bookings -->
    @if((!isset($pending) || $pending->count() === 0) && (!isset($approved) || $approved->count() === 0) && (!isset($ongoing) || $ongoing->count() === 0) && (!isset($completed) || $completed->count() === 0))
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-8 text-center">
            <p class="text-gray-600 text-lg mb-4">You don't have any bookings yet.</p>
            <a href="{{ route('book.create') }}" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition">
                Book Your First Vehicle
            </a>
        </div>
    @endif
</div>
@endsection
