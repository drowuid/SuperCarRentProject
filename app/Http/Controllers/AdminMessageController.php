<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMessageController extends Controller
{
    public function index() {
    $users = User::whereHas('messages')->with('messages')->get();
    $reservas = Reserva::with('carro.marca')->latest()->get();
    return view('admin.painel-admin', compact('users', 'reservas'));
}

    public function store(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Message::create([
            'sender_id' => Auth::id(),      // admin
            'receiver_id' => $user->id,     // user to respond to
            'message' => $request->message,
        ]);

        return back()->with('success', 'Mensagem enviada ao usuário.');
    }

    public function reply(Request $request, User $user) {
    $request->validate(['message' => 'required|string|max:1000']);
    Message::create([
        'user_id' => $user->id,
        'message' => $request->message,
        'is_admin' => true,
    ]);
    return back()->with('success', 'Mensagem enviada.');
}
}
