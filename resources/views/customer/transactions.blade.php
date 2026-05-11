@extends('layouts.app')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-cream mb-2">My Transactions</h1>
        <p class="text-gray-400">View all your rental transactions and their status</p>
    </div>

    <!-- Filters and Action -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('customer.transactions') }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ !request('status') ? 'bg-gold text-dark' : 'bg-dark-100 text-gray-400 hover:text-cream border border-white/10' }}">
                All
            </a>
            <a href="{{ route('customer.transactions', ['status' => 'ongoing']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') === 'ongoing' ? 'bg-gold text-dark' : 'bg-dark-100 text-gray-400 hover:text-cream border border-white/10' }}">
                Ongoing
            </a>
            <a href="{{ route('customer.transactions', ['status' => 'reserved']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') === 'reserved' ? 'bg-gold text-dark' : 'bg-dark-100 text-gray-400 hover:text-cream border border-white/10' }}">
                Reserved
            </a>
            <a href="{{ route('customer.transactions', ['status' => 'completed']) }}"
               class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request('status') === 'completed' ? 'bg-gold text-dark' : 'bg-dark-100 text-gray-400 hover:text-cream border border-white/10' }}">
                Completed
            </a>
        </div>
        <a href="/quote" class="inline-flex items-center gap-2 bg-gold hover:bg-gold-dark text-dark font-bold py-2 px-4 rounded-lg transition text-sm">
            <i class="bi bi-plus-lg"></i>
            <span>Get a Quote</span>
        </a>
    </div>

    <!-- Transactions List -->
    <div class="space-y-4">
        @forelse($rentals as $rental)
            <div class="bg-dark-100 rounded-xl p-5 hover:bg-dark-100/80 transition-all">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <!-- Car Info -->
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 bg-dark-200 rounded-xl flex items-center justify-center">
                            <i class="bi bi-car-front text-gold text-2xl"></i>
                        </div>
                        <div>
                            <div class="font-semibold text-cream text-lg">{{ $rental->car->brand }} {{ $rental->car->model }}</div>
                            <div class="text-sm text-gray-500">{{ $rental->car->plate_number }}</div>
                        </div>
                    </div>

                    <!-- Dates -->
                    <div class="text-center md:text-left">
                        <div class="text-sm text-gray-400">Rental Period</div>
                        <div class="text-cream font-medium">
                            {{ $rental->start_date->format('M d, Y') }} - {{ $rental->end_date->format('M d, Y') }}
                        </div>
                        <div class="text-xs text-gray-500">{{ $rental->start_date->diffInDays($rental->end_date) }} days</div>
                    </div>

                    <!-- Status -->
                    @php
                        $statusColors = [
                            'reserved' => 'bg-blue-500/20 text-blue-500 border-blue-500/30',
                            'ongoing' => 'bg-gold/20 text-gold border-gold/30',
                            'completed' => 'bg-green-500/20 text-green-500 border-green-500/30',
                            'cancelled' => 'bg-red-500/20 text-red-500 border-red-500/30',
                        ];
                        $statusClass = $statusColors[$rental->status] ?? 'bg-gray-500/20 text-gray-400 border-gray-500/30';
                    @endphp
                    <div class="px-3 py-1.5 rounded-full border text-sm font-medium {{ $statusClass }}">
                        {{ ucfirst($rental->status) }}
                    </div>

                    <!-- Cost -->
                    <div class="text-right">
                        <div class="text-2xl font-bold text-cream">₱{{ number_format($rental->total_cost, 2) }}</div>
                        @php
                            $paymentColors = [
                                'paid' => 'text-green-500',
                                'partial' => 'text-yellow-500',
                                'unpaid' => 'text-red-500',
                            ];
                            $paymentClass = $paymentColors[$rental->payment_status] ?? 'text-gray-400';
                        @endphp
                        <div class="text-sm {{ $paymentClass }}">
                            {{ ucfirst($rental->payment_status) }}
                            @if($rental->payment_status !== 'paid')
                                @if($rental->amount_paid > 0)
                                    (Paid: ₱{{ number_format($rental->amount_paid, 2) }})
                                @endif
                            @endif
                        </div>
                    </div>

                    <!-- Action -->
                    <a href="{{ route('customer.rental-show', $rental) }}" 
                       class="inline-flex items-center gap-2 bg-dark-200 hover:bg-gold/20 border border-white/10 hover:border-gold/30 text-cream px-4 py-2 rounded-lg transition text-sm">
                        <span>View Details</span>
                        <i class="bi bi-arrow-right text-gold"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="bg-dark-100 border border-white/10 rounded-xl py-16 px-12 text-center">
                <div class="w-16 h-16 bg-dark-200 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="bi bi-receipt text-gray-500 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-cream mb-2">No transactions yet</h3>
                <p class="text-gray-500 mb-8">You haven't made any rental bookings yet.</p>
                <div class="h-4"></div>
            </div>
        @endforelse
    </div>

    <!-- Bottom Spacer -->
    <div class="h-6"></div>

    <!-- Pagination -->
    @if($rentals->hasPages())
        <div class="mt-6">
            {{ $rentals->links() }}
        </div>
    @endif
</div>
@endsection
