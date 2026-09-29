@extends('layouts.admin')

@section('page_title', 'Gerenciamento de Missões')

@section('content')
<div class="admin-card-table">
    <div class="admin-table-filters" style="display: flex; justify-content: space-between; align-items: center;">
        <span style="font-family: var(--font-heading); font-weight: 700; font-size: 16px;">Lista de Missões</span>
        
        <button type="button" class="admin-badge" style="background-color: var(--color-yellow); color: #000; font-weight: 700; padding: 8px 16px; border: none; cursor: pointer; border-radius: 6px;" onclick="openCreateModal()">
            <i class="fa-solid fa-plus" style="margin-right: 4px;"></i> Nova Missão
        </button>
    </div>

    @if(session('success'))
        <div style="background-color: rgba(0, 200, 83, 0.1); border: 1px solid rgba(0, 200, 83, 0.2); color: #00c853; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 13px; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Tipo de Requisito</th>
                    <th>Alvo do Objetivo</th>
                    <th>Prêmio</th>
                    <th>Frequência</th>
                    <th>Status</th>
                    <th style="text-align: right;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($missions as $m)
                    <tr>
                        <td>#{{ $m->id }}</td>
                        <td>
                            <strong style="color: #fff;">{{ $m->title }}</strong>
                            @if($m->description)
                                <p style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">{{ $m->description }}</p>
                            @endif
                        </td>
                        <td>
                            @if($m->type === 'bet_count')
                                <span style="color: #00b0ff; font-weight: 600;">Qtd. de Rodadas/Apostas</span>
                            @elseif($m->type === 'bet_amount')
                                <span style="color: #00e676; font-weight: 600;">Valor Total Apostado</span>
                            @elseif($m->type === 'deposit_amount')
                                <span style="color: #e040fb; font-weight: 600;">Valor do Depósito</span>
                            @endif
                        </td>
                        <td>
                            @if($m->type === 'bet_count')
                                <strong>{{ intval($m->target_value) }}</strong> jogadas
                            @else
                                R$ <strong>{{ number_format($m->target_value, 2, ',', '.') }}</strong>
                            @endif
                            
                            @if($m->game_slug)
                                <span style="font-size: 10px; background-color: rgba(255,255,255,0.05); padding: 2px 6px; border-radius: 4px; color: var(--text-secondary); margin-left: 4px;">no {{ strtoupper($m->game_slug) }}</span>
                            @else
                                <span style="font-size: 10px; background-color: rgba(255,255,255,0.05); padding: 2px 6px; border-radius: 4px; color: var(--text-secondary); margin-left: 4px;">Todos os jogos</span>
                            @endif
                        </td>
                        <td style="color: var(--color-yellow); font-weight: 700;">R$ {{ number_format($m->reward_amount, 2, ',', '.') }}</td>
                        <td>
                            @if($m->period === 'daily')
                                <span class="admin-badge" style="background-color: rgba(0, 230, 118, 0.1); color: var(--color-neon-green); border-color: rgba(0, 230, 118, 0.2);">Diário</span>
                            @elseif($m->period === 'weekly')
                                <span class="admin-badge" style="background-color: rgba(0, 176, 255, 0.1); color: #00b0ff; border-color: rgba(0, 176, 255, 0.2);">Semanal</span>
                            @else
                                <span class="admin-badge" style="background-color: rgba(255, 255, 255, 0.05); color: var(--text-secondary); border-color: transparent;">Único</span>
                            @endif
                        </td>
                        <td>
                            @if($m->is_active)
                                <span class="admin-badge admin-badge-approved" style="background-color: rgba(0, 200, 83, 0.1); color: #00c853; border-color: rgba(0, 200, 83, 0.2);">Ativo</span>
                            @else
                                <span class="admin-badge" style="background-color: rgba(255, 255, 255, 0.05); color: var(--text-muted); border-color: rgba(255, 255, 255, 0.1);">Inativo</span>
                            @endif
                        </td>
                        <td>
                            <div class="admin-actions-cell" style="justify-content: flex-end; gap: 8px;">
                                <button type="button" class="admin-btn-action btn-edit" title="Editar Missão" onclick="openEditModal({{ json_encode($m) }})">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="{{ route('admin.missions.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Deseja realmente excluir esta missão?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn-action" style="background-color: rgba(255, 61, 0, 0.1); color: #ff3d00; border: 1px solid rgba(255, 61, 0, 0.2);" title="Remover Missão">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 40px 0;">
                            Nenhuma missão cadastrada no momento.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add / Edit Mission Modal -->
<div class="admin-balance-modal" id="missionModal" style="display: none;">
    <div class="admin-balance-modal-content" style="max-width: 550px;">
        <div class="admin-modal-header">
            <h3 id="modalTitle">Criar Nova Missão</h3>
            <span class="admin-btn-close-modal" onclick="closeMissionModal()">&times;</span>
        </div>
        
        <form id="missionForm" method="POST" action="">
            @csrf
            <div class="admin-form-group">
                <label for="m_title">Título da Missão</label>
                <input type="text" id="m_title" name="title" required placeholder="ex: Complete 10 apostas no Mines">
            </div>

            <div class="admin-form-group">
                <label for="m_description">Descrição</label>
                <textarea id="m_description" name="description" rows="2" placeholder="ex: Requisito: Aposta mínima de R$ 1,00"></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="admin-form-group">
                    <label for="m_type">Tipo de Objetivo</label>
                    <select id="m_type" name="type" required style="width: 100%; background: #161619; border: 1px solid var(--border-color); color: #fff; padding: 12px; border-radius: 8px; font-family: var(--font-body); outline: none; margin-top: 6px;">
                        <option value="bet_count">Quantidade de Rodadas (Apostas)</option>
                        <option value="bet_amount">Total Apostado (R$)</option>
                        <option value="deposit_amount">Valor de Depósito Único/Acumulado (R$)</option>
                    </select>
                </div>
                <div class="admin-form-group">
                    <label for="m_game_slug">Jogo Específico (Opcional)</label>
                    <select id="m_game_slug" name="game_slug" style="width: 100%; background: #161619; border: 1px solid var(--border-color); color: #fff; padding: 12px; border-radius: 8px; font-family: var(--font-body); outline: none; margin-top: 6px;">
                        <option value="">Qualquer Jogo (Sem restrição)</option>
                        @foreach($games as $game)
                            <option value="{{ $game->action_value }}">{{ $game->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="admin-form-group">
                    <label for="m_target_value">Valor Objetivo (Qtd. ou R$)</label>
                    <input type="number" id="m_target_value" name="target_value" step="0.01" min="0.01" required placeholder="ex: 10 ou 100.00">
                </div>
                <div class="admin-form-group">
                    <label for="m_reward_amount">Prêmio / Recompensa (R$)</label>
                    <input type="number" id="m_reward_amount" name="reward_amount" step="0.01" min="0.01" required placeholder="ex: 5.00">
                </div>
            </div>

            <div class="admin-form-group">
                <label for="m_period">Período de Reset</label>
                <select id="m_period" name="period" required style="width: 100%; background: #161619; border: 1px solid var(--border-color); color: #fff; padding: 12px; border-radius: 8px; font-family: var(--font-body); outline: none; margin-top: 6px;">
                    <option value="daily">Diário (Reinicia à meia-noite)</option>
                    <option value="weekly">Semanal (Reinicia no fim de semana)</option>
                    <option value="once">Único (Completa apenas uma vez por jogador)</option>
                </select>
            </div>

            <div style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
                <input type="checkbox" name="is_active" id="m_is_active" value="1" checked style="width: auto;">
                <label for="m_is_active" style="margin-bottom: 0; cursor: pointer;">Missão Ativa e Visível</label>
            </div>

            <button type="submit" class="admin-btn-save" style="margin-top: 20px;">SALVAR CONFIGURAÇÕES</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openCreateModal() {
        const form = document.getElementById('missionForm');
        form.action = "{{ route('admin.missions.store') }}";
        
        document.getElementById('modalTitle').innerText = "Criar Nova Missão";
        form.reset();
        
        document.getElementById('m_is_active').checked = true;
        document.getElementById('missionModal').style.display = 'flex';
    }

    const missionsUpdateUrlTemplate = "{{ route('admin.missions.update', ['id' => '__ID__']) }}";

    function openEditModal(m) {
        const form = document.getElementById('missionForm');
        form.action = missionsUpdateUrlTemplate.replace('__ID__', m.id);
        
        document.getElementById('modalTitle').innerText = "Editar Missão";
        
        document.getElementById('m_title').value = m.title;
        document.getElementById('m_description').value = m.description || '';
        document.getElementById('m_type').value = m.type;
        document.getElementById('m_game_slug').value = m.game_slug || '';
        document.getElementById('m_target_value').value = m.target_value;
        document.getElementById('m_reward_amount').value = m.reward_amount;
        document.getElementById('m_period').value = m.period;
        
        document.getElementById('m_is_active').checked = !!m.is_active;
        document.getElementById('missionModal').style.display = 'flex';
    }

    function closeMissionModal() {
        document.getElementById('missionModal').style.display = 'none';
    }

    // Close modal when clicking outside content
    window.onclick = function(event) {
        const modal = document.getElementById('missionModal');
        if (event.target == modal) {
            closeMissionModal();
        }
    }
</script>
@endsection
