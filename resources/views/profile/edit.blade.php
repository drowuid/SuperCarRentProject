@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Perfil</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Name -->
        <div class="form-group">
            <label for="name">Nome</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <!-- Phone -->
        <div class="form-group">
            <label for="phone">Telefone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password">Nova Senha</label>
            <input type="password" name="password" class="form-control">
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation">Confirmar Nova Senha</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <!-- Profile Picture -->
        <div class="form-group">
            <label for="profile_picture">Foto de Perfil</label>
            <input type="file" name="profile_picture" class="form-control-file">
            @if($user->profile_picture)
                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Foto de Perfil" class="img-thumbnail mt-2" width="150">
            @endif
        </div>

        <button type="submit" class="btn btn-primary">Atualizar Perfil</button>
    </form>
</div>
@endsection

