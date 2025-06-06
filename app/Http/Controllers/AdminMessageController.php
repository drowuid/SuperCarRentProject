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
        // Get all messages grouped by user
        $messages = Message::with('user')->orderBy('created_at')->get()->groupBy('user_id');
        $reservas = Reserva::with(['carro.marca'])->latest()->get();

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

    // 🔁 Important: Return valid JSON to the AJAX fetch
    return response()->json(['status' => 'ok']);
}



    // AJAX endpoint
    public function fetch(User $user)
    {
        $messages = Message::where('user_id', $user->id)->orderBy('created_at')->get();

        return response()->json(
            $messages->map(function ($msg) {
                return [
                    'message' => $msg->message,
                    'is_admin' => $msg->is_admin,
                    'created_at' => $msg->created_at->format('d/m/Y H:i'),
                    'user_name' => $msg->user->name ?? 'User'
                ];
            })
        );
    }
}

