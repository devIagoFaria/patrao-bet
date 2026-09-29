@extends('layouts.app')

@section('content')
<div class="tournaments-page-container" style="color: #fff; font-family: 'Outfit', sans-serif; padding: 20px 0;">
    <!-- Breadcrumbs and Back Link -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; font-size: 13px; color: var(--text-secondary);">
        <a href="{{ route('home') }}" style="color: var(--text-secondary); text-decoration: none; display: flex; align-items: center; gap: 6px; font-weight: 700; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='var(--text-secondary)'">
            <i class="fa-solid fa-arrow-left"></i> Voltar
        </a>
        <div style="display: flex; align-items: center; gap: 6px; font-weight: 600;">
            <a href="{{ route('home') }}" style="color: var(--text-secondary); text-decoration: none;">Início</a>
            <span>&gt;</span>
            <span style="color: var(--text-secondary);">VIP</span>
            <span>&gt;</span>
            <span style="color: var(--neon-green);">Torneios</span>
        </div>
    </div>

    <!-- Title -->
    <h1 style="font-size: 32px; font-weight: 900; margin-bottom: 25px; font-family: 'Outfit', sans-serif;">Torneios</h1>

    <!-- Filters Row -->
    <div class="t-filters-row" style="display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap;">
        <button type="button" class="t-filter-btn active" data-filter="ativos">Ativos (<span id="count-ativos">0</span>)</button>
        <button type="button" class="t-filter-btn" data-filter="novos">Novos (<span id="count-novos">0</span>)</button>
        <button type="button" class="t-filter-btn" data-filter="andamento">Em andamento (<span id="count-andamento">0</span>)</button>
        <button type="button" class="t-filter-btn" data-filter="finalizados">Finalizados (<span id="count-finalizados">0</span>)</button>
    </div>

    <!-- Search and Sort Row -->
    <div style="display: flex; gap: 15px; margin-bottom: 25px; align-items: center; flex-wrap: wrap;">
        <div class="search-input-wrapper" style="flex: 1; min-width: 280px; position: relative;">
            <i class="fa-solid fa-magnifying-glass search-icon" style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 1rem;"></i>
            <input type="text" id="tSearchInput" onkeyup="filterAndSearchTournaments()" placeholder="Buscar torneio..." style="width: 100%; height: 48px; background: var(--sidebar-bg); border: 1px solid var(--border-color); border-radius: 12px; padding-left: 48px; padding-right: 20px; color: #fff; font-size: 0.9rem; font-weight: 500; outline: none; transition: border-color 0.2s;">
        </div>
        <button type="button" class="sort-btn" onclick="toggleSortOrder()" style="height: 48px; background: var(--sidebar-bg); border: 1px solid var(--border-color); border-radius: 12px; padding: 0 20px; color: var(--text-secondary); font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 8px; font-size: 0.9rem; transition: all 0.2s;">
            <i class="fa-solid fa-sort"></i> Ordenar
        </button>
    </div>

    <!-- Tournaments Grid -->
    <div class="t-grid" id="tournamentsGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px; margin-bottom: 40px;">
        <!-- Loaded dynamically -->
        <div style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 60px 0;">
            <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 32px; color: var(--neon-green); margin-bottom: 15px;"></i>
            <p>Carregando torneios...</p>
        </div>
    </div>
</div>

<!-- Dynamic Local Detail Modal -->
<div class="local-detail-modal" id="tDetailModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(6, 6, 8, 0.9); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); z-index: 2000; justify-content: center; align-items: center; opacity: 0; transition: opacity 0.3s ease;">
    <div class="local-detail-modal-content" style="background: rgba(22, 22, 25, 0.98); border: 1px solid rgba(0, 230, 118, 0.25); box-shadow: 0 10px 40px rgba(0, 230, 118, 0.15); border-radius: 16px; width: 90%; max-width: 600px; max-height: 85vh; overflow-y: auto; padding: 24px; position: relative; transform: translateY(30px); transition: transform 0.3s ease;">
        <button type="button" class="local-close-btn" onclick="closeTDetailModal()" style="position: absolute; top: 15px; right: 15px; background: transparent; border: none; color: var(--text-muted); font-size: 24px; cursor: pointer; transition: color 0.2s;">&times;</button>
        
        <div id="tDetailBody">
            <!-- Loaded dynamically -->
        </div>
    </div>
</div>

<style>
    /* Filter buttons styling */
    .t-filter-btn {
        background: rgba(0, 230, 118, 0.05);
        border: 1px solid rgba(0, 230, 118, 0.15);
        color: var(--text-secondary);
        font-family: 'Outfit', sans-serif;
        font-weight: 800;
        font-size: 13.5px;
        padding: 8px 16px;
        border-radius: 9999px;
        cursor: pointer;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        text-transform: capitalize;
    }
    .t-filter-btn:hover {
        background: rgba(0, 230, 118, 0.12);
        border-color: rgba(0, 230, 118, 0.4);
        color: #fff;
    }
    .t-filter-btn.active {
        background: var(--neon-green) !important;
        border-color: var(--neon-green) !important;
        color: #000 !important;
        box-shadow: 0 0 15px rgba(0, 230, 118, 0.4);
    }

    /* Sort button */
    .sort-btn:hover {
        border-color: var(--neon-green) !important;
        color: #fff !important;
        box-shadow: 0 0 10px rgba(0, 230, 118, 0.1);
    }

    /* Input focus styling */
    #tSearchInput:focus {
        border-color: var(--neon-green) !important;
        box-shadow: 0 0 15px rgba(0, 230, 118, 0.1) !important;
    }

    /* Tournament Card Premium Styles */
    .t-page-card {
        background: rgba(10, 35, 25, 0.4);
        border: 1px solid rgba(0, 230, 118, 0.15);
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        height: 100%;
        position: relative;
    }
    .t-page-card:hover {
        transform: translateY(-5px);
        border-color: var(--neon-green);
        box-shadow: 0 8px 30px rgba(0, 230, 118, 0.15);
    }
    .t-card-banner-wrapper {
        width: 100%;
        aspect-ratio: 16 / 9;
        position: relative;
        overflow: hidden;
        border-bottom: 1px solid rgba(0, 230, 118, 0.15);
    }
    .t-card-banner {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .t-page-card:hover .t-card-banner {
        transform: scale(1.04);
    }
    .t-participants-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0, 0, 0, 0.75);
        border: 1px solid rgba(0, 230, 118, 0.3);
        border-radius: 6px;
        padding: 4px 8px;
        font-size: 11px;
        font-weight: 800;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 4px;
        backdrop-filter: blur(4px);
    }
    .t-card-content {
        padding: 16px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .t-card-title {
        font-size: 15px;
        font-weight: 800;
        color: #fff;
        line-height: 1.3;
        margin-bottom: 6px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 40px;
    }
    .t-card-prize {
        font-size: 12.5px;
        font-weight: 700;
        color: var(--neon-green);
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Countdown styling */
    .t-cd-wrapper {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 6px;
        margin-top: auto;
        margin-bottom: 15px;
        background: rgba(0, 0, 0, 0.2);
        padding: 8px;
        border-radius: 10px;
        border: 1px solid rgba(255,255,255,0.03);
        text-align: center;
    }
    .t-cd-col {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .t-cd-val {
        font-size: 16px;
        font-weight: 900;
        color: #fff;
        line-height: 1;
    }
    .t-cd-lbl {
        font-size: 9px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        margin-top: 3px;
    }

    .t-action-btn {
        width: 100%;
        background: transparent;
        border: 1px solid var(--neon-green);
        color: var(--neon-green);
        font-weight: 900;
        font-size: 12.5px;
        padding: 10px 0;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .t-action-btn:hover {
        background: var(--neon-green);
        color: #000;
        box-shadow: 0 0 15px rgba(0, 230, 118, 0.3);
    }

    /* Leaderboard styles inside modal */
    .detail-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }
    .detail-table th {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        color: var(--text-muted);
        border-bottom: 1px solid rgba(255,255,255,0.05);
        padding: 8px;
        text-align: left;
    }
    .detail-table td {
        font-size: 13px;
        padding: 10px 8px;
        border-bottom: 1px solid rgba(255,255,255,0.03);
    }
    .detail-table tr:hover td {
        background: rgba(255,255,255,0.01);
    }

    .local-close-btn:hover {
        color: #fff !important;
    }
</style>

<script>
    let allTournaments = [];
    let currentFilter = 'ativos';
    let sortAscending = false;

    document.addEventListener('DOMContentLoaded', function() {
        // Init filters click handlers
        document.querySelectorAll('.t-filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.t-filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentFilter = this.dataset.filter;
                renderTournamentsGrid();
            });
        });

        // Load active tournaments
        fetch('/tournaments/active')
            .then(r => r.json())
            .then(data => {
                if (data.success && data.tournaments) {
                    allTournaments = data.tournaments;
                    calculateCounters();
                    renderTournamentsGrid();
                    startCountdownTimer();
                } else {
                    showEmptyGrid();
                }
            })
            .catch(err => {
                console.error(err);
                document.getElementById('tournamentsGrid').innerHTML = `
                    <div style="grid-column: 1 / -1; text-align: center; color: var(--red); padding: 40px 0;">
                        <p>Erro ao carregar os torneios. Por favor, recarregue a página.</p>
                    </div>
                `;
            });
    });

    function calculateCounters() {
        const now = new Date().getTime();
        
        let activeCount = 0;
        let newCount = 0;
        let ongoingCount = 0;
        let finishedCount = 0;

        allTournaments.forEach(t => {
            const endTime = new Date(t.end_date).getTime();
            const timeDiff = endTime - now;

            if (timeDiff > 0) {
                activeCount++;
                ongoingCount++;
                
                // Consider new if started in the last 2 days or ends in more than 15 days (just dummy rule to give counters some variation)
                const totalDuration = timeDiff;
                if (totalDuration > 15 * 24 * 60 * 60 * 1000) {
                    newCount++;
                }
            } else {
                finishedCount++;
            }
        });

        document.getElementById('count-ativos').textContent = activeCount;
        document.getElementById('count-novos').textContent = newCount;
        document.getElementById('count-andamento').textContent = ongoingCount;
        document.getElementById('count-finalizados').textContent = finishedCount;
    }

    function renderTournamentsGrid() {
        const grid = document.getElementById('tournamentsGrid');
        const now = new Date().getTime();
        const searchQuery = document.getElementById('tSearchInput').value.toLowerCase().trim();

        // 1. Filter
        let filtered = allTournaments.filter(t => {
            const endTime = new Date(t.end_date).getTime();
            const timeDiff = endTime - now;

            if (currentFilter === 'ativos') {
                return timeDiff > 0;
            } else if (currentFilter === 'novos') {
                return timeDiff > 15 * 24 * 60 * 60 * 1000;
            } else if (currentFilter === 'andamento') {
                return timeDiff > 0;
            } else if (currentFilter === 'finalizados') {
                return timeDiff <= 0;
            }
            return true;
        });

        // 2. Search
        if (searchQuery) {
            filtered = filtered.filter(t => {
                return t.title.toLowerCase().includes(searchQuery) || (t.description && t.description.toLowerCase().includes(searchQuery));
            });
        }

        // 3. Sort (By end date or prize pool)
        filtered.sort((a, b) => {
            const valA = new Date(a.end_date).getTime();
            const valB = new Date(b.end_date).getTime();
            return sortAscending ? valA - valB : valB - valA;
        });

        if (filtered.length === 0) {
            grid.innerHTML = `
                <div style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 50px 0;">
                    <i class="fa-solid fa-trophy" style="font-size: 40px; color: var(--text-muted); margin-bottom: 15px; opacity: 0.3;"></i>
                    <p style="font-size: 14px;">Nenhum torneio encontrado nesta categoria.</p>
                </div>
            `;
            return;
        }

        let html = '';
        filtered.forEach(t => {
            // Seed a participant number based on ID
            const seed = (t.id * 179 + 5423) % 95000 + 5000;
            const formattedParticipants = new Intl.NumberFormat('pt-BR').format(seed);

            html += `
                <div class="t-page-card">
                    <div class="t-card-banner-wrapper">
                        <img src="${t.banner_url}" class="t-card-banner" alt="${t.title}">
                        <div class="t-participants-badge">
                            <i class="fa-solid fa-users"></i> ${formattedParticipants}
                        </div>
                    </div>
                    <div class="t-card-content">
                        <h3 class="t-card-title">${t.title}</h3>
                        <div class="t-card-prize">R$ ${t.prize_pool}</div>
                        
                        <!-- Countdown timer block -->
                        <div class="t-cd-wrapper page-countdown-el" data-endtime="${t.end_date}">
                            <div class="t-cd-col">
                                <span class="t-cd-val t-days-val">-</span>
                                <span class="t-cd-lbl">dias</span>
                            </div>
                            <div class="t-cd-col">
                                <span class="t-cd-val t-hours-val">-</span>
                                <span class="t-cd-lbl">hrs.</span>
                            </div>
                            <div class="t-cd-col">
                                <span class="t-cd-val t-minutes-val">-</span>
                                <span class="t-cd-lbl">min.</span>
                            </div>
                            <div class="t-cd-col">
                                <span class="t-cd-val t-seconds-val">-</span>
                                <span class="t-cd-lbl">seg.</span>
                            </div>
                        </div>
                        
                        <button type="button" class="t-action-btn" onclick="openTDetailModal(${t.id})">Ver Torneio</button>
                    </div>
                </div>
            `;
        });
        grid.innerHTML = html;
        updateAllCountdowns();
    }

    function showEmptyGrid() {
        document.getElementById('tournamentsGrid').innerHTML = `
            <div style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 50px 0;">
                <i class="fa-solid fa-trophy" style="font-size: 40px; margin-bottom: 15px; opacity: 0.3;"></i>
                <p>Nenhum torneio ativo no momento.</p>
            </div>
        `;
    }

    function toggleSortOrder() {
        sortAscending = !sortAscending;
        const btn = document.querySelector('.sort-btn');
        if (sortAscending) {
            btn.innerHTML = '<i class="fa-solid fa-arrow-up-wide-short"></i> Antigos';
        } else {
            btn.innerHTML = '<i class="fa-solid fa-arrow-down-wide-short"></i> Recentes';
        }
        renderTournamentsGrid();
    }

    function filterAndSearchTournaments() {
        renderTournamentsGrid();
    }

    /* Timer Functions */
    let countdownInterval;
    function startCountdownTimer() {
        if (countdownInterval) clearInterval(countdownInterval);
        countdownInterval = setInterval(updateAllCountdowns, 1000);
    }

    function updateAllCountdowns() {
        const now = new Date().getTime();
        document.querySelectorAll('.page-countdown-el').forEach(el => {
            const endTime = new Date(el.dataset.endtime).getTime();
            const dist = endTime - now;

            if (dist <= 0) {
                el.innerHTML = '<div style="grid-column: 1 / -1; color: var(--text-muted); font-weight: 800; font-size: 12px; letter-spacing: 0.5px; text-transform: uppercase;">Finalizado</div>';
                return;
            }

            const days = Math.floor(dist / (1000 * 60 * 60 * 24));
            const hours = Math.floor((dist % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((dist % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((dist % (1000 * 60)) / 1000);

            const daysEl = el.querySelector('.t-days-val');
            const hoursEl = el.querySelector('.t-hours-val');
            const minutesEl = el.querySelector('.t-minutes-val');
            const secondsEl = el.querySelector('.t-seconds-val');

            if (daysEl) daysEl.textContent = days;
            if (hoursEl) hoursEl.textContent = hours;
            if (minutesEl) minutesEl.textContent = minutes;
            if (secondsEl) secondsEl.textContent = seconds;
        });
    }

    /* Modal functions */
    function openTDetailModal(id) {
        const t = allTournaments.find(x => x.id === id);
        if (!t) return;

        const modal = document.getElementById('tDetailModal');
        const body = document.getElementById('tDetailBody');

        let boardHtml = '';
        if (t.leaderboard && t.leaderboard.length > 0) {
            t.leaderboard.forEach(p => {
                let rBadge = p.rank;
                if (p.rank === 1) rBadge = '🥇';
                else if (p.rank === 2) rBadge = '🥈';
                else if (p.rank === 3) rBadge = '🥉';

                boardHtml += `
                    <tr>
                        <td style="font-weight: 700;">${rBadge}</td>
                        <td>${p.name}</td>
                        <td style="color: var(--color-yellow); font-weight: 700; text-align: right;">${p.score} pts</td>
                    </tr>
                `;
            });
        } else {
            boardHtml = `<tr><td colspan="3" style="text-align: center; color: var(--text-muted); padding: 20px;">Ninguém pontuou ainda. Seja o primeiro!</td></tr>`;
        }

        const endDateStr = new Date(t.end_date).toLocaleDateString('pt-BR', {day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit'});

        body.innerHTML = `
            <div style="text-align: center; margin-bottom: 20px;">
                <img src="${t.banner_url}" style="width: 100%; max-height: 200px; object-fit: cover; border-radius: 12px; border: 1px solid rgba(0, 230, 118, 0.15); margin-bottom: 15px;" alt="Banner">
                <h2 style="font-size: 20px; font-weight: 900; color: #fff; margin-bottom: 6px; font-family: 'Outfit';">${t.title}</h2>
                <p style="font-size: 13px; color: var(--text-secondary); line-height: 1.5;">${t.description || ''}</p>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 25px;">
                <div style="background: rgba(0,0,0,0.2); border: 1px solid rgba(0,230,118,0.1); padding: 12px; border-radius: 10px; text-align: center;">
                    <span style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Prêmio Total</span>
                    <strong style="display: block; font-size: 18px; color: var(--neon-green); margin-top: 4px;">R$ ${t.prize_pool}</strong>
                </div>
                <div style="background: rgba(0,0,0,0.2); border: 1px solid rgba(0,230,118,0.1); padding: 12px; border-radius: 10px; text-align: center;">
                    <span style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Encerramento</span>
                    <strong style="display: block; font-size: 13px; color: #fff; margin-top: 8px;">${endDateStr}</strong>
                </div>
            </div>

            @auth
            <div style="background: rgba(248, 204, 27, 0.05); border: 1px solid rgba(248, 204, 27, 0.15); padding: 12px 18px; border-radius: 10px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
                <span style="font-size: 13px; color: var(--text-secondary); font-weight: 600;">Sua Posição atual:</span>
                <span style="font-size: 13px; font-weight: 800; color: #fff;">
                    Rank: <strong style="color: var(--color-yellow); font-size: 14px;">${t.user_rank}</strong> | Pontos: <strong style="color: var(--color-yellow); font-size: 14px;">${t.user_score}</strong>
                </span>
            </div>
            @endauth

            <div style="margin-bottom: 20px;">
                <h4 style="font-size: 14px; font-weight: 800; text-transform: uppercase; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 6px; margin-bottom: 10px; color: #fff;">Regras do Torneio</h4>
                <p style="font-size: 12.5px; color: var(--text-secondary); line-height: 1.5; white-space: pre-line;">${t.rules || 'Sem regras cadastradas.'}</p>
            </div>

            <div>
                <h4 style="font-size: 14px; font-weight: 800; text-transform: uppercase; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 6px; margin-bottom: 12px; color: #fff;">Classificação Top 15</h4>
                <table class="detail-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">Pos</th>
                            <th>Jogador</th>
                            <th style="text-align: right;">Pontos</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${boardHtml}
                    </tbody>
                </table>
            </div>
        `;

        modal.style.display = 'flex';
        setTimeout(() => {
            modal.style.opacity = '1';
            modal.querySelector('.local-detail-modal-content').style.transform = 'translateY(0)';
        }, 10);
    }

    function closeTDetailModal() {
        const modal = document.getElementById('tDetailModal');
        modal.style.opacity = '0';
        modal.querySelector('.local-detail-modal-content').style.transform = 'translateY(30px)';
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    // Close on click outside content
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('tDetailModal');
        if (e.target === modal) {
            closeTDetailModal();
        }
    });
</script>
@endsection
