<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\BemLocavelController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ReservationConfirmationMailController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PayPalController;

Route::get('/', [BemLocavelController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::middleware('auth')->group(function () {
    Route::get('/minhas-reservas', [ReservaController::class, 'minhasReservas'])->name('reservas.minhas');

    // Create/store
    Route::post('/reserva', [ReservaController::class, 'store'])->name('reserva.store');

    // Send confirmation email
    Route::post('/send-email', [ReservationConfirmationMailController::class, 'sendReservationEmail'])->name('send.email');

    // Edit and update
    Route::get('/reservas/{id}/edit', [ReservaController::class, 'edit'])->name('reservas.edit');
    Route::put('/reservas/{id}', [ReservaController::class, 'update'])->name('reservas.update');

    // Delete
    Route::delete('/reservas/{id}', [ReservaController::class, 'destroy'])->name('reservas.destroy');
    });

    Route::get('/carro/{id}', [BemLocavelController::class, 'show']);
    Route::get('/cars/{id}', [CarController::class, 'show']);

    Route::post('/paypal/pay', [PayPalController::class, 'payWithPayPal'])->name('paypal.pay');
Route::get('/paypal/status', [PayPalController::class, 'paymentStatus'])->name('paypal.status');
Route::get('/paypal/cancel', [PayPalController::class, 'paymentCancel'])->name('paypal.cancel');
Route::post('/reserva/paypal', [ReservaController::class, 'storePaypal'])->name('reserva.paypal');


});

require __DIR__.'/auth.php';
