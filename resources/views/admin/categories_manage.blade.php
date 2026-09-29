@extends('layouts.admin')

@section('page_title', 'Gerenciar Estrutura de Categorias')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<style>
    .manage-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 20px;
        margin-top: 25px;
    }
    .category-manage-card {
        background-color: #161619;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.25s ease;
    }
    .category-manage-card:hover {
        border-color: rgba(248, 204, 27, 0.3);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    .category-card-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }
    .category-icon-wrapper {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        background-color: #0c0c0e;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border-color);
        font-size: 20px;
        color: var(--color-yellow);
        overflow: hidden;
        flex-shrink: 0;
    }
    .category-icon-wrapper svg {
        width: 24px;
        height: 24px;
        color: var(--color-green);
    }
    .category-details {
        flex-grow: 1;
        min-w: 0;
    }
    .category-title {
        font-family: var(--font-heading);
        font-size: 14.5px;
        font-weight: 700;
        color: #fff;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .category-meta {
        font-size: 11px;
        color: var(--text-secondary);
        margin-top: 4px;
    }
    .category-status-badge {
        font-size: 9.5px;
        padding: 2px 8px;
        border-radius: 20px;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-block;
        margin-top: 4px;
    }
    .badge-active {
        background-color: rgba(16, 185, 129, 0.1);
        color: var(--color-green);
        border: 1px solid rgba(16, 185, 129, 0.2);
    }
    .badge-inactive {
        background-color: rgba(239, 68, 68, 0.1);
        color: var(--color-red);
        border: 1px solid rgba(239, 68, 68, 0.2);
    }
    .btn-edit-toggle:hover {
        background: #2a2a2e !important;
    }
    .drag-handle {
        cursor: move;
        color: var(--text-muted);
        padding: 5px;
        margin-right: 10px;
        font-size: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s ease;
    }
    .category-manage-card:hover .drag-handle {
        color: var(--color-yellow);
    }
    .sortable-ghost {
        opacity: 0.4;
        border: 2px dashed var(--color-yellow) !important;
    }
    .category-info-list {
        font-size: 12px;
        color: var(--text-secondary);
        margin-bottom: 18px;
        border-top: 1px solid rgba(255, 255, 255, 0.03);
        padding-top: 12px;
    }
    .category-info-list div {
        margin-bottom: 6px;
        display: flex;
        justify-content: space-between;
    }
    .category-info-list strong {
        color: #fff;
    }
    .category-actions {
        display: flex;
        gap: 10px;
    }
    .btn-edit-cat {
        background: #242428;
        border: 1px solid var(--border-color);
        color: #fff;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        flex: 1;
        text-align: center;
        transition: all 0.2s ease;
    }
    .btn-edit-cat:hover {
        background: #2e2e34;
        border-color: var(--color-yellow);
    }
    .edit-cat-panel {
        display: none;
        border-top: 1px solid var(--border-color);
        padding-top: 18px;
        margin-top: 18px;
        animation: slideDown 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
    .btn-create-trigger {
        background-color: var(--color-yellow);
        color: #000;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 25px;
        transition: all 0.2s ease;
    }
    .btn-create-trigger:hover {
        background-color: var(--color-yellow-hover);
        transform: translateY(-1px);
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection

@section('content')
<div class="admin-card-table" style="max-width: 1200px; margin: 0 auto; background: #121215; border: 1px solid var(--border-color); border-radius: 16px; padding: 32px;">
    
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; border-bottom: 1px solid var(--border-color); padding-bottom: 20px;">
        <div>
            <h3 style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; color: #fff;">Estrutura de Categorias / Seções</h3>
            <p style="font-size: 13px; color: var(--text-secondary); margin-top: 6px;">Adicione novas seções de jogos na página inicial, altere o título das seções existentes ou exclua as que não for usar.</p>
        </div>
        
        <a href="{{ route('admin.categories') }}" style="background: rgba(255, 255, 255, 0.03); border: 1px solid var(--border-color); color: #fff; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.2s ease;">
            <i class="fa-solid fa-arrow-left"></i> Voltar para Jogos
        </a>
    </div>

    <!-- Add Category Trigger -->
    <button class="btn-create-trigger" onclick="toggleCreateBox('create-cat-box')">
        <i class="fa-solid fa-plus"></i> Criar Nova Categoria / Seção
    </button>

    <!-- Create Category Box -->
    <div style="background-color: #161619; border: 1px solid var(--border-color); border-radius: 12px; padding: 24px; margin-bottom: 30px; display: none;" id="create-cat-box">
        <h4 style="font-family: var(--font-heading); font-size: 14px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 20px; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-folder-plus"></i> Nova Seção de Jogos
        </h4>

        <form action="{{ route('admin.categories_manage.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 20px;">
                <div class="admin-form-group">
                    <label for="new-title">Título da Categoria / Seção</label>
                    <input type="text" id="new-title" name="title" required placeholder="Ex: Jogos da Galera">
                </div>
                <div class="admin-form-group">
                    <label for="new-layout">Tipo de Layout</label>
                    <select id="new-layout" name="layout_type" required>
                        <option value="recommended">Cassino Recomendado (Grid Tradicional)</option>
                        <option value="weekly">Mais Jogados da Semana (Card Retangular com Emoji)</option>
                        <option value="live">Cassino Ao Vivo (Grade com Fundo Colorido e Tag)</option>
                    </select>
                </div>
                <div class="admin-form-group">
                    <label for="new-order">Ordem de Exibição</label>
                    <input type="number" id="new-order" name="order" value="10" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-top: 15px; border-top: 1px dashed rgba(255,255,255,0.05); padding-top: 20px;">
                <div class="admin-form-group">
                    <label for="new-icon">Classe do Ícone (FontAwesome) ou SVG Inline</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="text" id="new-icon" name="icon_class" placeholder="Ex: fa-solid fa-gamepad text-green ou <svg>...</svg>" style="flex: 1;">
                        <button type="button" class="btn-edit-cat" onclick="openIconPicker('new-icon')" style="width: auto; padding: 0 15px; flex-shrink: 0; white-space: nowrap;">
                            <i class="fa-solid fa-icons"></i> Procurar Ícone
                        </button>
                    </div>
                    <p class="admin-form-help">Usado no título da seção. Você pode colar a classe do FontAwesome ou um elemento SVG completo.</p>
                </div>
                <div class="admin-form-group" style="display: flex; flex-direction: column; gap: 10px; padding-top: 25px;">
                    <label class="admin-checkbox-container" style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_active" value="1" checked style="width: auto;">
                        <span style="font-size: 13px; color: #fff; font-weight: 600;">Ativar Imediatamente</span>
                    </label>
                    <label class="admin-checkbox-container" style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="show_ver_todos" value="1" checked style="width: auto;">
                        <span style="font-size: 13px; color: #fff; font-weight: 600;">Mostrar botão "Ver Todos"</span>
                    </label>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; border-top: 1px solid var(--border-color); padding-top: 15px;">
                <button type="button" class="btn-edit-toggle" onclick="toggleCreateBox('create-cat-box')" style="padding: 10px 20px; background: #242428; border: 1px solid var(--border-color); border-radius: 6px; cursor: pointer;">Cancelar</button>
                <button type="submit" class="admin-btn-save" style="margin-top: 0; padding: 10px 25px; font-size: 13px; font-weight: 700; background: var(--color-yellow); color: #000; border-radius: 6px;">CRIAR SEÇÃO</button>
            </div>
        </form>
    </div>

    <!-- Categories Grid List -->
    <div class="manage-grid" id="categories-sortable">
        @foreach($categories as $cat)
            @php
                $protected = ['sidebar', 'sports'];
                $isProtected = in_array($cat->slug, $protected);
            @endphp
            <div class="category-manage-card" data-id="{{ $cat->id }}">
                <div>
                    <!-- Header -->
                    <div class="category-card-header">
                        <div class="drag-handle" title="Arraste para reordenar">
                            <i class="fa-solid fa-grip-vertical"></i>
                        </div>
                        <div class="category-icon-wrapper">
                            @if($cat->icon_class && strpos($cat->icon_class, '<svg') !== false)
                                {!! $cat->icon_class !!}
                            @elseif($cat->icon_class)
                                <i class="{{ $cat->icon_class }}"></i>
                            @else
                                <i class="fa-regular fa-folder"></i>
                            @endif
                        </div>
                        <div class="category-details">
                            <div class="category-title" title="{{ $cat->title }}">{{ $cat->title }}</div>
                            <span class="category-status-badge {{ $cat->is_active ? 'badge-active' : 'badge-inactive' }}">
                                {{ $cat->is_active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                    </div>

                    <!-- Info -->
                    <div class="category-info-list">
                        <div>
                            <span>Slug / Identificador:</span>
                            <strong><code>{{ $cat->slug }}</code></strong>
                        </div>
                        <div>
                            <span>Tipo de Layout:</span>
                            <strong>
                                @if($cat->layout_type === 'sidebar') Barra Lateral
                                @elseif($cat->layout_type === 'sports') Esportes
                                @elseif($cat->layout_type === 'recommended') Cassino Recomendado
                                @elseif($cat->layout_type === 'weekly') Jogos da Semana
                                @elseif($cat->layout_type === 'live') Cassino Ao Vivo
                                @else {{ $cat->layout_type }}
                                @endif
                            </strong>
                        </div>
                        <div>
                            <span>Ordem na Home:</span>
                            <strong>{{ $cat->order }}</strong>
                        </div>
                        <div>
                            <span>Jogos Associados:</span>
                            <strong>{{ \App\Models\CategoryButton::where('group', $cat->slug)->count() }}</strong>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div>
                    <div class="category-actions">
                        <button type="button" onclick="toggleEditPanel('edit-panel-{{ $cat->id }}')" class="btn-edit-cat">
                            <i class="fa-solid fa-pen-to-square"></i> Editar Estrutura
                        </button>
                        
                        @if($isProtected)
                            <button type="button" disabled style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); color: var(--text-muted); padding: 8px; border-radius: 6px; font-size: 12px; font-weight: 700; flex: 1; cursor: not-allowed;" title="Sistema Protegido">
                                <i class="fa-solid fa-lock"></i> Protegido
                            </button>
                        @else
                            <form action="{{ route('admin.categories_manage.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('ATENÇÃO: Ao excluir esta categoria, TODOS os jogos vinculados a ela serão deletados permanentemente e a seção sumirá da home! Deseja continuar?')" style="flex:1; display:flex;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: var(--color-red); padding: 8px; border-radius: 6px; font-size: 12px; font-weight: 700; width: 100%; cursor: pointer; transition: all 0.2s ease;">
                                    <i class="fa-regular fa-trash-can"></i> Excluir Seção
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- Collapsible Edit Panel -->
                    <div class="edit-cat-panel" id="edit-panel-{{ $cat->id }}">
                        <form action="{{ route('admin.categories_manage.update', $cat->id) }}" method="POST">
                            @csrf
                            <div class="admin-form-group">
                                <label for="title-{{ $cat->id }}">Título da Categoria / Seção</label>
                                <input type="text" id="title-{{ $cat->id }}" name="title" value="{{ $cat->title }}" required>
                            </div>

                            <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 15px; margin-top: 12px;">
                                <div class="admin-form-group">
                                    <label for="layout-{{ $cat->id }}">Tipo de Layout</label>
                                    @if($isProtected)
                                        <input type="hidden" name="layout_type" value="{{ $cat->layout_type }}">
                                        <select disabled style="cursor: not-allowed; background: rgba(0,0,0,0.2);">
                                            <option selected>{{ $cat->layout_type === 'sidebar' ? 'Barra Lateral' : 'Navegação de Esportes' }}</option>
                                        </select>
                                    @else
                                        <select id="layout-{{ $cat->id }}" name="layout_type" required>
                                            <option value="recommended" {{ $cat->layout_type === 'recommended' ? 'selected' : '' }}>Cassino Recomendado (Grid)</option>
                                            <option value="weekly" {{ $cat->layout_type === 'weekly' ? 'selected' : '' }}>Jogos da Semana (Emoji)</option>
                                            <option value="live" {{ $cat->layout_type === 'live' ? 'selected' : '' }}>Cassino Ao Vivo (Background)</option>
                                        </select>
                                    @endif
                                </div>
                                <div class="admin-form-group">
                                    <label for="order-{{ $cat->id }}">Ordem</label>
                                    <input type="number" id="order-{{ $cat->id }}" name="order" value="{{ $cat->order }}" required>
                                </div>
                            </div>

                            <div class="admin-form-group" style="margin-top: 12px;">
                                <label for="icon-{{ $cat->id }}">Classe do Ícone / Elemento SVG</label>
                                <div style="display: flex; gap: 10px;">
                                    <input type="text" id="icon-{{ $cat->id }}" name="icon_class" value="{{ $cat->icon_class }}" style="flex: 1;">
                                    <button type="button" class="btn-edit-cat" onclick="openIconPicker('icon-{{ $cat->id }}')" style="width: auto; padding: 0 15px; flex-shrink: 0; white-space: nowrap;">
                                        <i class="fa-solid fa-icons"></i> Procurar
                                    </button>
                                </div>
                            </div>

                            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px; border-top: 1px dashed rgba(255,255,255,0.05); padding-top: 15px;">
                                <div style="display: flex; gap: 16px;">
                                    <label class="admin-checkbox-container" style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                        <input type="checkbox" name="is_active" value="1" {{ $cat->is_active ? 'checked' : '' }} style="width: auto;">
                                        <span style="font-size: 12px; color: #fff; font-weight: 600;">Ativo</span>
                                    </label>
                                    <label class="admin-checkbox-container" style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                        <input type="checkbox" name="show_ver_todos" value="1" {{ $cat->show_ver_todos ? 'checked' : '' }} style="width: auto;">
                                        <span style="font-size: 12px; color: #fff; font-weight: 600;">Mostrar "Ver Todos"</span>
                                    </label>
                                </div>

                                <button type="submit" class="admin-btn-save" style="margin-top: 0; padding: 8px 20px; font-size: 11.5px; font-weight: 700; width: auto; background: var(--color-yellow); color: #000; border-radius: 6px;">
                                    SALVAR ALTERAÇÕES
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Icon Picker Modal -->
<div id="icon-picker-modal" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(0,0,0,0.8); backdrop-filter: blur(5px); align-items: center; justify-content: center; padding: 20px;">
    <div style="background: #161619; border: 1px solid var(--border-color); border-radius: 16px; width: 100%; max-width: 600px; max-height: 80vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
        <!-- Modal Header -->
        <div style="padding: 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
            <h4 style="margin: 0; color: #fff; font-family: var(--font-heading); font-weight: 800;">Selecionar Ícone</h4>
            <button type="button" onclick="closeIconPicker()" style="background: transparent; border: none; color: #9ca3af; cursor: pointer; font-size: 20px;"><i class="fa-solid fa-xmark"></i></button>
        </div>

        <!-- Modal Search -->
        <div style="padding: 15px 20px; border-bottom: 1px solid rgba(255,255,255,0.05);">
            <div style="position: relative;">
                <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #4b5563;"></i>
                <input type="text" id="icon-search" onkeyup="filterIcons()" placeholder="Buscar ícones (ex: sports, casino, money...)" style="width: 100%; background: #0c0c0e; border: 1px solid var(--border-color); border-radius: 10px; padding: 12px 15px 12px 45px; color: #fff; outline: none; font-size: 14px;">
            </div>
        </div>

        <!-- Modal Content (Icon Grid) -->
        <div id="icon-grid" style="padding: 20px; overflow-y: auto; flex: 1; display: grid; grid-template-columns: repeat(auto-fill, minmax(60px, 1fr)); gap: 15px; min-height: 300px;">
            <!-- Icons will be injected here by JS -->
        </div>

        <!-- Modal Footer -->
        <div style="padding: 15px 20px; border-top: 1px solid var(--border-color); background: #0c0c0e; text-align: center; font-size: 11px; color: #4b5563;">
            Clique em um ícone para selecioná-lo. Suporte para FontAwesome 6.
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    let currentTargetInputId = '';

    const iconList = [
        // Casino & Gambling
        { class: 'fa-solid fa-dice', tags: 'casino gambling game play bet' },
        { class: 'fa-solid fa-dice-five', tags: 'casino gambling game play bet' },
        { class: 'fa-solid fa-clover', tags: 'luck casino luck gambling' },
        { class: 'fa-solid fa-diamond', tags: 'casino cards gambling' },
        { class: 'fa-solid fa-heart', tags: 'casino cards gambling' },
        { class: 'fa-solid fa-spade', tags: 'casino cards gambling' },
        { class: 'fa-solid fa-club', tags: 'casino cards gambling' },
        { class: 'fa-solid fa-crown', tags: 'vip winner king casino' },
        { class: 'fa-solid fa-trophy', tags: 'winner reward prize payouts' },
        { class: 'fa-solid fa-gem', tags: 'diamond jewel prize casino' },
        { class: 'fa-solid fa-star', tags: 'favorite feature winner' },
        { class: 'fa-solid fa-coins', tags: 'money profit balance payouts' },
        { class: 'fa-solid fa-money-bill-wave', tags: 'money cash profit bet' },
        { class: 'fa-solid fa-wallet', tags: 'money cash profit balance' },
        { class: 'fa-solid fa-ticket', tags: 'lotto raffle raspadinha' },
        { class: 'fa-solid fa-gift', tags: 'bonus reward gift present' },
        { class: 'fa-solid fa-fire', tags: 'hot weekly popular' },
        { class: 'fa-solid fa-bolt', tags: 'fast instant live' },
        { class: 'fa-solid fa-rocket', tags: 'fast crash aviator' },
        { class: 'fa-solid fa-plane', tags: 'crash aviator flight' },
        
        // Sports
        { class: 'fa-solid fa-futbol', tags: 'sports football soccer ball' },
        { class: 'fa-solid fa-basketball', tags: 'sports basketball ball' },
        { class: 'fa-solid fa-football', tags: 'sports american football ball' },
        { class: 'fa-solid fa-baseball-bat-ball', tags: 'sports baseball bat ball' },
        { class: 'fa-solid fa-volleyball', tags: 'sports volleyball ball' },
        { class: 'fa-solid fa-hockey-puck', tags: 'sports hockey ice' },
        { class: 'fa-solid fa-table-tennis-paddle-ball', tags: 'sports tennis pingpong' },
        { class: 'fa-solid fa-golf-ball-tee', tags: 'sports golf' },
        { class: 'fa-solid fa-medal', tags: 'sports winner medal' },
        { class: 'fa-solid fa-flag-checkered', tags: 'sports racing winner' },
        { class: 'fa-solid fa-bicycle', tags: 'sports cycling bike' },
        { class: 'fa-solid fa-motorcycle', tags: 'sports racing moto' },
        { class: 'fa-solid fa-car', tags: 'sports racing auto' },
        { class: 'fa-solid fa-stopwatch', tags: 'sports timer live' },
        
        // Interface & General
        { class: 'fa-solid fa-house', tags: 'home start' },
        { class: 'fa-solid fa-user', tags: 'profile account' },
        { class: 'fa-solid fa-users', tags: 'affiliates group' },
        { class: 'fa-solid fa-gear', tags: 'settings config' },
        { class: 'fa-solid fa-bell', tags: 'notifications' },
        { class: 'fa-solid fa-chart-line', tags: 'stats metrics profit' },
        { class: 'fa-solid fa-chart-pie', tags: 'stats metrics' },
        { class: 'fa-solid fa-tower-broadcast', tags: 'live streaming signal' },
        { class: 'fa-solid fa-gamepad', tags: 'game play playstation' },
        { class: 'fa-solid fa-mobile-screen', tags: 'mobile app' },
        { class: 'fa-solid fa-shield-halved', tags: 'security protected' },
        { class: 'fa-solid fa-lock', tags: 'security protected private' },
        { class: 'fa-solid fa-unlock', tags: 'security public' },
        { class: 'fa-solid fa-circle-info', tags: 'info help' },
        { class: 'fa-solid fa-circle-question', tags: 'help question' },
        { class: 'fa-solid fa-circle-exclamation', tags: 'alert warning' },
        { class: 'fa-solid fa-thumbs-up', tags: 'like recommended' },
        { class: 'fa-solid fa-eye', tags: 'view watch live' },
        { class: 'fa-solid fa-magnifying-glass', tags: 'search find' },
        { class: 'fa-solid fa-plus', tags: 'add create' },
        { class: 'fa-solid fa-bars', tags: 'menu sidebar' },
        { class: 'fa-solid fa-bars-staggered', tags: 'menu sidebar' },
        { class: 'fa-solid fa-network-wired', tags: 'network category group' },
        { class: 'fa-solid fa-layer-group', tags: 'category group sections' },
        { class: 'fa-solid fa-folder', tags: 'category folder' },
        { class: 'fa-solid fa-folder-open', tags: 'category folder' },
        { class: 'fa-solid fa-image', tags: 'banner image photo' },
        { class: 'fa-solid fa-images', tags: 'banner images photos' },
        { class: 'fa-solid fa-palette', tags: 'personalization colors style' },
        { class: 'fa-solid fa-bullhorn', tags: 'promotion announce' },
        { class: 'fa-solid fa-hand-holding-dollar', tags: 'cashback bonus' },
        { class: 'fa-solid fa-piggy-bank', tags: 'save money cofre' },
        { class: 'fa-solid fa-sack-dollar', tags: 'money profit bigwin' },
        { class: 'fa-solid fa-landmark', tags: 'bank gateway' },
        { class: 'fa-solid fa-credit-card', tags: 'payment card' },
        { class: 'fa-brands fa-pix', tags: 'pix payment brazil' },
        { class: 'fa-brands fa-whatsapp', tags: 'support contact social' },
        { class: 'fa-brands fa-telegram', tags: 'support contact social' },
        { class: 'fa-brands fa-instagram', tags: 'social media' },
    ];

    function openIconPicker(inputId) {
        currentTargetInputId = inputId;
        const modal = document.getElementById('icon-picker-modal');
        modal.style.display = 'flex';
        renderIcons();
        document.getElementById('icon-search').focus();
    }

    function closeIconPicker() {
        document.getElementById('icon-picker-modal').style.display = 'none';
        document.getElementById('icon-search').value = '';
    }

    function renderIcons(filteredList = iconList) {
        const grid = document.getElementById('icon-grid');
        grid.innerHTML = '';

        filteredList.forEach(icon => {
            const div = document.createElement('div');
            div.style.cssText = 'width: 60px; height: 60px; background: #0c0c0e; border: 1px solid rgba(255,255,255,0.05); border-radius: 10px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s ease; font-size: 20px; color: #9ca3af;';
            div.innerHTML = `<i class="${icon.class}"></i>`;
            div.title = icon.class;
            
            div.onmouseover = () => {
                div.style.borderColor = 'var(--color-yellow)';
                div.style.color = '#fff';
                div.style.transform = 'scale(1.1)';
                div.style.background = '#1f1f23';
            };
            
            div.onmouseout = () => {
                div.style.borderColor = 'rgba(255,255,255,0.05)';
                div.style.color = '#9ca3af';
                div.style.transform = 'scale(1)';
                div.style.background = '#0c0c0e';
            };
            
            div.onclick = () => {
                const input = document.getElementById(currentTargetInputId);
                if (input) {
                    input.value = icon.class;
                }
                closeIconPicker();
            };
            
            grid.appendChild(div);
        });

        if (filteredList.length === 0) {
            grid.innerHTML = '<div style="grid-column: 1 / -1; text-align: center; color: #4b5563; padding: 40px;">Nenhum ícone encontrado para esta busca.</div>';
        }
    }

    function filterIcons() {
        const query = document.getElementById('icon-search').value.toLowerCase();
        const filtered = iconList.filter(icon => 
            icon.class.toLowerCase().includes(query) || 
            icon.tags.toLowerCase().includes(query)
        );
        renderIcons(filtered);
    }

    // Close modal on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeIconPicker();
    });

    // Close modal on click outside
    document.getElementById('icon-picker-modal').onclick = (e) => {
        if (e.target.id === 'icon-picker-modal') closeIconPicker();
    };

    function toggleCreateBox(boxId) {
        const box = document.getElementById(boxId);
        if (box) {
            box.style.display = box.style.display === 'none' ? 'block' : 'none';
        }
    }

    function toggleEditPanel(panelId) {
        const panel = document.getElementById(panelId);
        const allPanels = document.querySelectorAll('.edit-cat-panel');
        
        allPanels.forEach(p => {
            if (p.id !== panelId) {
                p.style.display = 'none';
            }
        });

        if (panel) {
            panel.style.display = panel.style.display === 'block' ? 'none' : 'block';
        }
    }

    async function reorderCategory(id, direction) {
        // Manteve para compatibilidade com os botões individuais se necessário
        try {
            const response = await fetch("{{ route('admin.categories_manage.reorder') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ id, direction })
            });

            const result = await response.json();
            if (result.success) {
                window.location.reload();
            } else {
                alert(result.message || 'Erro ao reordenar categoria.');
            }
        } catch (error) {
            console.error(error);
            alert('Erro de conexão com o servidor.');
        }
    }

    // Inicializar Drag and Drop
    document.addEventListener('DOMContentLoaded', function() {
        const el = document.getElementById('categories-sortable');
        if (el) {
            Sortable.create(el, {
                animation: 150,
                handle: '.drag-handle',
                ghostClass: 'sortable-ghost',
                onEnd: async function() {
                    const order = Array.from(el.querySelectorAll('.category-manage-card'))
                        .map(card => card.getAttribute('data-id'));

                    try {
                        const response = await fetch("{{ route('admin.categories_manage.bulk_reorder') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({ order })
                        });

                        const result = await response.json();
                        if (!result.success) {
                            alert(result.message || 'Erro ao salvar nova ordem.');
                            window.location.reload();
                        }
                    } catch (error) {
                        console.error(error);
                        alert('Erro de conexão ao salvar nova ordem.');
                        window.location.reload();
                    }
                }
            });
        }
    });
</script>
@endsection
