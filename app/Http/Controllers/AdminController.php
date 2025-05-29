<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Reserva;

class AdminController extends Controller
{
    public function dashboard()
    {
        $reservas = Reserva::with(['carro.marca', 'carro.localizacoes', 'user'])->latest()->get();
        return view('admin.dashboard', compact('reservas'));
    }
}

