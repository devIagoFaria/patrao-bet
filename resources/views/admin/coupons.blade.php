@extends('layouts.admin')

@section('page_title', 'Gestão de Cupons')

@section('styles')
<style>
    .coupons-container {
        max-width: 1200px;
        margin: 0 auto;
        background: #121215;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 32px;
    }
    .btn-action {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-edit {
        background: rgba(248, 204, 27, 0.1);
        color: var(--color-yellow);
        border: 1px solid rgba(248, 204, 27, 0.2);
    }
    .btn-delete {
        background: rgba(239, 68, 68, 0.1);
        color: var(--color-red);
        border: 1px solid rgba(239, 68, 68, 0.2);
    }
    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0,0,0,0.8);
        backdrop-filter: blur(5px);
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .modal-content {
        background: #161619;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 24px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
</style>
@endsection

@section('content')
<div class="coupons-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 1px solid var(--border-color); padding-bottom: 20px;">
        <div>
            <h3 style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; color: #fff;">Cupons de Promoção</h3>
            <p style="font-size: 13px; color: var(--text-secondary); margin-top: 6px;">Crie e gerencie cupons promocionais para creditar bônus aos jogadores no depósito.</p>
        </div>
        <button class="admin-btn-save" style="margin-top: 0; width: auto;" onclick="openModal('create-coupon-modal')">
            <i class="fa-solid fa-plus"></i> Adicionar Cupom
        </button>
    </div>

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Tipo</th>
                    <th>Valor do Bônus</th>
                    <th>Depósito Mínimo</th>
                    <th>Limite de Usos</th>
                    <th>Total Usado</th>
                    <th>Expiração</th>
                    <th>Status</th>
                    <th style="text-align: right;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                    <tr>
                        <td style="font-weight: 800; color: var(--color-yellow); font-family: monospace; font-size: 14px;">{{ $coupon->code }}</td>
                        <td>
                            <span style="font-weight: 600;">
                                {{ $coupon->type === 'percentage' ? 'Percentual' : 'Fixo' }}
                            </span>
                        </td>
                        <td style="font-weight: 700; color: #fff;">
                            {{ $coupon->type === 'percentage' ? $coupon->value . '%' : 'R$ ' . number_format($coupon->value, 2, ',', '.') }}
                        </td>
                        <td style="color: var(--text-secondary);">R$ {{ number_format($coupon->min_deposit, 2, ',', '.') }}</td>
                        <td style="color: var(--text-secondary);">{{ $coupon->max_uses ?? 'Ilimitado' }}</td>
                        <td style="color: var(--text-secondary); font-weight: 700;">{{ $coupon->uses_count }}</td>
                        <td style="color: var(--text-secondary);">
                            {{ $coupon->expires_at ? $coupon->expires_at->format('d/m/Y') : 'Nunca' }}
                        </td>
                        <td>
                            @if($coupon->is_active && (!$coupon->expires_at || !$coupon->expires_at->isPast()) && ($coupon->max_uses === null || $coupon->uses_count < $coupon->max_uses))
                                <span style="color: var(--color-green); font-size: 11px; font-weight: 800;"><i class="fa-solid fa-circle" style="font-size: 8px; margin-right: 4px;"></i> ATIVO</span>
                            @else
                                <span style="color: var(--color-red); font-size: 11px; font-weight: 800;"><i class="fa-solid fa-circle" style="font-size: 8px; margin-right: 4px;"></i> INATIVO</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                <button class="btn-action btn-edit open-edit-btn" 
                                    data-id="{{ $coupon->id }}"
                                    data-code="{{ $coupon->code }}"
                                    data-type="{{ $coupon->type }}"
                                    data-value="{{ $coupon->value }}"
                                    data-min_deposit="{{ $coupon->min_deposit }}"
                                    data-max_uses="{{ $coupon->max_uses }}"
                                    data-expires_at="{{ $coupon->expires_at ? $coupon->expires_at->format('Y-m-d') : '' }}"
                                    data-is_active="{{ $coupon->is_active ? 1 : 0 }}"
                                    data-url="{{ route('admin.coupons.update', $coupon->id) }}">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Deseja realmente remover este cupom?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action btn-delete">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align: center; padding: 40px; color: var(--text-muted);">Nenhum cupom cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create Coupon Modal -->
<div id="create-coupon-modal" class="modal-overlay">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 15px;">
            <h4 style="margin: 0; color: #fff; font-family: var(--font-heading); font-weight: 800;">Adicionar Novo Cupom</h4>
            <button type="button" onclick="closeModal('create-coupon-modal')" style="background: transparent; border: none; color: #9ca3af; cursor: pointer; font-size: 20px;"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form action="{{ route('admin.coupons.store') }}" method="POST">
            @csrf
            <div class="admin-form-group">
                <label>Código do Cupom</label>
                <input type="text" name="code" required placeholder="Ex: BEMVINDO100" style="text-transform: uppercase;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="admin-form-group">
                    <label>Tipo de Bônus</label>
                    <select name="type" required>
                        <option value="percentage">Percentual (%)</option>
                        <option value="fixed">Fixo (R$)</option>
                    </select>
                </div>
                <div class="admin-form-group">
                    <label>Valor</label>
                    <input type="number" name="value" step="0.01" min="0.01" required placeholder="Ex: 100.00 ou 10">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="admin-form-group">
                    <label>Depósito Mínimo (R$)</label>
                    <input type="number" name="min_deposit" step="0.01" min="0.00" value="0.00" required>
                </div>
                <div class="admin-form-group">
                    <label>Limite Global de Usos</label>
                    <input type="number" name="max_uses" min="1" placeholder="Ilimitado se vazio">
                </div>
            </div>

            <div class="admin-form-group">
                <label>Data de Expiração</label>
                <input type="date" name="expires_at">
            </div>

            <div class="admin-form-group" style="margin-top: 15px;">
                <label class="admin-checkbox-container" style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" checked style="width: auto;">
                    <span style="font-size: 13px; color: #fff; font-weight: 600;">Ativo para uso</span>
                </label>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px; border-top: 1px solid var(--border-color); padding-top: 20px;">
                <button type="button" onclick="closeModal('create-coupon-modal')" style="padding: 10px 20px; background: #242428; border: 1px solid var(--border-color); border-radius: 8px; color: #fff; cursor: pointer; font-weight: 700; font-size: 13px;">Cancelar</button>
                <button type="submit" class="admin-btn-save" style="margin-top: 0; width: auto; padding: 10px 30px;">CADASTRAR CUPOM</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Coupon Modal -->
<div id="edit-coupon-modal" class="modal-overlay">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 15px;">
            <h4 style="margin: 0; color: #fff; font-family: var(--font-heading); font-weight: 800;">Editar Cupom</h4>
            <button type="button" onclick="closeModal('edit-coupon-modal')" style="background: transparent; border: none; color: #9ca3af; cursor: pointer; font-size: 20px;"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form id="edit-coupon-form" method="POST">
            @csrf
            <div class="admin-form-group">
                <label>Código do Cupom</label>
                <input type="text" name="code" id="edit-code" required placeholder="Ex: BEMVINDO100" style="text-transform: uppercase;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="admin-form-group">
                    <label>Tipo de Bônus</label>
                    <select name="type" id="edit-type" required>
                        <option value="percentage">Percentual (%)</option>
                        <option value="fixed">Fixo (R$)</option>
                    </select>
                </div>
                <div class="admin-form-group">
                    <label>Valor</label>
                    <input type="number" name="value" id="edit-value" step="0.01" min="0.01" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="admin-form-group">
                    <label>Depósito Mínimo (R$)</label>
                    <input type="number" name="min_deposit" id="edit-min_deposit" step="0.01" min="0.00" required>
                </div>
                <div class="admin-form-group">
                    <label>Limite Global de Usos</label>
                    <input type="number" name="max_uses" id="edit-max_uses" min="1" placeholder="Ilimitado se vazio">
                </div>
            </div>

            <div class="admin-form-group">
                <label>Data de Expiração</label>
                <input type="date" name="expires_at" id="edit-expires_at">
            </div>

            <div class="admin-form-group" style="margin-top: 15px;">
                <label class="admin-checkbox-container" style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="is_active" id="edit-is_active" value="1" style="width: auto;">
                    <span style="font-size: 13px; color: #fff; font-weight: 600;">Ativo para uso</span>
                </label>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px; border-top: 1px solid var(--border-color); padding-top: 20px;">
                <button type="button" onclick="closeModal('edit-coupon-modal')" style="padding: 10px 20px; background: #242428; border: 1px solid var(--border-color); border-radius: 8px; color: #fff; cursor: pointer; font-weight: 700; font-size: 13px;">Cancelar</button>
                <button type="submit" class="admin-btn-save" style="margin-top: 0; width: auto; padding: 10px 30px;">SALVAR ALTERAÇÕES</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'flex';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    document.querySelectorAll('.open-edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const data = this.dataset;
            document.getElementById('edit-code').value = data.code;
            document.getElementById('edit-type').value = data.type;
            document.getElementById('edit-value').value = data.value;
            document.getElementById('edit-min_deposit').value = data.min_deposit;
            document.getElementById('edit-max_uses').value = data.max_uses || '';
            document.getElementById('edit-expires_at').value = data.expires_at || '';
            document.getElementById('edit-is_active').checked = data.is_active === '1';

            const form = document.getElementById('edit-coupon-form');
            form.action = data.url;
            
            openModal('edit-coupon-modal');
        });
    });
</script>
@endsection
