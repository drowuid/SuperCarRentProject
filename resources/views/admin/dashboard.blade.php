<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Painel de Administração - SuperCarRent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .top-nav { background: #343a40; padding: 10px 20px; color: white; }
        .top-nav a { color: white; margin-left: 15px; text-decoration: none; font-weight: 500; }
        .top-nav a:hover { text-decoration: underline; }

        /* Custom styles for the admin content card */
        .admin-content-card {
            background-color: #ffffff; /* White background */
            border-radius: 0.5rem; /* Slightly rounded corners */
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.05); /* Subtle shadow */
            padding: 1.5rem; /* Padding inside the card */
            margin-top: 1.5rem; /* Space from the top navigation */
        }

        /* Custom styles for status badges */
        .status-badge {
            padding: .35em .65em;
            border-radius: .25rem;
            font-size: .75em;
            font-weight: 700;
            color: #fff; /* Default text color for badges */
            display: inline-block; /* Ensure it behaves like a block for padding/margin */
        }
        .status-paid { background-color: #28a745; } /* Green for paid */
        .status-pending { background-color: #ffc107; color: #343a40; } /* Yellow for pending (with dark text) */
        .status-refunded { background-color: #dc3545; } /* Red for refunded */
        .status-default { background-color: #6c757d; } /* Grey for other/default status */
    </style>
</head>
<body>

    <nav class="top-nav d-flex justify-content-between align-items-center px-3">
        <span class="fw-bold fs-4">SuperCarRent - Admin</span>
        <div>
            <a href="{{ route('home') }}">Início</a>
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sair</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </nav>

    <div class="container">
        <div class="admin-content-card">
            <h2 class="mb-4 text-center">Histórico de Todas as Reservas</h2>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Carro</th>
                            <th>Início</th>
                            <th>Fim</th>
                            <th>Preço Total</th>
                            <th>Método</th>
                            <th>Status</th>
                            <th>Data</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservas as $r)
                            <tr>
                                <td>{{ $r->id }}</td>
                                <td>{{ $r->nome_cliente }}</td>
                                <td>{{ $r->carro->modelo ?? 'N/A' }} ({{ $r->carro->marca->nome ?? 'N/A' }})</td>
                                <td>{{ \Carbon\Carbon::parse($r->data_inicio)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($r->data_fim)->format('d/m/Y') }}</td>
                                <td>€{{ number_format($r->preco_total, 2, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-secondary status-badge">{{ ucfirst($r->payment_method) }}</span>
                                </td>
                                <td>
                                    @php
                                        $statusClass = 'status-default'; // Default grey
                                        if ($r->payment_status === 'paid') {
                                            $statusClass = 'status-paid'; // Green
                                        } elseif ($r->payment_status === 'pending') {
                                            $statusClass = 'status-pending'; // Yellow
                                        } elseif ($r->payment_status === 'refunded') {
                                            $statusClass = 'status-refunded'; // Red
                                        }
                                    @endphp
                                    <span class="status-badge {{ $statusClass }}">{{ ucfirst($r->payment_status) }}</span>
                                </td>
                                <td>{{ $r->created_at->format('d/m/Y H:i') }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group" aria-label="Ações da Reserva">
                                        {{-- Edit Button --}}
                                        <a href="{{ route('admin.reservas.edit', $r->id) }}" class="btn btn-info btn-sm">Editar</a>

                                        {{-- Refund Button (only if paid) --}}
                                        @if($r->payment_status === 'paid')
                                            <form action="{{ route('admin.reservas.refund', $r->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Tem certeza que deseja reembolsar esta reserva? Esta ação é irreversível.');">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm">Reembolsar</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-4">Nenhuma reserva encontrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination Links --}}
            {{-- Check if $reservas is a paginator instance before calling links() --}}
            @if(method_exists($reservas, 'links'))
                <div class="d-flex justify-content-center mt-4">
                    {{ $reservas->links() }}
                </div>
            @endif
        </div> {{-- End .admin-content-card --}}
    </div> {{-- End .container --}}

    {{-- Bootstrap Bundle with Popper --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
@include('layouts.footer')
</html>
