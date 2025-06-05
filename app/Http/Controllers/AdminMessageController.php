<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use App\Models\Reserva;
use Illuminate\Http\Request;

class AdminMessageController extends Controller
{
    public function index()
    {
        // Get messages only from users that are not admins
        $messages = Message::whereHas('user', function ($query) {
                $query->where('is_admin', false); // Only regular users
            })
            ->with('user')
            ->orderBy('created_at')
            ->get()
            ->groupBy('user_id');

        // All reservations for admin view
        $reservas = Reserva::with('carro.marca')->latest()->get();

        return view('admin.dashboard', compact('messages', 'reservas'));
    }

    public function reply(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        Message::create([
            'user_id' => $user->id,
            'message' => $request->message,
            'is_admin' => true,
        ]);

        return back()->with('success', 'Mensagem enviada com sucesso.');
    }
}

