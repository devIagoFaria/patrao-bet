@extends('layouts.admin')

@section('page_title', 'Saques')

@section('content')
<div class="admin-card-table">
    <div class="admin-table-filters">
        <div class="admin-filter-left">
            <div class="admin-filter-tabs">
                <a href="{{ route('admin.withdrawals') }}" class="admin-filter-tab-btn {{ !$status ? 'active' : '' }}">Todos</a>
                <a href="{{ route('admin.withdrawals', ['status' => 'pending']) }}" class="admin-filter-tab-btn {{ $status === 'pending' ? 'active' : '' }}">Pendentes</a>
                <a href="{{ route('admin.withdrawals', ['status' => 'approved']) }}" class="admin-filter-tab-btn {{ $status === 'approved' ? 'active' : '' }}">Aprovados</a>
                <a href="{{ route('admin.withdrawals', ['status' => 'rejected']) }}" class="admin-filter-tab-btn {{ $status === 'rejected' ? 'active' : '' }}">Rejeitados</a>
            </div>
        </div>
    </div>

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Jogador</th>
                    <th>Valor</th>
                    <th>Chave PIX</th>
                    <th>Status</th>
                    <th>Data</th>
                    <th style="text-align: right;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($withdrawals as $wdraw)
                    <tr>
                        <td>#{{ $wdraw->id }}</td>
                        <td>
                            <div class="admin-table-user">
                                <div class="admin-table-avatar" style="overflow: hidden; display: flex; align-items: center; justify-content: center; background: none; border: 1.5px solid var(--color-red);">
                                    <img src="{{ $wdraw->user->avatar_url }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                </div>
                                <div class="admin-user-details">
                                    <h5>{{ $wdraw->user->name }}</h5>
                                    <p>{{ $wdraw->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td style="font-weight: 700; color: var(--color-red);">
                            -R$ {{ number_format($wdraw->amount, 2, ',', '.') }}
                        </td>
                        <td style="font-family: monospace; font-size: 12px; color: var(--text-secondary);">
                            {{ $wdraw->pix_key ?? 'N/A' }}
                        </td>
                        <td>
                            @if ($wdraw->status === 'pending')
                                <span class="admin-badge admin-badge-pending">Pendente</span>
                            @elseif ($wdraw->status === 'approved')
                                <span class="admin-badge admin-badge-approved">Aprovado</span>
                            @else
                                <span class="admin-badge admin-badge-rejected">Rejeitado</span>
                            @endif
                        </td>
                        <td style="color: var(--text-secondary);">
                            {{ $wdraw->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <div class="admin-actions-cell" style="justify-content: flex-end;">
                                @if ($wdraw->status === 'pending')
                                    <form action="{{ route('admin.withdrawals.approve', $wdraw->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="admin-btn-action btn-approve" title="Aprovar Saque">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.withdrawals.reject', $wdraw->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="admin-btn-action btn-reject" title="Rejeitar Saque">
                                            <i class="fa-solid fa-xmark"></i>
                                        </button>
                                    </form>
                                @else
                                    <span style="font-size: 11px; color: var(--text-muted); font-weight: 600;">Processado</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 40px 0;">
                            Nenhum saque encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="admin-pagination-wrapper">
        {{ $withdrawals->appends(['status' => $status])->links() }}
    </div>
</div>
@endsection
