@extends('layouts.admin')

@section('page_title', 'Gerenciamento de Torneios')

@section('content')
<div class="admin-card-table">
    <div class="admin-table-filters" style="display: flex; justify-content: space-between; align-items: center;">
        <span style="font-family: var(--font-heading); font-weight: 700; font-size: 16px;">Lista de Torneios</span>
        
        <button type="button" class="admin-badge" style="background-color: var(--color-yellow); color: #000; font-weight: 700; padding: 8px 16px; border: none; cursor: pointer; border-radius: 6px;" onclick="openCreateModal()">
            <i class="fa-solid fa-plus" style="margin-right: 4px;"></i> Novo Torneio
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
                    <th>Banner</th>
                    <th>Título</th>
                    <th>Premiação</th>
                    <th>Período</th>
                    <th>Status</th>
                    <th style="text-align: right;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tournaments as $t)
                    <tr>
                        <td>#{{ $t->id }}</td>
                        <td>
                            @if($t->banner_path)
                                <img src="{{ asset($t->banner_path) }}" alt="Banner" style="max-height: 40px; border-radius: 4px; max-width: 100px; object-fit: contain;">
                            @else
                                <span style="font-size: 11px; color: var(--text-muted);">Padrão</span>
                            @endif
                        </td>
                        <td>
                            <strong style="color: #fff;">{{ $t->title }}</strong>
                            <p style="font-size: 10px; color: var(--text-muted); margin-top: 2px;">{{ count($t->games) }} jogos elegíveis</p>
                        </td>
                        <td style="color: var(--color-yellow); font-weight: 700;">R$ {{ number_format($t->prize_pool, 2, ',', '.') }}</td>
                        <td style="font-size: 12px; color: var(--text-secondary);">
                            De: {{ $t->start_date->format('d/m/Y H:i') }}<br>
                            Até: {{ $t->end_date->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            @php
                                $now = now();
                                $isCurrent = $t->is_active && $t->start_date <= $now && $t->end_date >= $now;
                                $hasFinished = $t->end_date < $now;
                            @endphp
                            @if(!$t->is_active)
                                <span class="admin-badge" style="background-color: rgba(255, 255, 255, 0.05); color: var(--text-muted); border-color: rgba(255, 255, 255, 0.1);">Inativo</span>
                            @elseif($isCurrent)
                                <span class="admin-badge" style="background-color: rgba(0, 230, 118, 0.1); color: var(--color-neon-green); border-color: rgba(0, 230, 118, 0.2);">Ativo</span>
                            @elseif($hasFinished)
                                <span class="admin-badge" style="background-color: rgba(255, 255, 255, 0.05); color: var(--text-muted); border-color: transparent;">Encerrado</span>
                            @else
                                <span class="admin-badge" style="background-color: rgba(255, 193, 7, 0.1); color: #ffc107; border-color: rgba(255, 193, 7, 0.2);">Agendado</span>
                            @endif
                        </td>
                        <td>
                            <div class="admin-actions-cell" style="justify-content: flex-end; gap: 8px;">
                                <button type="button" class="admin-btn-action btn-edit" style="background-color: rgba(0, 176, 255, 0.1); color: #00b0ff; border: 1px solid rgba(0, 176, 255, 0.2);" title="Ver Classificação (Leaderboard)" onclick="openLeaderboardModal({{ $t->id }}, '{{ addslashes($t->title) }}')">
                                    <i class="fa-solid fa-list-ol"></i>
                                </button>
                                <button type="button" class="admin-btn-action btn-edit" title="Editar Torneio" onclick="openEditModal({{ json_encode($t) }})">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="{{ route('admin.tournaments.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Deseja realmente excluir este torneio?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="admin-btn-action" style="background-color: rgba(255, 61, 0, 0.1); color: #ff3d00; border: 1px solid rgba(255, 61, 0, 0.2);" title="Remover Torneio">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px 0;">
                            Nenhum torneio cadastrado no momento.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Add / Edit Tournament Modal -->
<div class="admin-balance-modal" id="tournamentModal" style="display: none;">
    <div class="admin-balance-modal-content" style="max-width: 600px; max-height: 90vh; overflow-y: auto;">
        <div class="admin-modal-header">
            <h3 id="modalTitle">Criar Novo Torneio</h3>
            <span class="admin-btn-close-modal" onclick="closeTournamentModal()">&times;</span>
        </div>
        
        <form id="tournamentForm" method="POST" action="" enctype="multipart/form-data">
            @csrf
            <div class="admin-form-group">
                <label for="t_title">Título do Torneio</label>
                <input type="text" id="t_title" name="title" required placeholder="ex: Torneio de Slots da Semana">
            </div>

            <div class="admin-form-group">
                <label for="t_description">Descrição Curta</label>
                <textarea id="t_description" name="description" rows="2" placeholder="ex: Jogue nos slots participantes para acumular pontos..."></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="admin-form-group">
                    <label for="t_prize_pool">Premiação Total (R$)</label>
                    <input type="number" id="t_prize_pool" name="prize_pool" step="0.01" min="0.01" required placeholder="ex: 1000.00">
                </div>
                <div class="admin-form-group">
                    <label for="t_banner">Banner do Torneio (Opcional)</label>
                    <input type="file" id="t_banner" name="banner" accept="image/*" onchange="previewTournamentBanner(this)">
                </div>
            </div>

            <!-- Tournament Banner Preview -->
            <div id="t_banner_preview_container" style="margin-top: 10px; margin-bottom: 15px; text-align: center; border: 1px dashed var(--border-color); padding: 12px; border-radius: 8px; background-color: #0c0c0e; display: none;">
                <p style="font-size: 10px; color: var(--text-muted); margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Visualização do Banner</p>
                <img id="t_banner_preview_img" src="#" alt="Prévia do Banner" style="max-height: 120px; max-width: 100%; border-radius: 6px; object-fit: contain;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="admin-form-group">
                    <label for="t_start_date">Data de Início</label>
                    <input type="datetime-local" id="t_start_date" name="start_date" required>
                </div>
                <div class="admin-form-group">
                    <label for="t_end_date">Data de Fim</label>
                    <input type="datetime-local" id="t_end_date" name="end_date" required>
                </div>
            </div>

            <div class="admin-form-group">
                <label for="t_rules">Regras e Termos</label>
                <textarea id="t_rules" name="rules" rows="3" placeholder="ex: 1. Aposta mínima de R$ 1,00. 2. Apenas os jogos marcados pontuam."></textarea>
            </div>

            <div class="admin-form-group">
                <label style="margin-bottom: 8px; display: block;">Jogos Participantes (Selecione quais pontuam)</label>
                <div style="background-color: #161619; border: 1px solid var(--border-color); padding: 12px; border-radius: 8px; max-height: 150px; overflow-y: auto;">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        @foreach($games as $game)
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 13px; color: var(--text-secondary); cursor: pointer;">
                                <input type="checkbox" name="games[]" value="{{ $game->action_value }}" class="t-game-checkbox">
                                <span>{{ $game->title }} ({{ strtoupper($game->action_value) }})</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div style="display: flex; align-items: center; gap: 10px; margin-top: 10px;">
                <input type="checkbox" name="is_active" id="t_is_active" value="1" checked style="width: auto;">
                <label for="t_is_active" style="margin-bottom: 0; cursor: pointer;">Torneio Ativo e Visível</label>
            </div>

            <button type="submit" class="admin-btn-save" style="margin-top: 20px;">SALVAR CONFIGURAÇÕES</button>
        </form>
    </div>
</div>

<!-- Tournament Leaderboard Modal -->
<div class="admin-balance-modal" id="leaderboardModal" style="display: none;">
    <div class="admin-balance-modal-content" style="max-width: 550px;">
        <div class="admin-modal-header">
            <h3>Classificação Geral</h3>
            <span class="admin-btn-close-modal" onclick="closeLeaderboardModal()">&times;</span>
        </div>
        
        <div style="margin-bottom: 15px;">
            <p style="font-size: 14px; color: var(--text-muted);">
                Torneio: <strong id="leadTournamentTitle" style="color: var(--color-yellow);"></strong>
            </p>
        </div>

        <div class="admin-table-wrapper" style="max-height: 400px; overflow-y: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 70px;">Rank</th>
                        <th>Jogador</th>
                        <th>Pontuação</th>
                    </tr>
                </thead>
                <tbody id="leaderboardTableBody">
                    <tr>
                        <td colspan="3" style="text-align: center; color: var(--text-muted); padding: 20px;">
                            Nenhum jogador pontuou neste torneio ainda.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openCreateModal() {
        const form = document.getElementById('tournamentForm');
        form.action = "{{ route('admin.tournaments.store') }}";
        
        document.getElementById('modalTitle').innerText = "Criar Novo Torneio";
        form.reset();
        
        // Uncheck all game checkboxes
        const checkboxes = document.querySelectorAll('.t-game-checkbox');
        checkboxes.forEach(c => c.checked = false);
        
        // Reset banner preview
        document.getElementById('t_banner_preview_img').src = '#';
        document.getElementById('t_banner_preview_container').style.display = 'none';
        
        document.getElementById('t_is_active').checked = true;
        document.getElementById('tournamentModal').style.display = 'flex';
    }

    const tournamentsUpdateUrlTemplate = "{{ route('admin.tournaments.update', ['id' => '__ID__']) }}";

    function openEditModal(t) {
        const form = document.getElementById('tournamentForm');
        form.action = tournamentsUpdateUrlTemplate.replace('__ID__', t.id);
        
        document.getElementById('modalTitle').innerText = "Editar Torneio";
        
        document.getElementById('t_title').value = t.title;
        document.getElementById('t_description').value = t.description || '';
        document.getElementById('t_prize_pool').value = t.prize_pool;
        document.getElementById('t_rules').value = t.rules || '';
        
        // Format dates correctly for input value (YYYY-MM-DDThh:mm)
        if (t.start_date) {
            const sDate = new Date(t.start_date);
            document.getElementById('t_start_date').value = sDate.toISOString().slice(0, 16);
        }
        if (t.end_date) {
            const eDate = new Date(t.end_date);
            document.getElementById('t_end_date').value = eDate.toISOString().slice(0, 16);
        }
        
        // Select games checkboxes
        const checkboxes = document.querySelectorAll('.t-game-checkbox');
        const selectedGames = t.games || [];
        checkboxes.forEach(c => {
            c.checked = selectedGames.includes(c.value);
        });

        // Set banner preview if exists
        const previewContainer = document.getElementById('t_banner_preview_container');
        const previewImg = document.getElementById('t_banner_preview_img');
        if (t.banner_path) {
            previewImg.src = window.location.origin + '/' + t.banner_path;
            previewContainer.style.display = 'block';
        } else {
            previewImg.src = '#';
            previewContainer.style.display = 'none';
        }

        document.getElementById('t_is_active').checked = !!t.is_active;
        document.getElementById('tournamentModal').style.display = 'flex';
    }

    function closeTournamentModal() {
        document.getElementById('tournamentModal').style.display = 'none';
    }

    function openLeaderboardModal(tournamentId, title) {
        document.getElementById('leadTournamentTitle').innerText = title;
        const body = document.getElementById('leaderboardTableBody');
        body.innerHTML = `<tr><td colspan="3" style="text-align: center; color: var(--text-muted); padding: 20px;"><i class="fa-solid fa-circle-notch fa-spin"></i> Carregando...</td></tr>`;
        
        document.getElementById('leaderboardModal').style.display = 'flex';
        
        // Fetch active rankings from API
        fetch(`/tournaments/active`)
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const match = data.tournaments.find(item => item.id === tournamentId);
                    if (match && match.leaderboard && match.leaderboard.length > 0) {
                        let html = '';
                        match.leaderboard.forEach(p => {
                            let rankBadge = p.rank;
                            if (p.rank === 1) rankBadge = '🥇';
                            else if (p.rank === 2) rankBadge = '🥈';
                            else if (p.rank === 3) rankBadge = '🥉';
                            
                            html += `<tr>
                                <td style="font-weight: 700; font-size: 14px;">${rankBadge}</td>
                                <td>${p.name}</td>
                                <td style="color: var(--color-yellow); font-weight: 700;">${p.score}</td>
                            </tr>`;
                        });
                        body.innerHTML = html;
                    } else {
                        body.innerHTML = `<tr><td colspan="3" style="text-align: center; color: var(--text-muted); padding: 20px;">Nenhum jogador participando ainda.</td></tr>`;
                    }
                } else {
                    body.innerHTML = `<tr><td colspan="3" style="text-align: center; color: var(--red); padding: 20px;">Erro ao carregar dados.</td></tr>`;
                }
            })
            .catch(err => {
                console.error(err);
                body.innerHTML = `<tr><td colspan="3" style="text-align: center; color: var(--red); padding: 20px;">Erro de rede.</td></tr>`;
            });
    }

    function closeLeaderboardModal() {
        document.getElementById('leaderboardModal').style.display = 'none';
    }

    // Close modal when clicking outside content
    window.onclick = function(event) {
        const modal1 = document.getElementById('tournamentModal');
        const modal2 = document.getElementById('leaderboardModal');
        if (event.target == modal1) {
            closeTournamentModal();
        }
        if (event.target == modal2) {
            closeLeaderboardModal();
        }
    }

    function previewTournamentBanner(input) {
        const container = document.getElementById('t_banner_preview_container');
        const img = document.getElementById('t_banner_preview_img');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                img.src = e.target.result;
                container.style.display = 'block';
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            img.src = '#';
            container.style.display = 'none';
        }
    }
</script>
@endsection
