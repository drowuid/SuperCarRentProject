<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Fatura - Reserva #{{ $reserva->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 30px; }
        .info, .details { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background-color: #f4f4f4; }
    </style>
</head>
<body>
    <div class="header">
        <h2>SuperCarRent - Fatura de Reserva</h2>
        <p><strong>Reserva ID:</strong> #{{ $reserva->id }}</p>
    </div>

    <div class="info">
        <p><strong>Cliente:</strong> {{ $reserva->nome_cliente }}</p>
        <p><strong>Email:</strong> {{ $reserva->email }}</p>
        <p><strong>Método de Pagamento:</strong> {{ ucfirst($reserva->payment_method) }}</p>
        <p><strong>Status:</strong> {{ ucfirst($reserva->payment_status) }}</p>
    </div>

    <div class="details">
        <h4>Detalhes do Carro</h4>
        <p><strong>Modelo:</strong> {{ $reserva->carro->modelo }}</p>
        <p><strong>Marca:</strong> {{ $reserva->carro->marca->nome }}</p>
        <p><strong>Preço Diário:</strong> €{{ number_format($reserva->carro->preco_diario, 2, ',', '.') }}</p>

        <table>
            <thead>
                <tr>
                    <th>Início</th>
                    <th>Fim</th>
                    <th>Dias</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $inicio = \Carbon\Carbon::parse($reserva->data_inicio);
                    $fim = \Carbon\Carbon::parse($reserva->data_fim);
                    $dias = $inicio->diffInDays($fim);
                    $total = $dias * $reserva->carro->preco_diario;
                @endphp
                <tr>
                    <td>{{ $inicio->format('d/m/Y') }}</td>
                    <td>{{ $fim->format('d/m/Y') }}</td>
                    <td>{{ $dias }}</td>
                    <td>€{{ number_format($total, 2, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <p><small>Emitido em {{ now()->format('d/m/Y H:i') }}</small></p>
</body>
</html>
