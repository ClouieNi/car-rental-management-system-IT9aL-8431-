<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\RentalController;

// Public routes
Route::get('/', function () { return redirect()->route('cars.index'); })->name('home');
Route::get('/about', function () { return view('about'); })->name('about');

// Authenticated routes
Route::middleware(['auth'])->group(function () {

    // Cars — view only (admin & user)
    Route::get('/cars', [CarController::class, 'index'])->name('cars.index');

    // Rentals — view only (admin & user)
    Route::get('/rentals', [RentalController::class, 'index'])->name('rentals.index');

    // Admin only
    Route::middleware(['admin'])->group(function () {

        // Cars CRUD
        Route::get('/cars/create', [CarController::class, 'create'])->name('cars.create');
        Route::post('/cars', [CarController::class, 'store'])->name('cars.store');
        Route::get('/cars/{car}/edit', [CarController::class, 'edit'])->name('cars.edit');
        Route::put('/cars/{car}', [CarController::class, 'update'])->name('cars.update');
        Route::delete('/cars/{car}', [CarController::class, 'destroy'])->name('cars.destroy');

        // Rentals CRUD
        Route::get('/rentals/create', [RentalController::class, 'create'])->name('rentals.create');
        Route::post('/rentals', [RentalController::class, 'store'])->name('rentals.store');
        Route::get('/rentals/{rental}/edit', [RentalController::class, 'edit'])->name('rentals.edit');
        Route::put('/rentals/{rental}', [RentalController::class, 'update'])->name('rentals.update');
        Route::delete('/rentals/{rental}', [RentalController::class, 'destroy'])->name('rentals.destroy');
    });
});

// Redirect /dashboard to cars index (Breeze default after login)
Route::get('/dashboard', function () {
    return redirect()->route('cars.index');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';