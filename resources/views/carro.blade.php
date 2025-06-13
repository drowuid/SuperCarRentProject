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
        .reservation-form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .payment-summary-card {
            max-width: 400px;
            margin: 20px auto;
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
                    <img src="{{ asset($carro->imagem) }}" alt="{{ $carro->modelo }}" class="car-image">
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

---

{{-- 📝 Reservation Form Section --}}
<div id="reserva" class="container my-5">
    <h3 class="text-center mb-4">Formulário de Reserva</h3>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger text-center">
            <ul class="mb-0 list-unstyled">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="reservation-form-container">
        @auth
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

                <div class="mb-3">
                    <label for="payment_method" class="form-label">Método de Pagamento</label>
                    <select id="payment_method" name="payment_method" class="form-select" required onchange="togglePaymentMethod(this.value); updatePaymentSummary();">
                        <option value="">Escolha um método</option>
                        <option value="paypal">PayPal</option>
                        <option value="referencia">Referência Multibanco</option>
                    </select>
                </div>

                {{-- Multibanco Specific Fields --}}
                <div id="multibanco-fields" style="display: none;">
                    <div class="mb-3 text-center">
                        <small class="form-text text-muted">A referência será gerada após a confirmação da reserva.</small>
                    </div>
                    <div class="mb-3 text-center"><small class="form-text text-muted">Estará disponível em "Gerir Reservas"</small></div>
                </div>

                {{-- 🔍 Payment Summary --}}
            <div id="payment-summary" style="display:none;" class="payment-summary-card card p-3 border-info mb-3"></div>

                {{-- PayPal Button Container --}}
                <div id="paypal-button-container" class="text-center mb-3" style="display: none;"></div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-sm" id="submit-button">Confirmar Reserva</button>
                </div>
            </form>
        @else
            <div class="text-center">
                <p class="mb-3 text-muted">É necessário estar registado para efetuar uma reserva.</p>
                <a href="{{ route('register') }}" class="btn btn-warning">Registar para Reservar</a>
            </div>
        @endauth
    </div>
</div>

---

{{-- PayPal SDK --}}
<script src="https://www.paypal.com/sdk/js?client-id=AVUx1ZUO5ji16WxPheuUr_C2qbWxEsVtDYwO6O0vIWD9xw2n9rcA-YPIDq7f6De6p9rSvc-jX-3b3hye&currency=EUR"></script>
<script>
    let paypalButtonsRendered = false;

    function renderPayPalButtonsIfNeeded() {
        if (paypalButtonsRendered) return;

        paypal.Buttons({
            createOrder: function (data, actions) {
                const start = new Date(document.querySelector('input[name="data_inicio"]').value);
                const end = new Date(document.querySelector('input[name="data_fim"]').value);
                if (isNaN(start) || isNaN(end) || end <= start) {
                    alert("Selecione um intervalo de datas válido para calcular o preço.");
                    return;
                }

                const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
                const dailyRate = parseFloat({{ $carro->preco_diario }});
                const totalAmount = (days * dailyRate).toFixed(2);

                return actions.order.create({
                    purchase_units: [{
                        amount: { value: totalAmount }
                    }]
                });
            },
            onApprove: function (data, actions) {
                return actions.order.capture().then(function (details) {
                    const formData = {
                        bem_locavel_id: '{{ $carro->id }}',
                        nome_cliente: document.querySelector('input[name="nome_cliente"]').value,
                        email: document.querySelector('input[name="email"]').value,
                        data_inicio: document.querySelector('input[name="data_inicio"]').value,
                        data_fim: document.querySelector('input[name="data_fim"]').value,
                        payment_method: 'paypal',
                        paypal_order_id: details.id
                    };

                    fetch("{{ route('reserva.paypal') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(formData)
                    }).then(res => res.json()).then(data => {
                        if (data.success) {
                            alert('Reserva efetuada com sucesso via PayPal!');
                            window.location.href = "{{ route('reservas.minhas') }}";
                        } else {
                            alert('Erro ao processar a reserva: ' + (data.message || 'Erro desconhecido.'));
                        }
                    }).catch(error => {
                        console.error('Erro na reserva PayPal:', error);
                        alert('Ocorreu um erro ao finalizar a reserva. Por favor, tente novamente.');
                    });
                });
            },
            onError: function (err) {
                console.warn('Erro do PayPal (ignorado):', err);
            }
        }).render('#paypal-button-container');

        paypalButtonsRendered = true;
    }

    function togglePaymentMethod(method) {
        const submitButton = document.getElementById('submit-button');
        const paypalButtonContainer = document.getElementById('paypal-button-container');
        const multibancoFields = document.getElementById('multibanco-fields');

        submitButton.style.display = 'none';
        paypalButtonContainer.style.display = 'none';
        multibancoFields.style.display = 'none';

        if (method === 'paypal') {
            paypalButtonContainer.style.display = 'block';
            renderPayPalButtonsIfNeeded();
        } else if (method === 'referencia') {
            multibancoFields.style.display = 'block';
            submitButton.style.display = 'inline-block';
        } else {
            submitButton.style.display = 'inline-block';
        }
    }

    // Payment summary updater
    function updatePaymentSummary() {
        const startInput = document.querySelector('input[name="data_inicio"]');
        const endInput = document.querySelector('input[name="data_fim"]');
        const paymentSummary = document.getElementById('payment-summary');
        const paymentMethod = document.getElementById('payment_method') ? document.getElementById('payment_method').value : '';
        if (!startInput || !endInput || !paymentSummary) return;

        const start = new Date(startInput.value);
        const end = new Date(endInput.value);

        if (isNaN(start) || isNaN(end) || end <= start) {
            paymentSummary.style.display = 'none';
            paymentSummary.innerHTML = '';
            return;
        }

        const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
        const dailyRate = parseFloat({{ $carro->preco_diario }});
        const totalAmount = (days * dailyRate).toFixed(2);

        let methodText = '';
        if (paymentMethod === 'paypal') {
            methodText = 'PayPal';
        } else if (paymentMethod === 'referencia') {
            methodText = 'Referência Multibanco';
        } else {
            methodText = 'Não selecionado';
        }

        paymentSummary.innerHTML = `
            <h5 class="mb-2">Resumo do Pagamento</h5>
            <ul class="list-unstyled mb-2">
                <li><strong>Dias:</strong> ${days}</li>
                <li><strong>Preço diário:</strong> €${dailyRate.toFixed(2)}</li>
                <li><strong>Total:</strong> <span class="text-success fw-bold">€${totalAmount}</span></li>
                <li><strong>Método:</strong> ${methodText}</li>
            </ul>
        `;
        paymentSummary.style.display = 'block';
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', () => {
        updatePaymentSummary();
        togglePaymentMethod(document.getElementById('payment_method').value);

        // Update summary on payment method change
        const paymentMethodSelect = document.getElementById('payment_method');
        if (paymentMethodSelect) {
            paymentMethodSelect.addEventListener('change', updatePaymentSummary);
        }
        // Update summary on date change
        const startInput = document.querySelector('input[name="data_inicio"]');
        const endInput = document.querySelector('input[name="data_fim"]');
        if (startInput) startInput.addEventListener('change', updatePaymentSummary);
        if (endInput) endInput.addEventListener('change', updatePaymentSummary);
    });
</script>


{{-- 📌 Footer --}}
<footer class="text-center py-4">
    &copy; {{ date('Y') }} SuperCarRent. Todos os direitos reservados.
</footer>

</body>
</html>
