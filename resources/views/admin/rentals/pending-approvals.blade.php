@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Pending Rental Approvals</h1>

    @if ($rentals->count() > 0)
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b border-gray-300">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Rental ID</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Customer</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Vehicle</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Dates</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Total Cost</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Driver License</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($rentals as $rental)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm font-semibold text-gold">
                                <a href="{{ route('rentals.show', $rental) }}" class="hover:underline">
                                    {{ $rental->getRentalIdDisplayAttribute() }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                <p class="font-semibold">{{ $rental->customer->name }}</p>
                                <p class="text-xs text-gray-600">{{ $rental->customer->email }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                <p>{{ $rental->car->brand }} {{ $rental->car->model }}</p>
                                <p class="text-xs text-gray-600">{{ $rental->car->plate_number }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-800">
                                <p>{{ $rental->start_date->format('M d') }} - {{ $rental->end_date->format('M d') }}</p>
                                <p class="text-xs text-gray-600">{{ $rental->duration_days }} day{{ $rental->duration_days > 1 ? 's' : '' }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-800">
                                ${{ number_format($rental->total_cost, 2) }}
                            </td>
                            <td class="px-6 py-4 text-sm">
                                @if($rental->driver && $rental->driver->license_file_path)
                                    <a href="{{ route('rentals.show', $rental) }}" class="text-gold hover:underline">
                                        View License
                                    </a>
                                @else
                                    <span class="text-gray-500">No file</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex gap-2">
                                    <form action="{{ route('rentals.approve', $rental) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs font-semibold transition">
                                            Approve
                                        </button>
                                    </form>
                                    <button type="button" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs font-semibold transition" onclick="openRejectModal({{ $rental->id }})">
                                        Reject
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $rentals->links() }}
        </div>
    @else
        <div class="bg-gold-muted border border-gold/20 rounded-lg p-8 text-center">
            <p class="text-gold text-lg">No pending rental approvals at the moment.</p>
        </div>
    @endif
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full mx-4">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Reject Rental Booking</h2>
        <form id="rejectForm" method="POST">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Rejection Reason</label>
                <textarea name="rejection_reason" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500" rows="4" placeholder="Please provide a reason for rejection..."></textarea>
            </div>

            <div class="flex gap-4">
                <button type="button" class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg transition" onclick="closeRejectModal()">
                    Cancel
                </button>
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition">
                    Reject
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openRejectModal(rentalId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = `/rentals/${rentalId}/reject`;
    modal.classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}

// Close modal on outside click
document.getElementById('rejectModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRejectModal();
    }
});
</script>
@endsection
