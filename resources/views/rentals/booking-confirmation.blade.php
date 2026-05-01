@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Success Message -->
        <div class="mb-8 p-6 bg-green-50 border border-green-200 rounded-lg text-center">
            <div class="text-5xl text-green-600 mb-4">✓</div>
            <h1 class="text-3xl font-bold text-green-900 mb-2">Booking Request Submitted!</h1>
            <p class="text-green-700">Your booking has been submitted successfully. Our staff will review and approve your booking within 24 hours.</p>
        </div>

        <!-- Booking Details -->
        <div class="bg-white rounded-lg shadow-md p-8 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Booking Reference: <span class="text-gold">{{ $rental->getRentalIdDisplayAttribute() }}</span></h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Vehicle Info -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-3">Vehicle</h3>
                    <div class="space-y-2">
                        <p><strong>Car:</strong> {{ $rental->car->brand }} {{ $rental->car->model }}</p>
                        <p><strong>Year:</strong> {{ $rental->car->year }}</p>
                        <p><strong>Plate:</strong> {{ $rental->car->plate_number }}</p>
                        <p><strong>Type:</strong> {{ ucfirst($rental->rental_type) }}</p>
                    </div>
                </div>

                <!-- Dates & Duration -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-3">Rental Period</h3>
                    <div class="space-y-2">
                        <p><strong>Check-In:</strong> {{ $rental->start_date->format('M d, Y') }}</p>
                        <p><strong>Check-Out:</strong> {{ $rental->end_date->format('M d, Y') }}</p>
                        <p><strong>Duration:</strong> {{ $rental->duration_days }} day{{ $rental->duration_days > 1 ? 's' : '' }}</p>
                        @if($rental->destination)
                            <p><strong>Destination:</strong> {{ $rental->destination }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Price Breakdown -->
            <div class="bg-gold-muted border border-gold/20 rounded-lg p-6 mb-8">
                <h3 class="font-semibold text-gray-800 mb-4">Cost Breakdown</h3>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span>Daily Rate:</span>
                        <span>${{ number_format($rental->car->daily_rate, 2) }}/day × {{ $rental->duration_days }} days</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Base Cost:</span>
                        <span>${{ number_format($rental->car->daily_rate * $rental->duration_days, 2) }}</span>
                    </div>
                    @if($rental->distance_km > 0)
                        <div class="flex justify-between">
                            <span>Distance Surcharge:</span>
                            <span>{{ $rental->distance_km }} km × $0.50 = ${{ number_format($rental->distance_surcharge, 2) }}</span>
                        </div>
                    @endif
                    <div class="border-t border-gold/30 pt-3 flex justify-between font-bold text-lg">
                        <span>Total Cost:</span>
                        <span>${{ number_format($rental->total_cost, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Status Info -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-8">
                <h3 class="font-semibold text-gray-800 mb-3">Current Status</h3>
                <p class="mb-2"><strong>Status:</strong> <span class="inline-block px-3 py-1 bg-yellow-200 text-yellow-900 rounded-full text-sm font-semibold">{{ ucfirst($rental->status) }}</span></p>
                <p class="text-gray-700">Your booking is awaiting staff approval. You will receive an email notification once it has been reviewed.</p>
            </div>

            <!-- Next Steps -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h3 class="font-semibold text-gray-800 mb-3">What Happens Next?</h3>
                <ol class="list-decimal list-inside space-y-2 text-gray-700">
                    <li>Our staff reviews your booking and driver information</li>
                    <li>You'll receive an email notification when your booking is approved</li>
                    <li>Payment will be due after your booking is approved</li>
                    <li>You'll receive rental details and pickup instructions before your rental date</li>
                </ol>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col gap-3">
            <a href="{{ route('customer.dashboard') }}" class="block text-center bg-gold hover:bg-gold-dark text-dark font-bold py-3 px-4 rounded-lg transition">
                View My Bookings
            </a>
            <a href="{{ route('book.create') }}" class="block text-center bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-4 rounded-lg transition">
                Book Another Vehicle
            </a>
        </div>
    </div>
</div>
@endsection
