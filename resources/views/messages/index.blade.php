@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3 class="mb-3">Chat com o Admin</h3>

    <div class="card mb-4 p-3" style="max-height: 400px; overflow-y: auto;">
        @forelse($messages as $message)
            <div class="mb-2">
                <strong>{{ $message->sender_id === auth()->id() ? 'Você' : 'Admin' }}:</strong>
                {{ $message->message }}
            </div>
        @empty
            <p>Nenhuma mensagem ainda.</p>
        @endforelse
    </div>

    <form method="POST" action="{{ route('messages.store') }}">
        @csrf
        <textarea name="message" class="form-control mb-2" required placeholder="Digite sua mensagem..."></textarea>
        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
</div>
@endsection
