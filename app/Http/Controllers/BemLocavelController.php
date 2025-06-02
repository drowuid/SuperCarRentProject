<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BemLocavel;

class BemLocavelController extends Controller
{
    public function index(Request $request)
{
    $query = BemLocavel::with(['marca', 'localizacoes', 'caracteristicas'])
        ->where('is_available', true);

    // Filter by city
    if ($request->filled('cidade')) {
        $query->whereHas('localizacoes', function ($q) use ($request) {
            $q->where('cidade', 'like', '%' . $request->cidade . '%');
        });
    }

    // Filter by brand
    if ($request->filled('marca')) {
        $query->whereHas('marca', function ($q) use ($request) {
            $q->where('nome', 'like', '%' . $request->marca . '%');
        });
    }

    // ✅ Corrected price range filter using correct request parameter names
    if ($request->filled('price_min') && $request->filled('price_max')) {
        $query->whereBetween('preco_diario', [
            (float) $request->price_min,
            (float) $request->price_max,
        ]);
    }

    $carros = $query->get();

    return view('home', compact('carros'));
}



    public function show($id)
    {
        $carro = BemLocavel::with(['marca', 'localizacoes', 'caracteristicas'])->findOrFail($id);
        return view('carro', compact('carro'));
    }
}
