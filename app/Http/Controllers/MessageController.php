<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $messages = Message::where('user_id', Auth::id())
                   ->orderBy('created_at', 'asc')
                   ->get();

return view('messages.index', compact('messages'));

    }

    public function store(Request $request)
{
    $request->validate([
        'message' => 'required|string|max:1000',
    ]);

    Message::create([
        'user_id' => Auth::id(),
        'message' => $request->message,
        'is_admin' => false,
    ]);

    return redirect()->route('messages.index')->with('success', 'Mensagem enviada com sucesso.');
}

    public function send(Request $request)
{
    $validated = $request->validate([
        'user_id' => 'nullable|exists:users,id', // Only set for admin
        'message' => 'required|string|max:1000',
    ]);

    $isAdmin = Auth::user()->is_admin;

    Message::create([
        'user_id' => $isAdmin ? $validated['user_id'] : Auth::id(),
        'sender_id' => Auth::id(),
        'message' => $validated['message'],
        'is_admin' => $isAdmin,
    ]);

    return response()->json(['status' => 'success']);
}

}


