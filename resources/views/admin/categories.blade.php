@extends('layouts.admin')

@section('page_title', 'Gerenciar Categorias e Botões')

@section('styles')
<style>
    /* Tabs Navigation */
    .admin-tabs-nav {
        display: flex;
        gap: 12px;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 30px;
        padding-bottom: 12px;
        overflow-x: auto;
    }
    .admin-tab-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 700;
        color: var(--text-secondary);
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid var(--border-color);
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    .admin-tab-btn:hover {
        color: var(--text-primary);
        background: rgba(255, 255, 255, 0.06);
    }
    .admin-tab-btn.active {
        background: var(--color-yellow);
        color: #000;
        border-color: var(--color-yellow);
    }

    /* Grid layout */
    .categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 20px;
        margin-bottom: 40px;
    }

    /* Cards */
    .category-item-card {
        background-color: #161619;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: all 0.25s ease;
    }
    .category-item-card:hover {
        border-color: rgba(248, 204, 27, 0.3);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }
    .category-item-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }
    .category-item-icon-wrapper {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        background-color: #0c0c0e;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border-color);
        overflow: hidden;
        flex-shrink: 0;
    }
    .category-item-icon-wrapper img {
        max-width: 80%;
        max-height: 80%;
        object-fit: contain;
    }
    .category-item-icon-wrapper i {
        font-size: 24px;
        color: var(--color-yellow);
    }
    .category-item-icon-wrapper .weekly-emoji-preview {
        font-size: 32px;
    }
    .category-item-title-area {
        flex-grow: 1;
    }
    .category-item-title {
        font-family: var(--font-heading);
        font-size: 15px;
        font-weight: 700;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .category-item-badge {
        font-size: 10px;
        padding: 2px 8px;
        border-radius: 20px;
        font-weight: 700;
        text-transform: uppercase;
        margin-top: 4px;
        display: inline-block;
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
    .category-item-info {
        font-size: 12px;
        color: var(--text-secondary);
        margin-bottom: 15px;
        line-height: 1.5;
        border-top: 1px solid rgba(255, 255, 255, 0.03);
        padding-top: 12px;
    }
    .category-item-info div {
        margin-bottom: 4px;
    }
    .category-item-info strong {
        color: #fff;
    }

    /* Actions */
    .category-actions {
        display: flex;
        gap: 10px;
    }
    .btn-edit-toggle {
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
    .btn-edit-toggle:hover {
        background: #2e2e34;
        border-color: var(--color-yellow);
    }

    /* Form editing panel */
    .edit-form-panel {
        display: none;
        border-top: 1px solid var(--border-color);
        padding-top: 18px;
        margin-top: 18px;
        animation: slideDown 0.3s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Create Panel Trigger Button */
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
</style>
@endsection

@section('content')
<div class="admin-card-table" style="max-width: 1200px; margin: 0 auto; background: #121215; border: 1px solid var(--border-color); border-radius: 16px; padding: 32px;">
    
    <!-- Header with Actions -->
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 25px; border-bottom: 1px solid var(--border-color); padding-bottom: 20px;">
        <div>
            <h3 style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; color: #fff;">Categorias e Jogos do Sistema</h3>
            <p style="font-size: 13px; color: var(--text-secondary); margin-top: 6px;">Gerencie dinamicamente a barra lateral, os esportes da home, os jogos recomendados e a lista semanal.</p>
        </div>
        
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.categories_manage') }}" style="background: var(--color-yellow); color: #000; padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 700; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.2s ease;">
                <i class="fa-solid fa-folder-gear"></i> Gerenciar Categorias/Seções
            </a>

            <form action="{{ route('admin.categories.reset') }}" method="POST" onsubmit="return confirm('Deseja realmente redefinir todos os botões e categorias para os padrões originais de fábrica?')">
                @csrf
                <button type="submit" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: var(--color-red); padding: 10px 18px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-arrow-rotate-left"></i> Restaurar Padrões
                </button>
            </form>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="admin-tabs-nav">
        @foreach($categories as $index => $cat)
            <button type="button" onclick="switchCategoryTab('tab-{{ $cat->slug }}')" id="btn-tab-{{ $cat->slug }}" class="admin-tab-btn {{ $index === 0 ? 'active' : '' }}">
                @if($cat->icon_class && strpos($cat->icon_class, '<svg') !== false)
                    {!! $cat->icon_class !!}
                @elseif($cat->icon_class)
                    <i class="{{ $cat->icon_class }}"></i>
                @else
                    <i class="fa-regular fa-folder-open"></i>
                @endif
                {{ $cat->title }}
            </button>
        @endforeach
    </div>

    <!-- TABS CONTAINER -->
    @foreach($categories as $index => $cat)
        @php
            $btnQuery = \App\Models\CategoryButton::where('group', $cat->slug)->orderBy('order')->get();
        @endphp
        <div id="tab-{{ $cat->slug }}" style="{{ $index === 0 ? '' : 'display: none;' }}">
            
            <!-- Add New Item Trigger -->
            <button class="btn-create-trigger" onclick="toggleCreateBox('create-box-{{ $cat->slug }}')">
                <i class="fa-solid fa-plus"></i> Cadastrar Item em "{{ $cat->title }}"
            </button>

            <!-- Collapsible Creation Box -->
            <div style="background-color: #161619; border: 1px solid var(--border-color); border-radius: 12px; padding: 24px; margin-bottom: 30px; display: none;" id="create-box-{{ $cat->slug }}">
                <h4 style="font-family: var(--font-heading); font-size: 14px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 20px; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-plus-circle"></i> Novo Cadastro
                </h4>

                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="group" value="{{ $cat->slug }}">

                    <div style="display: grid; grid-template-columns: 1.5fr 1fr; gap: 20px;">
                        <div class="admin-form-group">
                            <label for="new-title-{{ $cat->slug }}">Título / Nome</label>
                            <input type="text" id="new-title-{{ $cat->slug }}" name="title" required placeholder="Ex: Fortune Rabbit">
                        </div>
                        <div class="admin-form-group">
                            <label for="new-order-{{ $cat->slug }}">Ordem de Exibição</label>
                            <input type="number" id="new-order-{{ $cat->slug }}" name="order" value="0" required>
                        </div>
                    </div>

                    <!-- Custom Metadata fields based on layout_type of the target category -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-top: 15px;">
                        @if($cat->layout_type === 'weekly' || $cat->layout_type === 'recommended')
                            <div class="admin-form-group">
                                <label for="new-badge-{{ $cat->slug }}">Jogadores Online / Curtidas</label>
                                <input type="text" id="new-badge-{{ $cat->slug }}" name="badge_value" placeholder="Ex: 77">
                            </div>
                        @elseif($cat->layout_type === 'live')
                            <div class="admin-form-group">
                                <label for="new-subtitle-{{ $cat->slug }}">Estilo de Fundo (CSS)</label>
                                <select id="new-subtitle-{{ $cat->slug }}" name="subtitle">
                                    <option value="live-bg-1">Fundo 1 (Vermelho/Roxo)</option>
                                    <option value="live-bg-2">Fundo 2 (Azul/Ciano)</option>
                                    <option value="live-bg-3">Fundo 3 (Laranja/Amarelo)</option>
                                    <option value="live-bg-4">Fundo 4 (Verde/Azul)</option>
                                </select>
                            </div>
                            <div class="admin-form-group">
                                <label for="new-badge-{{ $cat->slug }}">Tag do Card</label>
                                <input type="text" id="new-badge-{{ $cat->slug }}" name="badge_value" placeholder="Ex: LIVE GAME ou AO VIVO" required>
                            </div>
                            <input type="hidden" name="extra_info" value="">
                        @else
                            <div class="admin-form-group">
                                <label for="new-subtitle-{{ $cat->slug }}">Subtítulo</label>
                                <input type="text" id="new-subtitle-{{ $cat->slug }}" name="subtitle" placeholder="Subtítulo opcional">
                            </div>
                            <div class="admin-form-group">
                                <label for="new-badge-{{ $cat->slug }}">Badge/Etiqueta</label>
                                <input type="text" id="new-badge-{{ $cat->slug }}" name="badge_value" placeholder="Texto curto (ex: Novo)">
                            </div>
                            <div class="admin-form-group">
                                <label for="new-info-{{ $cat->slug }}">Informação Extra</label>
                                <input type="text" id="new-info-{{ $cat->slug }}" name="extra_info" placeholder="Texto adicional">
                            </div>
                        @endif
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 20px; margin-top: 15px;">
                        <div class="admin-form-group">
                            <label for="new-action-type-{{ $cat->slug }}">Tipo de Ação</label>
                            <select id="new-action-type-{{ $cat->slug }}" name="action_type" onchange="updateActionHelp(this.value, 'new-action-help-{{ $cat->slug }}')" required>
                                <option value="play">Abre Jogo (Chave do Jogo)</option>
                                <option value="link">Abre Link (URL / Âncora)</option>
                                <option value="scroll">Rola até Seção (ID)</option>
                                <option value="alert">Mostra Alerta (Mensagem)</option>
                                <option value="none">Sem Ação</option>
                            </select>
                        </div>
                        <div class="admin-form-group">
                            <label for="new-action-value-{{ $cat->slug }}">Valor da Ação</label>
                            <input type="text" id="new-action-value-{{ $cat->slug }}" name="action_value" placeholder="Ex: fortune_tiger">
                            <span class="admin-form-help" id="new-action-help-{{ $cat->slug }}">Chave do jogo (ex: mines ou fortune_tiger)</span>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 15px; border-top: 1px dashed rgba(255,255,255,0.05); padding-top: 20px; align-items: start;">
                        <!-- Icon configurations -->
                        <div class="admin-form-group">
                            @if($cat->layout_type === 'weekly')
                                <label for="new-icon-{{ $cat->slug }}">Emoji do Card</label>
                                <input type="text" id="new-icon-{{ $cat->slug }}" name="icon_class" placeholder="Ex: 🐰">
                            @else
                                <label for="new-icon-{{ $cat->slug }}">Classe do Ícone (FontAwesome)</label>
                                <input type="text" id="new-icon-{{ $cat->slug }}" name="icon_class" placeholder="Ex: fa-solid fa-soccer-ball">
                            @endif
                            <p class="admin-form-help">Usado caso não queira enviar uma imagem customizada ao lado.</p>
                        </div>

                        <!-- Image File with Live Preview -->
                        <div class="admin-form-group">
                            <label for="new-image-{{ $cat->slug }}">Imagem Customizada</label>
                            <input type="file" id="new-image-{{ $cat->slug }}" name="image" accept="image/*" onchange="previewCategoryUpload(this, 'new-preview-container-{{ $cat->slug }}', 'new-preview-img-{{ $cat->slug }}')">
                            <p class="admin-form-help">Imagem transparente (PNG recomendado).</p>
                            
                            <div id="new-preview-container-{{ $cat->slug }}" style="display: none; margin-top: 12px; text-align: center; border: 1px dashed var(--border-color); padding: 10px; border-radius: 8px; background-color: #0c0c0e;">
                                <p style="font-size: 10px; color: var(--text-muted); margin-bottom: 6px; font-weight: 700; text-transform: uppercase;">Visualização da Imagem</p>
                                <img id="new-preview-img-{{ $cat->slug }}" src="#" alt="Nova Imagem" style="max-height: 50px; max-width: 100%; object-fit: contain;">
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 20px; border-top: 1px solid var(--border-color); padding-top: 15px;">
                        <label class="admin-checkbox-container" style="display: flex; align-items: center; gap: 8px; cursor: pointer; user-select: none;">
                            <input type="checkbox" name="is_active" value="1" checked style="width: auto; cursor: pointer;">
                            <span style="font-size: 12px; color: #fff; font-weight: 600;">Ativar Imediatamente</span>
                        </label>
                        
                        <div style="display: flex; gap: 10px;">
                            <button type="button" class="btn-edit-toggle" onclick="toggleCreateBox('create-box-{{ $cat->slug }}')" style="padding: 10px 20px;">Cancelar</button>
                            <button type="submit" class="admin-btn-save" style="margin-top: 0; padding: 10px 25px; font-size: 13px; font-weight: 700; background: var(--color-yellow); color: #000; border-radius: 6px;">CADASTRAR ITEM</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- List Grid -->
            <div class="categories-grid">
                @forelse($btnQuery as $btn)
                    <div class="category-item-card" id="card-{{ $btn->id }}">
                        <div>
                            <!-- Header Preview -->
                            <div class="category-item-header">
                                <div class="category-item-icon-wrapper">
                                    @if($btn->image_path)
                                        <img src="{{ asset($btn->image_path) }}" alt="{{ $btn->title }}">
                                    @elseif(($cat->layout_type === 'weekly' || $cat->layout_type === 'live') && $btn->icon_class)
                                        <span class="weekly-emoji-preview">{{ $btn->icon_class }}</span>
                                    @elseif($btn->icon_class)
                                        <i class="{{ $btn->icon_class }}"></i>
                                    @else
                                        <i class="fa-regular fa-folder-open"></i>
                                    @endif
                                </div>
                                <div class="category-item-title-area">
                                    <div class="category-item-title">{{ $btn->title }}</div>
                                    <span class="category-item-badge {{ $btn->is_active ? 'badge-active' : 'badge-inactive' }}">
                                        {{ $btn->is_active ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Card Info -->
                            <div class="category-item-info">
                                <div><strong>Tipo de Ação:</strong> 
                                    @if($btn->action_type === 'link') Link
                                    @elseif($btn->action_type === 'scroll') Rolar até Elemento
                                    @elseif($btn->action_type === 'alert') Pop-up
                                    @elseif($btn->action_type === 'play') Jogar
                                    @else Sem Ação
                                    @endif
                                </div>
                                @if($btn->action_type !== 'none' && $btn->action_value)
                                    <div style="word-break: break-all;"><strong>Valor da Ação:</strong> <code>{{ $btn->action_value }}</code></div>
                                @endif
                                @if($cat->layout_type === 'weekly' || $cat->layout_type === 'recommended')
                                    <div><strong>Status:</strong> {{ $btn->badge_value }}</div>
                                @elseif($cat->layout_type === 'live')
                                    <div><strong>Classe de Fundo:</strong> <code>{{ $btn->subtitle }}</code></div>
                                    <div><strong>Tag:</strong> {{ $btn->badge_value }}</div>
                                @else
                                    @if($btn->badge_value) <div><strong>Badge:</strong> {{ $btn->badge_value }}</div> @endif
                                @endif
                                @if($cat->layout_type !== 'weekly' && $btn->icon_class)
                                    <div><strong>Classe de Ícone:</strong> <code>{{ $btn->icon_class }}</code></div>
                                @endif
                                <div><strong>Ordem:</strong> {{ $btn->order }}</div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div>
                            <div class="category-actions">
                                <button type="button" onclick="toggleEditPanel('edit-panel-{{ $btn->id }}')" class="btn-edit-toggle">
                                    <i class="fa-solid fa-pen-to-square"></i> Editar
                                </button>
                                
                                <form action="{{ route('admin.categories.destroy', $btn->id) }}" method="POST" onsubmit="return confirm('Deseja realmente excluir este item?')" style="flex:1; display:flex;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: var(--color-red); padding: 8px; border-radius: 6px; font-size: 12px; font-weight: 700; width: 100%; cursor: pointer; transition: all 0.2s ease;">
                                        <i class="fa-regular fa-trash-can"></i> Excluir
                                    </button>
                                </form>
                            </div>

                            <!-- Collapsible Edit Form -->
                            <div class="edit-form-panel" id="edit-panel-{{ $btn->id }}">
                                <form action="{{ route('admin.categories.update', $btn->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="admin-form-group">
                                        <label for="title-{{ $btn->id }}">Título / Nome</label>
                                        <input type="text" id="title-{{ $btn->id }}" name="title" value="{{ $btn->title }}" required>
                                    </div>

                                    <!-- Group-specific edit fields -->
                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 12px;">
                                        @if($cat->layout_type === 'weekly' || $cat->layout_type === 'recommended')
                                            <div class="admin-form-group">
                                                <label for="badge-{{ $btn->id }}">Jogadores / Curtidas</label>
                                                <input type="text" id="badge-{{ $btn->id }}" name="badge_value" value="{{ $btn->badge_value }}">
                                            </div>
                                        @elseif($cat->layout_type === 'live')
                                            <div class="admin-form-group">
                                                <label for="subtitle-{{ $btn->id }}">Estilo de Fundo (CSS)</label>
                                                <select id="subtitle-{{ $btn->id }}" name="subtitle">
                                                    <option value="live-bg-1" {{ $btn->subtitle === 'live-bg-1' ? 'selected' : '' }}>Fundo 1 (Vermelho/Roxo)</option>
                                                    <option value="live-bg-2" {{ $btn->subtitle === 'live-bg-2' ? 'selected' : '' }}>Fundo 2 (Azul/Ciano)</option>
                                                    <option value="live-bg-3" {{ $btn->subtitle === 'live-bg-3' ? 'selected' : '' }}>Fundo 3 (Laranja/Amarelo)</option>
                                                    <option value="live-bg-4" {{ $btn->subtitle === 'live-bg-4' ? 'selected' : '' }}>Fundo 4 (Verde/Azul)</option>
                                                </select>
                                            </div>
                                            <div class="admin-form-group">
                                                <label for="badge_value-{{ $btn->id }}">Tag do Card</label>
                                                <input type="text" id="badge_value-{{ $btn->id }}" name="badge_value" value="{{ $btn->badge_value }}">
                                            </div>
                                        @else
                                            <div class="admin-form-group">
                                                <label for="subtitle-{{ $btn->id }}">Subtítulo</label>
                                                <input type="text" id="subtitle-{{ $btn->id }}" name="subtitle" value="{{ $btn->subtitle }}">
                                            </div>
                                            <div class="admin-form-group">
                                                <label for="badge_value-{{ $btn->id }}">Badge</label>
                                                <input type="text" id="badge_value-{{ $btn->id }}" name="badge_value" value="{{ $btn->badge_value }}">
                                            </div>
                                        @endif
                                    </div>

                                    @if($cat->layout_type === 'weekly' || $cat->layout_type === 'recommended' || $cat->layout_type === 'payouts')
                                        <div class="admin-form-group" style="margin-top: 12px;">
                                            <label for="extra_info-{{ $btn->id }}">Valor do Pagamento</label>
                                            <input type="text" id="extra_info-{{ $btn->id }}" name="extra_info" value="{{ $btn->extra_info }}">
                                        </div>
                                    @elseif($cat->layout_type !== 'live')
                                        <div class="admin-form-group" style="margin-top: 12px;">
                                            <label for="extra_info-{{ $btn->id }}">Informação Extra</label>
                                            <input type="text" id="extra_info-{{ $btn->id }}" name="extra_info" value="{{ $btn->extra_info }}">
                                        </div>
                                    @endif

                                    <div class="admin-form-group" style="margin-top: 12px;">
                                        @if($cat->layout_type === 'weekly')
                                            <label for="icon_class-{{ $btn->id }}">Emoji do Card</label>
                                            <input type="text" id="icon_class-{{ $btn->id }}" name="icon_class" value="{{ $btn->icon_class }}">
                                        @else
                                            <label for="icon_class-{{ $btn->id }}">Classe do Ícone (FontAwesome)</label>
                                            <input type="text" id="icon_class-{{ $btn->id }}" name="icon_class" value="{{ $btn->icon_class }}">
                                        @endif
                                    </div>

                                    <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 15px; margin-top: 12px;">
                                        <div class="admin-form-group">
                                            <label for="action_type-{{ $btn->id }}">Tipo de Ação</label>
                                            <select id="action_type-{{ $btn->id }}" name="action_type" onchange="updateActionHelp(this.value, 'action-help-{{ $btn->id }}')" required>
                                                <option value="play" {{ $btn->action_type === 'play' ? 'selected' : '' }}>Abre Jogo (Chave)</option>
                                                <option value="link" {{ $btn->action_type === 'link' ? 'selected' : '' }}>Abre Link</option>
                                                <option value="scroll" {{ $btn->action_type === 'scroll' ? 'selected' : '' }}>Rola até Seção</option>
                                                <option value="alert" {{ $btn->action_type === 'alert' ? 'selected' : '' }}>Mostra Alerta</option>
                                                <option value="none" {{ $btn->action_type === 'none' ? 'selected' : '' }}>Sem Ação</option>
                                            </select>
                                        </div>
                                        <div class="admin-form-group">
                                            <label for="action_value-{{ $btn->id }}">Valor da Ação</label>
                                            <input type="text" id="action_value-{{ $btn->id }}" name="action_value" value="{{ $btn->action_value }}">
                                        </div>
                                    </div>
                                    <span class="admin-form-help" id="action-help-{{ $btn->id }}" style="margin-top: 4px; display: block;">
                                        @if($btn->action_type === 'link') Digite a URL ou âncora (ex: https://site.com ou #secao)
                                        @elseif($btn->action_type === 'scroll') Digite o ID do elemento da página (ex: cassino-section)
                                        @elseif($btn->action_type === 'alert') Digite o texto que aparecerá na mensagem
                                        @elseif($btn->action_type === 'play') Chave do jogo (ex: mines ou fortune_tiger)
                                        @else Nenhuma ação
                                        @endif
                                    </span>

                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 12px;">
                                        <div class="admin-form-group">
                                            <label for="order-{{ $btn->id }}">Ordem</label>
                                            <input type="number" id="order-{{ $btn->id }}" name="order" value="{{ $btn->order }}" required>
                                        </div>
                                        <div class="admin-form-group" style="display: flex; flex-direction: column; justify-content: center; padding-top: 18px;">
                                            <label class="admin-checkbox-container" style="display: flex; align-items: center; gap: 8px; cursor: pointer; user-select: none;">
                                                <input type="checkbox" name="is_active" value="1" {{ $btn->is_active ? 'checked' : '' }} style="width: auto; cursor: pointer;">
                                                <span style="font-size: 12px; color: #fff; font-weight: 600;">Ativo</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="admin-form-group" style="margin-top: 15px; border-top: 1px dashed rgba(255,255,255,0.05); padding-top: 15px;">
                                        <label for="image-{{ $btn->id }}">Substituir por Imagem Personalizada</label>
                                        <input type="file" id="image-{{ $btn->id }}" name="image" accept="image/*" onchange="previewCategoryUpload(this, 'preview-container-{{ $btn->id }}', 'preview-img-{{ $btn->id }}')">
                                        
                                        <div id="preview-container-{{ $btn->id }}" style="display: none; margin-top: 12px; text-align: center; border: 1px dashed var(--border-color); padding: 10px; border-radius: 8px; background-color: #0c0c0e;">
                                            <p style="font-size: 10px; color: var(--text-muted); margin-bottom: 6px; font-weight: 700; text-transform: uppercase;">Visualização da Nova Imagem</p>
                                            <img id="preview-img-{{ $btn->id }}" src="#" alt="Nova Imagem" style="max-height: 50px; max-width: 100%; object-fit: contain;">
                                        </div>
                                    </div>

                                    <button type="submit" class="admin-btn-save" style="margin-top: 15px; padding: 10px; font-size: 12px; font-weight: 700; width: 100%;">
                                        SALVAR ALTERAÇÕES
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: span 3; text-align: center; padding: 40px 0; color: var(--text-muted); background: #161619; border: 1px dashed var(--border-color); border-radius: 12px;">
                        <i class="fa-regular fa-folder-open" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                        <p style="font-size: 13.5px;">Nenhum item cadastrado neste grupo.</p>
                    </div>
                @endforelse
            </div>
        </div>
    @endforeach
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Load active tab from localStorage, default to first tab if not set
        const firstTab = document.querySelector('.admin-tab-btn');
        const defaultTab = firstTab ? firstTab.id.replace('btn-', '') : '';
        const activeTab = localStorage.getItem('admin_categories_active_tab') || defaultTab;
        if (activeTab) {
            switchCategoryTab(activeTab);
        }
    });

    function switchCategoryTab(tabId) {
        // Collect all tab divs
        const tabDivs = document.querySelectorAll('[id^="tab-"]');
        const tabBtns = document.querySelectorAll('.admin-tab-btn');
        
        let targetId = tabId;
        // Backward compatibility check for old tabs
        if (tabId === 'sidebar-tab') targetId = 'tab-sidebar';
        if (tabId === 'sports-tab') targetId = 'tab-sports';
        if (tabId === 'recommended-tab') targetId = 'tab-recommended';
        if (tabId === 'weekly-tab') targetId = 'tab-weekly';
        if (tabId === 'payouts-tab') targetId = 'tab-payouts';
        if (tabId === 'live-tab') targetId = 'tab-live';

        let exists = false;
        tabDivs.forEach(pane => {
            if (pane.id === targetId) {
                pane.style.display = 'block';
                exists = true;
            } else {
                pane.style.display = 'none';
            }
        });

        // If the stored tabId doesn't exist, activate the first one
        if (!exists && tabDivs.length > 0) {
            targetId = tabDivs[0].id;
            tabDivs[0].style.display = 'block';
        }

        tabBtns.forEach(btn => {
            if (btn.id === 'btn-' + targetId) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
        
        // Save active tab to localStorage
        localStorage.setItem('admin_categories_active_tab', targetId);
    }

    function toggleCreateBox(boxId) {
        const box = document.getElementById(boxId);
        if (box) {
            box.style.display = box.style.display === 'none' ? 'block' : 'none';
        }
    }

    // Keep active upload preview working
    function previewCategoryUpload(input, containerId, imgId) {
        const container = document.getElementById(containerId);
        const img = document.getElementById(imgId);
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                img.src = e.target.result;
                container.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            img.src = '#';
            container.style.display = 'none';
        }
    }

    function toggleEditPanel(panelId) {
        const panel = document.getElementById(panelId);
        const allPanels = document.querySelectorAll('.edit-form-panel');
        
        // Hide other panels first
        allPanels.forEach(p => {
            if (p.id !== panelId) {
                p.style.display = 'none';
            }
        });

        // Toggle requested panel
        if (panel) {
            panel.style.display = panel.style.display === 'block' ? 'none' : 'block';
        }
    }

    function updateActionHelp(actionType, helpId) {
        const help = document.getElementById(helpId);
        if (!help) return;

        if (actionType === 'link') {
            help.innerText = 'Digite a URL ou âncora (ex: https://site.com ou #secao)';
        } else if (actionType === 'scroll') {
            help.innerText = 'Digite o ID do elemento da página (ex: cassino-section)';
        } else if (actionType === 'alert') {
            help.innerText = 'Digite o texto que aparecerá na mensagem';
        } else if (actionType === 'play') {
            help.innerText = 'Chave do jogo (ex: mines ou fortune_tiger)';
        } else {
            help.innerText = 'Nenhuma ação será disparada';
        }
    }
</script>
@endsection
