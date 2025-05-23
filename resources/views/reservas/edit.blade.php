<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Editar Reserva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .top-nav {
            background: #343a40;
            padding: 10px 20px;
            text-align: right;
        }
        .top-nav a {
            color: white;
            margin-left: 15px;
            text-decoration: none;
            font-weight: 500;
        }
        .top-nav a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    {{-- Navbar --}}
    <nav class="top-nav">
        <a href="{{ route('home') }}">Início</a>
        <a href="{{ route('reservas.minhas') }}">Gerir Reservas</a>
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sair</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </nav>

    <div class="container py-5">
        <h2 class="mb-4">Editar Reserva</h2>

        <form method="POST" action="{{ route('reservas.update', $reserva->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="data_inicio" class="form-label">Data de Início</label>
                <input type="date" name="data_inicio" id="data_inicio" class="form-control" value="{{ old('data_inicio', $reserva->data_inicio) }}" required>
            </div>

            <div class="mb-3">
                <label for="data_fim" class="form-label">Data de Fim</label>
                <input type="date" name="data_fim" id="data_fim" class="form-control" value="{{ old('data_fim', $reserva->data_fim) }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Atualizar Reserva</button>
            <a href="{{ route('reservas.minhas') }}" class="btn btn-secondary ms-2">Cancelar</a>
        </form>
    </div>

</body>
</html>
