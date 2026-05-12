<!DOCTYPE html>
<html lang="en" x-data="{ sidebarOpen: false, dropdownOpen: false }" :class="{ 'overflow-hidden': sidebarOpen }">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Cars ni Bai') — Rental Management</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Flatpickr Date Picker -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    
    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>
<body class="font-sans text-sm text-cream bg-dark antialiased">
<div class="flex min-h-screen" x-cloak>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/60 z-40 lg:hidden"
         @click="sidebarOpen = false">
    </div>

    <!-- Sidebar -->
    <aside class="fixed inset-y-0 left-0 z-50 w-60 bg-dark-100 border-r border-gold/15 flex flex-col transition-transform duration-250 ease-in-out"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
        <!-- Logo -->
        <div class="p-5 pb-4 border-b border-white/5">
            <div class="font-display text-2xl tracking-wider text-gold">Cars ni Bai</div>
            <div class="text-[10px] text-gray-500 uppercase tracking-widest">Rental Management</div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 py-4 overflow-y-auto">
            @if(auth()->user()->role === 'customer')
                {{-- CUSTOMER NAVIGATION --}}
                <div class="px-5 py-2 text-[10px] font-semibold text-gray-600 uppercase tracking-widest">Overview</div>
                <a href="{{ route('customer.dashboard') }}"
                   class="flex items-center gap-2.5 px-5 py-2.5 text-gray-400 hover:text-cream hover:bg-gold/10 transition-all border-l-[3px] border-transparent {{ request()->routeIs('customer.dashboard') ? 'text-gold bg-gold/10 border-gold' : '' }}">
                    <i class="bi bi-grid-1x2 text-base w-5"></i> Dashboard
                </a>

                <div class="px-5 py-2 mt-2 text-[10px] font-semibold text-gray-600 uppercase tracking-widest">My Account</div>
                <a href="{{ route('customer.transactions') }}"
                   class="flex items-center gap-2.5 px-5 py-2.5 text-gray-400 hover:text-cream hover:bg-gold/10 transition-all border-l-[3px] border-transparent {{ request()->routeIs('customer.transactions') ? 'text-gold bg-gold/10 border-gold' : '' }}">
                    <i class="bi bi-receipt text-base w-5"></i> Transactions
                    @php $myActiveRentals = \App\Models\Rental::forCustomer(auth()->id())->active()->count(); @endphp
                    @if($myActiveRentals > 0)
                        <span class="ml-auto bg-gold text-dark text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full">{{ $myActiveRentals }}</span>
                    @endif
                </a>
                <a href="{{ route('customer.messages') }}"
                   class="flex items-center gap-2.5 px-5 py-2.5 text-gray-400 hover:text-cream hover:bg-gold/10 transition-all border-l-[3px] border-transparent {{ request()->routeIs('customer.messages') ? 'text-gold bg-gold/10 border-gold' : '' }}">
                    <i class="bi bi-chat-square-text text-base w-5"></i> Messages
                    @php $myUnread = \App\Models\CustomerMessage::where('user_id', auth()->id())->whereNotNull('admin_reply')->where('is_read', false)->count(); @endphp
                    @if($myUnread > 0)
                        <span class="ml-auto bg-gold text-dark text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full">{{ $myUnread }}</span>
                    @endif
                </a>

                <div class="px-5 py-2 mt-2 text-[10px] font-semibold text-gray-600 uppercase tracking-widest">Settings</div>
                <a href="{{ route('customer.profile') }}"
                   class="flex items-center gap-2.5 px-5 py-2.5 text-gray-400 hover:text-cream hover:bg-gold/10 transition-all border-l-[3px] border-transparent {{ request()->routeIs('customer.profile') ? 'text-gold bg-gold/10 border-gold' : '' }}">
                    <i class="bi bi-person text-base w-5"></i> Profile
                </a>
            @else
                {{-- ADMIN NAVIGATION --}}
                <div class="px-5 py-2 text-[10px] font-semibold text-gray-600 uppercase tracking-widest">Overview</div>
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-2.5 px-5 py-2.5 text-gray-400 hover:text-cream hover:bg-gold/10 transition-all border-l-[3px] border-transparent {{ request()->routeIs('dashboard') ? 'text-gold bg-gold/10 border-gold' : '' }}">
                    <i class="bi bi-grid-1x2 text-base w-5"></i> Dashboard
                </a>

                <div class="px-5 py-2 mt-2 text-[10px] font-semibold text-gray-600 uppercase tracking-widest">Fleet</div>
                <a href="{{ route('cars.index') }}"
                   class="flex items-center gap-2.5 px-5 py-2.5 text-gray-400 hover:text-cream hover:bg-gold/10 transition-all border-l-[3px] border-transparent {{ request()->routeIs('cars.*') ? 'text-gold bg-gold/10 border-gold' : '' }}">
                    <i class="bi bi-car-front text-base w-5"></i> Fleet Management
                </a>
                <a href="{{ route('suppliers.index') }}"
                   class="flex items-center gap-2.5 px-5 py-2.5 text-gray-400 hover:text-cream hover:bg-gold/10 transition-all border-l-[3px] border-transparent {{ request()->routeIs('suppliers.*') ? 'text-gold bg-gold/10 border-gold' : '' }}">
                    <i class="bi bi-building text-base w-5"></i> Suppliers
                </a>

                <div class="px-5 py-2 mt-2 text-[10px] font-semibold text-gray-600 uppercase tracking-widest">Rentals</div>
                <a href="{{ route('rentals.index') }}"
                   class="flex items-center gap-2.5 px-5 py-2.5 text-gray-400 hover:text-cream hover:bg-gold/10 transition-all border-l-[3px] border-transparent {{ request()->routeIs('rentals.index') ? 'text-gold bg-gold/10 border-gold' : '' }}">
                    <i class="bi bi-receipt text-base w-5"></i> Transactions
                    @php $activeCount = \App\Models\Rental::active()->count(); @endphp
                    @if($activeCount > 0)
                        <span class="ml-auto bg-gold text-dark text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full">{{ $activeCount }}</span>
                    @endif
                </a>
                <a href="{{ route('rentals.create') }}"
                   class="flex items-center gap-2.5 px-5 py-2.5 text-gray-400 hover:text-cream hover:bg-gold/10 transition-all border-l-[3px] border-transparent {{ request()->routeIs('rentals.create') ? 'text-gold bg-gold/10 border-gold' : '' }}">
                    <i class="bi bi-plus-circle text-base w-5"></i> New Rental
                </a>
                <a href="{{ route('rentals.calendar') }}"
                   class="flex items-center gap-2.5 px-5 py-2.5 text-gray-400 hover:text-cream hover:bg-gold/10 transition-all border-l-[3px] border-transparent {{ request()->routeIs('rentals.calendar') ? 'text-gold bg-gold/10 border-gold' : '' }}">
                    <i class="bi bi-calendar3 text-base w-5"></i> Calendar
                </a>
                <a href="{{ route('rentals.mastersheet') }}"
                   class="flex items-center gap-2.5 px-5 py-2.5 text-gray-400 hover:text-cream hover:bg-gold/10 transition-all border-l-[3px] border-transparent {{ request()->routeIs('rentals.mastersheet') ? 'text-gold bg-gold/10 border-gold' : '' }}">
                    <i class="bi bi-table text-base w-5"></i> Master Sheet
                </a>

                <div class="px-5 py-2 mt-2 text-[10px] font-semibold text-gray-600 uppercase tracking-widest">Quotes</div>
                <a href="{{ route('quotes.index') }}"
                   class="flex items-center gap-2.5 px-5 py-2.5 text-gray-400 hover:text-cream hover:bg-gold/10 transition-all border-l-[3px] border-transparent {{ request()->routeIs('quotes.*') ? 'text-gold bg-gold/10 border-gold' : '' }}">
                    <i class="bi bi-file-earmark-text text-base w-5"></i> Quote Requests
                    @php $pendingQuotes = \App\Models\Quote::pending()->count(); @endphp
                    @if($pendingQuotes > 0)
                        <span class="ml-auto bg-gold text-dark text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full">{{ $pendingQuotes }}</span>
                    @endif
                </a>

                <div class="px-5 py-2 mt-2 text-[10px] font-semibold text-gray-600 uppercase tracking-widest">Customers</div>
                <a href="{{ route('messages.index') }}"
                   class="flex items-center gap-2.5 px-5 py-2.5 text-gray-400 hover:text-cream hover:bg-gold/10 transition-all border-l-[3px] border-transparent {{ request()->routeIs('messages.*') ? 'text-gold bg-gold/10 border-gold' : '' }}">
                    <i class="bi bi-chat-square-text text-base w-5"></i> Messages
                    @php $unread = \App\Models\CustomerMessage::where('is_read', false)->count(); @endphp
                    @if($unread > 0)
                        <span class="ml-auto bg-gold text-dark text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full">{{ $unread }}</span>
                    @endif
                </a>
            @endif
        </nav>

        <!-- User Card -->
        <div class="p-5 border-t border-white/5">
            <div class="flex items-center justify-between p-2.5 bg-dark-200 rounded-md border border-white/5">
                <div class="min-w-0">
                    <div class="text-[13px] font-semibold text-cream truncate">{{ auth()->user()->name }}</div>
                    <div class="text-[10px] text-gold uppercase tracking-wider">{{ auth()->user()->role }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-500 hover:text-red-500 transition-colors p-1" title="Logout">
                        <i class="bi bi-box-arrow-right text-base"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 lg:ml-60 flex flex-col min-h-screen">
        <!-- Header -->
        <header class="h-16 bg-dark-100 border-b border-white/5 flex items-center px-7 gap-4 sticky top-0 z-30">
            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden flex items-center justify-center w-9 h-9 border border-white/10 rounded-md text-cream hover:bg-gold/10 hover:text-gold transition-colors">
                <i class="bi bi-list text-lg"></i>
            </button>
            
            
        </header>

        <!-- Page Body -->
        <main class="flex-1 p-7 bg-dark">
            <!-- AlpineJS Auto-dismiss Alerts -->
            @if(session('success'))
                <div x-data="{ show: true }" 
                     x-init="setTimeout(() => show = false, 5000)"
                     x-show="show"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="mb-4 flex items-center gap-2 px-4 py-3 rounded-md text-[13px] border-l-3 bg-green-500/10 border-green-500 text-green-400">
                    <i class="bi bi-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div x-data="{ show: true }" 
                     x-init="setTimeout(() => show = false, 5000)"
                     x-show="show"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="mb-4 flex items-center gap-2 px-4 py-3 rounded-md text-[13px] border-l-3 bg-red-500/10 border-red-500 text-red-400">
                    <i class="bi bi-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div x-data="{ show: true }" 
                     x-init="setTimeout(() => show = false, 5000)"
                     x-show="show"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="mb-4 flex items-start gap-2 px-4 py-3 rounded-md text-[13px] border-l-3 bg-red-500/10 border-red-500 text-red-400">
                    <i class="bi bi-exclamation-triangle mt-0.5"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.datepicker').forEach(function(el) {
            flatpickr(el, {
                dateFormat: 'Y-m-d',
                altInput: true,
                altFormat: 'M d, Y',
                allowInput: true,
                disableMobile: true,
            });
        });
    });
</script>

@stack('scripts')
</body>
</html>