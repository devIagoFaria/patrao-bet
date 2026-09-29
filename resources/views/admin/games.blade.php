@extends('layouts.admin')

@section('page_title', 'Gestão de Jogos')

@section('styles')
<style>
    .games-container {
        max-width: 1200px;
        margin: 0 auto;
        background: #121215;
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 32px;
    }
    .game-image-preview {
        width: 100%;
        max-width: 200px;
        height: 120px;
        border-radius: 12px;
        background-color: #0c0c0e;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px dashed var(--border-color);
        margin-top: 10px;
        overflow: hidden;
    }
    .game-image-preview img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    .game-table-img {
        width: 60px;
        height: 40px;
        border-radius: 6px;
        object-fit: contain;
        border: 1px solid var(--border-color);
        background: #0c0c0e;
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
        max-width: 640px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 28px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    .modal-content .admin-form-group label {
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        color: var(--text-secondary);
        margin-bottom: 6px;
        display: block;
    }
    .modal-content .admin-form-group input[type="text"],
    .modal-content .admin-form-group input[type="number"],
    .modal-content .admin-form-group input[type="file"],
    .modal-content .admin-form-group select {
        width: 100%;
        background: #0c0c0e;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 10px 12px;
        color: #fff;
        font-size: 13px;
        transition: border-color 0.2s ease;
    }
    .modal-content .admin-form-group input:focus,
    .modal-content .admin-form-group select:focus {
        outline: none;
        border-color: var(--color-yellow);
    }
    .modal-section-label {
        font-size: 10.5px;
        font-weight: 800;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        color: var(--color-yellow);
        margin: 22px 0 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .modal-section-label:first-child { margin-top: 0; }
    .modal-section-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--border-color);
    }
    .provider-search-box {
        background: rgba(248, 204, 27, 0.05);
        border: 1px solid rgba(248, 204, 27, 0.15);
        border-radius: 10px;
        padding: 16px;
    }
    .provider-search-results {
        margin-top: 10px;
        max-height: 260px;
        overflow-y: auto;
        border-radius: 8px;
        display: none;
    }
    .provider-search-result-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.15s ease;
    }
    .provider-search-result-item:hover {
        background: rgba(255, 255, 255, 0.05);
    }
    .provider-search-result-item img {
        width: 42px;
        height: 32px;
        object-fit: cover;
        border-radius: 5px;
        background: #0c0c0e;
        flex-shrink: 0;
    }
    .provider-search-result-item .psr-name {
        font-size: 12.5px;
        font-weight: 700;
        color: #fff;
    }
    .provider-search-result-item .psr-provider {
        font-size: 10.5px;
        color: var(--text-muted);
    }
    .provider-filled-badge {
        display: none;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        font-weight: 700;
        color: var(--color-green);
        margin-top: 8px;
    }
    .admin-pagination-container {
        margin-top: 30px;
        display: flex;
        justify-content: center;
    }
    .admin-pagination-container .pagination {
        display: flex;
        gap: 5px;
        list-style: none;
        padding: 0;
    }
    .admin-pagination-container .page-item .page-link {
        background: #161619;
        border: 1px solid var(--border-color);
        color: #fff;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
    }
    .admin-pagination-container .page-item.active .page-link {
        background: var(--color-yellow);
        color: #000;
        border-color: var(--color-yellow);
    }
    .admin-pagination-container .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .admin-pagination-container .page-item:not(.active):not(.disabled) .page-link:hover {
        background: #242428;
        border-color: #fff;
    }
</style>
@endsection

@section('content')
<div class="games-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 1px solid var(--border-color); padding-bottom: 20px;">
        <div>
            <h3 style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; color: #fff;">Gestão de Jogos</h3>
            <p style="font-size: 13px; color: var(--text-secondary); margin-top: 6px;">Adicione jogos manualmente ou importe diretamente da API PlayFiver ou MAX API GAMES.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <button class="admin-tab-btn active" onclick="switchMainTab('tab-list')" id="btn-tab-list">
                <i class="fa-solid fa-list"></i> Lista de Jogos
            </button>
            <button class="admin-tab-btn" onclick="switchMainTab('tab-providers')" id="btn-tab-providers">
                <i class="fa-solid fa-server"></i> Provedores (API)
            </button>
        </div>
    </div>

    <!-- MAIN TAB 1: Game List -->
    <div id="tab-list">
        <!-- Filters and Search -->
        <div style="background: #161619; border: 1px solid var(--border-color); border-radius: 12px; padding: 20px; margin-bottom: 25px;">
            <form action="{{ route('admin.games') }}" method="GET" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: flex-end;">
                <div class="admin-form-group" style="margin-bottom: 0;">
                    <label>Pesquisar Nome</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome do jogo...">
                </div>
                <div class="admin-form-group" style="margin-bottom: 0;">
                    <label>Provedor</label>
                    <select name="provider">
                        <option value="">Todos Provedores</option>
                        @foreach($providers as $prov)
                            <option value="{{ $prov }}" {{ request('provider') == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="admin-form-group" style="margin-bottom: 0;">
                    <label>Distribuição</label>
                    <select name="distribution">
                        <option value="">Todas</option>
                        <option value="playfiver" {{ request('distribution') == 'playfiver' ? 'selected' : '' }}>PlayFiver</option>
                        <option value="maxapigames" {{ request('distribution') == 'maxapigames' ? 'selected' : '' }}>MAX API GAMES</option>
                    </select>
                </div>
                <div class="admin-form-group" style="margin-bottom: 0;">
                    <label>Categoria</label>
                    <select name="category">
                        <option value="">Todas Categorias</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>{{ $cat->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="admin-btn-save" style="margin-top: 0; width: auto; padding: 10px 15px;">
                        <i class="fa-solid fa-magnifying-glass"></i> Filtrar
                    </button>
                    <a href="{{ route('admin.games') }}" class="btn-action btn-edit" style="padding: 10px 15px; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                        <i class="fa-solid fa-xmark"></i> Limpar
                    </a>
                </div>
            </form>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div style="display: flex; gap: 15px; align-items: center;">
                <div style="display: flex; align-items: center; gap: 10px; background: #161619; padding: 8px 15px; border-radius: 8px; border: 1px solid var(--border-color);">
                    <input type="checkbox" id="master-checkbox" style="width: 16px; height: 16px; cursor: pointer;">
                    <span style="font-size: 13px; color: #fff; font-weight: 600;">Selecionar Tudo</span>
                </div>
                
                <select id="bulk-action-select" style="background: #161619; border: 1px solid var(--border-color); color: #fff; padding: 8px 15px; border-radius: 8px; font-size: 13px; outline: none;">
                    <option value="">Ações em Massa</option>
                    <option value="activate">Ativar Selecionados</option>
                    <option value="deactivate">Desativar Selecionados</option>
                    <option value="delete">Excluir Selecionados</option>
                </select>
                <button type="button" onclick="applyBulkAction()" class="admin-btn-save" style="margin-top: 0; width: auto; padding: 8px 20px; font-size: 12px;">
                    Aplicar
                </button>
            </div>
            
            <button class="admin-btn-save" style="margin-top: 0; width: auto;" onclick="openModal('create-game-modal')">
                <i class="fa-solid fa-plus"></i> Adicionar Jogo Manual
            </button>
        </div>

        <form id="bulk-action-form" action="{{ route('admin.games.bulk') }}" method="POST" style="display: none;">
            @csrf
            <input type="hidden" name="action" id="bulk-action-input">
        </form>

        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 40px;"></th>
                        <th>Imagem</th>
                        <th>Nome</th>
                        <th>Provedor</th>
                        <th>Distribuição</th>
                        <th>Categoria</th>
                        <th>Status</th>
                        <th>Ordem</th>
                        <th style="text-align: right;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($games as $game)
                        <tr>
                            <td>
                                <input type="checkbox" class="game-checkbox" value="{{ $game->id }}" style="width: 16px; height: 16px; cursor: pointer;">
                            </td>
                            <td>
                                @if($game->image_path)
                                    <img src="{{ asset($game->image_path) }}" class="game-table-img" alt="">
                                @else
                                    <div class="game-table-img" style="display: flex; align-items: center; justify-content: center; background: #0c0c0e;">
                                        <i class="fa-solid fa-gamepad" style="font-size: 14px; color: #333;"></i>
                                    </div>
                                @endif
                            </td>
                            <td style="font-weight: 700; color: #fff;">{{ $game->title }}</td>
                            <td>{{ $game->subtitle ?? 'N/A' }}</td>
                            <td>
                                <span style="text-transform: uppercase; font-size: 11px; font-weight: 700; color: #9ca3af;">
                                    {{ $game->distribution ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span style="background: rgba(16, 185, 129, 0.1); color: var(--color-green); padding: 2px 8px; border-radius: 4px; font-size: 11px; font-weight: 700;">
                                    {{ $game->group }}
                                </span>
                            </td>
                            <td>
                                @if($game->is_active)
                                    <span style="color: var(--color-green); font-size: 11px; font-weight: 800;"><i class="fa-solid fa-circle" style="font-size: 8px; margin-right: 4px;"></i> ATIVO</span>
                                @else
                                    <span style="color: var(--color-red); font-size: 11px; font-weight: 800;"><i class="fa-solid fa-circle" style="font-size: 8px; margin-right: 4px;"></i> INATIVO</span>
                                @endif
                            </td>
                            <td>{{ $game->order }}</td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 8px; justify-content: flex-end;">
                                    <button class="btn-action btn-edit open-edit-btn" 
                                        data-title="{{ $game->title }}"
                                         data-code="{{ $game->action_value }}"
                                         data-provider="{{ $game->subtitle }}"
                                         data-distribution="{{ $game->distribution }}"
                                         data-group="{{ $game->group }}"
                                         data-order="{{ $game->order }}"
                                        data-active="{{ $game->is_active }}"
                                        data-original="{{ $game->is_original }}"
                                        data-image="{{ $game->image_path ? asset($game->image_path) : '' }}"
                                        data-url="{{ route('admin.games.update', $game->id) }}">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form action="{{ route('admin.games.destroy', $game->id) }}" method="POST" onsubmit="return confirm('Deseja realmente excluir este jogo?')">
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
                            <td colspan="9" style="text-align: center; padding: 40px; color: var(--text-muted);">Nenhum jogo encontrado com os filtros aplicados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="admin-pagination-container">
            {{ $games->links('pagination::bootstrap-4') }}
        </div>
    </div>

    <!-- MAIN TAB 2: API Providers -->
    <div id="tab-providers" style="display: none;">
        <div style="background: #161619; border: 1px solid var(--border-color); border-radius: 12px; padding: 25px; margin-bottom: 25px;">
            <div style="display: flex; gap: 20px; align-items: flex-end;">
                <div class="admin-form-group" style="margin-bottom: 0; min-width: 220px;">
                    <label>API</label>
                    <select id="api-distribution-select" onchange="onApiDistributionChange()">
                        <option value="playfiver">PlayFiver</option>
                        <option value="maxapigames">MAX API GAMES</option>
                    </select>
                </div>
                <div class="admin-form-group" style="flex: 1; margin-bottom: 0;">
                    <label>Selecione um Provedor</label>
                    <select id="api-provider-select" onchange="fetchApiGames(this.value)">
                        <option value="">Carregando provedores...</option>
                    </select>
                </div>
                <button type="button" class="btn-create-trigger" onclick="fetchApiProviders()" style="margin-bottom: 0; padding: 12px 20px;">
                    <i class="fa-solid fa-rotate"></i> Atualizar Lista
                </button>
            </div>
        </div>

        <!-- Bulk Import actions -->
        <div id="api-bulk-actions-bar" style="background: #161619; border: 1px solid var(--border-color); border-radius: 12px; padding: 20px; margin-bottom: 25px; display: none; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <input type="checkbox" id="master-api-checkbox" style="width: 18px; height: 18px; cursor: pointer;" onchange="toggleAllApiCheckboxes(this)">
                <span style="font-size: 13px; color: #fff; font-weight: 700;">Selecionar Todos</span>
            </div>
            <div style="display: flex; gap: 15px; align-items: center; flex: 1; justify-content: flex-end; min-width: 300px;">
                <div class="admin-form-group" style="margin-bottom: 0; min-width: 220px;">
                    <select id="bulk-import-category" style="width: 100%;">
                        <option value="">Selecione a Categoria para Importar</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}">{{ $cat->title }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="button" onclick="applyApiBulkImport()" class="admin-btn-save" style="margin-top: 0; width: auto; padding: 10px 25px; white-space: nowrap;">
                    <i class="fa-solid fa-download"></i> Importar Selecionados
                </button>
            </div>
        </div>

        <div id="api-games-status" style="text-align: center; padding: 20px; color: var(--text-secondary); display: none;">
            <i class="fa-solid fa-spinner fa-spin"></i> Buscando jogos na API...
        </div>

        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 40px;"></th>
                        <th>Capa</th>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Provedor</th>
                        <th>Original</th>
                        <th style="text-align: right;">Ações</th>
                    </tr>
                </thead>
                <tbody id="api-games-list">
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: var(--text-muted);">Selecione um provedor para listar os jogos da API.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Game Modal -->
<div id="create-game-modal" class="modal-overlay">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 15px;">
            <h4 style="margin: 0; color: #fff; font-family: var(--font-heading); font-weight: 800;">Adicionar Novo Jogo</h4>
            <button type="button" onclick="closeModal('create-game-modal')" style="background: transparent; border: none; color: #9ca3af; cursor: pointer; font-size: 20px;"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <div class="provider-search-box">
            <label style="font-size: 11.5px; font-weight: 700; letter-spacing: 0.3px; text-transform: uppercase; color: var(--color-yellow); margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-magnifying-glass"></i> Preencher automaticamente da API
            </label>
            <div style="display: grid; grid-template-columns: 130px 1fr; gap: 10px;">
                <select id="psr-distribution" style="background: #0c0c0e; border: 1px solid var(--border-color); border-radius: 8px; padding: 10px 8px; color: #fff; font-size: 12.5px;">
                    <option value="playfiver">PlayFiver</option>
                    <option value="maxapigames">MAX API GAMES</option>
                </select>
                <input type="text" id="psr-query" placeholder="Digite o nome do jogo, ex: Fortune Tiger" autocomplete="off" style="background: #0c0c0e; border: 1px solid var(--border-color); border-radius: 8px; padding: 10px 12px; color: #fff; font-size: 13px;">
            </div>
            <div id="psr-status" style="font-size: 11px; color: var(--text-muted); margin-top: 8px; display: none;">
                <i class="fa-solid fa-spinner fa-spin"></i> Buscando...
            </div>
            <div id="psr-results" class="provider-search-results"></div>
            <div id="psr-filled-badge" class="provider-filled-badge">
                <i class="fa-solid fa-circle-check"></i> Preenchido a partir da API — confira os campos abaixo antes de salvar
            </div>
        </div>

        <form action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data" id="create-game-form">
            @csrf
            <div class="modal-section-label">Detalhes do Jogo</div>

            <div class="admin-form-group">
                <label>Nome do Jogo</label>
                <input type="text" name="title" required placeholder="Ex: Aviator">
            </div>

            <div class="admin-form-group">
                <label>Código do Jogo (Identificador)</label>
                <input type="text" name="action_value" required placeholder="Ex: aviator">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="admin-form-group">
                    <label>Provedor</label>
                    <input type="text" name="subtitle" placeholder="Ex: PG SOFT">
                </div>
                <div class="admin-form-group">
                    <label>Distribuição (API)</label>
                    <select name="distribution">
                        <option value="playfiver">PlayFiver</option>
                        <option value="maxapigames">MAX API GAMES</option>
                        <option value="outros">Outros</option>
                    </select>
                </div>
            </div>

            <div class="modal-section-label">Organização</div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="admin-form-group">
                    <label>Categoria / Seção</label>
                    <select name="group" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}">{{ $cat->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="admin-form-group">
                    <label>Ordem de Exibição</label>
                    <input type="number" name="order" value="10" required>
                </div>
            </div>

            <div class="admin-form-group">
                <label>Imagem do Jogo (Thumbnail)</label>
                <input type="file" name="image" accept="image/*" onchange="previewImage(this, 'create-preview')">
                <div class="game-image-preview" id="create-preview">
                    <i class="fa-solid fa-image" style="color: #333; font-size: 32px;"></i>
                </div>
            </div>

            <div class="admin-form-group" style="margin-top: 15px; display: flex; gap: 20px;">
                <label class="admin-checkbox-container" style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="is_active" value="1" checked style="width: auto;">
                    <span style="font-size: 13px; color: #fff; font-weight: 600;">Ativar jogo</span>
                </label>
                <label class="admin-checkbox-container" style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="is_original" value="1" checked style="width: auto;">
                    <span style="font-size: 13px; color: #fff; font-weight: 600;">Jogo Original (API)</span>
                </label>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px; border-top: 1px solid var(--border-color); padding-top: 20px;">
                <button type="button" onclick="closeModal('create-game-modal')" style="padding: 10px 20px; background: #242428; border: 1px solid var(--border-color); border-radius: 8px; color: #fff; cursor: pointer; font-weight: 700; font-size: 13px;">Cancelar</button>
                <button type="submit" class="admin-btn-save" style="margin-top: 0; width: auto; padding: 10px 30px;">CADASTRAR JOGO</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Game Modal -->
<div id="edit-game-modal" class="modal-overlay">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 15px;">
            <h4 style="margin: 0; color: #fff; font-family: var(--font-heading); font-weight: 800;">Editar Jogo</h4>
            <button type="button" onclick="closeModal('edit-game-modal')" style="background: transparent; border: none; color: #9ca3af; cursor: pointer; font-size: 20px;"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <form id="edit-game-form" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="admin-form-group">
                <label>Nome do Jogo</label>
                <input type="text" name="title" id="edit-title" required>
            </div>

            <div class="admin-form-group">
                <label>Código do Jogo</label>
                <input type="text" name="action_value" id="edit-code" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="admin-form-group">
                    <label>Provedor</label>
                    <input type="text" name="subtitle" id="edit-provider">
                </div>
                <div class="admin-form-group">
                    <label>Distribuição (API)</label>
                    <select name="distribution" id="edit-distribution">
                        <option value="maxigames">Maxigames</option>
                        <option value="playfiver">Playfiver</option>
                        <option value="outros">Outros</option>
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="admin-form-group">
                    <label>Categoria / Seção</label>
                    <select name="group" id="edit-group" required>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}">{{ $cat->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="admin-form-group">
                    <label>Ordem</label>
                    <input type="number" name="order" id="edit-order" required>
                </div>
            </div>

            <div class="admin-form-group">
                <label>Substituir Imagem</label>
                <input type="file" name="image" accept="image/*" onchange="previewImage(this, 'edit-preview')">
                <div class="game-image-preview" id="edit-preview">
                    <img id="edit-img-current" src="#" alt="">
                </div>
            </div>

            <div class="admin-form-group" style="margin-top: 15px; display: flex; gap: 20px;">
                <label class="admin-checkbox-container" style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="is_active" id="edit-active" value="1" style="width: auto;">
                    <span style="font-size: 13px; color: #fff; font-weight: 600;">Jogo Ativo</span>
                </label>
                <label class="admin-checkbox-container" style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <input type="checkbox" name="is_original" id="edit-original" value="1" style="width: auto;">
                    <span style="font-size: 13px; color: #fff; font-weight: 600;">Jogo Original (API)</span>
                </label>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 25px; border-top: 1px solid var(--border-color); padding-top: 20px;">
                <button type="button" onclick="closeModal('edit-game-modal')" style="padding: 10px 20px; background: #242428; border: 1px solid var(--border-color); border-radius: 8px; color: #fff; cursor: pointer; font-weight: 700; font-size: 13px;">Cancelar</button>
                <button type="submit" class="admin-btn-save" style="margin-top: 0; width: auto; padding: 10px 30px;">SALVAR ALTERAÇÕES</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const API_ROUTES = {
        playfiver: {
            providers: '{{ route("admin.playfiver.providers") }}',
            games: '{{ route("admin.playfiver.games") }}'
        },
        maxapigames: {
            providers: '{{ route("admin.maxapigames.providers") }}',
            games: '{{ route("admin.maxapigames.games") }}'
        }
    };

    function getApiDistribution() {
        const select = document.getElementById('api-distribution-select');
        return select ? select.value : 'playfiver';
    }

    function onApiDistributionChange() {
        document.getElementById('api-games-list').innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 40px; color: var(--text-muted);">Selecione um provedor para listar os jogos da API.</td></tr>';
        const actionsBar = document.getElementById('api-bulk-actions-bar');
        if (actionsBar) actionsBar.style.display = 'none';
        fetchApiProviders();
    }

    // Master checkbox logic
    document.getElementById('master-checkbox').addEventListener('change', function() {
        const isChecked = this.checked;
        document.querySelectorAll('.game-checkbox').forEach(cb => {
            cb.checked = isChecked;
        });
    });

    function applyBulkAction() {
        const action = document.getElementById('bulk-action-select').value;
        if (!action) {
            alert('Por favor, selecione uma ação.');
            return;
        }

        const selectedIds = Array.from(document.querySelectorAll('.game-checkbox:checked')).map(cb => cb.value);
        if (selectedIds.length === 0) {
            alert('Por favor, selecione pelo menos um jogo.');
            return;
        }

        if (action === 'delete' && !confirm(`Deseja realmente excluir ${selectedIds.length} jogos selecionados?`)) {
            return;
        }

        const form = document.getElementById('bulk-action-form');
        document.getElementById('bulk-action-input').value = action;
        
        // Add selected IDs to the form
        selectedIds.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            form.appendChild(input);
        });

        form.submit();
    }

    function openModal(modalId) {
        document.getElementById(modalId).style.display = 'flex';

        if (modalId === 'create-game-modal') {
            const psrQuery = document.getElementById('psr-query');
            const psrResults = document.getElementById('psr-results');
            const psrBadge = document.getElementById('psr-filled-badge');
            const form = document.getElementById('create-game-form');
            if (psrQuery) psrQuery.value = '';
            if (psrResults) { psrResults.style.display = 'none'; psrResults.innerHTML = ''; }
            if (psrBadge) psrBadge.style.display = 'none';
            if (form) {
                form.reset();
                const hiddenImg = document.getElementById('import_image_url');
                if (hiddenImg) hiddenImg.remove();
                document.getElementById('create-preview').innerHTML = '<i class="fa-solid fa-image" style="color: #333; font-size: 32px;"></i>';
            }
        }
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    function switchMainTab(tabId) {
        document.getElementById('tab-list').style.display = tabId === 'tab-list' ? 'block' : 'none';
        document.getElementById('tab-providers').style.display = tabId === 'tab-providers' ? 'block' : 'none';
        
        document.getElementById('btn-tab-list').classList.toggle('active', tabId === 'tab-list');
        document.getElementById('btn-tab-providers').classList.toggle('active', tabId === 'tab-providers');

        if (tabId === 'tab-providers') {
            fetchApiProviders();
        }
    }

    async function fetchApiProviders() {
        const select = document.getElementById('api-provider-select');
        const distribution = getApiDistribution();
        select.innerHTML = '<option value="">Carregando provedores...</option>';

        try {
            const response = await fetch(API_ROUTES[distribution].providers);
            const result = await response.json();

            if (result.status === 1) {
                select.innerHTML = '<option value="">Selecione um provedor</option>';
                result.data.forEach(provider => {
                    const opt = document.createElement('option');
                    opt.value = provider.id;
                    opt.textContent = provider.name;
                    select.appendChild(opt);
                });
            } else {
                select.innerHTML = '<option value="">Erro ao carregar provedores</option>';
            }
        } catch (error) {
            select.innerHTML = '<option value="">Erro de conexão</option>';
        }
    }

    async function fetchApiGames(providerId) {
        if (!providerId) return;

        const status = document.getElementById('api-games-status');
        const list = document.getElementById('api-games-list');
        const actionsBar = document.getElementById('api-bulk-actions-bar');
        const distribution = getApiDistribution();

        if (actionsBar) actionsBar.style.display = 'none';
        document.getElementById('master-api-checkbox').checked = false;
        status.style.display = 'block';
        list.innerHTML = '';

        try {
            const response = await fetch(`${API_ROUTES[distribution].games}?provider_id=${providerId}`);
            const result = await response.json();
            
            status.style.display = 'none';
            
            if (result.status === 1) {
                if (result.data.length === 0) {
                    list.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 40px;">Nenhum jogo encontrado para este provedor.</td></tr>';
                    return;
                }
                
                if (actionsBar) actionsBar.style.display = 'flex';
                
                result.data.forEach(game => {
                    const tr = document.createElement('tr');
                    const gameName = game.game_name || game.name || 'Sem nome';
                    const providerName = (game.provider && typeof game.provider === 'object') ? game.provider.name : (game.provider || 'N/A');
                    const gameCode = game.game_code || game.code || 'N/A';
                    const gameImage = game.image_url || game.banner || game.image || game.icon || '';
                    
                    tr.innerHTML = `
                        <td>
                            <input type="checkbox" class="api-game-checkbox" data-title="${gameName.replace(/"/g, '&quot;')}" data-code="${gameCode}" data-provider="${providerName.replace(/"/g, '&quot;')}" data-image="${gameImage}" style="width: 16px; height: 16px; cursor: pointer;">
                        </td>
                        <td>
                            ${gameImage ? `<img src="${gameImage}" class="game-table-img" style="width: 50px; height: 35px; object-fit: contain;">` : '<i class="fa-solid fa-image" style="color: #333;"></i>'}
                        </td>
                        <td><code>${gameCode}</code></td>
                        <td style="font-weight: 700; color: #fff;">${gameName}</td>
                        <td>${providerName}</td>
                        <td>${game.game_original ? '<span style="color: var(--color-green);">Sim</span>' : 'Não'}</td>
                        <td style="text-align: right;">
                            <button class="admin-btn-save" style="margin-top: 0; width: auto; padding: 5px 15px; font-size: 11px;"
                                onclick="importGame('${gameName.replace(/'/g, "\\'")}', '${gameCode}', '${providerName.replace(/'/g, "\\'")}', '${gameImage}', ${game.game_original ? 1 : 0}, '${distribution}')">
                                <i class="fa-solid fa-download"></i> IMPORTAR
                            </button>
                        </td>
                    `;
                    list.appendChild(tr);
                });
            } else {
                list.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 40px; color: var(--color-red);">Erro ao buscar jogos.</td></tr>';
            }
        } catch (error) {
            status.style.display = 'none';
            list.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 40px; color: var(--color-red);">Erro de conexão.</td></tr>';
        }
    }

    function toggleAllApiCheckboxes(master) {
        document.querySelectorAll('.api-game-checkbox').forEach(cb => {
            cb.checked = master.checked;
        });
    }

    async function applyApiBulkImport() {
        const category = document.getElementById('bulk-import-category').value;
        if (!category) {
            alert('Por favor, selecione uma categoria.');
            return;
        }

        const checkedBoxes = document.querySelectorAll('.api-game-checkbox:checked');
        if (checkedBoxes.length === 0) {
            alert('Por favor, selecione pelo menos um jogo para importar.');
            return;
        }

        if (!confirm(`Deseja importar ${checkedBoxes.length} jogos selecionados para a categoria "${category}"?`)) {
            return;
        }

        // Prepare games payload
        const distribution = getApiDistribution();
        const games = [];
        checkedBoxes.forEach(cb => {
            games.push({
                title: cb.getAttribute('data-title'),
                action_value: cb.getAttribute('data-code'),
                subtitle: cb.getAttribute('data-provider'),
                image_url: cb.getAttribute('data-image')
            });
        });

        // Show loading state or block UI
        const btn = document.querySelector('[onclick="applyApiBulkImport()"]');
        const originalHtml = btn.innerHTML;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Importando...';
        btn.disabled = true;

        try {
            const response = await fetch("{{ route('admin.games.bulk-import') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    group: category,
                    games: games,
                    distribution: distribution
                })
            });

            const result = await response.json();
            if (response.ok && result.success) {
                alert(result.message);
                window.location.reload();
            } else {
                alert(result.message || 'Erro ao importar jogos.');
            }
        } catch (error) {
            console.error(error);
            alert('Erro de conexão com o servidor. Tente novamente.');
        } finally {
            btn.innerHTML = originalHtml;
            btn.disabled = false;
        }
    }

    function importGame(name, code, provider, image, isOriginal, distribution) {
        // Preencher o modal de criação
        document.querySelector('#create-game-modal input[name="title"]').value = name;
        document.querySelector('#create-game-modal input[name="action_value"]').value = code;
        document.querySelector('#create-game-modal input[name="subtitle"]').value = provider;
        document.querySelector('#create-game-modal select[name="distribution"]').value = distribution || 'playfiver';
        document.querySelector('#create-game-modal input[name="is_original"]').checked = isOriginal === 1;
        
        // Se tiver imagem da API, podemos tentar mostrar um preview ou salvar a URL (se o backend aceitar)
        if (image) {
            const preview = document.getElementById('create-preview');
            preview.innerHTML = `<img src="${image}" alt="Preview" style="max-width: 100%; height: 100%; object-fit: contain;">`;
            
            // Adicionar um campo oculto para a URL da imagem se o backend for configurado para baixar/usar externa
            let hiddenImg = document.getElementById('import_image_url');
            if (!hiddenImg) {
                hiddenImg = document.createElement('input');
                hiddenImg.type = 'hidden';
                hiddenImg.name = 'import_image_url';
                hiddenImg.id = 'import_image_url';
                document.querySelector('#create-game-modal form').appendChild(hiddenImg);
            }
            hiddenImg.value = image;
        }
        
        openModal('create-game-modal');
    }

    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // --- Busca de jogo por nome direto na API (preenche o modal "Adicionar Jogo") ---
    let psrTimeout = null;
    const psrQueryInput = document.getElementById('psr-query');
    const psrDistributionSelect = document.getElementById('psr-distribution');

    if (psrQueryInput) {
        psrQueryInput.addEventListener('input', function() {
            clearTimeout(psrTimeout);
            const query = this.value.trim();
            const resultsBox = document.getElementById('psr-results');
            const statusBox = document.getElementById('psr-status');

            if (query.length < 2) {
                resultsBox.style.display = 'none';
                resultsBox.innerHTML = '';
                statusBox.style.display = 'none';
                return;
            }

            statusBox.style.display = 'block';
            resultsBox.style.display = 'none';

            psrTimeout = setTimeout(() => {
                const distribution = psrDistributionSelect.value;
                fetch(`{{ route('admin.games.search-provider') }}?distribution=${distribution}&q=${encodeURIComponent(query)}`)
                    .then(r => r.json())
                    .then(result => {
                        statusBox.style.display = 'none';
                        resultsBox.innerHTML = '';

                        if (!result.data || result.data.length === 0) {
                            resultsBox.style.display = 'block';
                            resultsBox.innerHTML = '<div style="padding: 10px; font-size: 12px; color: var(--text-muted); text-align: center;">Nenhum jogo encontrado</div>';
                            return;
                        }

                        result.data.forEach(game => {
                            const item = document.createElement('div');
                            item.className = 'provider-search-result-item';
                            const img = game.image || '';
                            item.innerHTML = `
                                ${img ? `<img src="${img}" alt="">` : '<div style="width:42px;height:32px;border-radius:5px;background:#0c0c0e;flex-shrink:0;"></div>'}
                                <div>
                                    <div class="psr-name">${game.game_name}</div>
                                    <div class="psr-provider">${game.provider || ''} · ${game.game_code}</div>
                                </div>
                            `;
                            item.addEventListener('click', () => fillFromProviderGame(game, distribution));
                            resultsBox.appendChild(item);
                        });
                        resultsBox.style.display = 'block';
                    })
                    .catch(() => {
                        statusBox.style.display = 'none';
                        resultsBox.style.display = 'block';
                        resultsBox.innerHTML = '<div style="padding: 10px; font-size: 12px; color: var(--color-red); text-align: center;">Erro ao buscar. Tente novamente.</div>';
                    });
            }, 350);
        });
    }

    function fillFromProviderGame(game, distribution) {
        const form = document.getElementById('create-game-form');
        form.querySelector('input[name="title"]').value = game.game_name;
        form.querySelector('input[name="action_value"]').value = game.game_code;
        form.querySelector('input[name="subtitle"]').value = game.provider || '';
        form.querySelector('select[name="distribution"]').value = distribution;
        form.querySelector('input[name="is_original"]').checked = !!game.game_original;

        if (game.image) {
            const preview = document.getElementById('create-preview');
            preview.innerHTML = `<img src="${game.image}" alt="Preview" style="max-width: 100%; height: 100%; object-fit: contain;">`;

            let hiddenImg = document.getElementById('import_image_url');
            if (!hiddenImg) {
                hiddenImg = document.createElement('input');
                hiddenImg.type = 'hidden';
                hiddenImg.name = 'import_image_url';
                hiddenImg.id = 'import_image_url';
                form.appendChild(hiddenImg);
            }
            hiddenImg.value = game.image;
        }

        document.getElementById('psr-results').style.display = 'none';
        document.getElementById('psr-filled-badge').style.display = 'flex';
        document.getElementById('psr-query').value = game.game_name;
    }

    document.querySelectorAll('.open-edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const data = this.dataset;
            document.getElementById('edit-title').value = data.title;
            document.getElementById('edit-code').value = data.code;
            document.getElementById('edit-provider').value = data.provider || '';
            document.getElementById('edit-distribution').value = data.distribution || 'playfiver';
            document.getElementById('edit-group').value = data.group;
            document.getElementById('edit-order').value = data.order;
            document.getElementById('edit-active').checked = data.active === '1';
            document.getElementById('edit-original').checked = data.original === '1';
            
            const currentImg = document.getElementById('edit-img-current');
            const previewContainer = document.getElementById('edit-preview');
            if (data.image) {
                currentImg.src = data.image;
                currentImg.style.display = 'block';
                previewContainer.innerHTML = '';
                previewContainer.appendChild(currentImg);
            } else {
                previewContainer.innerHTML = '<i class="fa-solid fa-image" style="color: #333; font-size: 32px;"></i>';
            }

            const form = document.getElementById('edit-game-form');
            form.action = data.url;
            
            openModal('edit-game-modal');
        });
    });

    // Close modals on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModal('create-game-modal');
            closeModal('edit-game-modal');
        }
    });
</script>
@endsection
