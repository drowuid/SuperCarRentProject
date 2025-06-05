<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Pedir Ajuda - SuperCarRent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .chat-box {
            max-height: 400px;
            overflow-y: auto;
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .message-user {
            text-align: right;
            background: #d1e7dd;
            padding: 8px 12px;
            border-radius: 10px;
            margin-bottom: 8px;
            display: inline-block;
        }
        .message-admin {
            text-align: left;
            background: #f8d7da;
            padding: 8px 12px;
            border-radius: 10px;
            margin-bottom: 8px;
            display: inline-block;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4">
    <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name') }}</a>
    <div class="ms-auto">
        <a href="{{ route('reservas.minhas') }}" class="text-white me-3">Gerir Reservas</a>
        <a href="{{ route('profile.edit') }}" class="text-white me-3">Olá, {{ Auth::user()->name }}</a>
        <a href="{{ route('logout') }}" class="text-white" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sair</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </div>
</nav>

<div class="container py-5">
    <h3 class="text-center mb-4">Pedir Ajuda</h3>

    <div class="chat-box">
        @foreach ($messages as $msg)
            <div class="{{ $msg->is_admin ? 'message-admin' : 'message-user' }}">
                <small><strong>{{ $msg->is_admin ? 'Admin' : 'Você' }}:</strong> {{ $msg->message }}</small>
            </div>
        @endforeach
    </div>

    <form method="POST" action="{{ route('messages.store') }}">
        @csrf
        <div class="input-group">
            <input type="text" name="message" class="form-control" placeholder="Digite sua mensagem..." required>
            <button class="btn btn-primary" type="submit">Enviar</button>
        </div>
    </form>
</div>

</body>
</html>
