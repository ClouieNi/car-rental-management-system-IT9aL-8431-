<?php
namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::latest()->get();
        return view('cars.index', compact('cars'));
    }

    public function create()
    {
        return view('cars.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|unique:cars|max:20',
            'brand'        => 'required|max:50',
            'model'        => 'required|max:50',
            'year'         => 'required|digits:4|integer|min:1990|max:' . date('Y'),
            'daily_rate'   => 'required|numeric|min:0',
            'status'       => 'required|in:available,rented,maintenance',
        ]);

        Car::create($request->all());
        return redirect()->route('cars.index')->with('success', 'Car added successfully!');
    }

    public function edit(Car $car)
    {
        return view('cars.edit', compact('car'));
    }

    public function update(Request $request, Car $car)
    {
        $request->validate([
            'plate_number' => 'required|max:20|unique:cars,plate_number,' . $car->id,
            'brand'        => 'required|max:50',
            'model'        => 'required|max:50',
            'year'         => 'required|digits:4|integer|min:1990|max:' . date('Y'),
            'daily_rate'   => 'required|numeric|min:0',
            'status'       => 'required|in:available,rented,maintenance',
        ]);

        $car->update($request->all());
        return redirect()->route('cars.index')->with('success', 'Car updated successfully!');
    }

    public function destroy(Car $car)
    {
        $car->delete();
        return redirect()->route('cars.index')->with('success', 'Car deleted successfully!');
    }
}