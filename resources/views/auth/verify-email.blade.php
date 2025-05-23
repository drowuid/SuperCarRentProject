<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Verificação de Email - SuperCarRent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .verify-container {
            max-width: 500px;
            margin: 80px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .brand-title {
            font-weight: 700;
            color: #ff9900;
            margin-bottom: 25px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="verify-container">
            <h2 class="brand-title">SuperCarRent</h2>
            <h5>Verifique o seu endereço de email</h5>
            <p class="text-muted mt-3">
                Enviámos um link de verificação para o seu email. Por favor, verifique a sua caixa de entrada antes de entrar.
            </p>

            @if (session('status') === 'verification-link-sent')
                <div class="alert alert-success">
                    Um novo link de verificação foi enviado para o seu email.
                </div>
            @endif

            <form method="POST" action="{{ route('verification.send') }}" class="d-grid gap-2 mt-4">
                @csrf
                <button type="submit" class="btn btn-outline-primary w-100">
                    Reenviar Email de Verificação
                </button>
            </form>

            <div class="mt-4">
                <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="btn btn-outline-secondary w-100 mt-3">
        Ir para o login
    </button>
</form>

            </div>
        </div>
    </div>

</body>
</html>
