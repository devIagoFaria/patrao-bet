@extends('layouts.admin')

@section('page_title', 'Gestão de Níveis VIP')

@section('styles')
<style>
    .vip-container {
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
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 24px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    .badge-preview {
        width: 48px;
        height: 48px;
        object-fit: contain;
        background: rgba(255,255,255,0.05);
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }
    .benefits-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-top: 10px;
        background: #121215;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 12px;
    }
    .benefit-item {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: var(--text-secondary);
        cursor: pointer;
    }
    .benefit-item input {
        width: auto;
        margin: 0;
        cursor: pointer;
    }
</style>
@endsection

@section('content')
<div class="vip-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 1px solid var(--border-color); padding-bottom: 20px;">
        <div>
            <h3 style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; color: #fff;">Níveis e Recompensas VIP</h3>
            <p style="font-size: 13px; color: var(--text-secondary); margin-top: 6px;">Configure os níveis de fidelidade, a taxa de câmbio para troca de moedas, bônus de cashback e os benefícios desbloqueados por nível.</p>
        </div>
        <button class="admin-btn-save" style="margin-top: 0; width: auto;" onclick="openModal('create-vip-modal')">
            <i class="fa-solid fa-plus"></i> Adicionar Nível
        </button>
    </div>

    @if(session('success'))
        <div style="background: rgba(74, 222, 128, 0.1); border: 1px solid rgba(74, 222, 128, 0.2); color: #4ade80; padding: 12px; border-radius: 8px; font-size: 14px; margin-bottom: 20px;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #f87171; padding: 12px; border-radius: 8px; font-size: 14px; margin-bottom: 20px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="admin-table-wrapper">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Nível #</th>
                    <th>Badge</th>
                    <th>Nome</th>
                    <th>XP Requerido</th>
                    <th>Câmbio (Moedas/R$ 1)</th>
                    <th>Troca Mínima</th>
                    <th>Cashback</th>
                    <th>Benefícios</th>
                    <th style="text-align: right;">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vipLevels as $level)
                    <tr>
                        <td style="font-weight: 800; color: #fff;">#{{ $level->level_number }}</td>
                        <td>
                            @if($level->badge_path)
                                <img src="{{ $level->badge_path }}" class="badge-preview" alt="Badge">
                            @else
                                <span style="font-size: 11px; color: var(--text-muted);">Sem Badge</span>
                            @endif
                        </td>
                        <td style="font-weight: 700; color: var(--color-yellow);">{{ $level->name }}</td>
                        <td>{{ number_format($level->required_points, 0, ',', '.') }} XP</td>
                        <td style="font-weight: 700; color: #fff;">{{ number_format($level->exchange_rate, 2, ',', '.') }} moedas</td>
                        <td>{{ number_format($level->min_exchange, 0, ',', '.') }} moedas</td>
                        <td>{{ number_format($level->cashback_percent, 2, ',', '.') }}%</td>
                        <td>
                            <span style="font-size: 11px; font-weight: 700; background: rgba(255,255,255,0.05); padding: 4px 8px; border-radius: 6px; color: var(--text-secondary);">
                                {{ is_array($level->benefits) ? count($level->benefits) : 0 }} itens
                            </span>
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                <button class="btn-action btn-edit open-edit-btn" 
                                    data-id="{{ $level->id }}"
                                    data-level_number="{{ $level->level_number }}"
                                    data-name="{{ $level->name }}"
                                    data-required_points="{{ $level->required_points }}"
                                    data-exchange_rate="{{ $level->exchange_rate }}"
                                    data-min_exchange="{{ $level->min_exchange }}"
                                    data-cashback_percent="{{ $level->cashback_percent }}"
                                    data-benefits="{{ json_encode($level->benefits ?? []) }}"
                                    data-badge_path="{{ $level->badge_path }}"
                                    data-url="{{ route('admin.vip-levels.update', $level->id) }}">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <form action="{{ route('admin.vip-levels.destroy', $level->id) }}" method="POST" onsubmit="return confirm('Deseja realmente remover este nível VIP?')">
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
                        <td colspan="9" style="text-align: center; padding: 40px; color: var(--text-muted);">Nenhum nível VIP cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@php
    $availableBenefits = [
        'cashback' => 'Cashback',
        'vip_club' => 'Acesso ao VIP Club',
        'vip_support' => 'Suporte VIP',
        'private_tournaments' => 'Torneios VIP privados',
        'vip_bonus' => 'Bônus VIP',
        'sports_limits' => 'Limites maiores para apostas esportivas',
        'exclusive_channels' => 'Canais VIP exclusivos',
        'vip_manager' => 'Gerente VIP',
        'concierge' => 'Serviço de concierge',
        'manager_bonus' => 'Bônus do gerente VIP',
        'vip_gifts' => 'Brindes e produtos VIP',
        'vip_trips' => 'Viagens para eventos VIP',
        'personal_assistant' => 'Assistente pessoal',
    ];
@endphp

<!-- Create VIP Level Modal -->
<div id="create-vip-modal" class="modal-overlay">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 15px;">
            <h4 style="margin: 0; color: #fff; font-family: var(--font-heading); font-weight: 800;">Adicionar Nível VIP</h4>
            <button type="button" onclick="closeModal('create-vip-modal')" style="background: transparent; border: none; color: #9ca3af; cursor: pointer; font-size: 20px;"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form action="{{ route('admin.vip-levels.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 15px;">
                <div class="admin-form-group">
                    <label>Nível #</label>
                    <input type="number" name="level_number" required placeholder="Ex: 1">
                </div>
                <div class="admin-form-group">
                    <label>Nome do Nível</label>
                    <input type="text" name="name" required placeholder="Ex: Bronze 1">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="admin-form-group">
                    <label>XP Requerido (Wager)</label>
                    <input type="number" name="required_points" required placeholder="Ex: 5000">
                </div>
                <div class="admin-form-group">
                    <label>Cashback (%)</label>
                    <input type="number" name="cashback_percent" step="0.01" min="0.00" max="100.00" value="1.00" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="admin-form-group">
                    <label>Taxa de Câmbio (Moedas/R$ 1)</label>
                    <input type="number" name="exchange_rate" step="0.01" min="0.01" value="100.00" required placeholder="Ex: 100">
                </div>
                <div class="admin-form-group">
                    <label>Troca Mínima (Moedas)</label>
                    <input type="number" name="min_exchange" min="1" value="100" required>
                </div>
            </div>

            <div class="admin-form-group">
                <label>Badge do Nível (Distintivo)</label>
                <input type="file" name="badge" accept="image/*">
            </div>

            <div class="admin-form-group">
                <label>Benefícios Desbloqueados</label>
                <div class="benefits-grid">
                    @foreach($availableBenefits as $key => $label)
                        <label class="benefit-item">
                            <input type="checkbox" name="benefits[]" value="{{ $key }}">
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px; border-top: 1px solid var(--border-color); padding-top: 20px;">
                <button type="button" onclick="closeModal('create-vip-modal')" style="padding: 10px 20px; background: #242428; border: 1px solid var(--border-color); border-radius: 8px; color: #fff; cursor: pointer; font-weight: 700; font-size: 13px;">Cancelar</button>
                <button type="submit" class="admin-btn-save" style="margin-top: 0; width: auto; padding: 10px 30px;">SALVAR NÍVEL</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit VIP Level Modal -->
<div id="edit-vip-modal" class="modal-overlay">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 15px;">
            <h4 style="margin: 0; color: #fff; font-family: var(--font-heading); font-weight: 800;">Editar Nível VIP</h4>
            <button type="button" onclick="closeModal('edit-vip-modal')" style="background: transparent; border: none; color: #9ca3af; cursor: pointer; font-size: 20px;"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form id="edit-vip-form" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 15px;">
                <div class="admin-form-group">
                    <label>Nível #</label>
                    <input type="number" name="level_number" id="edit-level_number" required>
                </div>
                <div class="admin-form-group">
                    <label>Nome do Nível</label>
                    <input type="text" name="name" id="edit-name" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="admin-form-group">
                    <label>XP Requerido (Wager)</label>
                    <input type="number" name="required_points" id="edit-required_points" required>
                </div>
                <div class="admin-form-group">
                    <label>Cashback (%)</label>
                    <input type="number" name="cashback_percent" id="edit-cashback_percent" step="0.01" min="0.00" max="100.00" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="admin-form-group">
                    <label>Taxa de Câmbio (Moedas/R$ 1)</label>
                    <input type="number" name="exchange_rate" id="edit-exchange_rate" step="0.01" min="0.01" required>
                </div>
                <div class="admin-form-group">
                    <label>Troca Mínima (Moedas)</label>
                    <input type="number" name="min_exchange" id="edit-min_exchange" min="1" required>
                </div>
            </div>

            <div class="admin-form-group">
                <label>Badge do Nível (Deixe vazio para manter o atual)</label>
                <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 8px;">
                    <img id="edit-badge-preview-img" src="" class="badge-preview" alt="Badge preview" style="display: none;">
                    <input type="file" name="badge" accept="image/*">
                </div>
            </div>

            <div class="admin-form-group">
                <label>Benefícios Desbloqueados</label>
                <div class="benefits-grid">
                    @foreach($availableBenefits as $key => $label)
                        <label class="benefit-item">
                            <input type="checkbox" name="benefits[]" class="edit-benefit-checkbox" value="{{ $key }}" id="checkbox-{{ $key }}">
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px; border-top: 1px solid var(--border-color); padding-top: 20px;">
                <button type="button" onclick="closeModal('edit-vip-modal')" style="padding: 10px 20px; background: #242428; border: 1px solid var(--border-color); border-radius: 8px; color: #fff; cursor: pointer; font-weight: 700; font-size: 13px;">Cancelar</button>
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
            document.getElementById('edit-level_number').value = data.level_number;
            document.getElementById('edit-name').value = data.name;
            document.getElementById('edit-required_points').value = data.required_points;
            document.getElementById('edit-exchange_rate').value = data.exchange_rate;
            document.getElementById('edit-min_exchange').value = data.min_exchange;
            document.getElementById('edit-cashback_percent').value = data.cashback_percent;

            // Reset all checkbox states
            document.querySelectorAll('.edit-benefit-checkbox').forEach(cb => {
                cb.checked = false;
            });

            // Set active checkbox states
            try {
                const activeBenefits = JSON.parse(data.benefits);
                if (Array.isArray(activeBenefits)) {
                    activeBenefits.forEach(key => {
                        const cb = document.getElementById('checkbox-' + key);
                        if (cb) cb.checked = true;
                    });
                }
            } catch(e) {
                console.error('Error parsing benefits JSON', e);
            }

            // Set badge preview
            const previewImg = document.getElementById('edit-badge-preview-img');
            if (data.badge_path) {
                previewImg.src = data.badge_path;
                previewImg.style.display = 'block';
            } else {
                previewImg.style.display = 'none';
            }

            const form = document.getElementById('edit-vip-form');
            form.action = data.url;
            
            openModal('edit-vip-modal');
        });
    });
</script>
@endsection
