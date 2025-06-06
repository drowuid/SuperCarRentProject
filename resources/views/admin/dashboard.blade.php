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
    max-height: 400px;
    overflow-y: auto;
    background: #ffffff;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 16px;
    display: flex;
    flex-direction: column;
    font-size: 14px;
}

.message-admin,
.message-user {
    padding: 8px 12px;
    border-radius: 12px;
    margin-bottom: 8px;
    max-width: 75%;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    word-wrap: break-word;
}


        .message-admin {
    background-color: #d1e7dd;
    align-self: flex-end;
    text-align: left;
    color: #155724;
}

        .message-user {
    background-color: #f8d7da;
    align-self: flex-start;
    text-align: left;
    color: #721c24;
}

.message-admin small,
.message-user small {
    display: block;
    font-size: 11px;
    color: #6c757d;
    margin-top: 4px;
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

        <div class="tab-content" id="adminTabsContent">
            {{-- 📄 Reservas --}}
            <div class="tab-pane fade show active" id="reservas" role="tabpanel" aria-labelledby="reservas-tab">
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
                                            <td>{{ $r->carro->modelo ?? 'N/A' }} ({{ $r->carro->marca->nome ?? 'N/A' }})
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($r->data_inicio)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($r->data_fim)->format('d/m/Y') }}</td>
                                            <td>€{{ number_format($r->preco_total, 2, ',', '.') }}</td>
                                            <td><span
                                                    class="badge bg-secondary status-badge">{{ ucfirst($r->payment_method) }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $statusClass = match ($r->payment_status) {
                                                        'paid' => 'status-paid',
                                                        'pending' => 'status-pending',
                                                        'refunded' => 'status-refunded',
                                                        default => 'status-default',
                                                    };
                                                @endphp
                                                <span
                                                    class="status-badge {{ $statusClass }}">{{ ucfirst($r->payment_status) }}</span>
                                            </td>
                                            <td>{{ $r->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="text-center">
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.reservas.edit', $r->id) }}"
                                                        class="btn btn-info btn-sm">Editar</a>
                                                    @if ($r->payment_status === 'paid')
                                                        <form action="{{ route('admin.reservas.refund', $r->id) }}"
                                                            method="POST" style="display:inline-block;"
                                                            onsubmit="return confirm('Tem certeza que deseja reembolsar esta reserva?');">
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
                                            <td colspan="10" class="text-center py-4">Nenhuma reserva encontrada.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>

            {{-- 💬 Mensagens --}}
<div class="tab-pane fade" id="mensagens" role="tabpanel" aria-labelledby="mensagens-tab">
    <div class="admin-content-card">
        <h4 class="mb-3 text-center">Mensagens de Clientes</h4>

        @if($messages->isEmpty())
            <p class="text-center text-muted">Nenhuma mensagem recebida.</p>
        @else
            <div class="accordion" id="accordionMensagens">
                @foreach ($messages as $userId => $msgs)
                    @php $chatUser = $msgs->first()->user; @endphp

                    <div class="accordion-item mb-3">
                        <h2 class="accordion-header" id="heading-{{ $userId }}">
                            <button class="accordion-button collapsed" type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse-{{ $userId }}"
                                    aria-expanded="false"
                                    aria-controls="collapse-{{ $userId }}">
                                {{ $chatUser->name }} ({{ $chatUser->email }})
                            </button>
                        </h2>
                        <div id="collapse-{{ $userId }}" class="accordion-collapse collapse"
                             aria-labelledby="heading-{{ $userId }}"
                             data-bs-parent="#accordionMensagens">
                            <div class="accordion-body">

                                {{-- 🔁 Chat Box --}}
                                <div class="chat-box bg-light rounded p-3 mb-3" id="chat-box-{{ $chatUser->id }}">
                                    <div class="text-muted">Carregando mensagens...</div>
                                </div>

                                {{-- ✉️ Reply Form --}}
                                <form onsubmit="sendAdminMessage(event, {{ $chatUser->id }})" class="d-flex">
                                    @csrf
                                    <input type="text" id="msg-input-{{ $chatUser->id }}"
                                           class="form-control me-2"
                                           placeholder="Responder..." required>
                                    <button type="submit" class="btn btn-success">Enviar</button>
                                </form>

                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function fetchMessages(userId) {
            // route to match the backend endpoint for fetching user messages
            fetch(`/admin/messages/${userId}/fetch`)
                .then(response => {
                    if (!response.ok) {
                        if (response.status === 403) {
                            console.error('Unauthorized to fetch messages for user:', userId);

                            return Promise.reject('Unauthorized');
                        }
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(messages => {
                    const box = document.getElementById('chat-box-' + userId);
                    if (!box) {
                        console.warn(`Chat box for user ID ${userId} not found.`);
                        return;
                    }
                    box.innerHTML = '';
                    messages.forEach(msg => {
                        const div = document.createElement('div');

                        const isAdminSender = msg.is_admin;
                        div.className = 'mb-2 ' + (isAdminSender ? 'text-end text-success' : 'text-start text-primary');
                        div.innerHTML = `
                            <div><strong>${isAdminSender ? 'Admin' : msg.user_name}:</strong> ${msg.message}</div>
                            <small class="text-muted">${msg.created_at}</small>
                        `;
                        box.appendChild(div);
                    });
                    box.scrollTop = box.scrollHeight;
                })
                .catch(error => console.error('Error fetching messages:', error));
        }

        function sendAdminMessage(event, userId) {
            event.preventDefault();
            const input = document.getElementById('msg-input-' + userId);
            const message = input.value.trim();
            if (!message) return;

            // route to match the backend endpoint for sending messages
            fetch(`/admin/messages/${userId}`, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ message })
})
.then(response => {
    if (!response.ok) throw new Error('Server error');
    return response.json();
})
.then(data => {
    if (data.status === 'ok') {
        input.value = '';
        fetchMessages(userId);
    } else {
        alert('Erro ao enviar mensagem.');
    }
})
.catch(() => alert('Erro ao enviar mensagem.'));

        }

        // Global object to store intervals
        window.messagePollingIntervals = {};

        // Function to start polling for a specific user ID
        function startPollingMessages(userId) {

            if (window.messagePollingIntervals[userId]) {
                clearInterval(window.messagePollingIntervals[userId]);
            }
            // Fetch messages immediately
            fetchMessages(userId);
            // Set up interval for future fetches
            window.messagePollingIntervals[userId] = setInterval(() => fetchMessages(userId), 5000); // Poll every 5 seconds
        }

        // Function to stop polling for a specific user ID
        function stopPollingMessages(userId) {
            if (window.messagePollingIntervals[userId]) {
                clearInterval(window.messagePollingIntervals[userId]);
                delete window.messagePollingIntervals[userId];
            }
        }

        // Event listener for tab changes
        document.addEventListener('DOMContentLoaded', function() {
            const adminTabs = document.getElementById('adminTabs');
            if (adminTabs) {
                adminTabs.addEventListener('shown.bs.tab', function(event) {
                    const activeTabId = event.target.id;


                    for (const userId in window.messagePollingIntervals) {
                        stopPollingMessages(userId);
                    }

                    if (activeTabId === 'mensagens-tab') {

                        @foreach ($messages as $userId => $msgs)
                            startPollingMessages({{ $userId }});
                        @endforeach
                    }
                });


                const mensagensTabButton = document.getElementById('mensagens-tab');
                if (mensagensTabButton && mensagensTabButton.classList.contains('active')) {

                    mensagensTabButton.dispatchEvent(new Event('shown.bs.tab'));
                }
            }
        });


        @if (Request::segment(2) === 'dashboard' && Request::query('tab') === 'mensagens')
             @foreach ($messages as $userId => $msgs)
                startPollingMessages({{ $userId }});
            @endforeach
        @endif

    </script>

</body>

</html>
