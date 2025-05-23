<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Minhas Reservas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .top-nav {
            background: #343a40;
            padding: 10px 20px;
        }
        .card-img-left {
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body>

    {{-- Top Navbar --}}
    <nav class="top-nav d-flex justify-content-between align-items-center px-3" style="background:#343a40; padding:10px 20px;">
        <!-- Company name on the left -->
        <span class="text-white fw-bold fs-4" aria-label="{{ config('app.name') }} - SuperRentCar">
            {{ config('app.name') }}
        </span>

        <!-- Navigation and user links on the right -->
        <div>
            @guest
                <a href="{{ route('login') }}" class="text-white">Login</a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-white ms-3">Register</a>
                @endif
            @else
                <a href="{{ route('home') }}" class="text-white">Início</a>
                <a href="{{ route('reservas.minhas') }}" class="text-white ms-3">Gerir Reservas</a>
                <span class="text-white ms-3">Olá, {{ Auth::user()->name }}</span>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="text-white ms-3"
                   role="button"
                   tabindex="0">
                   Sair
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            @endguest
        </div>
    </nav>

    <div class="container py-5">
    <h2 class="mb-4 text-center">Gerir Reservas</h2>

    @if($reservas->isEmpty())
        <div class="alert alert-info text-center">
            Você ainda não fez nenhuma reserva.
        </div>
    @else
        <div class="row">
            @foreach($reservas as $reserva)
                @php $carro = $reserva->carro; @endphp

                <div class="col-md-6 mb-4">
                    <div class="card h-100 shadow">
                        <div class="row g-0">
                            @if($carro->imagem)
                                <div class="col-md-5">
                                    <img src="{{ $carro->imagem }}"
                                         alt="{{ $carro->modelo }}"
                                         class="img-fluid card-img-left w-100">
                                </div>
                            @endif
                            <div class="{{ $carro->imagem ? 'col-md-7' : 'col-md-12' }}">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $carro->modelo }} ({{ $carro->marca->nome }})</h5>
                                    <p class="mb-1"><strong>Período:</strong><br>
                                        {{ \Carbon\Carbon::parse($reserva->data_inicio)->format('d/m/Y') }}
                                        a
                                        {{ \Carbon\Carbon::parse($reserva->data_fim)->format('d/m/Y') }}
                                    </p>
                                    <p class="mb-1"><strong>Preço diário:</strong> €{{ $carro->preco_diario }}</p>
                                    <p class="mb-2"><strong>Localizações:</strong><br>
                                        @foreach($carro->localizacoes as $loc)
                                            <span class="badge bg-secondary">{{ $loc->cidade }}</span>
                                        @endforeach
                                    </p>
                                    <small class="text-muted d-block mb-2">Reservado em {{ $reserva->created_at->format('d/m/Y H:i') }}</small>

                                    <a href="{{ route('reservas.edit', $reserva->id) }}" class="btn btn-sm btn-outline-primary me-2">Editar</a>

                                    <form action="{{ route('reservas.destroy', $reserva->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja cancelar esta reserva?')">Cancelar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>
    @endif
</div>


    <footer class="text-center py-4 bg-dark text-white">
        &copy; {{ date('Y') }} SuperCarRent. Todos os direitos reservados.
    </footer>

</body>
</html>

