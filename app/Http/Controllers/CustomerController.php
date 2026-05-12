<?php

namespace App\Http\Controllers;

use App\Models\{Car, CustomerMessage, Driver, Quote, Rental};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();

        // Stats
        $activeRentalsCount = Rental::forCustomer($user->id)->active()->count();
        $totalRentalsCount = Rental::forCustomer($user->id)->count();
        $pendingQuotesCount = Quote::where('user_id', $user->id)->where('status', 'pending')->count();
        $unreadMessagesCount = CustomerMessage::where('user_id', $user->id)
                                               ->whereNotNull('admin_reply')
                                               ->where('is_read', false)->count();

        // Recent Activity
        $recentRentals = Rental::forCustomer($user->id)
                               ->with('car')
                               ->orderByDesc('created_at')
                               ->limit(3)
                               ->get();
        $recentQuotes = Quote::where('user_id', $user->id)
                             ->with('car')
                             ->orderByDesc('created_at')
                             ->limit(3)
                             ->get();

        return view('customer.dashboard', compact(
            'activeRentalsCount', 'totalRentalsCount', 'pendingQuotesCount', 'unreadMessagesCount',
            'recentRentals', 'recentQuotes'
        ));
    }

    public function transactions(Request $request)
    {
        $query = Rental::forCustomer(auth()->id())->with('car');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rentals = $query->orderByDesc('start_date')->paginate(10)->withQueryString();

        return view('customer.transactions', compact('rentals'));
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

    public function createBooking()
    {
        $cars = Car::where('status', 'available')->get();
        return view('rentals.create-booking', ['cars' => $cars]);
    }

    public function storeBooking(Request $request)
    {
        $validated = $request->validate([
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'rental_type' => 'required|in:with_driver,self_drive',
            'destination' => 'nullable|string|max:255',
            'distance_km' => 'nullable|integer|min:0',
            'license_number' => 'required|string|max:50',
            'license_expiry' => 'required|date|after:today',
            'license_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'customer_notes' => 'nullable|string|max:1000',
        ]);

        $car = Car::findOrFail($validated['car_id']);
        
        // Check license expiry before rental end date
        $licenseExpiry = Carbon::parse($validated['license_expiry']);
        $rentalEnd = Carbon::parse($validated['end_date']);
        if ($licenseExpiry->lessThan($rentalEnd)) {
            return back()->withErrors(['license_expiry' => 'License expires before rental end date']);
        }

        // Check car availability for these dates
        $carAvailable = Car::availableBetween($validated['start_date'], $validated['end_date'])
            ->where('id', $validated['car_id'])
            ->exists();
        
        if (!$carAvailable) {
            return back()->withErrors(['car_id' => 'Car not available for selected dates']);
        }

        // Create or get driver record
        $driver = Driver::where('license_number', $validated['license_number'])->first();
        if (!$driver) {
            $driver = Driver::create([
                'user_id' => auth()->id(),
                'license_number' => $validated['license_number'],
                'license_expiry' => $validated['license_expiry'],
            ]);
        }

        // Store license file
        if ($request->hasFile('license_file')) {
            $path = $request->file('license_file')->store("drivers/{$driver->id}", 'private');
            $driver->update(['license_file_path' => $path]);
        }

        // Calculate pricing
        $days = Carbon::parse($validated['start_date'])->diffInDays(Carbon::parse($validated['end_date'])) ?: 1;
        $baseCost = $car->daily_rate * $days;
        $surcharge = $validated['distance_km'] ? ($validated['distance_km'] * 0.50) : 0;
        $totalCost = $baseCost + $surcharge;

        // Create rental with pending status
        $rental = Rental::create([
            'car_id' => $car->id,
            'customer_user_id' => auth()->id(),
            'driver_id' => $driver->id,
            'rental_type' => $validated['rental_type'],
            'destination' => $validated['destination'],
            'distance_km' => $validated['distance_km'] ?? 0,
            'distance_surcharge' => $surcharge,
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'total_cost' => $totalCost,
            'customer_notes' => $validated['customer_notes'],
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        return redirect()->route('customer.booking-confirmation', $rental)
            ->with('success', 'Booking request submitted. Awaiting staff approval.');
    }

    public function bookingConfirmation(Rental $rental)
    {
        abort_if($rental->customer_user_id !== auth()->id(), 403);
        return view('rentals.booking-confirmation', ['rental' => $rental]);
    }

    public function requestCancellation(Rental $rental)
    {
        abort_if($rental->customer_user_id !== auth()->id(), 403);
        
        if (!in_array($rental->status, ['pending', 'approved'])) {
            return back()->withErrors('Cannot cancel this rental');
        }

        $validated = request()->validate([
            'cancellation_reason' => 'required|string|max:500',
        ]);

        $refundPercent = $rental->calculateRefundPercent();
        $refundAmount = $rental->calculateRefundAmount();

        $rental->update([
            'status' => 'cancellation_requested',
            'cancellation_reason' => $validated['cancellation_reason'],
            'cancellation_requested_at' => now(),
            'cancellation_refund_percent' => $refundPercent,
            'refund_amount' => $refundAmount,
        ]);

        return back()->with('success', "Cancellation requested. Refund: \${$refundAmount}");
    }

    public function documentsForm(Rental $rental)
    {
        abort_if($rental->customer_user_id !== auth()->id(), 403);
        
        // Only allow document upload for approved or documents_pending status
        if (!in_array($rental->status, ['approved', 'documents_pending', 'documents_verified'])) {
            return redirect()->route('customer.rental-show', $rental)
                ->with('info', 'Document upload is not available for this rental status.');
        }
        
        $rental->load('car');
        return view('customer.documents', compact('rental'));
    }

    public function profileEdit()
    {
        return view('customer.profile', ['user' => auth()->user()]);
    }

    public function profileUpdate(Request $request)
    {
        $user = auth()->user();

        $data = $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|max:255|unique:users,email,' . $user->id,
            'current_password'      => 'nullable|string',
            'password'              => ['nullable', 'confirmed', Password::min(8)],
            'security_question'     => 'nullable|string',
            'security_answer'       => 'nullable|string|min:2',
        ]);

        // If user wants to change password, verify current password
        if (!empty($data['current_password'])) {
            if (!Hash::check($data['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
            }
        }

        $user->name  = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        // Update security question if provided
        if (!empty($data['security_question'])) {
            $user->security_question = $data['security_question'];
            
            // Only update answer if a new one is provided
            if (!empty($data['security_answer'])) {
                $user->security_answer_hash = password_hash(strtolower(trim($data['security_answer'])), PASSWORD_DEFAULT);
            }
        }

        $user->save();

        // Update customer_name on user's rentals to keep them in sync
        Rental::forCustomer($user->id)
            ->whereIn('status', ['pending', 'reserved', 'approved', 'ongoing'])
            ->update(['customer_name' => $data['name']]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function uploadDocuments(Request $request, Rental $rental)
    {
        abort_if($rental->customer_user_id !== auth()->id(), 403);
        
        // Only allow document upload for approved or documents_pending status
        if (!in_array($rental->status, ['approved', 'documents_pending'])) {
            return back()->withErrors(['error' => 'Document upload is not available for this rental status.']);
        }

        // Require files if not already uploaded
        $rules = [];
        $messages = [];
        
        if (!$rental->contract_file_path) {
            $rules['contract_file'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:10240';
            $messages['contract_file.required'] = 'The rental contract is required. Please upload your signed contract.';
        } else {
            $rules['contract_file'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240';
        }
        
        if (!$rental->id_file_path) {
            $rules['id_file'] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
            $messages['id_file.required'] = 'A valid ID is required. Please upload your government-issued ID.';
        } else {
            $rules['id_file'] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
        }
        
        $validated = $request->validate($rules, $messages);

        $uploaded = [];

        // Handle contract file upload
        if ($request->hasFile('contract_file')) {
            $contractPath = $request->file('contract_file')->store(
                "rentals/{$rental->id}/contract", 
                'private'
            );
            $rental->update([
                'contract_file_path' => $contractPath,
                'contract_status' => 'uploaded',
            ]);
            $uploaded[] = 'Contract';
        }

        // Handle ID file upload
        if ($request->hasFile('id_file')) {
            $idPath = $request->file('id_file')->store(
                "rentals/{$rental->id}/id", 
                'private'
            );
            $rental->update([
                'id_file_path' => $idPath,
                'id_uploaded_at' => now(),
            ]);
            $uploaded[] = 'ID';
        }

        // Update rental status to documents_pending if files were uploaded
        if (count($uploaded) > 0 && $rental->status === 'approved') {
            $rental->update(['status' => 'documents_pending']);
        }

        $uploadedText = implode(' and ', $uploaded);
        return redirect()->route('customer.rental-show', $rental)
            ->with('doc_success', "{$uploadedText} uploaded successfully. Awaiting staff verification.");
    }

    public function recordPayment(Request $request, Rental $rental)
    {
        abort_if($rental->customer_user_id !== auth()->id(), 403);
        
        // Only allow payment for reserved status with unpaid or partial payment
        if ($rental->status !== 'reserved' || $rental->payment_status === 'paid') {
            return back()->withErrors(['error' => 'Payment is not available for this rental.']);
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . ($rental->total_cost - $rental->amount_paid),
            'payment_method' => 'required|in:gcash,maya,bank_transfer,cash',
            'reference_number' => 'required|string|max:100',
        ]);

        $newAmountPaid = $rental->amount_paid + $validated['amount'];
        
        // Determine new payment status
        $paymentStatus = 'partial';
        if ($newAmountPaid >= $rental->total_cost) {
            $paymentStatus = 'paid';
        }

        // Append payment info to admin notes
        $paymentNote = "[Payment] ₱{$validated['amount']} via " . str_replace('_', ' ', $validated['payment_method']) . 
                      " (Ref: {$validated['reference_number']}) - " . now()->format('M d, Y H:i') . "\n";

        $rental->update([
            'amount_paid' => $newAmountPaid,
            'payment_status' => $paymentStatus,
            'admin_notes' => $rental->admin_notes . "\n" . $paymentNote,
        ]);

        return redirect()->route('customer.rental-show', $rental)
            ->with('payment_success', "Payment of ₱{$validated['amount']} recorded successfully. Awaiting verification.");
    }
}