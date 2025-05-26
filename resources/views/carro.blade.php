<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>{{ $carro->modelo }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function showPaymentForm() {
            document.getElementById('payment-section').classList.remove('d-none');
            document.getElementById('initial-reserve-btn').classList.add('d-none');
        }

        function togglePaymentMethod(method) {
            if (method === 'paypal') {
                document.getElementById('reserveButton').style.display = 'none';
                document.getElementById('paypal-button-container').style.display = 'block';
            } else {
                document.getElementById('reserveButton').style.display = 'inline-block';
                document.getElementById('paypal-button-container').style.display = 'none';
            }
        }
    </script>
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
    <h3>Reservar este carro</h3>

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

    {{-- 🔘 Initial Reserve Button --}}
    <button id="initial-reserve-btn" onclick="showPaymentForm()" class="btn btn-primary mb-4">Reservar</button>

    {{-- 💳 Payment Section Form --}}
    <form action="{{ route('reserva.store') }}" method="POST" class="mb-5 d-none" id="payment-section">
        @csrf
        <input type="hidden" name="bem_locavel_id" value="{{ $carro->id }}">

        <div class="mb-3">
            <label for="nome_cliente" class="form-label">Nome</label>
            <input type="text" name="nome_cliente" class="form-control"
                   value="{{ auth()->user()->name ?? old('nome_cliente') }}" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">E-mail</label>
            <input type="email" name="email" class="form-control"
                   value="{{ auth()->user()->email ?? old('email') }}" required>
        </div>

        <div class="mb-3">
            <label for="data_inicio" class="form-label">Data de Início</label>
            <input type="date" name="data_inicio" class="form-control" value="{{ old('data_inicio') }}" required>
        </div>

        <div class="mb-3">
            <label for="data_fim" class="form-label">Data de Fim</label>
            <input type="date" name="data_fim" class="form-control" value="{{ old('data_fim') }}" required>
        </div>

        {{-- 💰 Payment Method Selection --}}
        <div class="mb-4">
            <label for="payment_method" class="form-label">Método de Pagamento</label>
            <select id="payment_method" name="payment_method" class="form-select" required onchange="togglePaymentMethod(this.value)">
                <option value="">Escolha um método</option>
                <option value="paypal">PayPal</option>
                <option value="atm">Referência Multibanco</option>
            </select>
        </div>

        {{-- Default Submit Button --}}
        <button type="submit" id="reserveButton" class="btn btn-success">Confirmar Reserva</button>

        {{-- PayPal Button Container --}}
        <div id="paypal-button-container" style="display: none;"></div>
    </form>

    {{-- 💳 PayPal SDK --}}
    <script src="https://www.paypal.com/sdk/js?client-id=AVUx1ZUO5ji16WxPheuUr_C2qbWxEsVtDYwO6O0vIWD9xw2n9rcA-YPIDq7f6De6p9rSvc-jX-3b3hye&currency=EUR"></script>

    <script>
        paypal.Buttons({
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: { value: '{{ $carro->preco_diario }}' }
                    }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
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
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert('Reserva efetuada com sucesso via PayPal!');
                        window.location.href = "{{ route('reservas.minhas') }}";
                    });
                });
            }
        }).render('#paypal-button-container');
    </script>

</body>
</html>
