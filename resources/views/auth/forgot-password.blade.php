<x-guest-layout>
    @if ($step == 1)
        {{-- Step 1: Email Input --}}
        <div class="mb-4 text-sm text-gray-400">
            {{ __('Forgot your password? Enter your email address to verify your identity with your security question.') }}
        </div>

        @if (session('status'))
            <div class="mb-4 p-3 bg-green-500/10 border border-green-500/30 rounded text-sm text-green-400">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-cream mb-1">{{ __('Email') }}</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="block w-full px-3 py-2 bg-dark border border-white/10 rounded text-cream text-sm placeholder-gray-500 focus:outline-none focus:border-gold/50 focus:ring-1 focus:ring-gold/50 transition" />
                @if ($errors->get('email'))
                    <div class="mt-2 text-sm text-red-400">{{ $errors->first('email') }}</div>
                @endif
            </div>

            <div class="flex items-center justify-end mt-4">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gold hover:bg-gold/90 text-dark font-bold text-sm rounded-lg transition">
                    {{ __('Continue') }}
                    <i class="bi bi-arrow-right ml-2"></i>
                </button>
            </div>
        </form>

    @elseif ($step == 2)
        {{-- Step 2: Security Question --}}
        <div class="mb-4 text-sm text-gray-400">
            {{ __('Answer your security question to verify your identity.') }}
        </div>

        <div class="mb-4 p-3 bg-gold/10 border border-gold/30 rounded">
            <div class="text-sm text-gold font-medium mb-1">{{ __('Your Security Question:') }}</div>
            <div class="text-cream font-medium">{{ $question }}</div>
        </div>

        <form method="POST" action="{{ route('password.verify') }}">
            @csrf

            <div>
                <label for="security_answer" class="block text-sm font-medium text-cream mb-1">{{ __('Your Answer') }}</label>
                <input id="security_answer" type="text" name="security_answer" required autofocus
                       class="block w-full px-3 py-2 bg-dark border border-white/10 rounded text-cream text-sm placeholder-gray-500 focus:outline-none focus:border-gold/50 focus:ring-1 focus:ring-gold/50 transition" />
                @if ($errors->get('security_answer'))
                    <div class="mt-2 text-sm text-red-400">{{ $errors->first('security_answer') }}</div>
                @endif
            </div>

            <div class="flex items-center justify-between mt-4">
                <a href="{{ route('password.request') }}" class="text-sm text-gray-500 hover:text-gold transition">
                    {{ __('Back') }}
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gold hover:bg-gold/90 text-dark font-bold text-sm rounded-lg transition">
                    {{ __('Verify') }}
                    <i class="bi bi-arrow-right ml-2"></i>
                </button>
            </div>
        </form>

    @elseif ($step == 3)
        {{-- Step 3: New Password --}}
        <div class="mb-4 text-sm text-gray-400">
            {{ __('Verified! Enter your new password below.') }}
        </div>

        <form method="POST" action="{{ route('password.reset.store') }}">
            @csrf

            <div class="mb-3">
                <label for="password" class="block text-sm font-medium text-cream mb-1">{{ __('New Password') }}</label>
                <input id="password" type="password" name="password" required
                       class="block w-full px-3 py-2 bg-dark border border-white/10 rounded text-cream text-sm placeholder-gray-500 focus:outline-none focus:border-gold/50 focus:ring-1 focus:ring-gold/50 transition" />
            </div>

            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-cream mb-1">{{ __('Confirm Password') }}</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                       class="block w-full px-3 py-2 bg-dark border border-white/10 rounded text-cream text-sm placeholder-gray-500 focus:outline-none focus:border-gold/50 focus:ring-1 focus:ring-gold/50 transition" />
                @if ($errors->get('password'))
                    <div class="mt-2 text-sm text-red-400">{{ $errors->first('password') }}</div>
                @endif
            </div>

            <div class="flex items-center justify-end mt-4">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gold hover:bg-gold/90 text-dark font-bold text-sm rounded-lg transition">
                    {{ __('Reset Password') }}
                    <i class="bi bi-check-lg ml-2"></i>
                </button>
            </div>
        </form>
    @endif
</x-guest-layout>
