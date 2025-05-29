<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Painel de Administração - SuperCarRent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .top-nav { background: #343a40; padding: 10px 20px; color: white; }
        .top-nav a { color: white; margin-left: 15px; text-decoration: none; font-weight: 500; }
        .top-nav a:hover { text-decoration: underline; }
    </style>
</head>
<body>

    <nav class="top-nav d-flex justify-content-between align-items-center">
        <span class="fw-bold fs-4">SuperCarRent - Admin</span>
        <div>
            <a href="{{ route('home') }}">Início</a>
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sair</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </nav>

    <div class="container mt-4">

                <h2 class="mb-4">Histórico de Todas as Reservas</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif


        <table class="table table-bordered table-hover bg-white">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Carro</th>
                    <th>Início</th>
                    <th>Fim</th>
                    <th>Preço Total</th>
                    <th>Método</th>
                    <th>Status</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
@foreach($reservas as $r)
    <tr>
        <td>{{ $r->id }}</td>
        <td>{{ $r->nome_cliente }}</td>
        <td>{{ $r->carro->modelo ?? '-' }} ({{ $r->carro->marca->nome ?? '-' }})</td>
        <td>{{ $r->data_inicio }}</td>
        <td>{{ $r->data_fim }}</td>
        <td>{{ ucfirst($r->payment_method) }}</td>
        <td>{{ ucfirst($r->payment_status) }}</td>
        <td>{{ $r->created_at->format('d/m/Y H:i') }}</td>
        <td>
            {{-- Edit Button --}}
            <a href="{{ route('admin.reservas.edit', $r->id) }}" class=btn btn-sm style="background-color:gold; color:black; border:none;">Editar</a>

            {{-- Refund Button (only if paid) --}}
            @if($r->payment_status === 'paid')
                <form action="{{ route('admin.reservas.refund', $r->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Tem certeza que deseja reembolsar?');">
                    @csrf
                    <button class="btn btn-sm btn-warning mt-1">Reembolsar</button>
                </form>
            @endif
        </td>
    </tr>
@endforeach
</tbody>

        </table>
    </div>


</body>
@include('layouts.footer')
</html>
