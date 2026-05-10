<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RentalController extends Controller
{
    public function index(Request $request)
    {
        $query = Rental::with('car')->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment')) {
            $query->where('payment_status', $request->payment);
        }
        if ($request->filled('type')) {
            $query->where('rental_type', $request->type);
        }
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('customer_name', 'like', "%$q%")
                   ->orWhereHas('car', fn($cq) => $cq->where('brand', 'like', "%$q%")
                                                       ->orWhere('model', 'like', "%$q%")
                                                       ->orWhere('plate_number', 'like', "%$q%"));
            });
        }

        $rentals = $query->paginate(15)->withQueryString();

        return view('rentals.index', compact('rentals'));
    }

    public function create(Request $request)
    {
        $cars      = Car::where('status', 'available')->orderBy('brand')->get();
        $customers = User::where('role', 'user')->orderBy('name')->get();

        $quote = null;
        if ($request->filled('quote_id')) {
            $quote = \App\Models\Quote::findOrFail($request->quote_id);
        }

        return view('rentals.create', compact('cars', 'customers', 'quote'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'car_id'           => 'required|exists:cars,id',
            'customer_user_id' => 'nullable|exists:users,id',
            'customer_name'    => 'required|string|max:100',
            'rental_type'      => 'required|in:with_driver,self_drive',
            'destination'      => 'nullable|string|max:255',
            'distance_km'      => 'nullable|integer|min:0',
            'start_date'       => 'required|date|after_or_equal:today',
            'end_date'         => 'required|date|after:start_date',
            'payment_status'   => 'required|in:unpaid,partial,paid',
            'amount_paid'      => 'nullable|numeric|min:0',
            'customer_notes'   => 'nullable|string|max:1000',
            'admin_notes'      => 'nullable|string|max:1000',
            'driver_license'   => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        if ($data['rental_type'] === 'self_drive' && !empty($data['customer_user_id'])) {
            $existingSelfDrive = Rental::where('customer_user_id', $data['customer_user_id'])
                ->where('rental_type', 'self_drive')
                ->whereIn('status', ['reserved', 'ongoing'])
                ->exists();
            if ($existingSelfDrive) {
                return back()->withErrors(['rental_type' => 'Self-drive customers can only have 1 active rental at a time.'])
                             ->withInput();
            }
        }

        $car = Car::findOrFail($data['car_id']);
        if (!$car->isAvailableForDates($data['start_date'], $data['end_date'])) {
            return back()->withErrors(['car_id' => 'This vehicle is not available for the selected dates.'])
                         ->withInput();
        }

        $distanceKm = (int)($data['distance_km'] ?? 0);
        $surcharge  = Rental::calculateDistanceSurcharge($distanceKm);
        $days       = max(1, (int)((strtotime($data['end_date']) - strtotime($data['start_date'])) / 86400));
        $totalCost  = ($car->daily_rate * $days) + $surcharge;

        $licensePath = null;
        if ($request->hasFile('driver_license')) {
            $licensePath = $request->file('driver_license')->store('licenses', 'public');
        }

        $rental = Rental::create([
            'car_id'              => $data['car_id'],
            'customer_user_id'    => $data['customer_user_id'] ?? null,
            'customer_name'       => $data['customer_name'],
            'rental_type'         => $data['rental_type'],
            'destination'         => $data['destination'] ?? null,
            'distance_km'         => $distanceKm,
            'distance_surcharge'  => $surcharge,
            'driver_license_path' => $licensePath,
            'start_date'          => $data['start_date'],
            'end_date'            => $data['end_date'],
            'total_cost'          => $totalCost,
            'payment_status'      => $data['payment_status'],
            'amount_paid'         => $data['amount_paid'] ?? 0,
            'status'              => 'reserved',
            'customer_notes'      => $data['customer_notes'] ?? null,
            'admin_notes'         => $data['admin_notes'] ?? null,
        ]);

        $today = now()->toDateString();
        if ($data['start_date'] <= $today && $data['end_date'] >= $today) {
            $car->update(['status' => 'rented']);
            $rental->update(['status' => 'ongoing']);
        }

        if ($request->filled('quote_id')) {
            \App\Models\Quote::where('id', $request->quote_id)
                ->update(['status' => 'converted', 'rental_id' => $rental->id]);
        }

        return redirect()->route('rentals.show', $rental)
                         ->with('success', 'Rental booking created successfully.');
    }

    public function storeFromQuote(Request $request, \App\Models\Quote $quote)
    {
        $data = $request->validate([
            'car_id'        => 'required|exists:cars,id',
            'start_date'    => 'required|date|after_or_equal:today',
            'end_date'      => 'required|date|after:start_date',
            'rental_type'   => 'required|in:with_driver,self_drive',
            'destination'   => 'nullable|string|max:255',
            'distance_km'   => 'nullable|integer|min:0',
            'admin_notes'   => 'nullable|string|max:1000',
        ]);

        // Find or create customer user
        $customer = User::firstOrCreate(
            ['email' => $quote->guest_email],
            [
                'name' => $quote->guest_name,
                'role' => 'customer',
            ]
        );

        $car = Car::findOrFail($data['car_id']);
        if (!$car->isAvailableForDates($data['start_date'], $data['end_date'])) {
            return back()->withErrors(['car_id' => 'This vehicle is not available for the selected dates.'])
                         ->withInput();
        }

        $distanceKm = (int)($data['distance_km'] ?? 0);
        $surcharge  = Rental::calculateDistanceSurcharge($distanceKm);
        $days       = max(1, (int)((strtotime($data['end_date']) - strtotime($data['start_date'])) / 86400));
        $totalCost  = ($car->daily_rate * $days) + $surcharge;

        $rental = Rental::create([
            'car_id'              => $data['car_id'],
            'customer_user_id'    => $customer->id,
            'customer_name'       => $quote->guest_name,
            'rental_type'         => $data['rental_type'],
            'destination'         => $data['destination'] ?? null,
            'distance_km'         => $distanceKm,
            'distance_surcharge'  => $surcharge,
            'start_date'          => $data['start_date'],
            'end_date'            => $data['end_date'],
            'total_cost'          => $totalCost,
            'payment_status'      => 'unpaid',
            'amount_paid'         => 0,
            'status'              => 'reserved',
            'customer_notes'      => $quote->guest_notes ?? null,
            'admin_notes'         => $data['admin_notes'] ?? null,
        ]);

        // Update quote status to converted
        $quote->update(['status' => 'converted']);

        $today = now()->toDateString();
        if ($data['start_date'] <= $today && $data['end_date'] >= $today) {
            $car->update(['status' => 'rented']);
            $rental->update(['status' => 'ongoing']);
        }

        return redirect()->route('rentals.show', $rental)
                         ->with('success', 'Rental transaction created successfully from quote. Customer can now log in to view their booking.');
    }

    public function show(Rental $rental)
    {
        $rental->load(['car', 'customer', 'messages']);
        return view('rentals.show', compact('rental'));
    }

    public function contract(Rental $rental)
    {
        $rental->load('car');
        return view('rentals.contract', compact('rental'));
    }

    public function edit(Rental $rental)
    {
        $cars = Car::orderBy('brand')->get();
        return view('rentals.edit', compact('rental', 'cars'));
    }

    public function update(Request $request, Rental $rental)
    {
        $data = $request->validate([
            'customer_name'  => 'required|string|max:100',
            'rental_type'    => 'required|in:with_driver,self_drive',
            'destination'    => 'nullable|string|max:255',
            'distance_km'    => 'nullable|integer|min:0',
            'start_date'     => 'required|date',
            'end_date'       => 'required|date|after:start_date',
            'payment_status' => 'required|in:unpaid,partial,paid',
            'amount_paid'    => 'nullable|numeric|min:0',
            'status'         => 'required|in:reserved,ongoing,completed,cancelled',
            'customer_notes' => 'nullable|string|max:1000',
            'admin_notes'    => 'nullable|string|max:1000',
        ]);

        $distanceKm = (int)($data['distance_km'] ?? 0);
        $surcharge  = Rental::calculateDistanceSurcharge($distanceKm);
        $days       = max(1, (int)((strtotime($data['end_date']) - strtotime($data['start_date'])) / 86400));
        $totalCost  = ($rental->car->daily_rate * $days) + $surcharge;

        $rental->update(array_merge($data, [
            'distance_km'        => $distanceKm,
            'distance_surcharge' => $surcharge,
            'total_cost'         => $totalCost,
        ]));

        if ($data['status'] === 'completed' || $data['status'] === 'cancelled') {
            $rental->car->update(['status' => 'available']);
        } elseif ($data['status'] === 'ongoing') {
            $rental->car->update(['status' => 'rented']);
        }

        return redirect()->route('rentals.show', $rental)
                         ->with('success', 'Rental updated successfully.');
    }

    public function calendar()
    {
        $rentals = Rental::with('car')
            ->whereIn('status', ['reserved', 'ongoing', 'completed'])
            ->whereDate('end_date', '>=', now()->subDays(30))
            ->get()
            ->map(fn($r) => [
                'id'    => $r->id,
                'title' => "{$r->customer_name} — {$r->car->brand} {$r->car->model}",
                'start' => $r->start_date->toDateString(),
                'end'   => $r->end_date->addDay()->toDateString(),
                'color' => match($r->status) {
                    'ongoing'   => '#22C55E',
                    'reserved'  => '#38BDF8',
                    'completed' => '#555550',
                    default     => '#888880',
                },
                'url' => route('rentals.show', $r->id),
            ]);

        return view('rentals.calendar', compact('rentals'));
    }

    public function mastersheet(Request $request)
    {
        $query = Rental::with(['car.supplier'])->orderByDesc('start_date');

        if ($request->filled('from')) {
            $query->whereDate('start_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('end_date', '<=', $request->to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('supplier_id')) {
            $query->whereHas('car', fn($q) => $q->where('supplier_id', $request->supplier_id));
        }

        $rentals = $query->get();

        // Calculate commission totals
        $companyRevenue = 0;
        $partnerRevenue = 0;
        $totalCommission = 0;

        foreach ($rentals as $rental) {
            if ($rental->car && $rental->car->supplier) {
                if ($rental->car->supplier->isPartnerOwned() && $rental->car->supplier->commission_rate) {
                    $commission = $rental->car->supplier->calculateCommission($rental->total_cost);
                    $partnerRevenue += $rental->total_cost;
                    $totalCommission += $commission;
                } else {
                    $companyRevenue += $rental->total_cost;
                }
            } else {
                $companyRevenue += $rental->total_cost;
            }
        }

        $totals = [
            'total_cost'       => $rentals->sum('total_cost'),
            'amount_paid'      => $rentals->sum('amount_paid'),
            'balance'          => $rentals->sum(fn($r) => $r->total_cost - $r->amount_paid),
            'count'            => $rentals->count(),
            'company_revenue'  => $companyRevenue,
            'partner_revenue'  => $partnerRevenue,
            'total_commission' => $totalCommission,
            'net_revenue'      => $companyRevenue + ($partnerRevenue - $totalCommission),
        ];

        $suppliers = \App\Models\Supplier::active()->orderBy('name')->get();

        return view('rentals.mastersheet', compact('rentals', 'totals', 'suppliers'));
    }

    public function pendingApprovals()
    {
        // admin-only (guarded by middleware)
        
        $rentals = Rental::pending()
            ->with('car', 'customer', 'driver')
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('admin.rentals.pending-approvals', ['rentals' => $rentals]);
    }

    public function approve(Rental $rental)
    {
        // admin-only (guarded by middleware)
        
        if ($rental->status !== 'pending') {
            return back()->withErrors('Rental must be pending to approve');
        }

        // Check no overbooking
        $conflict = Rental::where('car_id', $rental->car_id)
            ->where('id', '!=', $rental->id)
            ->whereIn('status', ['reserved', 'approved', 'ongoing'])
            ->where(function ($q) use ($rental) {
                $q->whereBetween('start_date', [$rental->start_date, $rental->end_date])
                  ->orWhereBetween('end_date', [$rental->start_date, $rental->end_date])
                  ->orWhere(function ($q2) use ($rental) {
                      $q2->where('start_date', '<=', $rental->start_date)
                         ->where('end_date', '>=', $rental->end_date);
                  });
            })->exists();

        if ($conflict) {
            return back()->withErrors('Car has conflicting booking');
        }

        $rental->update(['status' => 'approved']);

        return back()->with('success', 'Rental approved. Customer notified.');
    }

    public function reject(Rental $rental, Request $request)
    {
        // admin-only (guarded by middleware)
        
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        if ($rental->status !== 'pending') {
            return back()->withErrors('Rental must be pending to reject');
        }

        $rental->update([
            'status' => 'cancelled',
            'admin_notes' => "Rejected: {$validated['rejection_reason']}",
        ]);

        return back()->with('success', 'Rental rejected.');
    }

    public function approveCancellation(Rental $rental)
    {
        // admin-only (guarded by middleware)
        
        if ($rental->status !== 'cancellation_requested') {
            return back()->withErrors('Rental must have cancellation request');
        }

        $rental->update([
            'status' => 'cancelled',
            'admin_notes' => "Cancellation approved. Refund: {$rental->refund_amount}",
        ]);

        return back()->with('success', 'Cancellation approved.');
    }

    public function rejectCancellation(Rental $rental)
    {
        // admin-only (guarded by middleware)
        
        if ($rental->status !== 'cancellation_requested') {
            return back()->withErrors('Rental must have cancellation request');
        }

        $rental->update([
            'status' => 'approved',
            'cancellation_requested_at' => null,
            'cancellation_reason' => null,
        ]);

        return back()->with('success', 'Cancellation request denied.');
    }

    // Document Management
    public function documentsForm(Rental $rental)
    {
        // admin-only (guarded by middleware)
        return view('rentals.documents', compact('rental'));
    }

    public function uploadContract(Request $request, Rental $rental)
    {
        // admin-only (guarded by middleware)
        
        $request->validate([
            'contract_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('contract_file')) {
            $path = $request->file('contract_file')->store("rentals/{$rental->id}/contract", 'private');
            $rental->update([
                'contract_file_path' => $path,
                'contract_status' => 'uploaded',
            ]);
        }

        return back()->with('success', 'Contract uploaded successfully.');
    }

    public function uploadId(Request $request, Rental $rental)
    {
        // admin-only (guarded by middleware)
        
        $request->validate([
            'id_file' => 'required|file|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('id_file')) {
            $path = $request->file('id_file')->store("rentals/{$rental->id}/id", 'private');
            $rental->update([
                'id_file_path' => $path,
                'id_status' => 'uploaded',
            ]);
        }

        return back()->with('success', 'ID uploaded successfully.');
    }

    public function verifyDocuments(Request $request, Rental $rental)
    {
        // admin-only (guarded by middleware)
        
        $validated = $request->validate([
            'document_type' => 'required|in:contract,id',
            'action' => 'required|in:verify,reject',
        ]);

        $isVerify = $validated['action'] === 'verify';
        $now = now();
        $userId = auth()->id();

        if ($validated['document_type'] === 'contract') {
            $rental->update([
                'contract_status' => $isVerify ? 'verified' : 'rejected',
                'contract_verified_at' => $isVerify ? $now : null,
                'contract_verified_by' => $isVerify ? $userId : null,
            ]);
        } else {
            $rental->update([
                'id_status' => $isVerify ? 'verified' : 'rejected',
                'id_verified_at' => $isVerify ? $now : null,
                'id_verified_by' => $isVerify ? $userId : null,
            ]);
        }

        // Check if both documents are verified and update status
        if ($isVerify && $rental->isDocumentsComplete()) {
            if ($rental->status === 'documents_pending') {
                $rental->update(['status' => 'documents_verified']);
            }
        }

        $message = $isVerify ? 'Document verified successfully.' : 'Document rejected.';
        return back()->with('success', $message);
    }

    public function requestDocuments(Rental $rental, Request $request)
    {
        // admin-only (guarded by middleware)
        
        $action = $request->input('action', 'request');
        
        if ($action === 'complete') {
            // Documents are complete, proceed to reserved
            if ($rental->isDocumentsComplete()) {
                $rental->update(['status' => 'reserved']);
                $rental->car->update(['status' => 'rented']);
                return redirect()->route('rentals.show', $rental)
                    ->with('success', 'Documents verified. Vehicle reserved.');
            }
            return back()->withErrors('Both documents must be verified first.');
        }
        
        // Request documents
        $rental->update(['status' => 'documents_pending']);
        return back()->with('success', 'Document request sent to customer.');
    }

    // Vehicle Release
    public function releaseVehicle(Rental $rental)
    {
        // admin-only (guarded by middleware)
        
        if (!$rental->canBeReleased()) {
            return back()->withErrors('Documents must be verified before releasing vehicle.');
        }

        $rental->update([
            'status' => 'ongoing',
            'vehicle_released_at' => now(),
            'released_by' => auth()->id(),
        ]);

        $rental->car->update(['status' => 'rented']);

        return redirect()->route('rentals.show', $rental)
            ->with('success', 'Vehicle released to customer.');
    }

    // Return Processing
    public function returnForm(Rental $rental)
    {
        // admin-only (guarded by middleware)
        
        if (!in_array($rental->status, ['ongoing', 'return_pending'])) {
            return redirect()->route('rentals.show', $rental)
                ->withErrors('Vehicle must be ongoing to process return.');
        }

        return view('rentals.return', compact('rental'));
    }

    public function processReturn(Request $request, Rental $rental)
    {
        // admin-only (guarded by middleware)
        
        $validated = $request->validate([
            'damage_panels' => 'required|integer|min:0',
            'damage_description' => 'nullable|string|max:1000',
            'damage_photos.*' => 'nullable|image|max:5120',
            'damage_rate_per_panel' => 'nullable|numeric|min:0',
            'fuel_level' => 'required|in:full,partial,empty',
            'mileage_returned' => 'nullable|integer|min:0',
            'mileage_start' => 'nullable|integer|min:0',
            'fuel_charge' => 'nullable|numeric|min:0',
            'late_return_charge' => 'nullable|numeric|min:0',
            'cleaning_charge' => 'nullable|numeric|min:0',
            'other_charges' => 'nullable|numeric|min:0',
            'other_charges_notes' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Handle damage photos
        $damagePhotos = [];
        if ($request->hasFile('damage_photos')) {
            foreach ($request->file('damage_photos') as $photo) {
                $damagePhotos[] = $photo->store("rentals/{$rental->id}/damage", 'private');
            }
        }

        // Calculate damage fee
        $damageRate = $validated['damage_rate_per_panel'] ?? 5000;
        $damageFee = $validated['damage_panels'] * $damageRate;

        // Calculate total additional charges
        $additionalCharges = 
            ($validated['fuel_charge'] ?? 0) +
            ($validated['late_return_charge'] ?? 0) +
            ($validated['cleaning_charge'] ?? 0) +
            ($validated['other_charges'] ?? 0);

        // Create return record
        $rental->rentalReturn()->create([
            'returned_at' => now(),
            'returned_by' => auth()->id(),
            'damage_panels' => $validated['damage_panels'],
            'damage_description' => $validated['damage_description'],
            'damage_photos' => $damagePhotos,
            'damage_fee' => $damageFee,
            'damage_rate_per_panel' => $damageRate,
            'fuel_level' => $validated['fuel_level'],
            'mileage_returned' => $validated['mileage_returned'],
            'mileage_start' => $validated['mileage_start'],
            'fuel_charge' => $validated['fuel_charge'] ?? 0,
            'late_return_charge' => $validated['late_return_charge'] ?? 0,
            'cleaning_charge' => $validated['cleaning_charge'] ?? 0,
            'other_charges' => $validated['other_charges'] ?? 0,
            'other_charges_notes' => $validated['other_charges_notes'],
            'total_additional_charges' => $additionalCharges,
            'notes' => $validated['notes'],
        ]);

        // Update rental and car status
        $rental->update(['status' => 'completed']);
        $rental->car->update(['status' => 'available']);

        return redirect()->route('rentals.show', $rental)
            ->with('success', 'Vehicle return processed successfully.');
    }

    // Download methods
    public function downloadContract(Rental $rental)
    {
        // admin-only (guarded by middleware)
        
        if (!$rental->contract_file_path || !Storage::disk('private')->exists($rental->contract_file_path)) {
            abort(404, 'Contract file not found.');
        }

        return Storage::disk('private')->download($rental->contract_file_path, "Contract_{$rental->rental_id_display}.pdf");
    }

    public function downloadId(Rental $rental)
    {
        // admin-only (guarded by middleware)
        
        if (!$rental->id_file_path || !Storage::disk('private')->exists($rental->id_file_path)) {
            abort(404, 'ID file not found.');
        }

        return Storage::disk('private')->download($rental->id_file_path, "ID_{$rental->rental_id_display}.jpg");
    }

    public function destroy(Rental $rental)
    {
        $rentalId = $rental->rental_id_display;

        // Delete associated files
        if ($rental->contract_file_path) {
            Storage::disk('private')->delete($rental->contract_file_path);
        }
        if ($rental->id_file_path) {
            Storage::disk('private')->delete($rental->id_file_path);
        }

        $rental->delete();

        return redirect()->route('rentals.index')
                         ->with('success', "Rental {$rentalId} has been deleted.");
    }
}