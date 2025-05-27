<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Models\BemLocavel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservaController extends Controller
{
    /**
     * Store a new reservation.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bem_locavel_id' => 'required|exists:bens_locaveis,id',
            'nome_cliente'   => 'required|string|max:255',
            'email'          => 'required|email',
            'data_inicio'    => 'required|date|after_or_equal:today',
            'data_fim'       => 'required|date|after:data_inicio',
            'payment_method' => 'required|in:paypal,atm',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['payment_status'] = $validated['payment_method'] === 'atm' ? 'pending' : 'paid';

        // Create reservation
        Reserva::create($validated);

        // ❗ Mark car as unavailable
        BemLocavel::where('id', $validated['bem_locavel_id'])->update(['is_available' => false]);

        return back()->with('success', 'Reserva efetuada com sucesso!');
    }

    /**
     * Show all reservations of the authenticated user.
     */
    public function minhasReservas()
    {
        $reservas = Reserva::with(['carro.marca', 'carro.localizacoes'])
            ->where('user_id', Auth::id())
            ->get();

        return view('reservas.minhas', compact('reservas'));
    }

    /**
     * Show the form to edit a reservation.
     */
    public function edit($id)
    {
        $reserva = Reserva::with('carro')->findOrFail($id);

        if ((int) $reserva->user_id !== (int) Auth::id()) {
            abort(403, 'Acesso não autorizado à reserva.');
        }

        return view('reservas.edit', compact('reserva'));
    }

    /**
     * Update a reservation.
     */
    public function update(Request $request, $id)
    {
        $reserva = Reserva::findOrFail($id);

        if ((int) $reserva->user_id !== (int) Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'data_inicio' => 'required|date|after_or_equal:today',
            'data_fim'    => 'required|date|after:data_inicio',
        ]);

        $reserva->update($validated);

        return redirect()->route('reservas.minhas')->with('success', 'Reserva atualizada com sucesso.');
    }

    /**
     * Delete a reservation.
     */
    public function destroy($id)
    {
        $reserva = Reserva::findOrFail($id);

        if ((int) $reserva->user_id !== (int) Auth::id()) {
            abort(403);
        }

        // ❗ Make the car available again
        BemLocavel::where('id', $reserva->bem_locavel_id)->update(['is_available' => true]);

        

        $reserva->delete();

        return redirect()->route('reservas.minhas')->with('success', 'Reserva cancelada.');
    }

    /**
     * Store reservation from PayPal callback.
     */
    public function storePaypal(Request $request)
    {
        $data = $request->validate([
            'bem_locavel_id' => 'required|exists:bens_locaveis,id',
            'nome_cliente'   => 'required|string|max:255',
            'email'          => 'required|email',
            'data_inicio'    => 'required|date|after_or_equal:today',
            'data_fim'       => 'required|date|after:data_inicio',
        ]);

        $data['user_id'] = Auth::id();
        $data['payment_method'] = 'paypal';
        $data['payment_status'] = 'paid';
        $data['atm_reference'] = null;

        Reserva::create($data);

        // ❗ Mark car as unavailable
        BemLocavel::where('id', $data['bem_locavel_id'])->update(['is_available' => false]);

        return response()->json(['success' => true]);
    }
}
