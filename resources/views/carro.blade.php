<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>{{ $carro->modelo }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">

    <h1>{{ $carro->modelo }} ({{ $carro->marca->nome }})</h1>
    <p><strong>Preço:</strong> €{{ $carro->preco_diario }}/dia</p>
    <p><strong>Cor:</strong> {{ $carro->cor }}</p>
    <p><strong>Transmissão:</strong> {{ $carro->transmissao }}</p>
    <p><strong>Combustível:</strong> {{ $carro->combustivel }}</p>

    <h3>Localizações:</h3>
    <ul>
        @foreach($carro->localizacoes as $loc)
            <li>{{ $loc->cidade }} - {{ $loc->filial }} ({{ $loc->posicao }})</li>
        @endforeach
    </ul>

    <h3>Características:</h3>
    <ul>
        @foreach($carro->caracteristicas as $c)
            <li>{{ $c->nome }}</li>
        @endforeach
    </ul>

    <a href="{{ url('/') }}">← Voltar à lista</a>

    <hr>

    {{-- Booking Form Section --}}
    <h3>Reservar este carro</h3>

    {{-- Success message --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Display validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('reserva.store') }}" method="POST" class="mb-5">
        @csrf
        <input type="hidden" name="bem_locavel_id" value="{{ $carro->id }}">

        <div class="mb-3">
            <label for="nome_cliente" class="form-label">Nome</label>
            <input type="text" name="nome_cliente" class="form-control" value="{{ old('nome_cliente') }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
        </div>

        <div class="mb-3">
            <label for="data_inicio" class="form-label">Data de Início</label>
            <input type="date" name="data_inicio" class="form-control" value="{{ old('data_inicio') }}" required>
        </div>

        <div class="mb-3">
            <label for="data_fim" class="form-label">Data de Fim</label>
            <input type="date" name="data_fim" class="form-control" value="{{ old('data_fim') }}" required>
        </div>

        <button type="submit" class="btn btn-success">Reservar</button>
    </form>

</body>
</html>
