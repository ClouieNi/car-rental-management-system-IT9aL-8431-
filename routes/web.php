<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CarController;
use App\Http\Controllers\RentalController;

Route::get('/', function () { return view('home'); })->name('home');
Route::get('/about', function () { return view('about'); })->name('about');
Route::resource('cars', CarController::class)->except(['show']);
Route::resource('rentals', RentalController::class)->except(['show']);