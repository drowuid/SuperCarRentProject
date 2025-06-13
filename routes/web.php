<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\BemLocavelController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\ReservationConfirmationMailController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AdminMessageController;
use App\Http\Middleware\UserMiddleware;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;
use App\Models\Reserva;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

Route::get('/', [BemLocavelController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', UserMiddleware::class])->group(function () {
    // ✅ User profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ✅ User Help Chat (Pedir Ajuda)
    Route::get('/pedir-ajuda', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/pedir-ajuda', [MessageController::class, 'store'])->name('messages.store');

    // ✅ Fatura
    Route::get('/reservas/{id}/fatura', [ReservaController::class, 'downloadFatura'])->name('reservas.fatura');
});

// ✅ Custom routes for updating password and uploading photo
Route::put('/user/password', [ProfileController::class, 'updatePassword'])->name('password.update');
Route::post('/profile/photo', [ProfileController::class, 'uploadPhoto'])->name('profile.uploadPhoto');

// ✅ Reservas
Route::get('/minhas-reservas', [ReservaController::class, 'minhasReservas'])->name('reservas.minhas');
Route::get('/minhas-reservas/historico', [ReservaController::class, 'historico'])->name('reservas.historico');
Route::post('/reserva', [ReservaController::class, 'store'])->middleware('auth')->name('reserva.store');
Route::post('/send-email', [ReservationConfirmationMailController::class, 'sendReservationEmail'])->name('send.email');
Route::get('/reservas/{id}/edit', [ReservaController::class, 'edit'])->name('reservas.edit');
Route::put('/reservas/{id}', [ReservaController::class, 'update'])->name('reservas.update');
Route::delete('/reservas/{id}', [ReservaController::class, 'destroy'])->name('reservas.destroy');

// ✅ Car Detail
Route::get('/carro/{id}', [BemLocavelController::class, 'show']);
Route::get('/cars/{id}', [CarController::class, 'show']);

// ✅ PayPal
Route::post('/paypal/pay', [PayPalController::class, 'payWithPayPal'])->name('paypal.pay');
Route::get('/paypal/status', [PayPalController::class, 'paymentStatus'])->name('paypal.status');
Route::get('/paypal/cancel', [PayPalController::class, 'paymentCancel'])->name('paypal.cancel');
Route::post('/reserva/paypal', [ReservaController::class, 'storePaypal'])->name('reserva.paypal');

// ✅ Admin-only routes
Route::middleware(['admin'])->group(function () {

    // ✅ Admin Dashboard
    Route::get('/admin/dashboard', function () {
        $reservas = Reserva::with(['carro.marca', 'user'])->latest()->get();
        $messages = Message::with('user')->latest()->get()->groupBy('user_id');
        return view('admin.dashboard', compact('reservas', 'messages'));
    })->name('admin.dashboard');

    // ✅ Admin Reservas Management
    Route::get('/admin/reservas/{id}/edit', [ReservaController::class, 'adminEdit'])->name('admin.reservas.edit');
    Route::put('/admin/reservas/{id}', [ReservaController::class, 'adminUpdate'])->name('admin.reservas.update');
    Route::post('/admin/reservas/{id}/refund', [ReservaController::class, 'adminRefund'])->name('admin.reservas.refund');

    // ✅ Admin Chat Management
    Route::get('/admin/messages', [AdminMessageController::class, 'index'])->name('admin.messages');
    Route::post('/admin/messages/{user}', [AdminMessageController::class, 'reply'])->name('admin.messages.reply');
    Route::get('/admin/messages/{user}/fetch', [AdminMessageController::class, 'fetch'])->name('admin.messages.fetch');
});

require __DIR__.'/auth.php';
