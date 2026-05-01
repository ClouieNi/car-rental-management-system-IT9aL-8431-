<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\RentalController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ── Auth Routes (Breeze) ───────────────────────────────────
require __DIR__.'/auth.php';

// Convenience: GET /logout so typing it in the URL bar works (POST /logout still used by buttons)
Route::get('/logout', function () {
    Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout.get');

// ── Public: Landing & Quote Request ────────────────────────
Route::get('/landing', function () {
    return view('welcome');
})->name('landing');

Route::get('/quote', [QuoteController::class, 'requestForm'])->name('quotes.request');
Route::post('/quote', [QuoteController::class, 'requestStore'])->name('quotes.request.store');
Route::get('/quote/thanks', [QuoteController::class, 'thanks'])->name('quotes.request.thanks');

// ── Authenticated Routes ───────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Profile (Breeze default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ── CUSTOMER BOOKING ──────────────────────────────────
    Route::prefix('book')->name('book.')->group(function () {
        Route::get('/', [CustomerController::class, 'createBooking'])->name('create');
        Route::post('/', [CustomerController::class, 'storeBooking'])->name('store');
        Route::get('/{rental}/confirmation', [CustomerController::class, 'bookingConfirmation'])->name('confirmation');
    });

    // ── ADMIN ONLY ───────────────────────────────────────
    Route::middleware(['admin'])->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Fleet Management (Cars & Suppliers)
        Route::resource('cars', CarController::class);
        Route::resource('suppliers', SupplierController::class);

        // Rentals — specific routes BEFORE resource to avoid conflicts
        Route::get('/rentals/pending',          [RentalController::class, 'pendingApprovals'])->name('rentals.pending');
        Route::patch('/rentals/{rental}/approve', [RentalController::class, 'approve'])->name('rentals.approve');
        Route::patch('/rentals/{rental}/reject',  [RentalController::class, 'reject'])->name('rentals.reject');
        Route::patch('/rentals/{rental}/cancellation/approve', [RentalController::class, 'approveCancellation'])->name('rentals.cancellation.approve');
        Route::patch('/rentals/{rental}/cancellation/reject',  [RentalController::class, 'rejectCancellation'])->name('rentals.cancellation.reject');
        Route::get('/rentals/calendar',          [RentalController::class, 'calendar'])->name('rentals.calendar');
        Route::get('/rentals/mastersheet',       [RentalController::class, 'mastersheet'])->name('rentals.mastersheet');
        Route::get('/rentals/{rental}/contract', [RentalController::class, 'contract'])->name('rentals.contract');
        
        // Document Management
        Route::get('/rentals/{rental}/documents', [RentalController::class, 'documentsForm'])->name('rentals.documents');
        Route::patch('/rentals/{rental}/upload-contract', [RentalController::class, 'uploadContract'])->name('rentals.upload-contract');
        Route::patch('/rentals/{rental}/upload-id', [RentalController::class, 'uploadId'])->name('rentals.upload-id');
        Route::patch('/rentals/{rental}/verify-documents', [RentalController::class, 'verifyDocuments'])->name('rentals.verify-documents');
        Route::patch('/rentals/{rental}/request-documents', [RentalController::class, 'requestDocuments'])->name('rentals.request-documents');
        Route::get('/rentals/{rental}/download-contract', [RentalController::class, 'downloadContract'])->name('rentals.download-contract');
        Route::get('/rentals/{rental}/download-id', [RentalController::class, 'downloadId'])->name('rentals.download-id');
        
        // Vehicle Release & Return
        Route::patch('/rentals/{rental}/release', [RentalController::class, 'releaseVehicle'])->name('rentals.release');
        Route::get('/rentals/{rental}/return', [RentalController::class, 'returnForm'])->name('rentals.return-form');
        Route::post('/rentals/{rental}/return', [RentalController::class, 'processReturn'])->name('rentals.return');
        
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
        Route::post('/rentals/{rental}/cancel-request', [CustomerController::class, 'requestCancellation'])->name('rental.cancel-request');
        
        // Customer Document Upload
        Route::get('/rentals/{rental}/documents', [CustomerController::class, 'documentsForm'])->name('rental.documents');
        Route::post('/rentals/{rental}/documents', [CustomerController::class, 'uploadDocuments'])->name('rental.upload-documents');
        
        Route::get('/messages',         [CustomerController::class, 'messages'])->name('messages');
        Route::post('/messages',        [CustomerController::class, 'sendMessage'])->name('send-message');
    });
});

