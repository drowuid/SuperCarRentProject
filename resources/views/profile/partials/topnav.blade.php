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
            @auth
    @if(!Auth::user()->is_admin)
        <a href="{{ route('reservas.minhas') }}">Gerir Reservas</a>
    @endif
@endauth

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
    @auth
    @if(Auth::user()->is_admin)
        <a href="{{ route('admin.dashboard') }}">Painel Admin</a>
    @endif
@endauth

</nav>
