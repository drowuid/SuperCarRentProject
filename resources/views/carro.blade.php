<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>{{ $carro->modelo }} - SuperCarRent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .top-nav {
            background: #343a40;
            padding: 10px 20px;
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
        .car-image {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
        }
        footer {
            background: #343a40;
            color: #ccc;
        }
    </style>
</head>
<body>

{{-- 🔝 Top Navigation --}}
<nav class="top-nav d-flex justify-content-between align-items-center px-3">
    <span class="text-white fw-bold fs-4">{{ config('app.name') }}</span>
    <div>
        <a href="{{ route('home') }}">Início</a>
         @auth
    @if(!Auth::user()->is_admin)
        <a href="{{ route('reservas.minhas') }}">Gerir Reservas</a>
    @endif
@endauth
        @auth
            <span class="text-white ms-3">Olá, {{ Auth::user()->name }}</span>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="ms-3">Sair</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        @endauth
    </div>
</nav>

{{-- 🚗 Car Detail Section --}}
<div class="container my-5">
    <div class="card shadow-lg">
        <div class="row g-0">
            @if($carro->imagem)
                <div class="col-md-5">
                    <img src="{{ $carro->imagem }}" alt="{{ $carro->modelo }}" class="car-image">
                </div>
            @endif
            <div class="{{ $carro->imagem ? 'col-md-7' : 'col-md-12' }}">
                <div class="card-body">
                    <h2 class="card-title">{{ $carro->modelo }} <small class="text-muted">({{ $carro->marca->nome }})</small></h2>
                    <p class="mb-2"><strong>Preço diário:</strong> €{{ $carro->preco_diario }}</p>
                    <p><strong>Cor:</strong> {{ ucfirst($carro->cor) }}</p>
                    <p><strong>Transmissão:</strong> {{ ucfirst($carro->transmissao) }}</p>
                    <p><strong>Combustível:</strong> {{ ucfirst($carro->combustivel) }}</p>

                    <hr>
                    <h5>Localizações</h5>
                    <ul>
                        @foreach($carro->localizacoes as $loc)
                            <li>{{ $loc->cidade }} - {{ $loc->filial }} ({{ $loc->posicao }})</li>
                        @endforeach
                    </ul>

                    <h5>Características</h5>
                    <ul>
                        @foreach($carro->caracteristicas as $c)
                            <li>{{ $c->nome }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary mt-3">← Voltar à lista</a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 📝 Reservation Form Section --}}
<div id="reserva" class="container my-5">
    <h3>Formulário de Reserva</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form id="reserva-form" action="{{ route('reserva.store') }}" method="POST">
        @csrf
        <input type="hidden" name="bem_locavel_id" value="{{ $carro->id }}">

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="nome_cliente" class="form-label">Nome</label>
                <input type="text" name="nome_cliente" class="form-control" value="{{ auth()->user()->name ?? old('nome_cliente') }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control" value="{{ auth()->user()->email ?? old('email') }}" required>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="data_inicio" class="form-label">Data de Início</label>
                <input type="date" name="data_inicio" class="form-control" value="{{ old('data_inicio') }}" required onchange="updatePaymentSummary()">
            </div>
            <div class="col-md-6 mb-3">
                <label for="data_fim" class="form-label">Data de Fim</label>
                <input type="date" name="data_fim" class="form-control" value="{{ old('data_fim') }}" required onchange="updatePaymentSummary()">
            </div>
        </div>

        {{-- 🔍 Payment Summary --}}
        <div id="payment-summary" style="display:none;" class="mb-3"></div>

        <div class="mb-3">
            <label for="payment_method" class="form-label">Método de Pagamento</label>
            <select id="payment_method" name="payment_method" class="form-select" required onchange="togglePayment(this.value); updatePaymentSummary();">
                <option value="">Escolha um método</option>
                <option value="paypal">PayPal</option>
                <option value="atm">Referência Multibanco</option>
            </select>
        </div>

        <div id="paypal-button-container" style="display: none;"></div>

        <button type="submit" class="btn btn-primary" id="submit-button">Confirmar Reserva</button>
    </form>
</div>

{{-- 📌 Footer --}}
<footer class="text-center py-4">
    &copy; {{ date('Y') }} SuperCarRent. Todos os direitos reservados.
</footer>

{{-- PayPal SDK --}}
<script src="https://www.paypal.com/sdk/js?client-id=AVUx1ZUO5ji16WxPheuUr_C2qbWxEsVtDYwO6O0vIWD9xw2n9rcA-YPIDq7f6De6p9rSvc-jX-3b3hye&currency=EUR"></script>
<script>
    function togglePayment(method) {
        if (method === 'paypal') {
            document.getElementById('submit-button').style.display = 'none';
            document.getElementById('paypal-button-container').style.display = 'block';
        } else {
            document.getElementById('submit-button').style.display = 'inline-block';
            document.getElementById('paypal-button-container').style.display = 'none';
        }
    }

    function updatePaymentSummary() {
        const start = new Date(document.querySelector('input[name="data_inicio"]').value);
        const end = new Date(document.querySelector('input[name="data_fim"]').value);
        const summary = document.getElementById('payment-summary');

        if (!isNaN(start) && !isNaN(end) && end > start) {
            const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
            const dailyRate = parseFloat({{ $carro->preco_diario }});
            const total = (days * dailyRate).toFixed(2);

            summary.innerHTML = `
                <div class="alert alert-info">
                    <strong>Resumo do Pagamento:</strong><br>
                    Duração: ${days} dia(s)<br>
                    Preço por dia: €${dailyRate}<br>
                    <strong>Total a pagar: €${total}</strong>
                </div>
            `;
            summary.style.display = 'block';
        } else {
            summary.innerHTML = '';
            summary.style.display = 'none';
        }
    }

    paypal.Buttons({
        createOrder: function (data, actions) {
            return actions.order.create({
                purchase_units: [{
                    amount: { value: '{{ $carro->preco_diario }}' }
                }]
            });
        },
        onApprove: function (data, actions) {
            return actions.order.capture().then(function (details) {
                fetch("{{ route('reserva.paypal') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        bem_locavel_id: '{{ $carro->id }}',
                        nome_cliente: '{{ auth()->user()->name }}',
                        email: '{{ auth()->user()->email }}',
                        data_inicio: document.querySelector('input[name="data_inicio"]').value,
                        data_fim: document.querySelector('input[name="data_fim"]').value,
                        payment_method: 'paypal'
                    })
                }).then(res => res.json()).then(data => {
                    alert('Reserva efetuada com sucesso via PayPal!');
                    window.location.href = "{{ route('reservas.minhas') }}";
                });
            });
        }
    }).render('#paypal-button-container');
</script>

</body>
</html>
