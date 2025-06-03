<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>SuperCarRent - Bem-vindo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .hero {
            background: url('https://images.unsplash.com/photo-1504215680853-026ed2a45def') center center / cover no-repeat;
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .card-img-top {
            height: 200px;
            object-fit: cover;
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
        footer .footer-links a {
            color: #ccc;
            text-decoration: none;
        }
        footer .footer-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    {{-- Include the reusable top navigation partial --}}
    @include('profile.partials.topnav')

    {{-- Hero Section --}}
    <section class="hero">
        <div class="container">
            <h1 class="display-4 fw-bold">Encontre o carro perfeito para a sua viagem</h1>
            <p class="lead">Serviço rápido, seguro e acessível em várias cidades de Portugal.</p>
            <a href="#carros" class="btn btn-warning btn-lg mt-3">Ver Carros Disponíveis</a>
        </div>
    </section>

    {{-- Company Highlights --}}
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4">
                    <h4>+30 Localizações</h4>
                    <p>Estamos presentes nas principais cidades de Portugal.</p>
                </div>
                <div class="col-md-4">
                    <h4>Variedade de Modelos</h4>
                    <p>Desde compactos até SUVs híbridos para todas as necessidades.</p>
                </div>
                <div class="col-md-4">
                    <h4>Preços Competitivos</h4>
                    <p>Alugue a partir de €45/dia com manutenção incluída.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Featured Cars --}}
    <section id="carros" class="py-5">
        <div class="container">
            <h2 class="mb-4 text-center">Carros Disponíveis</h2>

            {{-- Filter Form --}}
<form method="GET" action="{{ route('home') }}" class="mb-4">
    <div class="row justify-content-center g-3 mb-3">
        {{-- City Filter --}}
        <div class="col-md-4 col-sm-6">
            <input type="text" name="cidade" value="{{ request('cidade') }}" class="form-control" placeholder="Filtrar por cidade">
        </div>

        {{-- Brand Filter --}}
        <div class="col-md-4 col-sm-6">
            <input type="text" name="marca" value="{{ request('marca') }}" class="form-control" placeholder="Filtrar por marca">
        </div>
    </div>

    {{-- 💰 Single Price Range Slider --}}
    <div class="row justify-content-center g-3 mb-3 text-center">
        <div class="col-md-6 col-sm-8">
            <label for="price_max" class="form-label">Preço Máximo (€): <span id="priceDisplay">{{ request('price_max', 500) }}</span></label>
            <input type="range" class="form-range" min="0" max="500" step="10" id="price_max" name="price_max" value="{{ request('price_max', 500) }}">
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        </div>
    </div>
</form>




            <div class="row">
                @foreach($carros as $carro)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow">
                            @if(!empty($carro->imagem))
                                <img src="{{ $carro->imagem }}"
                                     class="card-img-top"
                                     alt="{{ $carro->modelo }}">
                            @else
                                <img src="https://placehold.co/400x250?text={{ urlencode($carro->modelo) }}"
                                     class="card-img-top"
                                     alt="{{ $carro->modelo }}">
                            @endif

                            <div class="card-body">
                                <h5 class="card-title">{{ $carro->modelo }} ({{ $carro->marca->nome }})</h5>
                                <ul class="list-unstyled">
                                    <li><strong>Cor:</strong> {{ ucfirst($carro->cor) }}</li>
                                    <li><strong>Transmissão:</strong> {{ ucfirst($carro->transmissao) }}</li>
                                    <li><strong>Combustível:</strong> {{ ucfirst($carro->combustivel) }}</li>
                                    <li><strong>Preço:</strong> €{{ $carro->preco_diario }}/dia</li>
                                </ul>
                                <p>
                                    <strong>Localizações:</strong><br>
                                    @foreach($carro->localizacoes as $loc)
                                        <span class="badge bg-secondary">{{ $loc->cidade }}</span>
                                    @endforeach
                                </p>
                                <a href="/carro/{{ $carro->id }}" class="btn btn-primary mt-2">Ver Detalhes</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-dark text-light pt-5 pb-3">
        <div class="container">
            <div class="row footer-links">
                <div class="col-md-3">
                    <h5>Empresa</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Sobre Nós</a></li>
                        <li><a href="#">Carreiras</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Sustentabilidade</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Ajuda</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Centro de Suporte</a></li>
                        <li><a href="#">Informações de Aluguer</a></li>
                        <li><a href="#">Contactos</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Legal</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Termos e Condições</a></li>
                        <li><a href="#">Política de Privacidade</a></li>
                        <li><a href="#">Cookies</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h5>Siga-nos</h5>
                    <ul class="list-unstyled">
                        <li><a href="#">Facebook</a></li>
                        <li><a href="#">Instagram</a></li>
                        <li><a href="#">LinkedIn</a></li>
                    </ul>
                </div>
            </div>

            <hr class="bg-secondary">

            <div class="text-center text-muted small">
                &copy; {{ date('Y') }} Locação de Carros. Todos os direitos reservados.
            </div>
        </div>
    </footer>

<script>
    const priceMaxInput = document.getElementById('price_max');
    const priceDisplay = document.getElementById('priceDisplay');

    priceMaxInput.addEventListener('input', () => {
        priceDisplay.textContent = priceMaxInput.value;
    });
</script>



</body>
</html>
