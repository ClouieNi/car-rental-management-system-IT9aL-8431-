@extends('layouts.app')

@section('title', 'Prepare Rental - Quote #' . $quote->getQuoteIdDisplayAttribute())

@section('content')
<div class="p-6 lg:p-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('quotes.index') }}" class="text-gold hover:text-gold-light">Quotes</a>
                <span class="text-gray-600">/</span>
                <span class="text-gray-400">{{ $quote->getQuoteIdDisplayAttribute() }}</span>
                <span class="text-gray-600">/</span>
                <span class="text-gold">Prepare Rental</span>
            </div>
            <h1 class="text-3xl font-bold text-cream mb-2">Prepare Rental Transaction</h1>
            <p class="text-gray-400">Review and adjust details before converting this quote to a rental.</p>
        </div>

        @if(session('success'))
            <div class="bg-green-900/20 border border-green-500/30 rounded-xl p-4 mb-6">
                <p class="text-green-400"><i class="bi bi-check-circle mr-2"></i>{{ session('success') }}</p>
            </div>
        @endif

        <!-- Customer Info Card -->
        <div class="bg-dark-100 border border-white/10 rounded-xl p-6 mb-6">
            <h2 class="text-lg font-semibold text-cream mb-4 flex items-center gap-2">
                <i class="bi bi-person text-gold"></i>
                Customer Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Name</label>
                    <p class="text-cream">{{ $quote->guest_name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Email</label>
                    <p class="text-cream">{{ $quote->guest_email }}</p>
                    @if($quote->guest_email)
                        <span class="text-xs text-green-400 mt-1 block"><i class="bi bi-check-circle mr-1"></i>Account created - customer can log in to view this transaction</span>
                    @endif
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Phone</label>
                    <p class="text-cream">{{ $quote->guest_phone }}</p>
                </div>
            </div>
        </div>

        <!-- Rental Details Form -->
        <form action="{{ route('rentals.store-from-quote', $quote) }}" method="POST" class="bg-dark-100 border border-white/10 rounded-xl p-6 mb-6">
            @csrf

            <h2 class="text-lg font-semibold text-cream mb-4 flex items-center gap-2">
                <i class="bi bi-car-front text-gold"></i>
                Rental Details
            </h2>

            <!-- Vehicle Selection -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-400 mb-2">Selected Vehicle</label>
                <select name="car_id" class="w-full px-4 py-2 bg-dark-200 border border-white/10 rounded-lg text-cream focus:ring-2 focus:ring-gold focus:border-gold @error('car_id') border-red-500 @enderror">
                    @foreach($cars as $car)
                        <option value="{{ $car->id }}" {{ $quote->car_id == $car->id ? 'selected' : '' }}>
                            {{ $car->brand }} {{ $car->model }} ({{ $car->plate_number }}) - ${{ number_format($car->daily_rate, 2) }}/day
                        </option>
                    @endforeach
                </select>
                @error('car_id')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Start Date</label>
                    <input type="date" name="start_date" value="{{ old('start_date', $quote->start_date->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 bg-dark-200 border border-white/10 rounded-lg text-cream focus:ring-2 focus:ring-gold focus:border-gold @error('start_date') border-red-500 @enderror">
                    @error('start_date')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">End Date</label>
                    <input type="date" name="end_date" value="{{ old('end_date', $quote->end_date->format('Y-m-d')) }}"
                           class="w-full px-4 py-2 bg-dark-200 border border-white/10 rounded-lg text-cream focus:ring-2 focus:ring-gold focus:border-gold @error('end_date') border-red-500 @enderror">
                    @error('end_date')<p class="text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Rental Type & Destination -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Rental Type</label>
                    <select name="rental_type" class="w-full px-4 py-2 bg-dark-200 border border-white/10 rounded-lg text-cream focus:ring-2 focus:ring-gold focus:border-gold">
                        <option value="self_drive" {{ $quote->rental_type === 'self_drive' ? 'selected' : '' }}>Self Drive</option>
                        <option value="with_driver" {{ $quote->rental_type === 'with_driver' ? 'selected' : '' }}>With Driver</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Destination</label>
                    <input type="text" name="destination" value="{{ old('destination', $quote->destination) }}"
                           class="w-full px-4 py-2 bg-dark-200 border border-white/10 rounded-lg text-cream focus:ring-2 focus:ring-gold focus:border-gold">
                </div>
            </div>

            <!-- Distance & Notes -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Distance (km)</label>
                    <input type="number" name="distance_km" value="{{ old('distance_km', $quote->distance_km) }}" min="0"
                           class="w-full px-4 py-2 bg-dark-200 border border-white/10 rounded-lg text-cream focus:ring-2 focus:ring-gold focus:border-gold">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Admin Notes</label>
                    <textarea name="admin_notes" rows="2" class="w-full px-4 py-2 bg-dark-200 border border-white/10 rounded-lg text-cream focus:ring-2 focus:ring-gold focus:border-gold resize-none">{{ old('admin_notes', $quote->admin_remarks) }}</textarea>
                </div>
            </div>

            <!-- Pricing Summary -->
            <div class="bg-gold/10 border border-gold/20 rounded-xl p-4 mb-6">
                <h3 class="font-semibold text-cream mb-3">Pricing Summary</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Base Cost:</span>
                        <span class="font-medium text-cream">${{ number_format($quote->base_cost, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Distance Surcharge:</span>
                        <span class="font-medium text-cream">${{ number_format($quote->distance_surcharge, 2) }}</span>
                    </div>
                    <div class="border-t border-gold/30 pt-2 flex justify-between font-bold text-lg">
                        <span class="text-cream">Total Estimate:</span>
                        <span class="text-gold">${{ number_format($quote->total_estimate, 2) }}</span>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-3">*Final pricing can be adjusted after rental creation</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-gold hover:bg-gold-dark text-dark font-bold py-3 px-4 rounded-lg transition">
                    <i class="bi bi-check-circle mr-2"></i>
                    Create Rental Transaction
                </button>
                <a href="{{ route('quotes.index') }}" class="bg-gray-600 hover:bg-gray-500 text-cream font-bold py-3 px-4 rounded-lg transition">
                    Cancel
                </a>
            </div>
        </form>

        <!-- Original Quote Details -->
        <div class="bg-dark-100 border border-white/10 rounded-xl p-4 text-sm">
            <h3 class="font-semibold text-cream mb-3">Original Quote Details</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <span class="text-gray-500 block mb-1">Quote ID:</span>
                    <p class="text-cream">{{ $quote->getQuoteIdDisplayAttribute() }}</p>
                </div>
                <div>
                    <span class="text-gray-500 block mb-1">Created:</span>
                    <p class="text-cream">{{ $quote->created_at->format('M d, Y') }}</p>
                </div>
                <div>
                    <span class="text-gray-500 block mb-1">Status:</span>
                    <p class="font-semibold {{ $quote->status === 'accepted' ? 'text-green-400' : 'text-cream' }}">{{ ucfirst($quote->status) }}</p>
                </div>
                <div>
                    <span class="text-gray-500 block mb-1">Expires:</span>
                    <p class="text-cream">{{ $quote->expires_at ? $quote->expires_at->format('M d, Y H:i') : 'N/A' }}</p>
                </div>
            </div>
            @if($quote->guest_notes)
                <div class="mt-4 pt-4 border-t border-white/10">
                    <span class="text-gray-500 block mb-1">Customer Notes:</span>
                    <p class="text-gray-400 italic">{{ $quote->guest_notes }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
