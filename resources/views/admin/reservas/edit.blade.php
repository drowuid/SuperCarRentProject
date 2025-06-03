@extends('layouts.app')

@section('content')
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
    <div class="container py-5">
        <h2>Editar Reserva de {{ $reserva->nome_cliente }}</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.reservas.update', $reserva->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Data de Início</label>
                <input type="date" name="data_inicio" class="form-control" value="{{ $reserva->data_inicio }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Data de Fim</label>
                <input type="date" name="data_fim" class="form-control" value="{{ $reserva->data_fim }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Método de Pagamento</label>
                <select name="payment_method" class="form-select" required disabled>
                    <option value="paypal" {{ $reserva->payment_method === 'paypal' ? 'selected' : '' }}>PayPal</option>
                    <option value="atm" {{ $reserva->payment_method === 'atm' ? 'selected' : '' }}>Multibanco</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Status do Pagamento</label>
                <select name="payment_status" class="form-select" required>
                    <option value="paid" {{ $reserva->payment_status === 'paid' ? 'selected' : '' }}>Pago</option>
                    <option value="pending" {{ $reserva->payment_status === 'pending' ? 'selected' : '' }}>Pendente</option>
                    <option value="refunded" {{ $reserva->payment_status === 'refunded' ? 'selected' : '' }}>Reembolsado
                    </option>
                </select>
            </div>

            <button type="submit" class="btn" style="background-color:gold; color:black;">Salvar Alterações</button>
        </form>

        @if ($reserva->payment_status === 'paid')
            <form method="POST" action="{{ route('admin.reservas.refund', $reserva->id) }}" class="mt-3">
                @csrf
                <button class="btn" style="background-color:gold; color:black;"
                    onclick="return confirm('Tem certeza que deseja reembolsar esta reserva?')">Reembolsar
                    Pagamento</button>
            </form>
        @endif
    </div>
@endsection

<footer class="fixed bottom-0 left-0 w-full text-center py-4 bg-dark-custom text-white">
        &copy; {{ date('Y') }} SuperCarRent. Todos os direitos reservados.
    </footer>
