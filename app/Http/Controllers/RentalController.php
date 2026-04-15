<?php
namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Rental;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RentalController extends Controller
{
    public function index()
    {
        $rentals = Rental::with('car')->latest()->get();
        return view('rentals.index', compact('rentals'));
    }

    public function create()
    {
        $cars = Car::where('status', 'available')->get();
        return view('rentals.create', compact('cars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'car_id'        => 'required|exists:cars,id',
            'customer_name' => 'required|max:100',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'status'        => 'required|in:ongoing,completed,cancelled',
        ]);

        $car = Car::findOrFail($request->car_id);
        $days = max(Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date)), 1);
        $total = $car->daily_rate * $days;

        Rental::create([
            'car_id'        => $request->car_id,
            'customer_name' => $request->customer_name,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
            'total_cost'    => $total,
            'status'        => $request->status,
        ]);

        if ($request->status === 'ongoing') {
            $car->update(['status' => 'rented']);
        }

        return redirect()->route('rentals.index')->with('success', 'Rental created successfully!');
    }

    public function edit(Rental $rental)
    {
        $cars = Car::all();
        return view('rentals.edit', compact('rental', 'cars'));
    }

    public function update(Request $request, Rental $rental)
    {
        $request->validate([
            'car_id'        => 'required|exists:cars,id',
            'customer_name' => 'required|max:100',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after_or_equal:start_date',
            'status'        => 'required|in:ongoing,completed,cancelled',
        ]);

        $car = Car::findOrFail($request->car_id);
        $days = max(Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date)), 1);
        $total = $car->daily_rate * $days;

        $rental->update([
            'car_id'        => $request->car_id,
            'customer_name' => $request->customer_name,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
            'total_cost'    => $total,
            'status'        => $request->status,
        ]);

        if ($request->status === 'ongoing') {
            $car->update(['status' => 'rented']);
        } else {
            $car->update(['status' => 'available']);
        }

        return redirect()->route('rentals.index')->with('success', 'Rental updated successfully!');
    }

    public function destroy(Rental $rental)
    {
        $rental->car->update(['status' => 'available']);
        $rental->delete();
        return redirect()->route('rentals.index')->with('success', 'Rental deleted successfully!');
    }
}