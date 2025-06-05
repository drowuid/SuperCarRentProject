@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3 class="mb-3">Mensagens dos Usuários</h3>

    @forelse($users as $user)
        <div class="card p-3 mb-4">
            <h5>Conversa com {{ $user->name }}</h5>

            <div class="mb-3" style="max-height: 300px; overflow-y: auto;">
                @foreach($user->sentMessages->merge($user->receivedMessages)->sortBy('created_at') as $message)
                    <div>
                        <strong>{{ $message->sender_id === auth()->id() ? 'Admin' : $user->name }}:</strong>
                        {{ $message->message }}
                    </div>
                @endforeach
            </div>

            <form method="POST" action="{{ route('admin.messages.store', $user) }}">
                @csrf
                <textarea name="message" class="form-control mb-2" required></textarea>
                <button type="submit" class="btn btn-sm btn-success">Responder</button>
            </form>
        </div>
    @empty
        <p>Nenhuma mensagem recebida ainda.</p>
    @endforelse
</div>
@endsection
