<x-guest-layout>
    <div class="mb-4 text-sm text-gray-400">
        {{ __('Create your account to start renting cars. You will also need to set up a security question for password recovery.') }}
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div class="mb-3">
            <label for="name" class="block text-sm font-medium text-cream mb-1">{{ __('Full Name') }}</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="block w-full px-3 py-2 bg-dark border border-white/10 rounded text-cream text-sm placeholder-gray-500 focus:outline-none focus:border-gold/50 focus:ring-1 focus:ring-gold/50 transition" />
            @if ($errors->get('name'))
                <div class="mt-1 text-sm text-red-400">{{ $errors->first('name') }}</div>
            @endif
        </div>

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="block text-sm font-medium text-cream mb-1">{{ __('Email') }}</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   class="block w-full px-3 py-2 bg-dark border border-white/10 rounded text-cream text-sm placeholder-gray-500 focus:outline-none focus:border-gold/50 focus:ring-1 focus:ring-gold/50 transition" />
            @if ($errors->get('email'))
                <div class="mt-1 text-sm text-red-400">{{ $errors->first('email') }}</div>
            @endif
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="block text-sm font-medium text-cream mb-1">{{ __('Password') }}</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="block w-full px-3 py-2 bg-dark border border-white/10 rounded text-cream text-sm placeholder-gray-500 focus:outline-none focus:border-gold/50 focus:ring-1 focus:ring-gold/50 transition" />
            @if ($errors->get('password'))
                <div class="mt-1 text-sm text-red-400">{{ $errors->first('password') }}</div>
            @endif
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-medium text-cream mb-1">{{ __('Confirm Password') }}</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="block w-full px-3 py-2 bg-dark border border-white/10 rounded text-cream text-sm placeholder-gray-500 focus:outline-none focus:border-gold/50 focus:ring-1 focus:ring-gold/50 transition" />
            @if ($errors->get('password_confirmation'))
                <div class="mt-1 text-sm text-red-400">{{ $errors->first('password_confirmation') }}</div>
            @endif
        </div>

        <!-- Security Question -->
        <div class="border-t border-white/10 pt-4 mb-3">
            <h3 class="text-sm font-semibold text-gold mb-3 flex items-center gap-2">
                <i class="bi bi-shield-check"></i> {{ __('Security Question') }}
                <span class="text-xs text-gray-500 font-normal">(for password recovery)</span>
            </h3>

            <div class="mb-3">
                <label for="security_question" class="block text-sm font-medium text-cream mb-1">{{ __('Your Question') }}</label>
                <input id="security_question" type="text" name="security_question" value="{{ old('security_question') }}" required
                       placeholder="e.g., What was my first car?"
                       class="block w-full px-3 py-2 bg-dark border border-white/10 rounded text-cream text-sm placeholder-gray-500 focus:outline-none focus:border-gold/50 focus:ring-1 focus:ring-gold/50 transition" />
                @if ($errors->get('security_question'))
                    <div class="mt-1 text-sm text-red-400">{{ $errors->first('security_question') }}</div>
                @endif
            </div>

            <div class="mb-4">
                <label for="security_answer" class="block text-sm font-medium text-cream mb-1">{{ __('Your Answer') }}</label>
                <input id="security_answer" type="text" name="security_answer" value="{{ old('security_answer') }}" required
                       placeholder="Answer you'll remember"
                       class="block w-full px-3 py-2 bg-dark border border-white/10 rounded text-cream text-sm placeholder-gray-500 focus:outline-none focus:border-gold/50 focus:ring-1 focus:ring-gold/50 transition" />
                @if ($errors->get('security_answer'))
                    <div class="mt-1 text-sm text-red-400">{{ $errors->first('security_answer') }}</div>
                @endif
            </div>
        </div>

        <div class="flex items-center justify-between mt-4">
            <a class="text-sm text-gray-500 hover:text-gold transition" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gold hover:bg-gold/90 text-dark font-bold text-sm rounded-lg transition">
                {{ __('Register') }}
                <i class="bi bi-person-plus ml-2"></i>
            </button>
        </div>
    </form>
</x-guest-layout>
