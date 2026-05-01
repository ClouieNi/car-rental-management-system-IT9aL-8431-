<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerSetupController extends Controller
{
    /**
     * Show the account setup form
     */
    public function showSetupForm(Request $request)
    {
        $token = $request->query('token');

        if (!$token) {
            return redirect()->route('login')
                           ->with('error', 'Invalid or missing setup token.');
        }

        $user = User::where('setup_token', $token)
                    ->where('setup_expires', '>', now())
                    ->first();

        if (!$user) {
            return redirect()->route('login')
                           ->with('error', 'Setup link has expired or is invalid. Please contact support.');
        }

        return view('customer.setup', compact('user', 'token'));
    }

    /**
     * Process the account setup
     */
    public function processSetup(Request $request)
    {
        $request->validate([
            'token'    => 'required|string',
            'username' => 'required|string|min:3|max:50|unique:users,name',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('setup_token', $request->token)
                    ->where('setup_expires', '>', now())
                    ->first();

        if (!$user) {
            return redirect()->route('login')
                           ->with('error', 'Setup link has expired or is invalid. Please contact support.');
        }

        $user->update([
            'name'          => $request->username,
            'password'      => Hash::make($request->password),
            'setup_token'   => null,
            'setup_expires' => null,
        ]);

        return redirect()->route('login')
                         ->with('success', 'Your account has been set up successfully! You can now log in with your username and password.');
    }
}
