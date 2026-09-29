@extends('layouts.app')

@section('content')
<div class="missions-page-container" style="color: #fff; font-family: 'Outfit', sans-serif; padding: 20px 0;">
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
            <span style="color: var(--neon-green);">Missões</span>
        </div>
    </div>

    <!-- Title and Subtitle -->
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 32px; font-weight: 900; margin-bottom: 8px; font-family: 'Outfit', sans-serif;">Missões de Recompensa</h1>
        <p style="font-size: 14px; color: var(--text-secondary);">Cumpra os objetivos e resgate seus prêmios em dinheiro real instantaneamente!</p>
    </div>

    <!-- Missions Grid -->
    <div class="m-grid" id="missionsGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-bottom: 40px;">
        <!-- Loaded dynamically -->
        <div style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 60px 0;">
            <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 32px; color: var(--neon-green); margin-bottom: 15px;"></i>
            <p>Carregando missões...</p>
        </div>
    </div>
</div>

<style>
    /* Mission Card Premium Styling */
    .m-page-card {
        background: rgba(10, 35, 25, 0.4);
        border: 1px solid rgba(0, 230, 118, 0.15);
        border-radius: 16px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        gap: 15px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 20px rgba(0,0,0,0.3);
        position: relative;
        overflow: hidden;
    }
    .m-page-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, var(--neon-green), #00b0ff);
        opacity: 0.8;
    }
    .m-page-card:hover {
        transform: translateY(-5px);
        border-color: var(--neon-green);
        box-shadow: 0 8px 30px rgba(0, 230, 118, 0.15);
    }
    
    .m-card-header-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
    }
    .m-title-text {
        font-size: 16px;
        font-weight: 800;
        color: #fff;
        margin-bottom: 4px;
        line-height: 1.3;
    }
    .m-desc-text {
        font-size: 12px;
        color: var(--text-secondary);
        line-height: 1.4;
    }
    .m-reward-badge {
        font-size: 15px;
        font-weight: 900;
        color: var(--neon-green);
        background: rgba(0, 230, 118, 0.1);
        border: 1px solid rgba(0, 230, 118, 0.25);
        padding: 6px 12px;
        border-radius: 8px;
        white-space: nowrap;
        box-shadow: 0 0 10px rgba(0, 230, 118, 0.05);
    }

    /* Progress styling */
    .m-prog-container {
        margin-top: 10px;
    }
    .m-prog-bar-bg {
        width: 100%;
        height: 8px;
        background: rgba(255,255,255,0.05);
        border-radius: 9999px;
        overflow: hidden;
        margin-bottom: 8px;
        border: 1px solid rgba(255,255,255,0.02);
    }
    .m-prog-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--neon-green), #00b0ff);
        border-radius: 9999px;
        transition: width 0.4s ease;
        box-shadow: 0 0 8px rgba(0, 230, 118, 0.4);
    }
    .m-prog-text {
        font-size: 11.5px;
        font-weight: 700;
        color: var(--text-secondary);
    }

    /* Period pill */
    .m-period-pill {
        font-size: 10px;
        color: var(--text-muted);
        font-weight: 700;
        text-transform: uppercase;
        background: rgba(255,255,255,0.02);
        padding: 4px 8px;
        border-radius: 6px;
        border: 1px solid rgba(255,255,255,0.05);
    }

    /* Buttons styling */
    .m-page-claim-btn {
        background: var(--neon-green);
        border: none;
        color: #000;
        font-weight: 900;
        font-size: 12px;
        padding: 6px 16px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 0 12px rgba(0, 230, 118, 0.3);
        animation: claimPulse 2s infinite;
    }
    .m-page-claim-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 0 20px rgba(0, 230, 118, 0.5);
    }
    .m-page-claim-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        animation: none;
    }

    .m-page-claimed-badge {
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        padding: 5px 12px;
        border-radius: 6px;
        background: rgba(255,255,255,0.03);
        border: 1px solid rgba(255,255,255,0.05);
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    @keyframes claimPulse {
        0% { box-shadow: 0 0 10px rgba(0, 230, 118, 0.3); }
        50% { box-shadow: 0 0 20px rgba(0, 230, 118, 0.6); }
        100% { box-shadow: 0 0 10px rgba(0, 230, 118, 0.3); }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadPageMissions();
    });

    function loadPageMissions() {
        const container = document.getElementById('missionsGrid');
        
        @guest
            container.innerHTML = `
                <div style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 50px 0; max-width: 400px; margin: 0 auto;">
                    <i class="fa-solid fa-lock" style="font-size: 40px; margin-bottom: 20px; opacity: 0.3; color: var(--neon-green);"></i>
                    <h3 style="font-size: 18px; font-weight: 800; color: #fff; margin-bottom: 10px;">Acesso Restrito</h3>
                    <p style="font-size: 13.5px; margin-bottom: 20px; line-height: 1.5;">Você precisa fazer login para ver e completar missões de recompensa!</p>
                    <button type="button" class="btn-deposit-submit" style="display: inline-block; width: auto; padding: 12px 30px;" onclick="openModal('loginModal')">
                        <span aria-hidden="true" class="btn-skew-bg"></span>
                        <span class="btn-content">FAZER LOGIN</span>
                    </button>
                </div>
            `;
            return;
        @endguest

        fetch('/missions/active')
            .then(r => r.json())
            .then(data => {
                if (data.success && data.missions && data.missions.length > 0) {
                    let html = '';
                    data.missions.forEach(m => {
                        let pct = (m.current_value / m.target_value) * 100;
                        if (pct > 100) pct = 100;
                        pct = pct.toFixed(0);

                        let valText = '';
                        let targetText = '';
                        if (m.type === 'bet_count') {
                            valText = `${m.current_value.toFixed(0)}`;
                            targetText = `${m.target_value.toFixed(0)}`;
                        } else {
                            valText = `R$ ${m.current_value.toFixed(2)}`;
                            targetText = `R$ ${m.target_value.toFixed(2)}`;
                        }

                        let actionBlock = '';
                        if (m.reward_claimed) {
                            actionBlock = `<span class="m-page-claimed-badge"><i class="fa-solid fa-circle-check" style="color: var(--neon-green);"></i> Reivindicado</span>`;
                        } else if (m.is_completed) {
                            actionBlock = `<button type="button" class="m-page-claim-btn" onclick="claimPageReward(${m.id}, this)">Resgatar</button>`;
                        } else {
                            actionBlock = `<span style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Em Andamento</span>`;
                        }

                        html += `
                            <div class="m-page-card">
                                <div class="m-card-header-row">
                                    <div style="min-width: 0;">
                                        <h3 class="m-title-text">${m.title}</h3>
                                        <p class="m-desc-text">${m.description || ''}</p>
                                    </div>
                                    <div class="m-reward-badge">R$ ${m.reward_amount}</div>
                                </div>
                                <div class="m-prog-container">
                                    <div class="m-prog-bar-bg">
                                        <div class="m-prog-bar-fill" style="width: ${pct}%"></div>
                                    </div>
                                    <div class="m-prog-text">${valText} / ${targetText}</div>
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.03); padding-top: 12px; margin-top: 5px;">
                                    <span class="m-period-pill">
                                        ${m.period === 'daily' ? 'Diário' : (m.period === 'weekly' ? 'Semanal' : 'Único')}
                                    </span>
                                    ${actionBlock}
                                </div>
                            </div>
                        `;
                    });
                    container.innerHTML = html;
                } else {
                    container.innerHTML = `
                        <div style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 50px 0;">
                            <i class="fa-solid fa-crosshairs" style="font-size: 40px; margin-bottom: 15px; opacity: 0.3;"></i>
                            <p>Nenhuma missão ativa no momento.</p>
                        </div>
                    `;
                }
            })
            .catch(err => {
                console.error(err);
                container.innerHTML = `
                    <div style="grid-column: 1 / -1; text-align: center; color: var(--red); padding: 40px 0;">
                        <p>Erro ao carregar as missões. Por favor, tente novamente.</p>
                    </div>
                `;
            });
    }

    function claimPageReward(missionId, button) {
        button.disabled = true;
        button.innerText = 'Processando...';

        fetch('/missions/claim', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ mission_id: missionId })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                if (typeof refreshUserBalance === 'function') {
                    refreshUserBalance();
                }
                loadPageMissions();
            } else {
                alert(data.message || 'Erro ao resgatar prêmio.');
                button.disabled = false;
                button.innerText = 'Resgatar';
            }
        })
        .catch(err => {
            console.error(err);
            alert('Erro de conexão ao resgatar recompensa.');
            button.disabled = false;
            button.innerText = 'Resgatar';
        });
    }
</script>
@endsection
