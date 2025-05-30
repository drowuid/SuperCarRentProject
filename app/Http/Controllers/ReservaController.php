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
    $userId = Auth::id();

    $reservasAtivas = Reserva::with('carro.marca', 'carro.localizacoes')
    ->where('user_id', Auth::id())
    ->where('payment_status', '!=', 'refunded')
    ->get();

$reservasHistorico = Reserva::with('carro.marca', 'carro.localizacoes')
    ->where('user_id', Auth::id())
    ->where('payment_status', 'refunded')
    ->get();

return view('reservas.minhas', compact('reservasAtivas', 'reservasHistorico'));

}


    /**Rent History for client */
    public function historico()
{
    $reservas = Reserva::with('carro.marca')
                ->where('user_id', Auth::id())
                ->whereIn('payment_status', ['paid', 'refunded'])
                ->latest()
                ->get();

    return view('reservas.historico', compact('reservas'));
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
    $reserva = Reserva::with('carro')->findOrFail($id);

    if ((int) $reserva->user_id !== (int) Auth::id()) {
        abort(403);
    }

    // ✅ Make car available again
    if ($reserva->carro) {
        $reserva->carro->is_available = true;
        $reserva->carro->save();
    }

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

    public function adminEdit($id)
{
    $reserva = Reserva::with('carro')->findOrFail($id);
    return view('admin.edit-reserva', compact('reserva'));
}

public function adminUpdate(Request $request, $id)
{
    $reserva = Reserva::with('carro')->findOrFail($id);

    $validated = $request->validate([
        'data_inicio' => 'required|date',
        'data_fim' => 'required|date|after:data_inicio',
        'payment_status' => 'required|in:pending,paid,refunded',

    ]);

    // Update reserva
    $reserva->update([
        'data_inicio' => $validated['data_inicio'],
        'data_fim' => $validated['data_fim'],
        'payment_status' => $validated['payment_status'],
    ]);

    // Update the car's price
    if ($reserva->carro) {
        $reserva->carro->save();
    }

    return redirect()->route('admin.dashboard')->with('success', 'Reserva atualizada com sucesso.');
}



public function adminRefund($id)
{
    $reserva = Reserva::with('carro')->findOrFail($id);

    // Only refund if it's paid
    if ($reserva->payment_status !== 'paid') {
        return back()->with('error', 'Reserva não está paga.');
    }

    $reserva->payment_status = 'refunded';
    $reserva->save();

    // ✅ Make car available again
    if ($reserva->carro) {
        $reserva->carro->is_available = true;
        $reserva->carro->save();
    }

    return back()->with('success', 'Reserva reembolsada com sucesso.');
}





}
