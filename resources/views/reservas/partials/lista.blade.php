@if($reservas->isEmpty())
    <div class="alert alert-info text-center">Nenhuma reserva encontrada.</div>
@else
    <div class="row">
        @foreach ($reservas as $reserva)
            @php $carro = $reserva->carro; @endphp

            <div class="col-md-6 mb-4">
                <div class="card h-100 shadow">
                    <div class="row g-0">
                        @if ($carro && $carro->imagem)
                            <div class="col-md-5">
                                <img src="{{ $carro->imagem }}" alt="{{ $carro->modelo }}"
                                     class="img-fluid card-img-left w-100">
                            </div>
                        @endif
                        <div class="{{ $carro && $carro->imagem ? 'col-md-7' : 'col-md-12' }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $carro->modelo ?? '-' }} ({{ $carro->marca->nome ?? '-' }})</h5>

                                <p class="mb-1">
                                    <strong>Status:</strong>
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

                                {{-- Display Payment Method --}}
                                <p class="mb-1"><strong>Método:</strong> {{ ucfirst($reserva->payment_method) }}</p>

                                {{-- Display Payment Reference based on method --}}
                                @if ($reserva->payment_method === 'atm' && $reserva->atm_reference)
                                    <p class="mb-1"><strong>Referência Multibanco:</strong> {{ $reserva->atm_reference }}</p>
                                @endif
                                @if($reserva->payment_method === 'referencia' && $reserva->entidade && $reserva->referencia)
                                    <p class="mb-1"><strong>Entidade:</strong> {{ $reserva->entidade }}</p>
                                    <p class="mb-1"><strong>Referência:</strong> {{ $reserva->referencia }}</p>
                                @endif
                                @if($reserva->payment_method === 'paypal' && $reserva->payment_reference)
                                    <p class="mb-1"><strong>Referência PayPal:</strong> {{ $reserva->payment_reference }}</p>
                                @elseif($reserva->payment_reference && $reserva->payment_method !== 'atm' && $reserva->payment_method !== 'referencia')
                                    <p class="mb-1"><strong>Referência de Pagamento:</strong> {{ $reserva->payment_reference }}</p>
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
                                    ({{ $days }} dias ×
                                    €{{ number_format($carro->preco_diario, 2, ',', '.') }})
                                </p>

                                <p class="mb-2"><strong>Localizações:</strong><br>
                                    @foreach ($carro->localizacoes as $loc)
                                        <span class="badge bg-secondary">{{ $loc->cidade }}</span>
                                    @endforeach
                                </p>

                                <small class="text-muted d-block mb-2">Reservado em
                                    {{ \Carbon\Carbon::parse($reserva->created_at)->format('d/m/Y H:i') }}</small>

                                {{-- Actions --}}
                                @if ($reserva->payment_status !== 'refunded')
                                    <a href="{{ route('reservas.edit', $reserva->id) }}"
                                       class="btn btn-sm btn-outline-primary me-2">Editar</a>

                                    <form action="{{ route('reservas.destroy', $reserva->id) }}" method="POST"
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Tem certeza que deseja cancelar esta reserva?')">
                                            Cancelar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
