@extends('layouts.admin')

@section('page_title', 'Verificações KYC')

@section('content')
<div class="admin-card-table">
    <div class="admin-table-filters">
        <div class="admin-filter-left" style="display: flex; gap: 12px; align-items: center;">
            <span style="font-family: var(--font-heading); font-weight: 700; font-size: 16px; margin-right: 12px;">Lista de Validações</span>
            
            <!-- Quick filters -->
            <a href="{{ route('admin.kyc') }}" class="admin-badge" style="background-color: {{ !$status ? 'rgba(0, 230, 118, 0.15)' : 'rgba(255,255,255,0.05)' }}; color: {{ !$status ? 'var(--color-neon-green)' : 'var(--text-secondary)' }}; border-color: {{ !$status ? 'var(--color-neon-green)' : 'transparent' }}; text-decoration: none;">
                Todos
            </a>
            <a href="{{ route('admin.kyc', ['status' => 'pending']) }}" class="admin-badge" style="background-color: {{ $status === 'pending' ? 'rgba(255, 193, 7, 0.15)' : 'rgba(255,255,255,0.05)' }}; color: {{ $status === 'pending' ? '#ffc107' : 'var(--text-secondary)' }}; border-color: {{ $status === 'pending' ? '#ffc107' : 'transparent' }}; text-decoration: none;">
                Pendentes
            </a>
            <a href="{{ route('admin.kyc', ['status' => 'approved']) }}" class="admin-badge" style="background-color: {{ $status === 'approved' ? 'rgba(0, 200, 83, 0.15)' : 'rgba(255,255,255,0.05)' }}; color: {{ $status === 'approved' ? '#00c853' : 'var(--text-secondary)' }}; border-color: {{ $status === 'approved' ? '#00c853' : 'transparent' }}; text-decoration: none;">
                Aprovados
            </a>
            <a href="{{ route('admin.kyc', ['status' => 'declined']) }}" class="admin-badge" style="background-color: {{ $status === 'declined' ? 'rgba(255, 61, 0, 0.15)' : 'rgba(255,255,255,0.05)' }}; color: {{ $status === 'declined' ? '#ff3d00' : 'var(--text-secondary)' }}; border-color: {{ $status === 'declined' ? '#ff3d00' : 'transparent' }}; text-decoration: none;">
                Recusados
            </a>
        </div>
        
        <form action="{{ route('admin.kyc') }}" method="GET" class="admin-search-box">
            @if($status)
                <input type="hidden" name="status" value="{{ $status }}">
            @endif
            <i class="fa-solid fa-magnifying-glass"></i>
            <input type="text" name="search" value="{{ $search }}" placeholder="Buscar por nome ou e-mail...">
        </form>
    </div>

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Usuário</th>
                    <th>Status KYC</th>
                    <th>Link Didit</th>
                    <th>Cadastrado Em</th>
                    <th style="text-align: right;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>#{{ $user->id }}</td>
                        <td>
                            <div class="admin-table-user">
                                <div class="admin-table-avatar" style="overflow: hidden; display: flex; align-items: center; justify-content: center; background: none; border: 1.5px solid var(--color-blue);">
                                    <img src="{{ $user->avatar_url }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                </div>
                                <div class="admin-user-details">
                                    <h5>{{ $user->name }}</h5>
                                    <p>{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($user->kyc_status === 'approved')
                                <span class="admin-badge admin-badge-approved" style="background-color: rgba(0, 200, 83, 0.1); color: #00c853; border-color: rgba(0, 200, 83, 0.2);">
                                    <i class="fa-solid fa-circle-check" style="margin-right: 4px;"></i> Aprovado
                                </span>
                            @elseif($user->kyc_status === 'pending')
                                <span class="admin-badge admin-badge-pending" style="background-color: rgba(255, 193, 7, 0.1); color: #ffc107; border-color: rgba(255, 193, 7, 0.2);">
                                    <i class="fa-solid fa-circle-notch fa-spin" style="margin-right: 4px;"></i> Pendente
                                </span>
                            @elseif($user->kyc_status === 'declined')
                                <span class="admin-badge admin-badge-rejected" style="background-color: rgba(255, 61, 0, 0.1); color: #ff3d00; border-color: rgba(255, 61, 0, 0.2);">
                                    <i class="fa-solid fa-circle-xmark" style="margin-right: 4px;"></i> Recusado
                                </span>
                            @else
                                <span class="admin-badge" style="background-color: rgba(255, 255, 255, 0.05); color: var(--text-muted); border-color: rgba(255, 255, 255, 0.1);">
                                    <i class="fa-regular fa-circle" style="margin-right: 4px;"></i> Não Iniciado
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($user->kyc_session_url)
                                <a href="{{ $user->kyc_session_url }}" target="_blank" style="color: var(--color-gold); font-size: 12.5px; text-decoration: underline;" title="Abrir URL do fluxo no navegador">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Abrir Sessão
                                </a>
                            @else
                                <span style="color: var(--text-muted); font-size: 12.5px;">Sem sessão gerada</span>
                            @endif
                        </td>
                        <td style="color: var(--text-secondary);">
                            {{ $user->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <div class="admin-actions-cell" style="justify-content: flex-end;">
                                <button type="button" class="admin-btn-action btn-edit" title="Alterar Status KYC" 
                                    onclick="openKycStatusModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->kyc_status ?? 'not_started' }}')">
                                    <i class="fa-solid fa-user-shield"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 40px 0;">
                            Nenhum registro de KYC encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="admin-pagination-wrapper">
        {{ $users->appends(['search' => $search, 'status' => $status])->links() }}
    </div>
</div>

<!-- Edit KYC Status Modal -->
<div class="admin-balance-modal" id="kycStatusModal">
    <div class="admin-balance-modal-content" style="max-width: 450px;">
        <div class="admin-modal-header">
            <h3>Alterar Status KYC</h3>
            <span class="admin-btn-close-modal" onclick="closeKycStatusModal()">&times;</span>
        </div>
        
        <form id="kycStatusForm" method="POST" action="">
            @csrf
            <div style="margin-bottom: 20px;">
                <p style="font-size: 13.5px; color: var(--text-secondary);">
                    Jogador: <strong id="modalUserName" style="color: var(--text-primary);"></strong>
                </p>
            </div>

            <div class="admin-form-group">
                <label for="kyc_status_select">Selecione o Novo Status</label>
                <select id="kyc_status_select" name="status" style="width: 100%; background: #161619; border: 1px solid var(--border-color); color: #fff; padding: 12px; border-radius: 8px; font-family: var(--font-body); outline: none; margin-top: 6px;">
                    <option value="not_started">Não Iniciado</option>
                    <option value="pending">Pendente (Em Análise)</option>
                    <option value="approved">Aprovado (Permite Saque)</option>
                    <option value="declined">Recusado (Bloqueia Saque)</option>
                </select>
                <span class="admin-form-help">Ao mudar para 'Aprovado', os saques do jogador serão imediatamente liberados. Mudar para 'Recusado' ou 'Pendente' impedirá os saques novamente.</span>
            </div>

            <button type="submit" class="admin-btn-save" style="margin-top: 15px;">SALVAR ALTERAÇÃO</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const kycStatusUrlTemplate = "{{ route('admin.kyc.status', ['id' => '__ID__']) }}";

    function openKycStatusModal(userId, name, currentStatus) {
        // Set action form URL
        const form = document.getElementById('kycStatusForm');
        form.action = kycStatusUrlTemplate.replace('__ID__', userId);
        
        // Set name
        document.getElementById('modalUserName').innerText = name;
        
        // Select status
        const select = document.getElementById('kyc_status_select');
        select.value = currentStatus ? currentStatus : 'not_started';
        
        // Show modal
        document.getElementById('kycStatusModal').style.display = 'flex';
    }

    function closeKycStatusModal() {
        document.getElementById('kycStatusModal').style.display = 'none';
    }

    // Close modal when clicking outside content
    window.onclick = function(event) {
        const modal = document.getElementById('kycStatusModal');
        if (event.target == modal) {
            closeKycStatusModal();
        }
    }
</script>
@endsection
