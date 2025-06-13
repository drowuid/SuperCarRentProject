<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha - SuperCarRent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .form-container {
            max-width: 400px;
            margin: 60px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark px-4">
        <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name', 'SuperCarRent') }}</a>
    </nav>

    <div class="container">
        <div class="form-container">
            <h4 class="text-center mb-4">Recuperar password</h4>

            @if (session('status'))
                <div class="alert alert-success text-center">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Endereço de Email</label>
                    <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-warning w-100">Recuperar password</button>


            </form>

            <div class="mt-3 text-center">
                <a href="{{ route('login') }}">Voltar para o login</a>
            </div>
        </div>
    </div>
</body>
</html>
