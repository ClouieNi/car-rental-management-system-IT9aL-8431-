<?php

namespace App\Http\Controllers;

use App\Models\{Car, CustomerMessage, Driver, Rental};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

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

    public function uploadDocuments(Request $request, Rental $rental)
    {
        abort_if($rental->customer_user_id !== auth()->id(), 403);
        
        // Only allow document upload for approved or documents_pending status
        if (!in_array($rental->status, ['approved', 'documents_pending'])) {
            return back()->withErrors(['error' => 'Document upload is not available for this rental status.']);
        }

        $validated = $request->validate([
            'contract_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'id_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

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
        return back()->with('success', "{$uploadedText} uploaded successfully. Awaiting staff verification.");
    }
}