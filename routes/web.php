<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\BemLocavelController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ReservationConfirmationMailController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use App\Models\Reserva;


Route::get('/', [BemLocavelController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/minhas-reservas', [ReservaController::class, 'minhasReservas'])->name('reservas.minhas');
    Route::get('/minhas-reservas/historico', [ReservaController::class, 'historico'])->middleware('auth')->name('reservas.historico');


    // Create/store
    Route::post('/reserva', [ReservaController::class, 'store'])->name('reserva.store');

    // Send confirmation email
    Route::post('/send-email', [ReservationConfirmationMailController::class, 'sendReservationEmail'])->name('send.email');

    // Edit and update
    Route::get('/reservas/{id}/edit', [ReservaController::class, 'edit'])->name('reservas.edit');
    Route::put('/reservas/{id}', [ReservaController::class, 'update'])->name('reservas.update');

    // Delete
    Route::delete('/reservas/{id}', [ReservaController::class, 'destroy'])->name('reservas.destroy');

    // Car detail routes
    Route::get('/carro/{id}', [BemLocavelController::class, 'show']);
    Route::get('/cars/{id}', [CarController::class, 'show']);

    // PayPal routes
    Route::post('/paypal/pay', [PayPalController::class, 'payWithPayPal'])->name('paypal.pay');
    Route::get('/paypal/status', [PayPalController::class, 'paymentStatus'])->name('paypal.status');
    Route::get('/paypal/cancel', [PayPalController::class, 'paymentCancel'])->name('paypal.cancel');
    Route::post('/reserva/paypal', [ReservaController::class, 'storePaypal'])->name('reserva.paypal');

    // Admin routes
    Route::middleware(['auth', 'admin'])->group(function () {

    // Admin dashboard
    Route::get('/admin/dashboard', function () {
        $reservas = Reserva::with(['carro.marca'])->latest()->get();
        return view('admin.dashboard', compact('reservas'));
    })->name('admin.dashboard');

    // Admin can edit reservations
    Route::get('/admin/reservas/{id}/edit', [ReservaController::class, 'adminEdit'])->name('admin.reservas.edit');
    Route::put('/admin/reservas/{id}', [ReservaController::class, 'adminUpdate'])->name('admin.reservas.update');
    Route::post('/admin/reservas/{id}/refund', [ReservaController::class, 'adminRefund'])->name('admin.reservas.refund');
});

}); // <-- Close the 'auth' middleware group

require __DIR__.'/auth.php';
