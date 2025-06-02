<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Perfil do Usuário - SuperCarRent</title>
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
        .profile-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ccc;
        }
        .profile-card {
            max-width: 700px;
            margin: 0 auto;
        }
    </style>
</head>
<body>

{{-- 🔝 Top Navbar --}}
<nav class="top-nav d-flex justify-content-between align-items-center px-3">
    <span class="text-white fw-bold fs-4">{{ config('app.name') }}</span>
    <div>
        <a href="{{ route('home') }}">Início</a>
        <a href="{{ route('reservas.minhas') }}">Gerir Reservas</a>
        <span class="text-white ms-3">Olá, {{ Auth::user()->name }}</span>
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="ms-3">Sair</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </div>
</nav>

<div class="container py-5">
    <div class="profile-card bg-white shadow p-4 rounded">
        <h2 class="mb-4 text-center">Editar Perfil</h2>

        @if(session('status') === 'profile-updated')
            <div class="alert alert-success text-center">Perfil atualizado com sucesso!</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            {{-- Profile Image --}}
            <div class="text-center mb-4">
                <img src="{{ Auth::user()->profile_photo_url }}" class="profile-photo mb-3" alt="Foto de Perfil">
                <input type="file" name="profile_photo" class="form-control w-50 mx-auto">
            </div>

            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', Auth::user()->name) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', Auth::user()->email) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Telefone</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', Auth::user()->phone ?? '') }}">
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            </div>
        </form>

        <hr class="my-4">

        {{-- 🔐 Change Password --}}
        <h5 class="mb-3">Alterar Senha</h5>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Senha Atual</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nova Senha</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Confirmar Nova Senha</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <div class="d-grid">
                <button class="btn btn-warning">Atualizar Senha</button>
            </div>
        </form>

        <hr class="my-4">

        {{-- ❌ Delete Account --}}
        <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Tem certeza que deseja excluir sua conta?')">
            @csrf
            @method('DELETE')
            <label class="form-label">Senha para confirmar exclusão</label>
            <input type="password" name="password" class="form-control mb-3" required>
            <div class="d-grid">
                <button class="btn btn-danger">Excluir Conta</button>
            </div>
        </form>
    </div>
</div>

<footer class="text-center py-4 bg-dark text-white mt-5">
    &copy; {{ date('Y') }} SuperCarRent. Todos os direitos reservados.
</footer>

</body>
</html>
