@extends('layouts.admin')

@section('page_title', 'Módulo de Afiliados')

@section('content')
<!-- Row 1: KPI Statistics -->
<h3 style="font-family: var(--font-heading); color: var(--text-primary); font-size: 16px; font-weight: 700; margin-bottom: 16px; margin-top: 8px; display: flex; align-items: center; gap: 8px;">
    <i class="fa-solid fa-users" style="color: var(--color-yellow);"></i> Estatísticas de Afiliação
</h3>
<div class="admin-grid-kpis" style="margin-bottom: 24px; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); display: grid; gap: 20px;">
    <!-- Total referred users -->
    <div class="admin-kpi-card" style="background: #121215; border: 1px solid var(--border-color); display: flex; flex-direction: column; justify-content: space-between; padding: 24px; border-radius: 16px; min-height: 120px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <span class="admin-kpi-lbl" style="font-size: 10px; font-weight: 800; color: var(--text-secondary); letter-spacing: 0.5px; text-transform: uppercase;">Total de Indicados</span>
            <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(59, 130, 246, 0.06); border: 1px solid rgba(59, 130, 246, 0.12); display: flex; align-items: center; justify-content: center; font-size: 14px; color: var(--color-blue);">
                <i class="fa-solid fa-user-plus"></i>
            </div>
        </div>
        <div class="admin-kpi-val" style="font-family: var(--font-heading); font-size: 26px; font-weight: 900; margin-top: 10px;">
            {{ number_format($totalReferrals, 0, ',', '.') }}
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto;">
            <span class="admin-kpi-sub" style="font-size: 11px; color: var(--text-muted); font-weight: 500;">Usuários com padrinho associado</span>
        </div>
    </div>

    <!-- Total referred deposits -->
    <div class="admin-kpi-card" style="background: #121215; border: 1px solid var(--border-color); display: flex; flex-direction: column; justify-content: space-between; padding: 24px; border-radius: 16px; min-height: 120px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <span class="admin-kpi-lbl" style="font-size: 10px; font-weight: 800; color: var(--text-secondary); letter-spacing: 0.5px; text-transform: uppercase;">Total Depositado por Indicados</span>
            <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(16, 185, 129, 0.06); border: 1px solid rgba(16, 185, 129, 0.12); display: flex; align-items: center; justify-content: center; font-size: 14px; color: var(--color-green);">
                <i class="fa-solid fa-money-bill-trend-up"></i>
            </div>
        </div>
        <div class="admin-kpi-val" style="font-family: var(--font-heading); font-size: 26px; font-weight: 900; margin-top: 10px; color: var(--color-green);">
            R$ {{ number_format($totalDepositedByReferred, 2, ',', '.') }}
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto;">
            <span class="admin-kpi-sub" style="font-size: 11px; color: var(--text-muted); font-weight: 500;">Volume de depósitos gerados</span>
        </div>
    </div>

    <!-- Total commissions paid -->
    <div class="admin-kpi-card" style="background: #121215; border: 1px solid var(--border-color); display: flex; flex-direction: column; justify-content: space-between; padding: 24px; border-radius: 16px; min-height: 120px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <span class="admin-kpi-lbl" style="font-size: 10px; font-weight: 800; color: var(--text-secondary); letter-spacing: 0.5px; text-transform: uppercase;">Comissões Pagas</span>
            <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(248, 204, 27, 0.06); border: 1px solid rgba(248, 204, 27, 0.12); display: flex; align-items: center; justify-content: center; font-size: 14px; color: var(--color-yellow);">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </div>
        </div>
        <div class="admin-kpi-val" style="font-family: var(--font-heading); font-size: 26px; font-weight: 900; margin-top: 10px; color: var(--color-yellow);">
            R$ {{ number_format($totalCommissionPaid, 2, ',', '.') }}
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto;">
            <span class="admin-kpi-sub" style="font-size: 11px; color: var(--text-muted); font-weight: 500;">Taxa de comissão atual: {{ $commissionPercent }}%</span>
        </div>
    </div>
</div>

<!-- Row 2: Referred Users Table -->
<div class="admin-card-table" style="padding: 24px;">
    <div style="margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 15px;">
        <h4 style="font-family: var(--font-heading); font-size: 16px; font-weight: 700;">Relação de Indicados</h4>
        <p style="font-size: 12.5px; color: var(--text-secondary); margin-top: 4px;">Lista detalhada de usuários cadastrados sob afiliação de padrinhos.</p>
    </div>

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Jogador Indicado</th>
                    <th>Padrinho (Afiliado)</th>
                    <th>Total Depositado</th>
                    <th>Comissão Gerada</th>
                    <th>Data Cadastro</th>
                </tr>
            </thead>
            <tbody>
                @forelse($referredUsers as $user)
                    <tr>
                        <td>#{{ $user->id }}</td>
                        <td>
                            <div class="admin-table-user">
                                <div class="admin-table-avatar" style="overflow: hidden; display: flex; align-items: center; justify-content: center; background: none; border: 1.5px solid var(--border-color);">
                                    <img src="{{ $user->avatar_url }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                </div>
                                <div class="admin-user-details">
                                    <h5>{{ $user->name }}</h5>
                                    <p>{{ $user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($user->referrer)
                                <div class="admin-table-user">
                                    <div class="admin-table-avatar" style="overflow: hidden; display: flex; align-items: center; justify-content: center; background: none; border: 1.5px solid var(--border-color);">
                                        <img src="{{ $user->referrer->avatar_url }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                    </div>
                                    <div class="admin-user-details">
                                        <h5>{{ $user->referrer->name }}</h5>
                                        <p>ID: #{{ $user->referrer->id }}</p>
                                    </div>
                                </div>
                            @else
                                <span style="font-size: 12px; color: var(--text-muted);">Nenhum</span>
                            @endif
                        </td>
                        <td style="font-weight: 700; color: #fff;">
                            R$ {{ number_format($user->total_deposited, 2, ',', '.') }}
                        </td>
                        <td style="font-weight: 700; color: var(--color-yellow);">
                            R$ {{ number_format($user->commission_generated, 2, ',', '.') }}
                        </td>
                        <td style="color: var(--text-secondary);">
                            {{ $user->created_at->format('d/m/Y H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 40px 0;">
                            Nenhuma indicação cadastrada até o momento.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="admin-pagination-wrapper" style="margin-top: 20px; display: flex; justify-content: center;">
        {{ $referredUsers->links() }}
    </div>
</div>
@endsection
