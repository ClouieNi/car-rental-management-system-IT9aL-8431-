<?php

namespace App\Http\Controllers;

use App\Models\CustomerMessage;
use App\Models\Rental;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user          = auth()->user();
        $activeRentals = Rental::forCustomer($user->id)->active()->with('car')->get();
        $pastRentals   = Rental::forCustomer($user->id)->completed()
                               ->with('car')->orderByDesc('end_date')->limit(5)->get();
        $unreadReplies = CustomerMessage::where('user_id', $user->id)
                                        ->whereNotNull('admin_reply')
                                        ->where('is_read', false)->count();

        return view('customer.dashboard', compact('activeRentals', 'pastRentals', 'unreadReplies'));
    }

    public function rentals()
    {
        $rentals = Rental::forCustomer(auth()->id())
                         ->with('car')
                         ->orderByDesc('start_date')
                         ->paginate(10);

        return view('customer.rentals', compact('rentals'));
    }

    public function rentalShow(Rental $rental)
    {
        abort_if($rental->customer_user_id !== auth()->id(), 403);
        $rental->load('car', 'messages');
        return view('customer.rental-show', compact('rental'));
    }

    public function messages()
    {
        $messages = CustomerMessage::where('user_id', auth()->id())
                                   ->with('rental.car')
                                   ->orderByDesc('created_at')
                                   ->paginate(15);

        return view('customer.messages', compact('messages'));
    }

    public function sendMessage(Request $request)
    {
        $data = $request->validate([
            'subject'   => 'nullable|string|max:200',
            'message'   => 'required|string|max:2000',
            'rental_id' => 'nullable|exists:rentals,id',
        ]);

        if (!empty($data['rental_id'])) {
            $rental = Rental::find($data['rental_id']);
            if (!$rental || $rental->customer_user_id !== auth()->id()) {
                return back()->withErrors(['rental_id' => 'Invalid rental selected.']);
            }
        }

        CustomerMessage::create([
            'user_id'   => auth()->id(),
            'rental_id' => $data['rental_id'] ?? null,
            'subject'   => $data['subject'] ?? null,
            'message'   => $data['message'],
        ]);

        return back()->with('success', 'Your message has been sent to Cars ni Bai.');
    }
}