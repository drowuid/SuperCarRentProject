<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Minhas Reservas</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
        /* Custom class for dark background if 'bg-dark' isn't working directly from Bootstrap/Tailwind */
        .bg-dark-custom {
            background-color: #343a40; /* Standard dark background color */
        }
    </style>
</head>

<body>

    {{-- Top Navbar --}}
    <nav class="top-nav d-flex justify-content-between align-items-center px-3">
        <span class="text-white fw-bold fs-4">{{ config('app.name') }}</span>
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
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-white ms-3">Sair</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            @endguest
        </div>
    </nav>

    <div class="container py-5">
        <h2 class="mb-4 text-center">Gerir Reservas</h2>

        <ul class="nav nav-tabs" id="reservasTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="ativas-tab" data-bs-toggle="tab" data-bs-target="#ativas" type="button" role="tab">Ativas</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="historico-tab" data-bs-toggle="tab" data-bs-target="#historico" type="button" role="tab">Histórico</button>
            </li>
        </ul>

        <div class="tab-content mt-4">
            {{-- Ativas --}}
            <div class="tab-pane fade show active" id="ativas" role="tabpanel">
                @isset($reservasAtivas)
                    @include('reservas.partials.lista', ['reservas' => $reservasAtivas])
                @else
                    <div class="alert alert-warning">Nenhuma reserva ativa encontrada.</div>
                @endisset
            </div>

            {{-- Histórico --}}
            <div class="tab-pane fade" id="historico" role="tabpanel">
                @isset($reservasHistorico)
                    @include('reservas.partials.lista', ['reservas' => $reservasHistorico])
                @else
                    <div class="alert alert-warning">Nenhum histórico de reservas encontrado.</div>
                @endisset
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const footerHeight = document.querySelector('footer').offsetHeight;
            document.body.style.paddingBottom = footerHeight + 'px';
        });
    </script>


    <footer class="fixed bottom-0 left-0 w-full text-center py-4 bg-dark-custom text-white">
        &copy; {{ date('Y') }} SuperCarRent. Todos os direitos reservados.
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
