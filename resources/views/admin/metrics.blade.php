@extends('layouts.admin')

@section('page_title', 'Métricas Analíticas')

@section('content')
<!-- Row 1: Cassino / Apostas KPIs -->
<h3 style="font-family: var(--font-heading); color: var(--text-primary); font-size: 16px; font-weight: 700; margin-bottom: 16px; margin-top: 8px; display: flex; align-items: center; gap: 8px;">
    <i class="fa-solid fa-gamepad" style="color: var(--color-yellow);"></i> Métricas de Apostas (Cassino)
</h3>
<div class="admin-grid-kpis" style="margin-bottom: 24px;">
    <!-- GGR Card -->
    <div class="admin-kpi-card" style="background: linear-gradient(135deg, #f8cc1b 0%, #d4ae0c 100%); border: none; color: #000; display: flex; flex-direction: column; justify-content: space-between; padding: 24px; border-radius: 16px; min-height: 150px; position: relative; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(248, 204, 27, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <span class="admin-kpi-lbl" style="font-size: 10px; font-weight: 800; color: rgba(0,0,0,0.6); letter-spacing: 0.5px; text-transform: uppercase;">GGR (Gross Gaming Revenue)</span>
            <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(0, 0, 0, 0.08); display: flex; align-items: center; justify-content: center; font-size: 14px; color: rgba(0,0,0,0.7);">
                <i class="fa-solid fa-calculator"></i>
            </div>
        </div>
        <div class="admin-kpi-val" style="font-family: var(--font-heading); font-size: 26px; font-weight: 900; margin-top: 10px;">
            R$ {{ number_format($ggr, 2, ',', '.') }}
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto;">
            <span class="admin-kpi-sub" style="font-size: 11px; color: rgba(0,0,0,0.5); font-weight: 600;">Volume Apostado - Prêmios</span>
            <span class="admin-kpi-badge" style="background: rgba(0, 0, 0, 0.08); color: #000; border: 1px solid rgba(0,0,0,0.1); font-size: 10px; font-weight: 700; padding: 3px 6px; border-radius: 4px;">Lucro da Casa</span>
        </div>
    </div>

    <!-- RTP Card -->
    <div class="admin-kpi-card" style="background: #121215; border: 1px solid var(--border-color); display: flex; flex-direction: column; justify-content: space-between; padding: 24px; border-radius: 16px; min-height: 150px; position: relative; overflow: hidden;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <span class="admin-kpi-lbl" style="font-size: 10px; font-weight: 800; color: var(--text-secondary); letter-spacing: 0.5px; text-transform: uppercase;">RTP Real (Return to Player)</span>
            <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(59, 130, 246, 0.06); border: 1px solid rgba(59, 130, 246, 0.12); display: flex; align-items: center; justify-content: center; font-size: 14px; color: var(--color-blue);">
                <i class="fa-solid fa-arrows-spin"></i>
            </div>
        </div>
        <div class="admin-kpi-val" style="font-family: var(--font-heading); font-size: 26px; font-weight: 900; margin-top: 10px; color: {{ $rtp > 97 ? 'var(--color-red)' : 'var(--color-green)' }}">
            {{ number_format($rtp, 1, ',', '.') }}%
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; z-index: 5; position: relative;">
            <span class="admin-kpi-sub" style="font-size: 11px; color: var(--text-muted); font-weight: 500;">Média recomendada: 92% a 97%</span>
            <span class="admin-kpi-badge" style="font-size: 10px; font-weight: 700; padding: 3px 6px; border-radius: 4px; background-color: {{ $rtp > 97 ? 'rgba(239, 68, 68, 0.06)' : 'rgba(16, 185, 129, 0.06)' }}; color: {{ $rtp > 97 ? 'var(--color-red)' : 'var(--color-green)' }}; border: 1px solid {{ $rtp > 97 ? 'rgba(239, 68, 68, 0.12)' : 'rgba(16, 185, 129, 0.12)' }};">
                {{ $rtp > 97 ? 'Prejuízo / Alto' : 'Saudável' }}
            </span>
        </div>
    </div>

    <!-- Total Apostado Card -->
    <div class="admin-kpi-card" style="background: #121215; border: 1px solid var(--border-color); display: flex; flex-direction: column; justify-content: space-between; padding: 24px; border-radius: 16px; min-height: 150px; position: relative; overflow: hidden;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <span class="admin-kpi-lbl" style="font-size: 10px; font-weight: 800; color: var(--text-secondary); letter-spacing: 0.5px; text-transform: uppercase;">Volume Apostado</span>
            <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(16, 185, 129, 0.06); border: 1px solid rgba(16, 185, 129, 0.12); display: flex; align-items: center; justify-content: center; font-size: 14px; color: var(--color-green);">
                <i class="fa-solid fa-coins"></i>
            </div>
        </div>
        <div class="admin-kpi-val" style="font-family: var(--font-heading); font-size: 26px; font-weight: 900; margin-top: 10px;">
            R$ {{ number_format($totalBetAmount, 2, ',', '.') }}
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; z-index: 5; position: relative;">
            <span class="admin-kpi-sub" style="font-size: 11px; color: var(--text-muted); font-weight: 500;">{{ number_format($totalBetsCount, 0, ',', '.') }} apostas registradas</span>
        </div>
    </div>

    <!-- Premios Pagos Card -->
    <div class="admin-kpi-card" style="background: #121215; border: 1px solid var(--border-color); display: flex; flex-direction: column; justify-content: space-between; padding: 24px; border-radius: 16px; min-height: 150px; position: relative; overflow: hidden;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <span class="admin-kpi-lbl" style="font-size: 10px; font-weight: 800; color: var(--text-secondary); letter-spacing: 0.5px; text-transform: uppercase;">Prêmios Pagos</span>
            <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(168, 85, 247, 0.06); border: 1px solid rgba(168, 85, 247, 0.12); display: flex; align-items: center; justify-content: center; font-size: 14px; color: #a855f7;">
                <i class="fa-solid fa-gift"></i>
            </div>
        </div>
        <div class="admin-kpi-val" style="font-family: var(--font-heading); font-size: 26px; font-weight: 900; margin-top: 10px;">
            R$ {{ number_format($totalPayout, 2, ',', '.') }}
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; z-index: 5; position: relative;">
            <span class="admin-kpi-sub" style="font-size: 11px; color: var(--text-muted); font-weight: 500;">Retorno total para os usuários</span>
        </div>
    </div>
</div>

<!-- Row 2: KPIs Financeiros -->
<h3 style="font-family: var(--font-heading); color: var(--text-primary); font-size: 16px; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
    <i class="fa-solid fa-sack-dollar" style="color: var(--color-yellow);"></i> Métricas Financeiras (Depósitos e Saques)
</h3>
<div class="admin-grid-kpis-sec" style="margin-bottom: 24px; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
    <!-- Depósitos Aprovados -->
    <div class="admin-kpi-card-sec" style="background: #121215; border: 1px solid var(--border-color); border-left: 3px solid var(--color-green); padding: 18px; border-radius: 12px; display: flex; flex-direction: column; justify-content: space-between;">
        <span class="admin-kpisec-lbl" style="font-size: 9.5px; font-weight: 700; color: var(--text-muted); letter-spacing: 0.5px; text-transform: uppercase;">Depósitos Aprovados</span>
        <div class="admin-kpisec-val" style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; margin-top: 6px; margin-bottom: 4px; color: var(--color-green);">
            R$ {{ number_format($totalDepositsSum, 2, ',', '.') }}
        </div>
        <span class="admin-kpisec-sub" style="font-size: 10.5px; color: var(--text-muted);">{{ $totalDepositsCount }} transações aprovadas</span>
    </div>

    <!-- Saques Aprovados -->
    <div class="admin-kpi-card-sec" style="background: #121215; border: 1px solid var(--border-color); border-left: 3px solid var(--color-red); padding: 18px; border-radius: 12px; display: flex; flex-direction: column; justify-content: space-between;">
        <span class="admin-kpisec-lbl" style="font-size: 9.5px; font-weight: 700; color: var(--text-muted); letter-spacing: 0.5px; text-transform: uppercase;">Saques Aprovados</span>
        <div class="admin-kpisec-val" style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; margin-top: 6px; margin-bottom: 4px; color: var(--color-red);">
            R$ {{ number_format($totalWithdrawalsSum, 2, ',', '.') }}
        </div>
        <span class="admin-kpisec-sub" style="font-size: 10.5px; color: var(--text-muted);">{{ $totalWithdrawalsCount }} transações pagas</span>
    </div>

    <!-- Resultado Líquido Financeiro -->
    <div class="admin-kpi-card-sec" style="background: #121215; border: 1px solid var(--border-color); border-left: 3px solid var(--color-yellow); padding: 18px; border-radius: 12px; display: flex; flex-direction: column; justify-content: space-between;">
        <span class="admin-kpisec-lbl" style="font-size: 9.5px; font-weight: 700; color: var(--text-muted); letter-spacing: 0.5px; text-transform: uppercase;">Resultado Líquido Financeiro</span>
        <div class="admin-kpisec-val" style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; margin-top: 6px; margin-bottom: 4px; color: #fff;">
            R$ {{ number_format($netFinancialProfit, 2, ',', '.') }}
        </div>
        <span class="admin-kpisec-sub" style="font-size: 10.5px; color: var(--text-muted);">Entradas - Saídas reais de caixa</span>
    </div>
</div>

<!-- Row 2.5: Médias e Destaques -->
<h3 style="font-family: var(--font-heading); color: var(--text-primary); font-size: 16px; font-weight: 700; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
    <i class="fa-solid fa-chart-simple" style="color: var(--color-yellow);"></i> Médias e Destaques do Período
</h3>
<div class="admin-grid-kpis-sec" style="margin-bottom: 24px; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));">
    <!-- Ticket Médio Depósito -->
    <div class="admin-kpi-card-sec" style="background: #121215; border: 1px solid var(--border-color); border-left: 3px solid var(--color-blue); padding: 18px; border-radius: 12px; display: flex; flex-direction: column; justify-content: space-between;">
        <span class="admin-kpisec-lbl" style="font-size: 9.5px; font-weight: 700; color: var(--text-muted); letter-spacing: 0.5px; text-transform: uppercase;">Ticket Médio (Depósitos)</span>
        <div class="admin-kpisec-val" style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; margin-top: 6px; margin-bottom: 4px; color: var(--color-blue);">
            R$ {{ number_format($avgDepositTicket, 2, ',', '.') }}
        </div>
        <span class="admin-kpisec-sub" style="font-size: 10.5px; color: var(--text-muted);">Média por depósito aprovado</span>
    </div>

    <!-- Ticket Médio Aposta -->
    <div class="admin-kpi-card-sec" style="background: #121215; border: 1px solid var(--border-color); border-left: 3px solid #a855f7; padding: 18px; border-radius: 12px; display: flex; flex-direction: column; justify-content: space-between;">
        <span class="admin-kpisec-lbl" style="font-size: 9.5px; font-weight: 700; color: var(--text-muted); letter-spacing: 0.5px; text-transform: uppercase;">Ticket Médio (Apostas)</span>
        <div class="admin-kpisec-val" style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; margin-top: 6px; margin-bottom: 4px; color: #a855f7;">
            R$ {{ number_format($avgBetTicket, 2, ',', '.') }}
        </div>
        <span class="admin-kpisec-sub" style="font-size: 10.5px; color: var(--text-muted);">Média por aposta de cassino</span>
    </div>

    <!-- Jogo Mais Jogado -->
    <div class="admin-kpi-card-sec" style="background: #121215; border: 1px solid var(--border-color); border-left: 3px solid var(--color-yellow); padding: 18px; border-radius: 12px; display: flex; flex-direction: column; justify-content: space-between;">
        <span class="admin-kpisec-lbl" style="font-size: 9.5px; font-weight: 700; color: var(--text-muted); letter-spacing: 0.5px; text-transform: uppercase;">Jogo Mais Popular</span>
        <div class="admin-kpisec-val" style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; margin-top: 6px; margin-bottom: 4px; color: #fff;">
            {{ ucfirst($popularGame) }}
        </div>
        <span class="admin-kpisec-sub" style="font-size: 10.5px; color: var(--text-muted);">{{ number_format($popularGameCount, 0, ',', '.') }} rodadas jogadas</span>
    </div>
</div>

<!-- Row 3: Gráficos de Evolução -->
<div class="admin-row-charts" style="margin-bottom: 24px;">
    <!-- Deposits vs Withdrawals Chart -->
    <div class="admin-card-chart">
        <div class="admin-card-chart-header">
            <div>
                <span class="admin-chart-title" style="display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-right-left" style="color: var(--color-blue);"></i> FLUXO FINANCEIRO
                </span>
                <p class="admin-chart-subtitle">Depósitos vs Saques diários aprovados</p>
            </div>
        </div>
        <div class="admin-chart-body">
            <canvas id="financialFlowChart" style="width: 100%; height: 240px;"></canvas>
        </div>
    </div>

    <!-- GGR Evolution Chart -->
    <div class="admin-card-chart">
        <div class="admin-card-chart-header">
            <div>
                <span class="admin-chart-title" style="display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-chart-line" style="color: var(--color-green);"></i> EVOLUÇÃO DO GGR
                </span>
                <p class="admin-chart-subtitle">Lucro bruto diário de jogos de cassino</p>
            </div>
        </div>
        <div class="admin-chart-body">
            <canvas id="ggrChart" style="width: 100%; height: 240px;"></canvas>
        </div>
    </div>
</div>

<!-- Row 4: Tabelas de Rankings -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap: 24px; margin-bottom: 24px;">
    <!-- Top Played Games Table -->
    <div class="admin-card-table" style="margin-top: 0; padding: 20px;">
        <div class="admin-table-filters" style="margin-bottom: 16px; border: none; padding: 0;">
            <div class="admin-filter-left">
                <span style="font-family: var(--font-heading); font-weight: 700; font-size: 16px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-trophy" style="color: var(--color-yellow);"></i> Desempenho por Jogo
                </span>
            </div>
        </div>
        <div class="admin-table-wrapper" style="max-height: 450px; overflow-y: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Jogo</th>
                        <th style="text-align: center;">Apostas</th>
                        <th style="text-align: right;">Total Apostado</th>
                        <th style="text-align: right;">Total Pago</th>
                        <th style="text-align: right;">GGR (Lucro)</th>
                        <th style="text-align: center;">RTP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gameStats as $game)
                        @php
                            $gRtp = $game->total_bet_amount > 0 ? ($game->total_payout / $game->total_bet_amount) * 100 : 0;
                        @endphp
                        <tr>
                            <td style="font-weight: 600; color: #fff;">{{ ucfirst($game->game) }}</td>
                            <td style="text-align: center; color: var(--text-secondary);">{{ number_format($game->total_bets, 0, ',', '.') }}</td>
                            <td style="text-align: right; color: var(--text-secondary);">R$ {{ number_format($game->total_bet_amount, 2, ',', '.') }}</td>
                            <td style="text-align: right; color: var(--text-secondary);">R$ {{ number_format($game->total_payout, 2, ',', '.') }}</td>
                            <td style="text-align: right; font-weight: 700; color: {{ $game->ggr >= 0 ? 'var(--color-green)' : 'var(--color-red)' }}">
                                R$ {{ number_format($game->ggr, 2, ',', '.') }}
                            </td>
                            <td style="text-align: center;">
                                <span class="admin-badge" style="background-color: {{ $gRtp > 97 ? 'rgba(239, 68, 68, 0.1)' : 'rgba(16, 185, 129, 0.1)' }}; color: {{ $gRtp > 97 ? 'var(--color-red)' : 'var(--color-green)' }}; border: 1px solid {{ $gRtp > 97 ? 'rgba(239, 68, 68, 0.2)' : 'rgba(16, 185, 129, 0.2)' }}; font-size: 11px;">
                                    {{ number_format($gRtp, 1, ',', '.') }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 40px 0;">
                                Nenhuma aposta registrada no período.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Top Players (High Rollers) Table -->
    <div class="admin-card-table" style="margin-top: 0; padding: 20px;">
        <div class="admin-table-filters" style="margin-bottom: 16px; border: none; padding: 0;">
            <div class="admin-filter-left">
                <span style="font-family: var(--font-heading); font-weight: 700; font-size: 16px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-crown" style="color: var(--color-yellow);"></i> Maiores Apostadores (High Rollers)
                </span>
            </div>
        </div>
        <div class="admin-table-wrapper" style="max-height: 450px; overflow-y: auto;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Jogador</th>
                        <th style="text-align: center;">Apostas</th>
                        <th style="text-align: right;">Total Apostado</th>
                        <th style="text-align: right;">Total Pago</th>
                        <th style="text-align: right;">Saldo Casa (GGR)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topPlayers as $player)
                        <tr>
                            <td>
                                @if($player->user)
                                    <div class="admin-table-user">
                                        <div class="admin-table-avatar" style="width: 28px; height: 28px; border: 1px solid var(--color-yellow);">
                                            <img src="{{ $player->user->avatar_url }}" alt="Avatar" style="width: 100%; height: 100%; border-radius: 50%;">
                                        </div>
                                        <div class="admin-user-details">
                                            <h5 style="font-size: 12px; font-weight: 600;">{{ $player->user->name }}</h5>
                                            <p style="font-size: 10.5px;">{{ substr($player->user->email, 0, 3) }}***{{ strrchr($player->user->email, '@') }}</p>
                                        </div>
                                    </div>
                                @else
                                    <span style="color: var(--text-muted);">Usuário Excluído</span>
                                @endif
                            </td>
                            <td style="text-align: center; color: var(--text-secondary);">{{ number_format($player->total_bets, 0, ',', '.') }}</td>
                            <td style="text-align: right; color: var(--text-secondary);">R$ {{ number_format($player->total_bet_amount, 2, ',', '.') }}</td>
                            <td style="text-align: right; color: var(--text-secondary);">R$ {{ number_format($player->total_payout, 2, ',', '.') }}</td>
                            <td style="text-align: right; font-weight: 700; color: {{ $player->ggr >= 0 ? 'var(--color-green)' : 'var(--color-red)' }}">
                                R$ {{ number_format($player->ggr, 2, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: var(--text-muted); padding: 40px 0;">
                                Nenhum registro encontrado no período.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // --- 1. Financial Flow Chart (Deposits vs Withdrawals) ---
        const flowCtx = document.getElementById('financialFlowChart').getContext('2d');
        const flowLabels = @json($chartLabels);
        const depositsData = @json($chartDeposits);
        const withdrawalsData = @json($chartWithdrawals);

        new Chart(flowCtx, {
            type: 'bar',
            data: {
                labels: flowLabels,
                datasets: [
                    {
                        label: 'Depósitos Aprovados',
                        data: depositsData,
                        backgroundColor: 'rgba(16, 185, 129, 0.75)',
                        borderColor: '#10b981',
                        borderWidth: 1.5,
                        borderRadius: 4
                    },
                    {
                        label: 'Saques Aprovados',
                        data: withdrawalsData,
                        backgroundColor: 'rgba(239, 68, 68, 0.75)',
                        borderColor: '#ef4444',
                        borderWidth: 1.5,
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#9496a1',
                            font: { size: 10, weight: '600' }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#121215',
                        titleColor: '#9496a1',
                        bodyColor: '#ffffff',
                        borderColor: '#242428',
                        borderWidth: 1,
                        padding: 10,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': R$ ' + context.parsed.y.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#5e606a',
                            font: { size: 9, weight: '600' }
                        }
                    },
                    y: {
                        grid: { color: '#1c1c20', drawTicks: false },
                        ticks: {
                            color: '#5e606a',
                            font: { size: 9, weight: '600' },
                            callback: function(value) {
                                return 'R$ ' + value.toLocaleString('pt-BR');
                            }
                        }
                    }
                }
            }
        });

        // --- 2. GGR Evolution Chart ---
        const ggrCtx = document.getElementById('ggrChart').getContext('2d');
        const ggrData = @json($chartGGR);

        const ggrGradient = ggrCtx.createLinearGradient(0, 0, 0, 240);
        ggrGradient.addColorStop(0, 'rgba(248, 204, 27, 0.25)');
        ggrGradient.addColorStop(1, 'rgba(248, 204, 27, 0.0)');

        new Chart(ggrCtx, {
            type: 'line',
            data: {
                labels: flowLabels,
                datasets: [{
                    label: 'Lucro Bruto (GGR)',
                    data: ggrData,
                    borderColor: '#f8cc1b',
                    borderWidth: 3,
                    backgroundColor: ggrGradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#f8cc1b',
                    pointBorderColor: '#0c0c0e',
                    pointBorderWidth: 2,
                    pointRadius: 0,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#121215',
                        titleColor: '#9496a1',
                        bodyColor: '#ffffff',
                        borderColor: '#242428',
                        borderWidth: 1,
                        padding: 10,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'GGR: R$ ' + context.parsed.y.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#5e606a',
                            font: { size: 9, weight: '600' }
                        }
                    },
                    y: {
                        grid: { color: '#1c1c20', drawTicks: false },
                        ticks: {
                            color: '#5e606a',
                            font: { size: 9, weight: '600' },
                            callback: function(value) {
                                return 'R$ ' + value.toLocaleString('pt-BR');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
