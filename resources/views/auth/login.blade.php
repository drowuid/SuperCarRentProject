<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>SuperCarRent - Login</title>
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
        /* Center the login form */
        .login-container {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
        }
        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 2rem;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 0.5rem;
            background: white;
        }
        .login-card h2 {
            margin-bottom: 1.5rem;
            font-weight: 700;
            color: #343a40;
            text-align: center;
        }
    </style>
</head>
<body>

    {{-- Include the reusable top navigation partial --}}
    @include('profile.partials.topnav')

    <div class="login-container">
        <div class="login-card">
            <h2>Entrar na SuperCarRent</h2>

            {{-- Display Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                    >
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Senha</label>
                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        required
                        autocomplete="current-password"
                    >
                </div>

                <div class="mb-3 form-check">
                    <input
                        type="checkbox"
                        class="form-check-input"
                        id="remember"
                        name="remember"
                        {{ old('remember') ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="remember">Lembrar-me</label>
                </div>

                <button type="submit" class="btn btn-warning w-100">Entrar</button>

                <div class="mt-3 text-center">
                    <a href="{{ route('password.request') }}">Esqueceu a senha?</a>
                </div>
            </form>
        </div>
    </div>
    <footer class="text-center py-4 bg-dark text-white mt-auto">
        &copy; {{ date('Y') }} SuperCarRent. Todos os direitos reservados.
    </footer>
</body>
</html>
