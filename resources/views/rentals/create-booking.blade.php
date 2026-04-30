@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Book a Vehicle</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <h3 class="text-red-900 font-semibold mb-2">Please fix the following errors:</h3>
                <ul class="list-disc list-inside text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('book.store') }}" method="POST" enctype="multipart/form-data" id="bookingForm" class="bg-white rounded-lg shadow-md p-8">
            @csrf

            <!-- Vehicle Selection -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Select Vehicle</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($cars as $car)
                        <label class="relative">
                            <input type="radio" name="car_id" value="{{ $car->id }}" class="sr-only car-selector" required>
                            <div class="border-2 border-gray-200 rounded-lg p-4 cursor-pointer hover:border-blue-500 transition" id="car-{{ $car->id }}">
                                @if($car->image_path)
                                    <img src="{{ asset('storage/' . $car->image_path) }}" alt="{{ $car->brand }} {{ $car->model }}" class="w-full h-40 object-cover rounded mb-2">
                                @else
                                    <div class="w-full h-40 bg-gray-200 rounded mb-2 flex items-center justify-center text-gray-400">No image</div>
                                @endif
                                <h3 class="font-bold text-lg">{{ $car->brand }} {{ $car->model }}</h3>
                                <p class="text-sm text-gray-600">{{ $car->year }} • {{ ucfirst($car->vehicle_type) }}</p>
                                <p class="text-sm text-gray-600">Seats: {{ $car->seating_capacity }}</p>
                                <p class="text-blue-600 font-semibold mt-2">${{ number_format($car->daily_rate, 2) }}/day</p>
                            </div>
                        </label>
                    @empty
                        <p class="text-gray-600 col-span-full">No available vehicles at the moment.</p>
                    @endforelse
                </div>
                @error('car_id')<p class="text-red-600 text-sm mt-2">{{ $message }}</p>@enderror
            </div>

            <!-- Dates and Duration -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Check-In Date *</label>
                    <input type="date" name="start_date" id="start_date" min="{{ today()->format('Y-m-d') }}" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
                    @error('start_date')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Check-Out Date *</label>
                    <input type="date" name="end_date" id="end_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
                    @error('end_date')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Duration</label>
                    <input type="text" id="duration" disabled class="w-full px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg" placeholder="-- days" />
                </div>
            </div>

            <!-- Rental Type -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rental Type *</label>
                <div class="flex gap-4">
                    <label class="flex items-center">
                        <input type="radio" name="rental_type" value="self_drive" checked required class="mr-2" />
                        <span>Self Drive</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="rental_type" value="with_driver" required class="mr-2" />
                        <span>With Driver</span>
                    </label>
                </div>
                @error('rental_type')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Destination and Distance -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Destination (Optional)</label>
                    <input type="text" name="destination" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="e.g., Manila" />
                    @error('destination')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Distance (km - Optional)</label>
                    <input type="number" name="distance_km" min="0" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="0" />
                    @error('distance_km')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Driver License Information -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Driver License Information</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">License Number *</label>
                        <input type="text" name="license_number" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="e.g., D12-34-567890" />
                        @error('license_number')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">License Expiry Date *</label>
                        <input type="date" name="license_expiry" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" />
                        @error('license_expiry')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload License Copy (PDF/Image) *</label>
                    <input type="file" name="license_file" accept=".pdf,.jpg,.jpeg,.png" required class="w-full px-4 py-2 border border-gray-300 rounded-lg" />
                    <p class="text-xs text-gray-600 mt-1">Accepted formats: PDF, JPG, PNG (Max 5MB)</p>
                    @error('license_file')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Additional Notes -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Additional Notes (Optional)</label>
                <textarea name="customer_notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Any special requests or notes..."></textarea>
                @error('customer_notes')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>

            <!-- Price Summary -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Price Summary</h2>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span>Daily Rate:</span>
                        <span id="dailyRate">$0.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Duration:</span>
                        <span id="durationDays">0 days</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Base Cost:</span>
                        <span id="baseCost">$0.00</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Distance Surcharge:</span>
                        <span id="surcharge">$0.00</span>
                    </div>
                    <div class="border-t border-blue-200 pt-2 mt-2 flex justify-between font-bold text-lg">
                        <span>Total Cost:</span>
                        <span id="totalCost">$0.00</span>
                    </div>
                </div>
                <p class="text-xs text-gray-600 mt-4">*Final payment will be collected after your booking is approved by our staff.</p>
            </div>

            <!-- Submit -->
            <div class="flex gap-4">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition">
                    Submit Booking Request
                </button>
                <a href="{{ route('book.create') }}" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-3 px-4 rounded-lg transition text-center">
                    Clear Form
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const carSelectors = document.querySelectorAll('.car-selector');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const distanceInput = document.querySelector('input[name="distance_km"]');
    
    function updatePrice() {
        const selectedCar = document.querySelector('.car-selector:checked');
        if (!selectedCar || !startDateInput.value || !endDateInput.value) {
            return;
        }

        const carId = selectedCar.value;
        const carCard = document.querySelector(`#car-${carId}`);
        const dailyRateText = carCard.querySelector('p.text-blue-600').textContent;
        const dailyRate = parseFloat(dailyRateText.match(/\$([0-9.]+)/)[1]);

        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        const days = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) || 1;
        const distance = parseInt(distanceInput.value) || 0;
        const surcharge = distance * 0.50;
        const baseCost = dailyRate * days;
        const totalCost = baseCost + surcharge;

        document.getElementById('duration').value = days + ' day' + (days > 1 ? 's' : '');
        document.getElementById('durationDays').textContent = days + ' day' + (days > 1 ? 's' : '');
        document.getElementById('dailyRate').textContent = '$' + dailyRate.toFixed(2);
        document.getElementById('baseCost').textContent = '$' + baseCost.toFixed(2);
        document.getElementById('surcharge').textContent = '$' + surcharge.toFixed(2);
        document.getElementById('totalCost').textContent = '$' + totalCost.toFixed(2);
    }

    carSelectors.forEach(selector => {
        selector.addEventListener('change', updatePrice);
        const carCard = selector.closest('label').querySelector('div');
        carCard.addEventListener('click', function() {
            selector.checked = true;
            selector.dispatchEvent(new Event('change'));
        });
    });

    startDateInput.addEventListener('change', updatePrice);
    endDateInput.addEventListener('change', updatePrice);
    distanceInput.addEventListener('change', updatePrice);
});
</script>
@endsection
