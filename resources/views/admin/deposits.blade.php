@extends('layouts.admin')

@section('page_title', 'Depósitos')

@section('content')
<div class="admin-card-table">
    <div class="admin-table-filters">
        <div class="admin-filter-left">
            <div class="admin-filter-tabs">
                <a href="{{ route('admin.deposits') }}" class="admin-filter-tab-btn {{ !$status ? 'active' : '' }}">Todos</a>
                <a href="{{ route('admin.deposits', ['status' => 'pending']) }}" class="admin-filter-tab-btn {{ $status === 'pending' ? 'active' : '' }}">Pendentes</a>
                <a href="{{ route('admin.deposits', ['status' => 'approved']) }}" class="admin-filter-tab-btn {{ $status === 'approved' ? 'active' : '' }}">Aprovados</a>
                <a href="{{ route('admin.deposits', ['status' => 'rejected']) }}" class="admin-filter-tab-btn {{ $status === 'rejected' ? 'active' : '' }}">Rejeitados</a>
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
                    <th>Método</th>
                    <th>ID Transação</th>
                    <th>Status</th>
                    <th>Data</th>
                    <th style="text-align: right;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deposits as $dep)
                    <tr>
                        <td>#{{ $dep->id }}</td>
                        <td>
                            <div class="admin-table-user">
                                <div class="admin-table-avatar" style="overflow: hidden; display: flex; align-items: center; justify-content: center; background: none; border: 1.5px solid var(--color-green);">
                                    <img src="{{ $dep->user->avatar_url }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                </div>
                                <div class="admin-user-details">
                                    <h5>{{ $dep->user->name }}</h5>
                                    <p>{{ $dep->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td style="font-weight: 700; color: var(--color-green);">
                            +R$ {{ number_format($dep->amount, 2, ',', '.') }}
                        </td>
                        <td style="text-transform: uppercase; font-size: 11px; font-weight: 700; color: var(--text-secondary);">
                            @if ($dep->payment_method === 'pix')
                                <i class="fa-brands fa-pix" style="color: #32b3a6; margin-right: 4px;"></i> PIX
                            @else
                                <i class="fa-solid fa-user-shield" style="margin-right: 4px;"></i> ADMIN
                            @endif
                        </td>
                        <td style="font-family: monospace; font-size: 12px; color: var(--text-muted);">
                            {{ $dep->transaction_id ?? 'N/A' }}
                        </td>
                        <td>
                            @if ($dep->status === 'pending')
                                <span class="admin-badge admin-badge-pending">Pendente</span>
                            @elseif ($dep->status === 'approved')
                                <span class="admin-badge admin-badge-approved">Aprovado</span>
                            @else
                                <span class="admin-badge admin-badge-rejected">Rejeitado</span>
                            @endif
                        </td>
                        <td style="color: var(--text-secondary);">
                            {{ $dep->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td>
                            <div class="admin-actions-cell" style="justify-content: flex-end;">
                                @if ($dep->status === 'pending')
                                    <form action="{{ route('admin.deposits.approve', $dep->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="admin-btn-action btn-approve" title="Aprovar Depósito">
                                            <i class="fa-solid fa-check"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.deposits.reject', $dep->id) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="admin-btn-action btn-reject" title="Rejeitar Depósito">
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
                        <td colspan="8" style="text-align: center; color: var(--text-muted); padding: 40px 0;">
                            Nenhum depósito encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="admin-pagination-wrapper">
        {{ $deposits->appends(['status' => $status])->links() }}
    </div>
</div>
@endsection
