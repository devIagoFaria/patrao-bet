@extends('layouts.admin')

@section('page_title', 'Painel de Controle')

@section('content')
<!-- Row 1: Welcome Banner & Pending Withdrawals -->
<div class="admin-row-top">
    <!-- Welcome Card -->
    @php
        $hour = date('H');
        $greeting = 'Olá';
        if ($hour >= 5 && $hour < 12) {
            $greeting = 'Bom dia';
        } elseif ($hour >= 12 && $hour < 18) {
            $greeting = 'Boa tarde';
        } else {
            $greeting = 'Boa noite';
        }
    @endphp
    <div class="admin-card-welcome" style="background: linear-gradient(135deg, #1c1c16 0%, #111113 100%);">
        <div class="admin-card-welcome-header">
            <span class="admin-live-dot"></span>
            <span>Ao Vivo</span>
        </div>
        <div class="admin-card-welcome-body" style="z-index: 5; position: relative;">
            <h2 style="font-size: 28px; font-weight: 800; font-family: var(--font-heading);">{{ $greeting }}, <span style="color: var(--color-yellow);">Administrador</span>.</h2>
            <p style="color: var(--text-secondary); font-size: 13.5px; margin-top: 6px; margin-bottom: 20px;">Resumo da atividade recente e desempenho do seu cassino:</p>
            
            <!-- Mini-Badges de Estatísticas -->
            <div style="display: flex; gap: 16px; flex-wrap: wrap;">
                <!-- Volume Recebido -->
                <div style="background: rgba(248, 204, 27, 0.06); border: 1px solid rgba(248, 204, 27, 0.12); padding: 12px 16px; border-radius: 12px; display: flex; align-items: center; gap: 12px; min-width: 180px;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--color-yellow); display: flex; align-items: center; justify-content: center; color: #000; font-size: 15px;">
                        <i class="fa-solid fa-dollar-sign"></i>
                    </div>
                    <div>
                        <div style="font-size: 9px; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Volume Recebido</div>
                        <div style="font-size: 15px; font-weight: 800; color: #fff; margin-top: 2px;">R$ {{ number_format($totalDepositsSum, 2, ',', '.') }}</div>
                    </div>
                </div>

                <!-- Depósitos -->
                <div style="background: rgba(16, 185, 129, 0.06); border: 1px solid rgba(16, 185, 129, 0.12); padding: 12px 16px; border-radius: 12px; display: flex; align-items: center; gap: 12px; min-width: 180px;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--color-green); display: flex; align-items: center; justify-content: center; color: #000; font-size: 15px;">
                        <i class="fa-solid fa-arrow-down"></i>
                    </div>
                    <div>
                        <div style="font-size: 9px; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Depósitos</div>
                        <div style="font-size: 15px; font-weight: 800; color: #fff; margin-top: 2px;">{{ number_format($totalDepositsCount, 0, ',', '.') }}</div>
                    </div>
                </div>

                <!-- Jogadores Ativos -->
                <div style="background: rgba(59, 130, 246, 0.06); border: 1px solid rgba(59, 130, 246, 0.12); padding: 12px 16px; border-radius: 12px; display: flex; align-items: center; gap: 12px; min-width: 180px;">
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--color-blue); display: flex; align-items: center; justify-content: center; color: #000; font-size: 15px;">
                        <i class="fa-solid fa-users"></i>
                    </div>
                    <div>
                        <div style="font-size: 9px; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Jogadores Ativos</div>
                        <div style="font-size: 15px; font-weight: 800; color: #fff; margin-top: 2px;">{{ $activeUsersCount }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Subtle golden waves under text -->
        <div class="admin-welcome-sparkline">
            <svg viewBox="0 0 500 70" width="100%" height="100%" preserveAspectRatio="none">
                <path d="M 0 50 Q 80 20 150 45 T 300 20 T 450 35 L 500 50 L 500 70 L 0 70 Z" fill="url(#yellowGradient)" opacity="0.15"></path>
                <path d="M 0 50 Q 80 20 150 45 T 300 20 T 450 35" fill="none" stroke="var(--color-yellow)" stroke-width="2" opacity="0.6"></path>
                <defs>
                    <linearGradient id="yellowGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="var(--color-yellow)"></stop>
                        <stop offset="100%" stop-color="transparent"></stop>
                    </linearGradient>
                </defs>
            </svg>
        </div>
    </div>
    <!-- Pending Withdrawals Card -->
    <div class="admin-card-pending-withdrawals" style="background: #121215; border: 1px solid var(--border-color); display: flex; flex-direction: column; justify-content: space-between; padding: 24px; border-radius: 16px; position: relative; overflow: hidden;">
        @if ($pendingWithdrawalsCount > 0)
            <div style="position: absolute; top: 0; right: 0; background: rgba(239, 68, 68, 0.1); border-bottom-left-radius: 12px; padding: 6px 12px; border-left: 1px solid rgba(239, 68, 68, 0.2); border-bottom: 1px solid rgba(239, 68, 68, 0.2); display: flex; align-items: center; gap: 6px;">
                <span style="width: 6px; height: 6px; border-radius: 50%; background-color: var(--color-red); display: inline-block; animation: pulse 2s infinite;"></span>
                <span style="font-size: 9px; font-weight: 800; color: var(--color-red); text-transform: uppercase;">Atenção</span>
            </div>
        @endif
        
        <div>
            <div class="admin-pending-title" style="display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-money-bill-transfer" style="color: {{ $pendingWithdrawalsCount > 0 ? 'var(--color-red)' : 'var(--color-green)' }}; font-size: 14px;"></i>
                <span>Saques Pendentes</span>
            </div>
            
            @if ($pendingWithdrawalsCount > 0)
                <div class="admin-pending-val" style="margin-top: 12px; font-size: 32px; font-weight: 900; font-family: var(--font-heading); color: var(--color-red);">
                    {{ $pendingWithdrawalsCount }} <span style="font-size: 13px; color: var(--text-secondary); font-weight: 500; font-family: var(--font-sans);">aguardando aprovação</span>
                </div>
            @else
                <div class="admin-pending-val" style="margin-top: 12px; font-size: 32px; font-weight: 900; font-family: var(--font-heading); color: var(--color-green);">
                    0 <span style="font-size: 13px; color: var(--text-secondary); font-weight: 500; font-family: var(--font-sans);">pedidos pendentes</span>
                </div>
            @endif
        </div>
        
        <div class="admin-pending-total" style="border-top: 1px solid var(--border-color); padding-top: 16px; margin-top: 16px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <span class="admin-pending-total-lbl" style="font-size: 9px; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Valor Total Solicitado</span>
                <div class="admin-pending-total-val" style="font-size: 16px; font-weight: 800; color: #fff; margin-top: 2px;">R$ {{ number_format($pendingWithdrawalsSum, 2, ',', '.') }}</div>
            </div>
            <a href="{{ route('admin.withdrawals', ['status' => 'pending']) }}" class="admin-btn-review" style="background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-color); font-size: 11px; font-weight: 700; padding: 8px 16px; border-radius: 8px; color: #fff; transition: all 0.2s ease;">Revisar →</a>
        </div>
    </div>
</div>

<!-- Row 2: KPIs Grid -->
<div class="admin-grid-kpis">
    <!-- Lucro Líquido (Yellow Card) -->
    <div class="admin-kpi-card" style="background: linear-gradient(135deg, #f8cc1b 0%, #d4ae0c 100%); border: none; color: #000; display: flex; flex-direction: column; justify-content: space-between; padding: 24px; border-radius: 16px; min-height: 165px; position: relative; overflow: hidden; box-shadow: 0 10px 25px -5px rgba(248, 204, 27, 0.2);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <span class="admin-kpi-lbl" style="font-size: 10px; font-weight: 800; color: rgba(0,0,0,0.6); letter-spacing: 0.5px; text-transform: uppercase;">Lucro Líquido</span>
            <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(0, 0, 0, 0.08); display: flex; align-items: center; justify-content: center; font-size: 14px; color: rgba(0,0,0,0.7);">
                <i class="fa-solid fa-wallet"></i>
            </div>
        </div>
        <div class="admin-kpi-val" style="font-family: var(--font-heading); font-size: 26px; font-weight: 900; margin-top: 10px;">R$ {{ number_format($netProfit, 2, ',', '.') }}</div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto;">
            <span class="admin-kpi-sub" style="font-size: 11px; color: rgba(0,0,0,0.5); font-weight: 600;">Depósitos - Saques</span>
            <span class="admin-kpi-badge" style="background: rgba(0, 0, 0, 0.08); color: #000; border: 1px solid rgba(0,0,0,0.1); font-size: 10px; font-weight: 700; padding: 3px 6px; border-radius: 4px;">+8% ↑</span>
        </div>
    </div>

    <!-- Depósitos Card -->
    <div class="admin-kpi-card" style="background: #121215; border: 1px solid var(--border-color); display: flex; flex-direction: column; justify-content: space-between; padding: 24px; border-radius: 16px; min-height: 165px; position: relative; overflow: hidden;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <span class="admin-kpi-lbl" style="font-size: 10px; font-weight: 800; color: var(--text-secondary); letter-spacing: 0.5px; text-transform: uppercase;">Depósitos</span>
            <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(16, 185, 129, 0.06); border: 1px solid rgba(16, 185, 129, 0.12); display: flex; align-items: center; justify-content: center; font-size: 14px; color: var(--color-green);">
                <i class="fa-solid fa-arrow-down-long"></i>
            </div>
        </div>
        <div class="admin-kpi-val" style="font-family: var(--font-heading); font-size: 26px; font-weight: 900; margin-top: 10px;">R$ {{ number_format($totalDepositsSum, 2, ',', '.') }}</div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; z-index: 5; position: relative;">
            <span class="admin-kpi-sub" style="font-size: 11px; color: var(--text-muted); font-weight: 500;">{{ $totalDepositsCount }} dep. - ticket R$ {{ number_format($averageTicket, 2, ',', '.') }}</span>
            <span class="admin-kpi-badge up" style="font-size: 10px; font-weight: 700; padding: 3px 6px; border-radius: 4px; background-color: rgba(16, 185, 129, 0.06); color: var(--color-green); border: 1px solid rgba(16, 185, 129, 0.12);">+9% ↑</span>
        </div>
        
        <!-- Small sparkline inside card -->
        <div class="admin-kpi-spark">
            <svg viewBox="0 0 100 30" width="100%" height="100%" preserveAspectRatio="none">
                <path d="M 0 25 Q 20 15 40 22 T 80 10 T 100 18" fill="none" stroke="var(--color-green)" stroke-width="1.5"></path>
                <path d="M 0 25 Q 20 15 40 22 T 80 10 T 100 18 L 100 30 L 0 30 Z" fill="rgba(16, 185, 129, 0.03)"></path>
            </svg>
        </div>
    </div>

    <!-- Saques Card -->
    <div class="admin-kpi-card" style="background: #121215; border: 1px solid var(--border-color); display: flex; flex-direction: column; justify-content: space-between; padding: 24px; border-radius: 16px; min-height: 165px; position: relative; overflow: hidden;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <span class="admin-kpi-lbl" style="font-size: 10px; font-weight: 800; color: var(--text-secondary); letter-spacing: 0.5px; text-transform: uppercase;">Saques</span>
            <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(239, 68, 68, 0.06); border: 1px solid rgba(239, 68, 68, 0.12); display: flex; align-items: center; justify-content: center; font-size: 14px; color: var(--color-red);">
                <i class="fa-solid fa-arrow-up-long"></i>
            </div>
        </div>
        <div class="admin-kpi-val" style="font-family: var(--font-heading); font-size: 26px; font-weight: 900; margin-top: 10px;">R$ {{ number_format($totalWithdrawalsSum, 2, ',', '.') }}</div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; z-index: 5; position: relative;">
            <span class="admin-kpi-sub" style="font-size: 11px; color: var(--text-muted); font-weight: 500;">{{ $totalWithdrawalsCount }} pagamentos</span>
            <span class="admin-kpi-badge up" style="font-size: 10px; font-weight: 700; padding: 3px 6px; border-radius: 4px; background-color: rgba(239, 68, 68, 0.06); color: var(--color-red); border: 1px solid rgba(239, 68, 68, 0.12);">+0% ↑</span>
        </div>

        <!-- Small sparkline inside card -->
        <div class="admin-kpi-spark">
            <svg viewBox="0 0 100 30" width="100%" height="100%" preserveAspectRatio="none">
                <path d="M 0 20 Q 25 28 50 18 T 75 22 T 100 15" fill="none" stroke="var(--color-red)" stroke-width="1.5" opacity="0.4"></path>
                <path d="M 0 20 Q 25 28 50 18 T 75 22 T 100 15 L 100 30 L 0 30 Z" fill="rgba(239, 68, 68, 0.02)"></path>
            </svg>
        </div>
    </div>

    <!-- Cadastros Card -->
    <div class="admin-kpi-card" style="background: #121215; border: 1px solid var(--border-color); display: flex; flex-direction: column; justify-content: space-between; padding: 24px; border-radius: 16px; min-height: 165px; position: relative; overflow: hidden;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <span class="admin-kpi-lbl" style="font-size: 10px; font-weight: 800; color: var(--text-secondary); letter-spacing: 0.5px; text-transform: uppercase;">Cadastros</span>
            <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(59, 130, 246, 0.06); border: 1px solid rgba(59, 130, 246, 0.12); display: flex; align-items: center; justify-content: center; font-size: 14px; color: var(--color-blue);">
                <i class="fa-solid fa-user-plus"></i>
            </div>
        </div>
        <div class="admin-kpi-val" style="font-family: var(--font-heading); font-size: 26px; font-weight: 900; margin-top: 10px;">{{ $totalUsers }}</div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; z-index: 5; position: relative;">
            <span class="admin-kpi-sub" style="font-size: 11px; color: var(--text-muted); font-weight: 500;">{{ $totalUsers }} total</span>
            <span class="admin-kpi-badge up" style="font-size: 10px; font-weight: 700; padding: 3px 6px; border-radius: 4px; background-color: rgba(59, 130, 246, 0.06); color: var(--color-blue); border: 1px solid rgba(59, 130, 246, 0.12);">+211% ↑</span>
        </div>

        <!-- Small sparkline inside card -->
        <div class="admin-kpi-spark">
            <svg viewBox="0 0 100 30" width="100%" height="100%" preserveAspectRatio="none">
                <path d="M 0 28 Q 15 20 30 25 T 60 10 T 80 18 T 100 5" fill="none" stroke="var(--color-blue)" stroke-width="1.5"></path>
                <path d="M 0 28 Q 15 20 30 25 T 60 10 T 80 18 T 100 5 L 100 30 L 0 30 Z" fill="rgba(59, 130, 246, 0.03)"></path>
            </svg>
        </div>
    </div>
</div>

<!-- Row 3: Secondary Metrics Grid -->
<div class="admin-grid-kpis-sec">
    <!-- Conversão FTD -->
    <div class="admin-kpi-card-sec" style="background: #121215; border: 1px solid var(--border-color); border-left: 3px solid var(--color-blue); padding: 18px; border-radius: 12px;">
        <span class="admin-kpisec-lbl" style="font-size: 9.5px; font-weight: 700; color: var(--text-muted); letter-spacing: 0.5px; text-transform: uppercase;">Conversão FTD</span>
        <div class="admin-kpisec-val" style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; margin-top: 6px; margin-bottom: 4px; color: #fff;">{{ number_format($ftdRate, 1, ',', '.') }}%</div>
        <span class="admin-kpisec-sub" style="font-size: 10.5px; color: var(--text-muted);">{{ $ftdUsersCount }} primeiros depósitos</span>
    </div>

    <!-- Usuários Ativos -->
    <div class="admin-kpi-card-sec" style="background: #121215; border: 1px solid var(--border-color); border-left: 3px solid var(--color-green); padding: 18px; border-radius: 12px;">
        <span class="admin-kpisec-lbl" style="font-size: 9.5px; font-weight: 700; color: var(--text-muted); letter-spacing: 0.5px; text-transform: uppercase;">Usuários Ativos</span>
        <div class="admin-kpisec-val" style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; margin-top: 6px; margin-bottom: 4px; color: #fff;">{{ $activeUsersCount }}</div>
        <span class="admin-kpisec-sub" style="font-size: 10.5px; color: var(--text-muted);">Jogaram no período</span>
    </div>

    <!-- Saldo Jogadores -->
    <div class="admin-kpi-card-sec" style="background: #121215; border: 1px solid var(--border-color); border-left: 3px solid var(--color-yellow); padding: 18px; border-radius: 12px;">
        <span class="admin-kpisec-lbl" style="font-size: 9.5px; font-weight: 700; color: var(--text-muted); letter-spacing: 0.5px; text-transform: uppercase;">Saldo dos Jogadores</span>
        <div class="admin-kpisec-val" style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; margin-top: 6px; margin-bottom: 4px; color: #fff;">R$ {{ number_format($playersBalance, 2, ',', '.') }}</div>
        <span class="admin-kpisec-sub" style="font-size: 10.5px; color: var(--text-muted);">{{ $totalUsers }} com saldo</span>
    </div>

    <!-- Bônus Ativo -->
    <div class="admin-kpi-card-sec" style="background: #121215; border: 1px solid var(--border-color); border-left: 3px solid #a855f7; padding: 18px; border-radius: 12px;">
        <span class="admin-kpisec-lbl" style="font-size: 9.5px; font-weight: 700; color: var(--text-muted); letter-spacing: 0.5px; text-transform: uppercase;">Bônus Ativo</span>
        <div class="admin-kpisec-val" style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; margin-top: 6px; margin-bottom: 4px; color: #fff;">R$ {{ number_format($activeBonus, 2, ',', '.') }}</div>
        <span class="admin-kpisec-sub" style="font-size: 10.5px; color: var(--text-muted);">Em carteiras bônus</span>
    </div>
</div>

<!-- Row 4: Charts and Transactions List -->
<div class="admin-row-charts">
    <!-- Revenue Chart -->
    <div class="admin-card-chart">
        <div class="admin-card-chart-header">
            <div>
                <span class="admin-chart-title">RECEITA</span>
                <p class="admin-chart-subtitle">Depósitos por período - Volume transacionado</p>
            </div>
            <div class="admin-chart-header-value">
                <h3>R$ {{ number_format($totalDepositsSum, 2, ',', '.') }}</h3>
                <p>Volume total</p>
            </div>
        </div>
        <div class="admin-chart-body">
            <canvas id="revenueChart" style="width: 100%; height: 240px;"></canvas>
        </div>
    </div>

    <!-- Recent Transactions Stream -->
    <div class="admin-card-stream">
        <div class="admin-card-stream-header">
            <div>
                <span class="admin-stream-title">FLUXO</span>
                <p class="admin-stream-subtitle">Transações recentes</p>
            </div>
            <a href="{{ route('admin.deposits') }}" class="admin-link-viewall">Ver todas →</a>
        </div>
        
        <ul class="admin-stream-list">
            @forelse($recentTransactions as $tx)
                <li class="admin-stream-item">
                    <div class="admin-stream-item-left">
                        <div class="admin-stream-icon {{ $tx->status === 'pending' ? 'pending' : '' }}">
                            @if ($tx->status === 'pending')
                                <i class="fa-regular fa-clock"></i>
                            @else
                                <i class="fa-solid fa-check"></i>
                            @endif
                        </div>
                        <div class="admin-stream-info">
                            <h4>{{ substr($tx->user->email, 0, 2) }}***@gmail.com</h4>
                            <p>{{ $tx->status === 'pending' ? 'Pendente' : 'Pago' }} - {{ $tx->created_at->format('d/m - H:i') }}</p>
                        </div>
                    </div>
                    <div class="admin-stream-value {{ $tx->status === 'pending' ? 'pending' : '' }}">
                        +R$ {{ number_format($tx->amount, 2, ',', '.') }}
                    </div>
                </li>
            @empty
                <li class="admin-stream-item" style="justify-content: center; color: var(--text-muted);">
                    Nenhuma transação recente encontrada.
                </li>
            @endforelse
        </ul>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Dynamic dates & values from backend, or fallback to mock if empty
        let labels = [
            @foreach($chartData as $d)
                '{{ date("d/m", strtotime($d->date)) }}',
            @endforeach
        ];
        
        let dataValues = [
            @foreach($chartData as $d)
                {{ $d->total }},
            @endforeach
        ];

        // Fallbacks for seeding preview
        if (labels.length === 0) {
            labels = ['19/05', '20/05', '21/05', '22/05', '23/05', '24/05', '25/05', '26/05', '27/05', '28/05'];
            dataValues = [12000, 18000, 15000, 25000, 22000, 31000, 29000, 42000, 39000, 45145];
        }

        // Create gradient fill
        const gradient = ctx.createLinearGradient(0, 0, 0, 240);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.25)');
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Volume de Depósitos',
                    data: dataValues,
                    borderColor: '#10b981',
                    borderWidth: 3.5,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#10b981',
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
                    legend: {
                        display: false
                    },
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
                                return 'R$ ' + context.parsed.y.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                        ticks: {
                            color: '#5e606a',
                            font: {
                                size: 10,
                                weight: '600'
                            }
                        }
                    },
                    y: {
                        grid: {
                            color: '#1c1c20',
                            drawTicks: false
                        },
                        ticks: {
                            color: '#5e606a',
                            font: {
                                size: 10,
                                weight: '600'
                            },
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
