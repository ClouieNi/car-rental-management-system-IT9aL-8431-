<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('vehicle_type', $request->type);
        }
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sq) use ($q) {
                $sq->where('brand', 'like', "%$q%")
                   ->orWhere('model', 'like', "%$q%")
                   ->orWhere('plate_number', 'like', "%$q%");
            });
        }

        $cars = $query->orderBy('brand')->paginate(12)->withQueryString();

        return view('cars.index', compact('cars'));
    }

    public function create()
    {
        return view('cars.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'plate_number'     => 'required|string|max:20|unique:cars,plate_number',
            'brand'            => 'required|string|max:50',
            'model'            => 'required|string|max:50',
            'year'             => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'vehicle_type'     => 'required|in:sedan,suv,mpv,pickup,van,other',
            'transmission'     => 'required|in:automatic,manual',
            'fuel_type'        => 'required|in:gasoline,diesel,electric',
            'seating_capacity' => 'required|integer|min:2|max:20',
            'daily_rate'       => 'required|numeric|min:0',
            'status'           => 'required|in:available,rented,maintenance',
            'description'      => 'nullable|string|max:500',
            'image'            => 'nullable|image|max:3072',
        ]);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('cars', 'public');
        }
        unset($data['image']);

        $car = Car::create($data);

        return redirect()->route('cars.show', $car)
                         ->with('success', "Vehicle {$car->display_name} added to fleet.");
    }

    public function show(Car $car)
    {
        $car->load(['rentals' => fn($q) => $q->orderByDesc('start_date')->limit(10)]);
        return view('cars.show', compact('car'));
    }

    public function edit(Car $car)
    {
        return view('cars.edit', compact('car'));
    }

    public function update(Request $request, Car $car)
    {
        $data = $request->validate([
            'plate_number'     => 'required|string|max:20|unique:cars,plate_number,' . $car->id,
            'brand'            => 'required|string|max:50',
            'model'            => 'required|string|max:50',
            'year'             => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'vehicle_type'     => 'required|in:sedan,suv,mpv,pickup,van,other',
            'transmission'     => 'required|in:automatic,manual',
            'fuel_type'        => 'required|in:gasoline,diesel,electric',
            'seating_capacity' => 'required|integer|min:2|max:20',
            'daily_rate'       => 'required|numeric|min:0',
            'status'           => 'required|in:available,rented,maintenance',
            'description'      => 'nullable|string|max:500',
            'image'            => 'nullable|image|max:3072',
        ]);

        if ($request->hasFile('image')) {
            if ($car->image_path) {
                Storage::disk('public')->delete($car->image_path);
            }
            $data['image_path'] = $request->file('image')->store('cars', 'public');
        }
        unset($data['image']);

        $car->update($data);

        return redirect()->route('cars.show', $car)
                         ->with('success', 'Vehicle updated successfully.');
    }

    public function destroy(Car $car)
    {
        if ($car->activeRental()->exists()) {
            return back()->with('error', 'Cannot delete a vehicle with an active rental.');
        }

        if ($car->image_path) {
            Storage::disk('public')->delete($car->image_path);
        }

        $car->delete();

        return redirect()->route('cars.index')
                         ->with('success', 'Vehicle removed from fleet.');
    }
}