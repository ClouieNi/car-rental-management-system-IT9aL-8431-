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
        $query = Rental::with('car')->orderByDesc('start_date');

        if ($request->filled('from')) {
            $query->whereDate('start_date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('end_date', '<=', $request->to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $rentals = $query->get();

        $totals = [
            'total_cost'  => $rentals->sum('total_cost'),
            'amount_paid' => $rentals->sum('amount_paid'),
            'balance'     => $rentals->sum(fn($r) => $r->total_cost - $r->amount_paid),
            'count'       => $rentals->count(),
        ];

        return view('rentals.mastersheet', compact('rentals', 'totals'));
    }
}