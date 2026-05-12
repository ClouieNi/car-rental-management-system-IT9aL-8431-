<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset request view (Step 1: Email).
     */
    public function create(): View
    {
        return view('auth.forgot-password', ['step' => 1]);
    }

    /**
     * Step 1: Find user by email and show security question.
     */
    public function findUser(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !$user->security_question) {
            return back()->withInput($request->only('email'))
                        ->withErrors(['email' => 'No account found with this email or security question not set.']);
        }

        // Store email in session and redirect to step 2
        session(['reset_email' => $request->email]);
        return redirect()->route('password.question');
    }

    /**
     * Display security question form (Step 2).
     */
    public function showQuestion(): View
    {
        $email = session('reset_email');
        
        if (!$email) {
            return view('auth.forgot-password', ['step' => 1]);
        }

        $user = User::where('email', $email)->first();
        
        if (!$user) {
            session()->forget('reset_email');
            return view('auth.forgot-password', ['step' => 1]);
        }

        // Use the custom security question directly
        $questionText = $user->security_question ?: 'Security Question';

        return view('auth.forgot-password', [
            'step' => 2,
            'question' => $questionText,
            'email' => $email,
        ]);
    }

    /**
     * Step 2: Verify security answer and show password reset form.
     */
    public function verifyAnswer(Request $request): RedirectResponse
    {
        $request->validate([
            'security_answer' => ['required', 'string'],
        ]);

        $email = session('reset_email');
        
        if (!$email) {
            return redirect()->route('password.request');
        }

        $user = User::where('email', $email)->first();

        if (!$user || !$user->verifySecurityAnswer($request->security_answer)) {
            return back()->withErrors(['security_answer' => 'Incorrect answer. Please try again.']);
        }

        // Answer correct, redirect to step 3 (reset password)
        session(['security_verified' => true]);
        return redirect()->route('password.reset.form');
    }

    /**
     * Display password reset form (Step 3).
     */
    public function showResetForm(): View
    {
        if (!session('reset_email') || !session('security_verified')) {
            session()->forget(['reset_email', 'security_verified']);
            return view('auth.forgot-password', ['step' => 1]);
        }

        return view('auth.forgot-password', ['step' => 3, 'email' => session('reset_email')]);
    }

    /**
     * Step 3: Reset password.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        if (!session('reset_email') || !session('security_verified')) {
            session()->forget(['reset_email', 'security_verified']);
            return redirect()->route('password.request');
        }

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::where('email', session('reset_email'))->first();

        if (!$user) {
            session()->forget(['reset_email', 'security_verified']);
            return redirect()->route('password.request');
        }

        // Update password
        $user->password = Hash::make($request->password);
        $user->save();

        // Clear session
        session()->forget(['reset_email', 'security_verified']);

        return redirect()->route('login')->with('status', 'Your password has been reset successfully!');
    }
}
