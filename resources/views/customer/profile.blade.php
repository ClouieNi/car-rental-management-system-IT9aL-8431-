@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-cream mb-1">My Profile</h1>
        <p class="text-gray-400 text-sm">Manage your account information and password.</p>
    </div>

    {{-- Personal Info --}}
    <div class="bg-dark-100 border border-white/10 rounded-xl p-6 mb-6">
        <h2 class="text-base font-semibold text-cream mb-5 flex items-center gap-2">
            <i class="bi bi-person text-gold"></i> Personal Information
        </h2>

        <form method="POST" action="{{ route('customer.profile.update') }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Full Name</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                       class="w-full bg-dark-200 border border-white/10 rounded-lg px-4 py-2.5 text-cream text-sm focus:outline-none focus:border-gold/50 transition-colors @error('name') border-red-500/50 @enderror"
                       placeholder="Your full name">
                @error('name')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-5">
                <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                       class="w-full bg-dark-200 border border-white/10 rounded-lg px-4 py-2.5 text-cream text-sm focus:outline-none focus:border-gold/50 transition-colors @error('email') border-red-500/50 @enderror"
                       placeholder="you@example.com">
                @error('email')
                    <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="border-t border-white/10 pt-5 mb-4">
                <h3 class="text-sm font-semibold text-cream mb-4 flex items-center gap-2">
                    <i class="bi bi-lock text-gold"></i> Change Password
                    <span class="text-xs text-gray-500 font-normal">(leave blank to keep current)</span>
                </h3>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Current Password</label>
                    <input type="password" name="current_password"
                           class="w-full bg-dark-200 border border-white/10 rounded-lg px-4 py-2.5 text-cream text-sm focus:outline-none focus:border-gold/50 transition-colors @error('current_password') border-red-500/50 @enderror"
                           placeholder="Enter current password">
                    @error('current_password')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">New Password</label>
                    <input type="password" name="password"
                           class="w-full bg-dark-200 border border-white/10 rounded-lg px-4 py-2.5 text-cream text-sm focus:outline-none focus:border-gold/50 transition-colors @error('password') border-red-500/50 @enderror"
                           placeholder="Min. 8 characters">
                    @error('password')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Confirm New Password</label>
                    <input type="password" name="password_confirmation"
                           class="w-full bg-dark-200 border border-white/10 rounded-lg px-4 py-2.5 text-cream text-sm focus:outline-none focus:border-gold/50 transition-colors"
                           placeholder="Repeat new password">
                </div>
            </div>

            <div class="border-t border-white/10 pt-5 mb-4">
                <h3 class="text-sm font-semibold text-cream mb-4 flex items-center gap-2">
                    <i class="bi bi-shield-check text-gold"></i> Security Question
                    <span class="text-xs text-gray-500 font-normal">(for password recovery)</span>
                </h3>

                <div class="mb-4">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Choose Question</label>
                    <select name="security_question"
                            class="w-full bg-dark-200 border border-white/10 rounded-lg px-4 py-2.5 text-cream text-sm focus:outline-none focus:border-gold/50 transition-colors @error('security_question') border-red-500/50 @enderror">
                        <option value="">Select a security question...</option>
                        @foreach(["mother_maiden" => "What is your mother's maiden name?", "first_pet" => "What was the name of your first pet?", "birth_city" => "What city were you born in?", "favorite_food" => "What is your favorite food?", "elementary_school" => "What was the name of your elementary school?"] as $key => $question)
                            <option value="{{ $question }}" {{ old('security_question', $user->security_question) == $question ? 'selected' : '' }}>
                                {{ $question }}
                            </option>
                        @endforeach
                    </select>
                    @error('security_question')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-5">
                    <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1.5">Your Answer</label>
                    <input type="text" name="security_answer"
                           class="w-full bg-dark-200 border border-white/10 rounded-lg px-4 py-2.5 text-cream text-sm focus:outline-none focus:border-gold/50 transition-colors @error('security_answer') border-red-500/50 @enderror"
                           placeholder="Enter your answer (case-insensitive)"
                           value="{{ old('security_answer') }}">
                    @if($user->security_question)
                        <p class="text-gray-500 text-xs mt-1">Leave blank to keep existing answer</p>
                    @endif
                    @error('security_answer')
                        <p class="text-red-400 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-between">
                <a href="{{ route('customer.dashboard') }}" class="text-sm text-gray-400 hover:text-cream transition-colors">
                    <i class="bi bi-arrow-left mr-1"></i> Back to Dashboard
                </a>
                <button type="submit"
                        class="bg-gold hover:bg-gold/80 text-dark font-bold text-sm px-6 py-2.5 rounded-lg transition-colors">
                    <i class="bi bi-check-lg mr-1"></i> Save Changes
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
