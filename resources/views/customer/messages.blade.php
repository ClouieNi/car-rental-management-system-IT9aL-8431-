@extends('layouts.app')

@section('content')
<div class="p-6 lg:p-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-cream mb-2">Messages</h1>
        <p class="text-gray-400">Contact us for inquiries or support</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Message List -->
        <div class="lg:col-span-2">
            <div class="bg-dark-100 border border-white/10 rounded-xl overflow-hidden">
                @if($messages->count() > 0)
                    <div class="divide-y divide-white/10">
                        @foreach($messages as $message)
                            <div class="p-5 hover:bg-dark-200/50 transition-all">
                                <div class="flex items-start gap-4">
                                    <!-- User Avatar -->
                                    <div class="w-10 h-10 bg-gold/20 rounded-full flex items-center justify-center flex-shrink-0">
                                        <i class="bi bi-person text-gold"></i>
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="font-semibold text-cream">You</span>
                                            <span class="text-xs text-gray-500">{{ $message->created_at->format('M d, Y h:i A') }}</span>
                                        </div>
                                        
                                        @if($message->subject)
                                            <div class="text-sm font-medium text-gold mb-2">{{ $message->subject }}</div>
                                        @endif
                                        
                                        <div class="text-gray-300 text-sm mb-3">{{ $message->message }}</div>

                                        @if($message->rental)
                                            <div class="text-xs text-gray-500 mb-3">
                                                <i class="bi bi-car-front"></i> 
                                                Related to: {{ $message->rental->car->brand }} {{ $message->rental->car->model }}
                                                ({{ $message->rental->getRentalIdDisplayAttribute() }})
                                            </div>
                                        @endif

                                        <!-- Admin Reply -->
                                        @if($message->admin_reply)
                                            <div class="mt-3 pl-4 border-l-2 border-gold/50 bg-dark-200/50 rounded-r-lg p-3">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <div class="w-6 h-6 bg-gold/20 rounded-full flex items-center justify-center">
                                                        <i class="bi bi-shield text-gold text-xs"></i>
                                                    </div>
                                                    <span class="text-sm font-medium text-gold">Cars ni Bai Team</span>
                                                    <span class="text-xs text-gray-500">{{ $message->updated_at->format('M d, Y h:i A') }}</span>
                                                </div>
                                                <div class="text-gray-300 text-sm">{{ $message->admin_reply }}</div>
                                            </div>
                                        @else
                                            <div class="mt-2 flex items-center gap-2 text-xs text-gray-500">
                                                <i class="bi bi-clock"></i>
                                                <span>Awaiting response...</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if($messages->hasPages())
                        <div class="p-4 border-t border-white/10">
                            {{ $messages->links() }}
                        </div>
                    @endif
                @else
                    <div class="py-20 px-12 text-center">
                        <div class="w-16 h-16 bg-dark-200 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="bi bi-chat-square-text text-gray-500 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-cream mb-2">No messages yet</h3>
                        <p class="text-gray-500 mb-6">Start a conversation with us for any inquiries.</p>
                        <div class="h-4"></div>
                    </div>
                @endif
            </div>
        </div>

        <!-- New Message Form -->
        <div class="lg:col-span-1">
            <div class="bg-dark-100 border border-white/10 rounded-xl p-6 sticky top-6">
                <h2 class="text-lg font-semibold text-cream mb-4">Send a Message</h2>
                
                <form action="{{ route('customer.send-message') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Subject (optional)</label>
                        <input type="text" name="subject" 
                               class="w-full px-4 py-2 bg-dark-200 border border-white/10 rounded-lg text-cream placeholder-gray-600 focus:ring-2 focus:ring-gold focus:border-transparent text-sm"
                               placeholder="e.g., Question about my booking">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-1">Message <span class="text-gold">*</span></label>
                        <textarea name="message" rows="5" required
                                  class="w-full px-4 py-2 bg-dark-200 border border-white/10 rounded-lg text-cream placeholder-gray-600 focus:ring-2 focus:ring-gold focus:border-transparent text-sm resize-none"
                                  placeholder="How can we help you today?"></textarea>
                    </div>

                    <button type="submit" 
                            class="w-full bg-gold hover:bg-gold-dark text-dark font-bold py-2.5 px-4 rounded-lg transition flex items-center justify-center gap-2">
                        <i class="bi bi-send"></i>
                        <span>Send Message</span>
                    </button>
                </form>

                <div class="mt-6 pt-6 border-t border-white/10">
                    <h3 class="text-sm font-medium text-gray-400 mb-3">Quick Links</h3>
                    <a href="/quote" class="flex items-center gap-2 text-sm text-gold hover:text-gold-light transition mb-2">
                        <i class="bi bi-plus-circle"></i>
                        <span>Get a Quote</span>
                    </a>
                    <a href="{{ route('customer.transactions') }}" class="flex items-center gap-2 text-sm text-gold hover:text-gold-light transition">
                        <i class="bi bi-receipt"></i>
                        <span>View Transactions</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
