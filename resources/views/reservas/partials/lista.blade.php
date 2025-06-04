@if($reservas->isEmpty())
    <div class="alert alert-info text-center">Nenhuma reserva encontrada.</div>
@else
    <div class="row">
        @foreach ($reservas as $reserva)
            @php $carro = $reserva->carro; @endphp

            <div class="col-md-6 mb-4">
                <div class="card shadow overflow-hidden" style="min-height: 280px;">
                    <div class="row g-0 h-100">
                        @if ($carro && $carro->imagem)
                            <div class="col-md-5 h-100">
                                <img src="{{ asset($carro->imagem) }}" alt="{{ $carro->modelo }}"
                                     class="img-fluid h-100 w-100"
                                     style="object-fit: cover; border-top-left-radius: .375rem; border-bottom-left-radius: .375rem;">
                            </div>
                        @endif

                        <div class="{{ $carro && $carro->imagem ? 'col-md-7' : 'col-md-12' }}">
                            <div class="card-body d-flex flex-column justify-content-between h-100">
                                <div>
                                    <h5 class="card-title">{{ $carro->modelo ?? '-' }} ({{ $carro->marca->nome ?? '-' }})</h5>

                                    <p class="mb-1"><strong>Status:</strong>
                                        @if ($reserva->payment_status === 'paid')
                                            <span class="badge bg-success">Pago</span>
                                        @elseif($reserva->payment_status === 'pending')
                                            <span class="badge bg-warning text-dark">Pendente</span>
                                        @elseif($reserva->payment_status === 'refunded')
                                            <span class="badge bg-danger">Reembolsado</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($reserva->payment_status) }}</span>
                                        @endif
                                    </p>

                                    <p class="mb-1"><strong>Método:</strong> {{ ucfirst($reserva->payment_method) }}</p>

                                    {{-- Referências de Pagamento --}}
                                    @if ($reserva->payment_method === 'atm' && $reserva->atm_reference)
                                        <div class="border border-primary rounded p-2 bg-light mb-2">
                                            <strong class="text-primary d-block mb-1">💳 Referência Multibanco</strong>
                                            <p class="mb-1"><strong>Referência:</strong> {{ $reserva->atm_reference }}</p>
                                        </div>
                                    @endif

                                    @if($reserva->payment_method === 'referencia' && $reserva->entidade && $reserva->referencia)
                                        <div class="border border-primary rounded p-2 bg-light mb-2">
                                            <strong class="text-primary d-block mb-1">💳 Entidade & Referência</strong>
                                            <p class="mb-1"><strong>Entidade:</strong> {{ $reserva->entidade }}</p>
                                            <p class="mb-1"><strong>Referência:</strong> {{ $reserva->referencia }}</p>
                                        </div>
                                    @endif

                                    @if($reserva->payment_method === 'paypal' && $reserva->payment_reference)
                                        <p class="mb-1"><strong>Referência PayPal:</strong> {{ $reserva->payment_reference }}</p>
                                    @elseif($reserva->payment_reference && !in_array($reserva->payment_method, ['atm', 'referencia']))
                                        <p class="mb-1"><strong>Referência:</strong> {{ $reserva->payment_reference }}</p>
                                    @endif

                                    <p class="mb-1"><strong>Período:</strong><br>
                                        {{ \Carbon\Carbon::parse($reserva->data_inicio)->format('d/m/Y') }}
                                        a
                                        {{ \Carbon\Carbon::parse($reserva->data_fim)->format('d/m/Y') }}
                                    </p>

                                    <p class="mb-1"><strong>Preço diário:</strong> €{{ number_format($carro->preco_diario, 2, ',', '.') }}</p>

                                    @php
                                        $start = \Carbon\Carbon::parse($reserva->data_inicio);
                                        $end = \Carbon\Carbon::parse($reserva->data_fim);
                                        $days = $start->diffInDays($end);
                                        $total = $days * $carro->preco_diario;
                                    @endphp

                                    <p class="mb-1">
                                        <strong>Total pago:</strong> €{{ number_format($total, 2, ',', '.') }}
                                        ({{ $days }} dias × €{{ number_format($carro->preco_diario, 2, ',', '.') }})
                                    </p>

                                    <p class="mb-2"><strong>Localizações:</strong><br>
                                        @foreach ($carro->localizacoes as $loc)
                                            <span class="badge bg-secondary">{{ $loc->cidade }}</span>
                                        @endforeach
                                    </p>
                                </div>

                                <div>
                                    <small class="text-muted d-block mb-2">Reservado em {{ \Carbon\Carbon::parse($reserva->created_at)->format('d/m/Y H:i') }}</small>

                                    @if ($reserva->payment_status !== 'refunded')
    <div class="d-flex gap-2">
        <a href="{{ route('reservas.edit', $reserva->id) }}"
           class="btn btn-sm btn-outline-primary flex-fill">Editar</a>

        <form action="{{ route('reservas.destroy', $reserva->id) }}" method="POST" class="m-0 p-0 flex-fill">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger w-100"
                    onclick="return confirm('Tem certeza que deseja cancelar esta reserva?')">
                Cancelar
            </button>
        </form>

        <a href="{{ route('reservas.fatura', $reserva->id) }}"
           class="btn btn-sm btn-outline-success flex-fill">Fatura</a>
    </div>
@endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

