<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <title>Painel de Administração - SuperCarRent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .top-nav {
            background: #343a40;
            padding: 10px 20px;
            color: white;
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

        .admin-content-card {
            background-color: #ffffff;
            border-radius: 0.5rem;
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-top: 1.5rem;
        }

        .status-badge {
            padding: .35em .65em;
            border-radius: .25rem;
            font-size: .75em;
            font-weight: 700;
            color: #fff;
        }

        .status-paid {
            background-color: #28a745;
        }

        .status-pending {
            background-color: #ffc107;
            color: #343a40;
        }

        .status-refunded {
            background-color: #dc3545;
        }

        .status-default {
            background-color: #6c757d;
        }

        .bg-dark-custom {
            background-color: #343a40;
        }

        .chat-box {
            height: 400px;
            overflow-y: auto;
            background: #f1f1f1;
            padding: 15px;
            border-radius: 5px;
        }

        .message-admin {
            text-align: right;
            color: #155724;
        }

        .message-user {
            text-align: left;
            color: #004085;
        }
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
        <ul class="nav nav-tabs mt-4" id="adminTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="reservas-tab" data-bs-toggle="tab" data-bs-target="#reservas"
                    type="button" role="tab">Reservas</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="mensagens-tab" data-bs-toggle="tab" data-bs-target="#mensagens"
                    type="button" role="tab">Mensagens</button>
            </li>
        </ul>

        <div class="tab-content">
            {{-- 📄 Reservas --}}
            <div class="tab-pane fade show active" id="reservas" role="tabpanel">
                    <div class="container">
                        <div class="admin-content-card">
                            <h2 class="mb-4 text-center">Histórico de Todas as Reservas</h2>

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
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
                                                <td>{{ $r->carro->modelo ?? 'N/A' }}
                                                    ({{ $r->carro->marca->nome ?? 'N/A' }})
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($r->data_inicio)->format('d/m/Y') }}</td>
                                                <td>{{ \Carbon\Carbon::parse($r->data_fim)->format('d/m/Y') }}</td>
                                                <td>€{{ number_format($r->preco_total, 2, ',', '.') }}</td>
                                                <td>
                                                    <span
                                                        class="badge bg-secondary status-badge">{{ ucfirst($r->payment_method) }}</span>
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
                                                    <span
                                                        class="status-badge {{ $statusClass }}">{{ ucfirst($r->payment_status) }}</span>
                                                </td>
                                                <td>{{ $r->created_at->format('d/m/Y H:i') }}</td>
                                                <td class="text-center">
                                                    <div class="btn-group" role="group" aria-label="Ações da Reserva">
                                                        {{-- Edit Button --}}
                                                        <a href="{{ route('admin.reservas.edit', $r->id) }}"
                                                            class="btn btn-info btn-sm">Editar</a>

                                                        {{-- Refund Button (only if paid) --}}
                                                        @if ($r->payment_status === 'paid')
                                                            <form action="{{ route('admin.reservas.refund', $r->id) }}"
                                                                method="POST" style="display:inline-block;"
                                                                onsubmit="return confirm('Tem certeza que deseja reembolsar esta reserva? Esta ação é irreversível.');">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="btn btn-danger btn-sm">Reembolsar</button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center py-4">Nenhuma reserva encontrada.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            {{-- 💬 Mensagens --}}
            <div class="tab-pane fade" id="mensagens" role="tabpanel">
                <div class="admin-content-card">
                    <h4 class="mb-3 text-center">Mensagens de Clientes</h4>

                    @if ($messages->isEmpty())
                        <p class="text-center text-muted">Nenhuma mensagem recebida.</p>
                    @else
                    @foreach ($reservas as $reserva)
                        @if ($reserva->user)
                            <div class="mb-5">
                                <h6 class="text-primary">{{ $reserva->user->name }}
                                    ({{ $reserva->user->email }})</h6>

                                <div class="chat-box mb-3">

                                    @foreach ($messages as $msg)
                                        <div class="mb-2 {{ $msg->is_admin ? 'message-admin' : 'message-user' }}">
                                            <small><strong>{{ $msg->is_admin ? 'Admin' : $reserva->user->name }}:</strong>
                                                {{ $msg->message }}</small>
                                        </div>
                                    @endforeach

                                </div>

                                <form action="{{ route('admin.messages.reply', $reserva->user->id) }}" method="POST"
                                    class="d-flex">
                                    @csrf
                                    <input type="text" name="message" class="form-control me-2"
                                        placeholder="Responder..." required>
                                    <button type="submit" class="btn btn-primary">Enviar</button>
                                </form>
                            </div>
                        @else
                            <p class="text-muted">Nenhuma mensagem recebida.</p>
                        @endif
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <footer class="fixed bottom-0 left-0 w-full text-center py-4 bg-dark-custom text-white">
        &copy; {{ date('Y') }} SuperCarRent. Todos os direitos reservados.
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const footer = document.querySelector('footer');
            if (footer) {
                document.body.style.paddingBottom = footer.offsetHeight + 'px';
            }
        });
    </script>
</body>

</html>
