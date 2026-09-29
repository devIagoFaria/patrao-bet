@extends('layouts.admin')

@section('page_title', 'Gestão de Usuários')

@section('content')
<div class="admin-card-table">
    <div class="admin-table-filters">
        <div class="admin-filter-left">
            <span style="font-family: var(--font-heading); font-weight: 700; font-size: 16px;">Lista de Jogadores</span>
        </div>

        <form action="{{ route('admin.users') }}" method="GET" class="admin-search-box">
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
                    <th>Saldo</th>
                    <th>Comissão</th>
                    <th>Apostas</th>
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
                                <div class="admin-table-avatar" style="overflow: hidden; display: flex; align-items: center; justify-content: center; background: none; border: 1.5px solid var(--color-yellow);">
                                    <img src="{{ $user->avatar_url }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                </div>
                                <div class="admin-user-details">
                                    <h5>{{ $user->name }}</h5>
                                    <p>{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td style="font-weight: 700; color: var(--color-yellow);">
                            R$ {{ number_format($user->balance, 2, ',', '.') }}
                        </td>
                        <td>
                            @if($user->commission_model === 'cpa' && $user->commission_value)
                                <span class="admin-badge admin-badge-approved" style="background-color: rgba(0, 230, 118, 0.1); color: var(--neon-green); border-color: rgba(0, 230, 118, 0.2);">
                                    CPA R$ {{ number_format($user->commission_value, 2, ',', '.') }}
                                </span>
                            @elseif($user->commission_value)
                                <span class="admin-badge admin-badge-approved" style="background-color: rgba(59, 130, 246, 0.1); color: var(--color-blue); border-color: rgba(59, 130, 246, 0.2);">
                                    REV {{ number_format($user->commission_value, 2, ',', '.') }}%
                                </span>
                            @else
                                <span style="color: var(--text-muted); font-size: 13px;">Padrão</span>
                            @endif
                        </td>
                        <td>
                            <span class="admin-badge admin-badge-approved" style="background-color: rgba(59, 130, 246, 0.1); color: var(--color-blue); border-color: rgba(59, 130, 246, 0.2);">
                                {{ $user->bets_count ?? $user->bets()->count() }} apostas
                            </span>
                        </td>
                        <td style="color: var(--text-secondary);">
                            {{ $user->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <div class="admin-actions-cell" style="justify-content: flex-end;">
                                <button type="button" class="admin-btn-action btn-edit" title="Ajustar Saldo"
                                    onclick="openBalanceModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->balance }}')">
                                    <i class="fa-solid fa-wallet"></i>
                                </button>
                                <button type="button" class="admin-btn-action btn-edit" title="Alterar Senha"
                                    onclick="openPasswordModal({{ $user->id }}, '{{ addslashes($user->name) }}')">
                                    <i class="fa-solid fa-key"></i>
                                </button>
                                <button type="button" class="admin-btn-action btn-edit" title="Comissão de Afiliado"
                                    onclick="openCommissionModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->commission_model ?? 'revshare' }}', '{{ $user->commission_value }}')">
                                    <i class="fa-solid fa-percent"></i>
                                </button>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Deseja realmente excluir o usuário {{ addslashes($user->name) }}? Esta ação não pode ser desfeita.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn-action btn-delete" title="Excluir Usuário">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px 0;">
                            Nenhum jogador encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="admin-pagination-wrapper">
        {{ $users->appends(['search' => $search])->links() }}
    </div>
</div>

<!-- Edit Balance Modal -->
<div class="admin-balance-modal" id="balanceModal">
    <div class="admin-balance-modal-content">
        <div class="admin-modal-header">
            <h3>Ajustar Saldo</h3>
            <span class="admin-btn-close-modal" onclick="closeBalanceModal()">&times;</span>
        </div>

        <form id="balanceForm" method="POST" action="">
            @csrf
            <div style="margin-bottom: 20px;">
                <p style="font-size: 13.5px; color: var(--text-secondary);">
                    Jogador: <strong id="modalUserName" style="color: var(--text-primary);"></strong>
                </p>
                <p style="font-size: 13.5px; color: var(--text-secondary); margin-top: 4px;">
                    Saldo atual: <strong id="modalUserBalance" style="color: var(--color-yellow);"></strong>
                </p>
            </div>

            <!-- Radio group for Add or Subtract -->
            <div class="admin-radio-group">
                <label class="admin-radio-btn selected-add" id="radioLabelAdd">
                    <input type="radio" name="action" value="add" checked onclick="selectAction('add')">
                    <i class="fa-solid fa-plus-circle"></i> Adicionar
                </label>
                <label class="admin-radio-btn" id="radioLabelSub">
                    <input type="radio" name="action" value="subtract" onclick="selectAction('subtract')">
                    <i class="fa-solid fa-minus-circle"></i> Subtrair
                </label>
            </div>

            <div class="admin-form-group">
                <label for="amount">Valor (R$)</label>
                <input type="number" id="amount" name="amount" required step="0.01" min="0.01" placeholder="ex: 150,00">
            </div>

            <button type="submit" class="admin-btn-save">SALVAR AJUSTE</button>
        </form>
    </div>
</div>

<!-- Change Password Modal -->
<div class="admin-balance-modal" id="passwordModal">
    <div class="admin-balance-modal-content">
        <div class="admin-modal-header">
            <h3>Alterar Senha</h3>
            <span class="admin-btn-close-modal" onclick="closePasswordModal()">&times;</span>
        </div>

        <form id="passwordForm" method="POST" action="">
            @csrf
            <div style="margin-bottom: 20px;">
                <p style="font-size: 13.5px; color: var(--text-secondary);">
                    Jogador: <strong id="modalPasswordUserName" style="color: var(--text-primary);"></strong>
                </p>
            </div>

            <div class="admin-form-group">
                <label for="password">Nova Senha</label>
                <input type="password" id="password" name="password" required minlength="6" placeholder="Mínimo 6 caracteres">
            </div>

            <div class="admin-form-group">
                <label for="password_confirmation">Confirmar Senha</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required minlength="6" placeholder="Repita a nova senha">
            </div>

            <button type="submit" class="admin-btn-save">SALVAR SENHA</button>
        </form>
    </div>
</div>

<!-- Commission Modal -->
<div class="admin-balance-modal" id="commissionModal">
    <div class="admin-balance-modal-content">
        <div class="admin-modal-header">
            <h3>Comissão de Afiliado</h3>
            <span class="admin-btn-close-modal" onclick="closeCommissionModal()">&times;</span>
        </div>

        <form id="commissionForm" method="POST" action="">
            @csrf
            <div style="margin-bottom: 20px;">
                <p style="font-size: 13.5px; color: var(--text-secondary);">
                    Jogador: <strong id="modalCommissionUserName" style="color: var(--text-primary);"></strong>
                </p>
            </div>

            <!-- Radio group for CPA or Revenue Share -->
            <div class="admin-radio-group">
                <label class="admin-radio-btn selected-add" id="commissionLabelRevshare">
                    <input type="radio" name="commission_model" value="revshare" checked onclick="selectCommissionModel('revshare')">
                    % REV
                </label>
                <label class="admin-radio-btn" id="commissionLabelCpa">
                    <input type="radio" name="commission_model" value="cpa" onclick="selectCommissionModel('cpa')">
                    CPA
                </label>
            </div>

            <div class="admin-form-group">
                <label for="commission_value" id="commissionValueLabel">Percentual de Revenue Share (%)</label>
                <input type="number" id="commission_value" name="commission_value" required step="0.01" min="0" placeholder="ex: 10">
            </div>

            <button type="submit" class="admin-btn-save">SALVAR COMISSÃO</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const usersBalanceUrlTemplate = "{{ route('admin.users.balance', ['id' => '__ID__']) }}";
    const usersPasswordUrlTemplate = "{{ route('admin.users.password', ['id' => '__ID__']) }}";
    const usersCommissionUrlTemplate = "{{ route('admin.users.commission', ['id' => '__ID__']) }}";

    function openBalanceModal(userId, name, balance) {
        const form = document.getElementById('balanceForm');
        form.action = usersBalanceUrlTemplate.replace('__ID__', userId);

        document.getElementById('modalUserName').innerText = name;

        const formattedBalance = parseFloat(balance).toLocaleString('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        });
        document.getElementById('modalUserBalance').innerText = formattedBalance;

        document.querySelector('input[value="add"]').checked = true;
        selectAction('add');

        document.getElementById('balanceModal').style.display = 'flex';
    }

    function closeBalanceModal() {
        document.getElementById('balanceModal').style.display = 'none';
    }

    function selectAction(action) {
        const labelAdd = document.getElementById('radioLabelAdd');
        const labelSub = document.getElementById('radioLabelSub');

        if (action === 'add') {
            labelAdd.classList.add('selected-add');
            labelSub.classList.remove('selected-sub');
        } else {
            labelSub.classList.add('selected-sub');
            labelAdd.classList.remove('selected-add');
        }
    }

    function openPasswordModal(userId, name) {
        const form = document.getElementById('passwordForm');
        form.action = usersPasswordUrlTemplate.replace('__ID__', userId);

        document.getElementById('modalPasswordUserName').innerText = name;
        document.getElementById('password').value = '';
        document.getElementById('password_confirmation').value = '';

        document.getElementById('passwordModal').style.display = 'flex';
    }

    function closePasswordModal() {
        document.getElementById('passwordModal').style.display = 'none';
    }

    function openCommissionModal(userId, name, model, value) {
        const form = document.getElementById('commissionForm');
        form.action = usersCommissionUrlTemplate.replace('__ID__', userId);

        document.getElementById('modalCommissionUserName').innerText = name;
        document.getElementById('commission_value').value = value && value !== '' ? value : '';

        const isCpa = model === 'cpa';
        document.querySelector(`input[name="commission_model"][value="${isCpa ? 'cpa' : 'revshare'}"]`).checked = true;
        selectCommissionModel(isCpa ? 'cpa' : 'revshare');

        document.getElementById('commissionModal').style.display = 'flex';
    }

    function closeCommissionModal() {
        document.getElementById('commissionModal').style.display = 'none';
    }

    function selectCommissionModel(model) {
        const labelRevshare = document.getElementById('commissionLabelRevshare');
        const labelCpa = document.getElementById('commissionLabelCpa');
        const valueLabel = document.getElementById('commissionValueLabel');
        const valueInput = document.getElementById('commission_value');

        if (model === 'cpa') {
            labelCpa.classList.add('selected-add');
            labelRevshare.classList.remove('selected-add');
            valueLabel.innerText = 'Valor fixo por indicação (R$)';
            valueInput.placeholder = 'ex: 50,00';
        } else {
            labelRevshare.classList.add('selected-add');
            labelCpa.classList.remove('selected-add');
            valueLabel.innerText = 'Percentual de Revenue Share (%)';
            valueInput.placeholder = 'ex: 10';
        }
    }

    // Close modal when clicking outside content
    window.onclick = function(event) {
        const modals = ['balanceModal', 'passwordModal', 'commissionModal'];
        modals.forEach(id => {
            const modal = document.getElementById(id);
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        });
    }
</script>
@endsection
