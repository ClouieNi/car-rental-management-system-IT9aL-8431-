@extends('layouts.app')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-cream mb-2">Welcome back, {{ auth()->user()->name }}</h1>
        <p class="text-gray-400">Here's an overview of your account activity</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Active Rentals -->
        <div class="bg-dark-100 border border-white/10 rounded-xl p-5 hover:border-gold/30 transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-gold/10 rounded-lg flex items-center justify-center">
                    <i class="bi bi-car-front text-gold text-lg"></i>
                </div>
                @if($activeRentalsCount > 0)
                    <span class="px-2 py-1 bg-gold/20 text-gold text-xs font-semibold rounded">{{ $activeRentalsCount }} Active</span>
                @endif
            </div>
            <div class="text-2xl font-bold text-cream">{{ $totalRentalsCount }}</div>
            <div class="text-sm text-gray-500">Total Rentals</div>
        </div>

        <!-- Pending Quotes -->
        <div class="bg-dark-100 border border-white/10 rounded-xl p-5 hover:border-gold/30 transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-yellow-500/10 rounded-lg flex items-center justify-center">
                    <i class="bi bi-file-earmark-text text-yellow-500 text-lg"></i>
                </div>
                @if($pendingQuotesCount > 0)
                    <span class="px-2 py-1 bg-yellow-500/20 text-yellow-500 text-xs font-semibold rounded">{{ $pendingQuotesCount }} Pending</span>
                @endif
            </div>
            <div class="text-2xl font-bold text-cream">{{ $recentQuotes->count() }}</div>
            <div class="text-sm text-gray-500">Total Quotes</div>
        </div>

        <!-- Unread Messages -->
        <div class="bg-dark-100 border border-white/10 rounded-xl p-5 hover:border-gold/30 transition-all">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-blue-500/10 rounded-lg flex items-center justify-center">
                    <i class="bi bi-chat-square-text text-blue-500 text-lg"></i>
                </div>
                @if($unreadMessagesCount > 0)
                    <span class="px-2 py-1 bg-blue-500/20 text-blue-500 text-xs font-semibold rounded">{{ $unreadMessagesCount }} New</span>
                @endif
            </div>
            <div class="text-2xl font-bold text-cream">{{ $unreadMessagesCount }}</div>
            <div class="text-sm text-gray-500">Unread Messages</div>
        </div>

        <!-- Quick Action -->
        <a href="/landing" class="bg-gradient-to-br from-gold/20 to-gold/5 border border-gold/30 rounded-xl p-5 hover:border-gold/50 transition-all group">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-gold/20 rounded-lg flex items-center justify-center group-hover:bg-gold/30 transition-all">
                    <i class="bi bi-plus-lg text-gold text-lg"></i>
                </div>
                <i class="bi bi-arrow-right text-gold"></i>
            </div>
            <div class="text-lg font-bold text-cream">Get a Quote</div>
            <div class="text-sm text-gray-400">Request a new rental</div>
        </a>
    </div>

    <!-- Recent Activity Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Quotes -->
        <div class="bg-dark-100 border border-white/10 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-cream">Recent Quotes</h2>
                <a href="/landing" class="text-sm text-gold hover:text-gold-light">Get New Quote</a>
            </div>

            @if($recentQuotes->count() > 0)
                <div class="space-y-3">
                    @foreach($recentQuotes as $quote)
                        <div class="flex items-center justify-between p-3 bg-dark-200/50 rounded-lg border border-white/5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-dark-200 rounded-lg flex items-center justify-center">
                                    <i class="bi bi-car-front text-gray-400"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-cream">{{ $quote->car->brand }} {{ $quote->car->model }}</div>
                                    <div class="text-sm text-gray-500">{{ $quote->created_at->format('M d, Y') }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-cream">${{ number_format($quote->total_estimate, 0) }}</div>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-500/20 text-yellow-500',
                                        'accepted' => 'bg-green-500/20 text-green-500',
                                        'rejected' => 'bg-red-500/20 text-red-500',
                                        'sent' => 'bg-blue-500/20 text-blue-500',
                                        'expired' => 'bg-gray-500/20 text-gray-400',
                                        'converted' => 'bg-gold/20 text-gold',
                                    ];
                                    $statusClass = $statusColors[$quote->status] ?? 'bg-gray-500/20 text-gray-400';
                                @endphp
                                <span class="px-2 py-0.5 text-xs font-medium rounded {{ $statusClass }}">
                                    {{ ucfirst($quote->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-12 h-12 bg-dark-200 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="bi bi-inbox text-gray-500 text-xl"></i>
                    </div>
                    <p class="text-gray-500 mb-4">No quotes yet</p>
                    <a href="/landing" class="inline-block bg-gold hover:bg-gold-dark text-dark font-bold py-2 px-4 rounded-lg transition text-sm">
                        Request a Quote
                    </a>
                </div>
            @endif
        </div>

        <!-- Recent Rentals -->
        <div class="bg-dark-100 border border-white/10 rounded-xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-cream">Recent Rentals</h2>
                <a href="{{ route('customer.transactions') }}" class="text-sm text-gold hover:text-gold-light">View All</a>
            </div>

            @if($recentRentals->count() > 0)
                <div class="space-y-3">
                    @foreach($recentRentals as $rental)
                        <div class="flex items-center justify-between p-3 bg-dark-200/50 rounded-lg border border-white/5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-dark-200 rounded-lg flex items-center justify-center">
                                    <i class="bi bi-car-front text-gray-400"></i>
                                </div>
                                <div>
                                    <div class="font-medium text-cream">{{ $rental->car->brand }} {{ $rental->car->model }}</div>
                                    <div class="text-sm text-gray-500">{{ $rental->start_date->format('M d') }} - {{ $rental->end_date->format('M d') }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-semibold text-cream">${{ number_format($rental->total_cost, 0) }}</div>
                                @php
                                    $rentalStatusColors = [
                                        'reserved' => 'bg-blue-500/20 text-blue-500',
                                        'ongoing' => 'bg-gold/20 text-gold',
                                        'completed' => 'bg-green-500/20 text-green-500',
                                        'cancelled' => 'bg-red-500/20 text-red-500',
                                    ];
                                    $rentalStatusClass = $rentalStatusColors[$rental->status] ?? 'bg-gray-500/20 text-gray-400';
                                @endphp
                                <span class="px-2 py-0.5 text-xs font-medium rounded {{ $rentalStatusClass }}">
                                    {{ ucfirst($rental->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-12 h-12 bg-dark-200 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="bi bi-car-front text-gray-500 text-xl"></i>
                    </div>
                    <p class="text-gray-500 mb-4">No rentals yet</p>
                    <p class="text-sm text-gray-600">Your approved quotes will appear here</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 flex flex-wrap gap-3">
        <a href="{{ route('customer.messages') }}" class="inline-flex items-center gap-2 bg-dark-100 hover:bg-dark-200 border border-white/10 text-cream px-4 py-2.5 rounded-lg transition">
            <i class="bi bi-chat-square-text text-gold"></i>
            <span>Contact Support</span>
        </a>
    </div>
</div>
@endsection
