<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Quote;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class QuoteController extends Controller
{
    public function index(Request $request)
    {
        $query = Quote::with('car')->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(fn($sq) => $sq->where('guest_name', 'like', "%$q%")
                                        ->orWhere('guest_email', 'like', "%$q%"));
        }

        $quotes = $query->paginate(15)->withQueryString();

        return view('quotes.index', compact('quotes'));
    }

    public function show(Quote $quote)
    {
        $quote->load('car');
        return view('quotes.show', compact('quote'));
    }

    public function updateStatus(Request $request, Quote $quote)
    {
        $request->validate([
            'status'        => 'required|in:pending,sent,accepted,rejected,expired',
            'admin_remarks' => 'nullable|string|max:1000',
        ]);

        $quote->update([
            'status'        => $request->status,
            'admin_remarks' => $request->admin_remarks,
        ]);

        if ($request->status === 'accepted') {
            $quote->update(['expires_at' => now()->addHours(48)]);

            // Create or update customer user account
            $user = User::firstOrCreate(
                ['email' => $quote->guest_email],
                [
                    'name' => $quote->guest_name,
                    'role' => 'customer',
                    'password' => null, // Will be set by customer
                    'setup_token' => hash('sha256', Str::random(40)),
                    'setup_expires' => now()->addHours(24),
                ]
            );

            // Send account setup email (optional - can be added later)
            // TODO: Send email notification with setup link

            return redirect()->route('quotes.prepare', $quote)
                           ->with('success', 'Quote accepted. Prepare the rental details before converting to transaction.');
        }

        return back()->with('success', "Quote status updated to {$request->status}.");
    }

    public function prepare(Quote $quote)
    {
        $quote->load('car');
        $cars = Car::where('status', 'available')->orWhere('id', $quote->car_id)->orderBy('brand')->get();
        return view('quotes.prepare', compact('quote', 'cars'));
    }

    public function convertToRental(Quote $quote)
    {
        if (!in_array($quote->status, ['accepted', 'sent'])) {
            return back()->with('error', 'Only accepted/sent quotes can be converted to rentals.');
        }

        return redirect()->route('rentals.create', ['quote_id' => $quote->id]);
    }

    public function requestForm()
    {
        $cars = Car::where('status', 'available')->orderBy('brand')->get();
        return view('quotes.request', compact('cars'));
    }

    public function requestStore(Request $request)
    {
        $data = $request->validate([
            'guest_name'   => 'required|string|max:100',
            'guest_email'  => 'required|email|max:150',
            'guest_phone'  => 'required|string|max:20',
            'car_id'       => 'required|exists:cars,id',
            'rental_type'  => 'required|in:with_driver,self_drive',
            'start_date'   => 'required|date|after_or_equal:today',
            'end_date'     => 'required|date|after:start_date',
            'destination'  => 'nullable|string|max:255',
            'distance_km'  => 'nullable|integer|min:0',
            'guest_notes'  => 'nullable|string|max:1000',
        ]);

        $car       = Car::findOrFail($data['car_id']);
        $days      = max(1, (int)((strtotime($data['end_date']) - strtotime($data['start_date'])) / 86400));
        $distKm    = (int)($data['distance_km'] ?? 0);
        $surcharge = \App\Models\Rental::calculateDistanceSurcharge($distKm);
        $baseCost  = $car->daily_rate * $days;
        $total     = $baseCost + $surcharge;

        $quote = Quote::create([
            'guest_name'         => $data['guest_name'],
            'guest_email'        => $data['guest_email'],
            'guest_phone'        => $data['guest_phone'],
            'car_id'             => $data['car_id'],
            'rental_type'        => $data['rental_type'],
            'start_date'         => $data['start_date'],
            'end_date'           => $data['end_date'],
            'days'               => $days,
            'destination'        => $data['destination'] ?? null,
            'distance_km'        => $distKm,
            'distance_surcharge' => $surcharge,
            'base_cost'          => $baseCost,
            'total_estimate'     => $total,
            'guest_notes'        => $data['guest_notes'] ?? null,
            'status'             => 'pending',
        ]);

        // Create or get existing customer account with auto-generated password
        $tempPassword = Str::random(10);
        $user = User::firstOrCreate(
            ['email' => $data['guest_email']],
            [
                'name'     => $data['guest_name'],
                'role'     => 'customer',
                'password' => Hash::make($tempPassword),
            ]
        );

        // If user already existed, generate a new temp password for display
        if (!$user->wasRecentlyCreated) {
            $tempPassword = null; // Don't show password for existing accounts
        }

        return redirect()->route('quotes.request.thanks')
                         ->with([
                             'quote_id'      => $quote->id,
                             'email'         => $data['guest_email'],
                             'temp_password' => $tempPassword,
                             'is_new_account'=> $user->wasRecentlyCreated,
                         ]);
    }

    public function thanks()
    {
        return view('quotes.thanks');
    }
}