<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\RentalController;
use Illuminate\Support\Facades\Route;

// ── Public: Quote Request ──────────────────────────────────
Route::get('/quote', [QuoteController::class, 'requestForm'])->name('quotes.request');
Route::post('/quote', [QuoteController::class, 'requestStore'])->name('quotes.request.store');
Route::get('/quote/thanks', [QuoteController::class, 'thanks'])->name('quotes.request.thanks');

// ── Authenticated Routes ───────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Profile (Breeze default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── ADMIN ONLY ───────────────────────────────────────
    Route::middleware(['admin'])->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Fleet Management (Cars)
        Route::resource('cars', CarController::class);

        // Rentals — specific routes BEFORE resource to avoid conflicts
        Route::get('/rentals/calendar',          [RentalController::class, 'calendar'])->name('rentals.calendar');
        Route::get('/rentals/mastersheet',       [RentalController::class, 'mastersheet'])->name('rentals.mastersheet');
        Route::get('/rentals/{rental}/contract', [RentalController::class, 'contract'])->name('rentals.contract');
        Route::resource('rentals', RentalController::class);

        // Quotes
        Route::get('/quotes',                   [QuoteController::class, 'index'])->name('quotes.index');
        Route::get('/quotes/{quote}',           [QuoteController::class, 'show'])->name('quotes.show');
        Route::patch('/quotes/{quote}/status',  [QuoteController::class, 'updateStatus'])->name('quotes.update-status');
        Route::get('/quotes/{quote}/convert',   [QuoteController::class, 'convertToRental'])->name('quotes.convert');

        // Messages
        Route::get('/messages',                  [MessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{message}',        [MessageController::class, 'show'])->name('messages.show');
        Route::post('/messages/{message}/reply', [MessageController::class, 'reply'])->name('messages.reply');
    });

    // ── CUSTOMER PORTAL ──────────────────────────────────
    Route::prefix('my')->name('customer.')->group(function () {
        Route::get('/dashboard',        [CustomerController::class, 'dashboard'])->name('dashboard');
        Route::get('/rentals',          [CustomerController::class, 'rentals'])->name('rentals');
        Route::get('/rentals/{rental}', [CustomerController::class, 'rentalShow'])->name('rental-show');
        Route::get('/messages',         [CustomerController::class, 'messages'])->name('messages');
        Route::post('/messages',        [CustomerController::class, 'sendMessage'])->name('send-message');
    });
});

// ── Breeze Auth Routes (already included by Breeze itself) ──
require __DIR__.'/auth.php';