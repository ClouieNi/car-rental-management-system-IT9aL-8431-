@extends('layouts.app')

@section('title', 'Set Up Your Account')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-dark">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo -->
        <div class="text-center">
            <div class="font-display text-4xl tracking-wider text-gold mb-2">Cars ni Bai</div>
            <p class="text-sm text-gray-500">Complete your account setup</p>
        </div>

        <!-- Setup Card -->
        <div class="bg-dark-100 rounded-lg p-8 border border-gold/20">
            <h2 class="text-xl font-semibold text-cream mb-6 text-center">Create Your Account</h2>

            <p class="text-sm text-gray-400 mb-6 text-center">
                Welcome! Please set a username and password for your account.
            </p>

            <form method="POST" action="{{ route('customer.setup.process') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <!-- Email Display (Read-only) -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Email</label>
                    <div class="px-4 py-2 bg-dark-200 rounded border border-white/5 text-cream">
                        {{ $user->email }}
                    </div>
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-400 mb-1">
                        Username <span class="text-gold">*</span>
                    </label>
                    <input id="username" name="username" type="text" required
                           class="w-full px-4 py-2 bg-dark-200 border border-white/10 rounded text-cream placeholder-gray-600 focus:ring-2 focus:ring-gold focus:border-transparent @error('username') border-red-500 @enderror"
                           placeholder="Choose a username"
                           value="{{ old('username') }}">
                    @error('username')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-400 mb-1">
                        Password <span class="text-gold">*</span>
                    </label>
                    <input id="password" name="password" type="password" required
                           class="w-full px-4 py-2 bg-dark-200 border border-white/10 rounded text-cream placeholder-gray-600 focus:ring-2 focus:ring-gold focus:border-transparent @error('password') border-red-500 @enderror"
                           placeholder="Create a password">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-400 mb-1">
                        Confirm Password <span class="text-gold">*</span>
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="w-full px-4 py-2 bg-dark-200 border border-white/10 rounded text-cream placeholder-gray-600 focus:ring-2 focus:ring-gold focus:border-transparent"
                           placeholder="Confirm your password">
                </div>

                <!-- Submit -->
                <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-dark bg-gold hover:bg-gold-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gold transition-colors">
                    Complete Setup
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500">
                    By creating an account, you agree to our Terms of Service and Privacy Policy.
                </p>
            </div>
        </div>

        <!-- Help -->
        <div class="text-center">
            <p class="text-sm text-gray-500">
                Need help? <a href="mailto:support@carsnibai.com" class="text-gold hover:text-gold-light">Contact support</a>
            </p>
        </div>
    </div>
</div>
@endsection
