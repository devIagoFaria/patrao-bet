@extends('layouts.admin')

@section('page_title', 'Personalização e Banners')

@section('content')
<div class="admin-card-table" id="personalization-card" style="max-width: 1000px; margin: 0 auto; background: #121215; border: 1px solid var(--border-color); border-radius: 16px; padding: 32px; transition: max-width 0.3s ease;">
    
    <!-- NAVEGAÇÃO POR ABAS -->
    <div style="display: flex; gap: 15px; margin-bottom: 30px; border-bottom: 1px solid var(--border-color); padding-bottom: 15px;">
        <button type="button" onclick="showTab('banners-tab')" id="btn-banners-tab" class="admin-tab-btn active" style="background: var(--color-yellow); color: #000; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 700; cursor: pointer; transition: all 0.2s ease;">
            <i class="fa-solid fa-images"></i> Banners do Carrossel e Modais
        </button>
        <button type="button" onclick="showTab('colors-tab')" id="btn-colors-tab" class="admin-tab-btn" style="background: rgba(255,255,255,0.05); color: #fff; border: 1px solid var(--border-color); padding: 10px 20px; border-radius: 8px; font-weight: 700; cursor: pointer; transition: all 0.2s ease;">
            <i class="fa-solid fa-palette"></i> Cores e Estilos do Site
        </button>
        <button type="button" onclick="showTab('support-tab')" id="btn-support-tab" class="admin-tab-btn" style="background: rgba(255,255,255,0.05); color: #fff; border: 1px solid var(--border-color); padding: 10px 20px; border-radius: 8px; font-weight: 700; cursor: pointer; transition: all 0.2s ease;">
            <i class="fa-solid fa-headset"></i> Banner de Suporte
        </button>
    </div>

    <!-- TAB 1: BANNERS -->
    <div id="banners-tab">
        <div style="margin-bottom: 30px; border-bottom: 1px solid var(--border-color); padding-bottom: 20px;">
            <h3 style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; color: #fff;">Gerenciamento de Banners</h3>
            <p style="font-size: 13px; color: var(--text-secondary); margin-top: 6px;">Adicione novos banners no carrossel de slides ou personalize as imagens laterais das telas de login e cadastro.</p>
        </div>

        <!-- FORMULÁRIO DE CADASTRO DE BANNER -->
        <div style="background-color: #161619; border: 1px solid var(--border-color); border-radius: 12px; padding: 24px; margin-bottom: 40px;">
            <h4 style="font-family: var(--font-heading); font-size: 15px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 20px; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-plus-circle"></i> Cadastrar Novo Banner / Imagem
            </h4>

            <form action="{{ route('admin.personalization.banner.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="admin-form-group">
                        <label for="title">Título do Banner (Identificação)</label>
                        <input type="text" id="title" name="title" placeholder="ex: Banner Boas-vindas Tiger" style="width: 100%; background-color: #1a1a1e; border: 1px solid var(--border-color); border-radius: 8px; padding: 12px; font-size: 14px; color: #fff;">
                    </div>

                    <div class="admin-form-group">
                        <label for="type">Local de Exibição / Tipo</label>
                        <select id="type" name="type" required onchange="handleTypeChange(this.value)" style="width: 100%; background-color: #1a1a1e; border: 1px solid var(--border-color); border-radius: 8px; padding: 12px; font-size: 14px; color: #fff; height: 45px;">
                            <option value="home_carousel">Carrossel Principal (Desktop - Home)</option>
                            <option value="login_modal">Modal de Login (Lateral Desktop)</option>
                            <option value="register_modal">Modal de Cadastro (Lateral Desktop)</option>
                            <option value="login_modal_mobile">Modal de Login (Topo Mobile)</option>
                            <option value="register_modal_mobile">Modal de Cadastro (Topo Mobile)</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 15px;">
                    <div class="admin-form-group" id="urlGroup">
                        <label for="link_url">Link de Redirecionamento (Opcional)</label>
                        <input type="text" id="link_url" name="link_url" placeholder="ex: #fortune-tiger ou url externa" style="width: 100%; background-color: #1a1a1e; border: 1px solid var(--border-color); border-radius: 8px; padding: 12px; font-size: 14px; color: #fff;">
                    </div>

                    <div class="admin-form-group" id="orderGroup">
                        <label for="order">Ordem de Exibição</label>
                        <input type="number" id="order" name="order" value="0" min="0" style="width: 100%; background-color: #1a1a1e; border: 1px solid var(--border-color); border-radius: 8px; padding: 12px; font-size: 14px; color: #fff;">
                    </div>
                </div>

                <!-- Upload Field & Live Preview -->
                <div style="margin-top: 25px; border-top: 1px solid var(--border-color); padding-top: 20px;">
                    <div style="display: grid; grid-template-columns: 1.2fr 1fr; gap: 25px; align-items: start;">
                        <div class="admin-form-group">
                            <label for="image" style="font-weight: 700;">Selecionar Imagem</label>
                            <input type="file" id="image" name="image" accept="image/*" required onchange="previewUpload(this)" style="width: 100%; padding: 10px 0; font-size: 14px; color: var(--text-secondary);">
                            <p class="admin-form-help" id="dimensionHelp" style="font-size: 11.5px; color: var(--text-muted); margin-top: 6px;">Dimensão recomendada: 1200x400 para Carrossel Desktop.</p>
                        </div>

                        <!-- Live Preview Container -->
                        <div style="background-color: #0c0c0e; border: 1px dashed var(--border-color); border-radius: 12px; padding: 16px; min-height: 140px; display: flex; flex-direction: column; align-items: center; justify-content: center; position: relative;">
                            <span id="previewPlaceholder" style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Pré-visualização do Banner</span>
                            <img id="imagePreview" src="#" alt="Preview" style="display: none; max-width: 100%; max-height: 120px; object-fit: contain; border-radius: 6px; box-shadow: var(--shadow-md);">
                        </div>
                    </div>
                </div>

                <button type="submit" class="admin-btn-save" style="margin-top: 25px; width: 100%; padding: 14px; font-size: 14px; font-weight: 700; background: var(--color-yellow); color: #000; border-radius: 8px; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; gap: 8px; border: none;">
                    <i class="fa-solid fa-cloud-arrow-up"></i> SALVAR BANNER E ATIVAR
                </button>
            </form>
        </div>

        <!-- LISTAGEM DE BANNERS -->
        <div>
            <h4 style="font-family: var(--font-heading); font-size: 16px; font-weight: 700; color: #fff; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-images"></i> Banners Ativos
            </h4>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                @forelse($banners as $banner)
                    <div style="background-color: #161619; border: 1px solid var(--border-color); border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; position: relative; opacity: {{ $banner->is_active ? '1' : '0.5' }};">
                        @if(!$banner->is_active)
                            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(0,0,0,0.8); color: #fff; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: 800; z-index: 10; border: 1px solid var(--color-red);">OCULTO</div>
                        @endif
                        <div style="position: relative; padding-top: 40%; background-color: #0c0c0e;">
                            <img src="{{ asset($banner->image_path) }}" alt="{{ $banner->title }}" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: contain;">
                            
                            <div style="position: absolute; top: 12px; left: 12px; background: rgba(0, 0, 0, 0.75); padding: 4px 10px; border-radius: 20px; font-size: 10px; font-weight: 800; text-transform: uppercase; color: var(--color-yellow); letter-spacing: 0.5px;">
                                @if($banner->type === 'home_carousel')
                                    Desktop Home
                                @elseif($banner->type === 'login_modal')
                                    Modal Login Desktop
                                @elseif($banner->type === 'register_modal')
                                    Modal Registro Desktop
                                @elseif($banner->type === 'login_modal_mobile')
                                    Modal Login Mobile
                                @elseif($banner->type === 'register_modal_mobile')
                                    Modal Registro Mobile
                                @endif
                            </div>
                        </div>
                        
                        <div style="padding: 16px; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <h5 style="font-size: 14px; font-weight: 700; color: #fff;">{{ $banner->title }}</h5>
                                @if($banner->link_url)
                                    <p style="font-size: 11px; color: var(--text-muted); margin-top: 4px; font-family: monospace;">Redireciona: {{ $banner->link_url }}</p>
                                @endif
                            </div>
                            
                            <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 15px; border-top: 1px solid var(--border-color); padding-top: 12px;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <span style="font-size: 11px; color: var(--text-secondary); font-weight: 600;">Ordem: #{{ $banner->order }}</span>
                                    <button type="button" onclick="toggleEditBanner('edit-banner-{{ $banner->id }}')" style="background: rgba(255, 215, 0, 0.1); border: 1px solid rgba(255, 215, 0, 0.2); color: var(--color-yellow); padding: 6px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; cursor: pointer; transition: all 0.2s ease;">
                                        <i class="fa-solid fa-pen"></i> Editar
                                    </button>
                                </div>

                                <div id="edit-banner-{{ $banner->id }}" style="display: none; background: #0c0c0e; padding: 15px; border-radius: 8px; margin-top: 10px; border: 1px solid var(--border-color);">
                                    <form action="{{ route('admin.personalization.banner.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div style="display: flex; flex-direction: column; gap: 10px;">
                                            <input type="text" name="title" value="{{ $banner->title }}" placeholder="Título" style="background: #1a1a1e; border: 1px solid var(--border-color); color: #fff; padding: 8px; border-radius: 4px; font-size: 12px;">
                                            <select name="type" style="background: #1a1a1e; border: 1px solid var(--border-color); color: #fff; padding: 8px; border-radius: 4px; font-size: 12px;">
                                                <option value="home_carousel" {{ $banner->type == 'home_carousel' ? 'selected' : '' }}>Carrossel Principal</option>
                                                <option value="login_modal" {{ $banner->type == 'login_modal' ? 'selected' : '' }}>Modal Login</option>
                                                <option value="register_modal" {{ $banner->type == 'register_modal' ? 'selected' : '' }}>Modal Registro</option>
                                                <option value="login_modal_mobile" {{ $banner->type == 'login_modal_mobile' ? 'selected' : '' }}>Modal Login Mobile</option>
                                                <option value="register_modal_mobile" {{ $banner->type == 'register_modal_mobile' ? 'selected' : '' }}>Modal Registro Mobile</option>
                                            </select>
                                            <input type="text" name="link_url" value="{{ $banner->link_url }}" placeholder="URL de Link" style="background: #1a1a1e; border: 1px solid var(--border-color); color: #fff; padding: 8px; border-radius: 4px; font-size: 12px;">
                                            <input type="number" name="order" value="{{ $banner->order }}" placeholder="Ordem" style="background: #1a1a1e; border: 1px solid var(--border-color); color: #fff; padding: 8px; border-radius: 4px; font-size: 12px;">
                                            
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <input type="file" name="image" style="font-size: 10px; color: #999;">
                                            </div>

                                            <label style="display: flex; align-items: center; gap: 8px; font-size: 12px; color: #fff; cursor: pointer;">
                                                <input type="checkbox" name="is_active" {{ $banner->is_active ? 'checked' : '' }} style="width: 16px; height: 16px;">
                                                Ativo / Visível
                                            </label>

                                            <div style="display: flex; gap: 10px;">
                                                <button type="submit" style="flex: 1; background: var(--color-yellow); color: #000; border: none; padding: 8px; border-radius: 4px; font-size: 11px; font-weight: 700; cursor: pointer;">SALVAR</button>
                                                <button type="button" onclick="toggleEditBanner('edit-banner-{{ $banner->id }}')" style="flex: 1; background: #222; color: #fff; border: 1px solid #444; padding: 8px; border-radius: 4px; font-size: 11px; cursor: pointer;">CANCELAR</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                
                                <form action="{{ route('admin.personalization.banner.destroy', $banner->id) }}" method="POST" onsubmit="return confirm('Deseja realmente remover este banner?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.15); color: #999; padding: 6px 12px; border-radius: 6px; font-size: 10px; font-weight: 600; cursor: pointer; transition: all 0.2s ease; border: none; width: 100%;">
                                        <i class="fa-regular fa-trash-can"></i> Excluir Permanentemente
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: span 2; text-align: center; padding: 40px 0; color: var(--text-muted); background: #161619; border: 1px dashed var(--border-color); border-radius: 12px;">
                        <i class="fa-regular fa-images" style="font-size: 24px; margin-bottom: 8px;"></i>
                        <p style="font-size: 13.5px;">Nenhum banner cadastrado. O sistema usará as imagens estáticas originais.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- TAB 2: CORES DO SITE -->
    <div id="colors-tab" style="display: none;">
        <div style="margin-bottom: 30px; border-bottom: 1px solid var(--border-color); padding-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h3 style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; color: #fff;">Esquema de Cores do Site</h3>
                <p style="font-size: 13px; color: var(--text-secondary); margin-top: 6px;">Personalize a paleta de cores e o visual do cassino. As mudanças são refletidas em tempo real.</p>
            </div>
            
            <form action="{{ route('admin.personalization.colors.reset') }}" method="POST" onsubmit="return confirm('Deseja realmente redefinir todas as cores do site para os padrões originais?')">
                @csrf
                <button type="submit" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.25); color: #ff3d00; padding: 10px 20px; border-radius: 8px; font-size: 13px; font-weight: 700; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; gap: 8px; border: none;">
                    <i class="fa-solid fa-arrow-rotate-left"></i> Redefinir Padrões
                </button>
            </form>
        </div>

        <div style="display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 30px; align-items: start;">
            <!-- FORMULARIO DE EDICAO (LADO ESQUERDO) -->
            <form action="{{ route('admin.personalization.colors.update') }}" method="POST" id="colorsForm" style="display: flex; flex-direction: column; gap: 20px;">
                @csrf

                <!-- TEMA GERAL E FUNDOS -->
                <div style="background-color: #161619; border: 1px solid var(--border-color); border-radius: 12px; padding: 20px;">
                    <h4 style="font-family: var(--font-heading); font-size: 13px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 15px; letter-spacing: 0.5px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 8px;">
                        <i class="fa-solid fa-desktop"></i> Tema Geral e Fundos
                    </h4>
                    
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Fundo do Site (Body):</label>
                            <input type="color" name="color_bg_color" value="{{ \App\Models\Setting::get('color_bg_color', '#02120d') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Fundo do Cabeçalho (Header):</label>
                            <input type="color" name="color_header_bg" value="{{ \App\Models\Setting::get('color_header_bg', '#031f15') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Fundo da Barra Lateral (Sidebar):</label>
                            <input type="color" name="color_sidebar_bg" value="{{ \App\Models\Setting::get('color_sidebar_bg', '#031c12') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Fundo dos Cards:</label>
                            <input type="color" name="color_card_bg" value="{{ \App\Models\Setting::get('color_card_bg', '#042419') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Fundo dos Cards no Hover:</label>
                            <input type="color" name="color_card_hover" value="{{ \App\Models\Setting::get('color_card_hover', '#063424') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Cor de Borda Geral:</label>
                            <input type="color" name="color_border_color" value="{{ \App\Models\Setting::get('color_border_color', '#00e676') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                    </div>
                </div>

                <!-- IDENTIDADE E DESTAQUES (ACCENT) -->
                <div style="background-color: #161619; border: 1px solid var(--border-color); border-radius: 12px; padding: 20px;">
                    <h4 style="font-family: var(--font-heading); font-size: 13px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 15px; letter-spacing: 0.5px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 8px;">
                        <i class="fa-solid fa-circle-check"></i> Identidade e Destaques (Neon)
                    </h4>
                    
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Verde Neon (Destaque Principal):</label>
                            <input type="color" name="color_neon_green" value="{{ \App\Models\Setting::get('color_neon_green', '#00e676') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Ouro/Amarelo (Secundário):</label>
                            <input type="color" name="color_gold" value="{{ \App\Models\Setting::get('color_gold', '#ffd700') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Vermelho (Alertas/Destaques):</label>
                            <input type="color" name="color_red" value="{{ \App\Models\Setting::get('color_red', '#ff3d00') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Texto Principal:</label>
                            <input type="color" name="color_text_primary" value="{{ \App\Models\Setting::get('color_text_primary', '#ffffff') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Texto Secundário:</label>
                            <input type="color" name="color_text_secondary" value="{{ \App\Models\Setting::get('color_text_secondary', '#90ab9f') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Texto Escuro (Muted):</label>
                            <input type="color" name="color_text_muted" value="{{ \App\Models\Setting::get('color_text_muted', '#5e7a6f') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                    </div>
                </div>

                <!-- BOTÕES DO TOPO -->
                <div style="background-color: #161619; border: 1px solid var(--border-color); border-radius: 12px; padding: 20px;">
                    <h4 style="font-family: var(--font-heading); font-size: 13px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 15px; letter-spacing: 0.5px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 8px;">
                        <i class="fa-solid fa-rectangle-ad"></i> Botões do Topo (Entrar e Cadastrar)
                    </h4>
                    
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Fundo do Botão "Entrar":</label>
                            <input type="color" name="color_btn_entrar_bg" value="{{ \App\Models\Setting::get('color_btn_entrar_bg', '#1c1c1f') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Borda do Botão "Entrar":</label>
                            <input type="color" name="color_btn_entrar_border" value="{{ \App\Models\Setting::get('color_btn_entrar_border', '#3a3a3c') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Texto do Botão "Entrar":</label>
                            <input type="color" name="color_btn_entrar_text" value="{{ \App\Models\Setting::get('color_btn_entrar_text', '#ffffff') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.05); margin: 5px 0;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Cadastrar - Gradiente Início:</label>
                            <input type="color" name="color_btn_cadastrar_bg_start" value="{{ \App\Models\Setting::get('color_btn_cadastrar_bg_start', '#00e676') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Cadastrar - Gradiente Fim:</label>
                            <input type="color" name="color_btn_cadastrar_bg_end" value="{{ \App\Models\Setting::get('color_btn_cadastrar_bg_end', '#00c853') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Texto do Botão "Cadastrar":</label>
                            <input type="color" name="color_btn_cadastrar_text" value="{{ \App\Models\Setting::get('color_btn_cadastrar_text', '#000000') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                    </div>
                </div>

                <!-- MODAIS DE LOGIN / CADASTRO -->
                <div style="background-color: #161619; border: 1px solid var(--border-color); border-radius: 12px; padding: 20px;">
                    <h4 style="font-family: var(--font-heading); font-size: 13px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 15px; letter-spacing: 0.5px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 8px;">
                        <i class="fa-solid fa-window-maximize"></i> Modais de Login / Cadastro
                    </h4>
                    
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Fundo dos Modais (Login / Cadastro / Jogos):</label>
                            <input type="color" name="color_modal_bg" value="{{ \App\Models\Setting::get('color_modal_bg', '#031c12') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Borda dos Modais:</label>
                            <input type="color" name="color_modal_border" value="{{ \App\Models\Setting::get('color_modal_border', '#00e676') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Fundo dos Inputs no Modal:</label>
                            <input type="color" name="color_modal_input_bg" value="{{ \App\Models\Setting::get('color_modal_input_bg', '#0b2018') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Borda dos Inputs no Modal:</label>
                            <input type="color" name="color_modal_input_border" value="{{ \App\Models\Setting::get('color_modal_input_border', '#00e676') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Borda dos Inputs ao Focar (Focus):</label>
                            <input type="color" name="color_modal_input_focus" value="{{ \App\Models\Setting::get('color_modal_input_focus', '#00e676') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.05); margin: 5px 0;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Botão Enviar Modal - Início:</label>
                            <input type="color" name="color_btn_auth_submit_bg_start" value="{{ \App\Models\Setting::get('color_btn_auth_submit_bg_start', '#00e676') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Botão Enviar Modal - Fim:</label>
                            <input type="color" name="color_btn_auth_submit_bg_end" value="{{ \App\Models\Setting::get('color_btn_auth_submit_bg_end', '#00c853') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Texto do Botão de Enviar Modal:</label>
                            <input type="color" name="color_btn_auth_submit_text" value="{{ \App\Models\Setting::get('color_btn_auth_submit_text', '#000000') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                    </div>
                </div>

                <!-- BOTÕES DO SIDEBAR (6 BOTÕES) -->
                <div style="background-color: #161619; border: 1px solid var(--border-color); border-radius: 12px; padding: 20px;">
                    <h4 style="font-family: var(--font-heading); font-size: 13px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 15px; letter-spacing: 0.5px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 8px;">
                        <i class="fa-solid fa-table-cells"></i> Botões do Sidebar (Prêmios, Cassino...)
                    </h4>
                    
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Fundo - Gradiente Início:</label>
                            <input type="color" name="color_sidebar_btn_bg_start" value="{{ \App\Models\Setting::get('color_sidebar_btn_bg_start', '#002814') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Fundo - Gradiente Fim:</label>
                            <input type="color" name="color_sidebar_btn_bg_end" value="{{ \App\Models\Setting::get('color_sidebar_btn_bg_end', '#000f08') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Cor da Borda:</label>
                            <input type="color" name="color_sidebar_btn_border" value="{{ \App\Models\Setting::get('color_sidebar_btn_border', '#00e676') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Cor do Texto:</label>
                            <input type="color" name="color_sidebar_btn_text" value="{{ \App\Models\Setting::get('color_sidebar_btn_text', '#ffffff') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Mostrar "V" no Fundo:</label>
                            <select name="sidebar_btn_show_v" style="width: 70px; background-color: #1a1a1e; border: 1px solid var(--border-color); border-radius: 6px; padding: 5px; font-size: 12px; color: #fff;">
                                <option value="1" {{ \App\Models\Setting::get('sidebar_btn_show_v', '1') == '1' ? 'selected' : '' }}>SIM</option>
                                <option value="0" {{ \App\Models\Setting::get('sidebar_btn_show_v', '1') == '0' ? 'selected' : '' }}>NÃO</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- BOTÕES DO MENU INFERIOR (MOBILE) -->
                <div style="background-color: #161619; border: 1px solid var(--border-color); border-radius: 12px; padding: 20px;">
                    <h4 style="font-family: var(--font-heading); font-size: 13px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 15px; letter-spacing: 0.5px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 8px;">
                        <i class="fa-solid fa-mobile-screen"></i> Menu Inferior (Mobile)
                    </h4>
                    
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Fundo - Gradiente Início:</label>
                            <input type="color" name="color_bottom_nav_btn_bg_start" value="{{ \App\Models\Setting::get('color_bottom_nav_btn_bg_start', '#002814') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Fundo - Gradiente Fim:</label>
                            <input type="color" name="color_bottom_nav_btn_bg_end" value="{{ \App\Models\Setting::get('color_bottom_nav_btn_bg_end', '#000f08') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Cor da Borda:</label>
                            <input type="color" name="color_bottom_nav_btn_border" value="{{ \App\Models\Setting::get('color_bottom_nav_btn_border', '#00e676') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Cor do Texto:</label>
                            <input type="color" name="color_bottom_nav_btn_text" value="{{ \App\Models\Setting::get('color_bottom_nav_btn_text', '#ffffff') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Fundo Total do Menu (Barra):</label>
                            <input type="color" name="color_bottom_nav_full_bg" value="{{ \App\Models\Setting::get('color_bottom_nav_full_bg', '#0c0c0e') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Cor Neon do Banner (Hover):</label>
                            <input type="color" name="color_banner_neon_hover" value="{{ \App\Models\Setting::get('color_banner_neon_hover', '#00e676') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Mostrar "V" no Fundo:</label>
                            <select name="bottom_nav_btn_show_v" style="width: 70px; background-color: #1a1a1e; border: 1px solid var(--border-color); border-radius: 6px; padding: 5px; font-size: 12px; color: #fff;">
                                <option value="1" {{ \App\Models\Setting::get('bottom_nav_btn_show_v', '1') == '1' ? 'selected' : '' }}>SIM</option>
                                <option value="0" {{ \App\Models\Setting::get('bottom_nav_btn_show_v', '1') == '0' ? 'selected' : '' }}>NÃO</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- ITENS DO MENU LATERAL E BALÕES DE JOGOS -->
                <div style="background-color: #161619; border: 1px solid var(--border-color); border-radius: 12px; padding: 20px;">
                    <h4 style="font-family: var(--font-heading); font-size: 13px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 15px; letter-spacing: 0.5px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 8px;">
                        <i class="fa-solid fa-list"></i> Menu Lateral e Balões de Jogos
                    </h4>
                    
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <p style="font-size: 11px; color: var(--color-yellow); font-weight: 700; margin-bottom: 5px;">MENU LATERAL (CASSINO, ESPORTES...)</p>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Fundo do Item (Hover/Ativo):</label>
                            <input type="color" name="color_sidebar_menu_item_bg" value="{{ \App\Models\Setting::get('color_sidebar_menu_item_bg', '#1a1a1e') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Cor do Texto:</label>
                            <input type="color" name="color_sidebar_menu_item_text" value="{{ \App\Models\Setting::get('color_sidebar_menu_item_text', '#999999') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Cor do Ícone:</label>
                            <input type="color" name="color_sidebar_menu_item_icon" value="{{ \App\Models\Setting::get('color_sidebar_menu_item_icon', '#999999') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>

                        <p style="font-size: 11px; color: var(--color-yellow); font-weight: 700; margin-top: 10px; margin-bottom: 5px;">BOTÕES "VER TODOS"</p>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Cor de Fundo:</label>
                            <input type="color" name="color_see_all_bg" value="{{ \App\Models\Setting::get('color_see_all_bg', 'rgba(255, 255, 255, 0.05)') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Cor da Borda:</label>
                            <input type="color" name="color_see_all_border" value="{{ \App\Models\Setting::get('color_see_all_border', 'rgba(0, 230, 118, 0.2)') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Cor do Texto:</label>
                            <input type="color" name="color_see_all_text" value="{{ \App\Models\Setting::get('color_see_all_text', '#00e676') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                    </div>
                </div>

                <!-- CABEÇALHO E MENU DO PERFIL -->
                <div style="background-color: #161619; border: 1px solid var(--border-color); border-radius: 12px; padding: 20px;">
                    <h4 style="font-family: var(--font-heading); font-size: 13px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 15px; letter-spacing: 0.5px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 8px;">
                        <i class="fa-solid fa-user-gear"></i> Cabeçalho e Menu do Perfil
                    </h4>
                    
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <p style="font-size: 11px; color: var(--color-yellow); font-weight: 700; margin-bottom: 5px;">BOTÕES DO TOPO (DEPOSITAR / SALDO)</p>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Botão Depositar (Fundo):</label>
                            <input type="color" name="color_header_deposit_btn_bg" value="{{ \App\Models\Setting::get('color_header_deposit_btn_bg', '#00e676') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Botão Depositar (Texto):</label>
                            <input type="color" name="color_header_deposit_btn_text" value="{{ \App\Models\Setting::get('color_header_deposit_btn_text', '#000000') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Botão Saldo (Fundo):</label>
                            <input type="color" name="color_header_balance_bg" value="{{ \App\Models\Setting::get('color_header_balance_bg', '#1a1a1e') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Botão Saldo (Texto/Valor):</label>
                            <input type="color" name="color_header_balance_text" value="{{ \App\Models\Setting::get('color_header_balance_text', '#ffffff') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>

                        <p style="font-size: 11px; color: var(--color-yellow); font-weight: 700; margin-top: 10px; margin-bottom: 5px;">MENU SUSPENSO DO PERFIL</p>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Fundo do Menu:</label>
                            <input type="color" name="color_header_profile_menu_bg" value="{{ \App\Models\Setting::get('color_header_profile_menu_bg', '#11221a') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Item em Foco (Hover):</label>
                            <input type="color" name="color_header_profile_menu_item_hover_bg" value="{{ \App\Models\Setting::get('color_header_profile_menu_item_hover_bg', 'rgba(255,255,255,0.05)') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Cor do Texto do Menu:</label>
                            <input type="color" name="color_header_profile_menu_text" value="{{ \App\Models\Setting::get('color_header_profile_menu_text', '#ffffff') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>

                        <p style="font-size: 11px; color: var(--color-yellow); font-weight: 700; margin-top: 10px; margin-bottom: 5px;">BOTÕES DE PERFIL E NOTIFICAÇÃO</p>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Botão Perfil (Fundo):</label>
                            <input type="color" name="color_header_profile_btn_bg" value="{{ \App\Models\Setting::get('color_header_profile_btn_bg', '#1a1a1e') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 12px; font-weight: 600; color: #fff;">Botão Notificação (Fundo):</label>
                            <input type="color" name="color_header_notif_btn_bg" value="{{ \App\Models\Setting::get('color_header_notif_btn_bg', '#1a1a1e') }}" style="width: 50px; height: 32px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                    </div>
                </div>

                <!-- SAVE BUTTON -->
                <button type="submit" class="admin-btn-save" style="width: 100%; padding: 14px; font-size: 14px; font-weight: 700; background: var(--color-yellow); color: #000; border-radius: 8px; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; gap: 8px; border: none;">
                    <i class="fa-solid fa-circle-check"></i> SALVAR ESQUEMA DE CORES
                </button>
            </form>

            <!-- SIMULADOR INTERATIVO (LADO DIREITO) -->
            <div style="background-color: #161619; border: 1px solid var(--border-color); border-radius: 12px; padding: 20px; position: sticky; top: 30px; display: flex; flex-direction: column; gap: 15px; align-items: center; width: 100%;">
                <div style="display: flex; justify-content: space-between; align-items: center; width: 100%; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 10px;">
                    <h4 style="font-family: var(--font-heading); font-size: 13px; font-weight: 700; color: #fff; margin: 0; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-mobile-screen-button"></i> Simulador do Site
                    </h4>
                    
                    <!-- DESKTOP / MOBILE SWITCHER -->
                    <div style="display: flex; background: #0c0c0e; border-radius: 8px; padding: 3px; border: 1px solid var(--border-color);">
                        <button type="button" onclick="setPreviewSize('desktop')" id="btn-preview-desktop" style="background: var(--color-yellow); color: #000; border: none; padding: 6px 12px; border-radius: 6px; font-size: 10px; font-weight: 700; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; gap: 4px;">
                            <i class="fa-solid fa-desktop"></i> Desktop
                        </button>
                        <button type="button" onclick="setPreviewSize('mobile')" id="btn-preview-mobile" style="background: transparent; color: #fff; border: none; padding: 6px 12px; border-radius: 6px; font-size: 10px; font-weight: 700; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; gap: 4px;">
                            <i class="fa-solid fa-mobile-button"></i> Mobile
                        </button>
                    </div>
                </div>

                <!-- PREVIEW CONTAINER AND IFRAME -->
                <div id="preview-frame-wrapper" style="width: 100%; transition: all 0.3s ease; display: flex; justify-content: center; align-items: center;">
                    <div id="preview-frame-holder" style="width: 100%; height: 620px; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color); background: #02120d; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.5); transition: all 0.3s ease;">
                        <iframe id="site-preview-iframe" src="" style="width: 100%; height: 100%; border: none;"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB 3: BANNER DE SUPORTE -->
    <div id="support-tab" style="display: none;">
        <div style="margin-bottom: 30px; border-bottom: 1px solid var(--border-color); padding-bottom: 20px;">
            <h3 style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; color: #fff;">Banner de Suporte Customizável</h3>
            <p style="font-size: 13px; color: var(--text-secondary); margin-top: 6px;">Configure o banner de suporte que aparece no final da homepage. Personalize textos, cores e links.</p>
        </div>

        <form action="{{ route('admin.personalization.colors.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <!-- Configurações de Texto e Link -->
                <div style="background-color: #161619; border: 1px solid var(--border-color); border-radius: 12px; padding: 24px;">
                    <h4 style="font-family: var(--font-heading); font-size: 15px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 20px;">Textos e Links</h4>
                    
                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        <div class="admin-form-group">
                            <label>Título do Banner</label>
                            <input type="text" name="support_banner_title" value="{{ \App\Models\Setting::get('support_banner_title', 'Suporte') }}" style="width: 100%; background: #1a1a1e; border: 1px solid var(--border-color); border-radius: 8px; padding: 12px; color: #fff;">
                        </div>
                        <div class="admin-form-group">
                            <label>Subtítulo / Descrição</label>
                            <input type="text" name="support_banner_subtitle" value="{{ \App\Models\Setting::get('support_banner_subtitle', 'Entre em contato se ainda tiver dúvidas') }}" style="width: 100%; background: #1a1a1e; border: 1px solid var(--border-color); border-radius: 8px; padding: 12px; color: #fff;">
                        </div>
                        <div class="admin-form-group">
                            <label>Texto do Selo (Badge)</label>
                            <input type="text" name="support_banner_badge_text" value="{{ \App\Models\Setting::get('support_banner_badge_text', '24/7') }}" style="width: 100%; background: #1a1a1e; border: 1px solid var(--border-color); border-radius: 8px; padding: 12px; color: #fff;">
                        </div>
                        <div class="admin-form-group">
                            <label>Link do Botão (WhatsApp, Telegram ou JivoChat)</label>
                            <input type="text" name="support_banner_link_url" value="{{ \App\Models\Setting::get('support_banner_link_url', 'javascript:void(0)') }}" placeholder="ex: https://wa.me/55... ou javascript:jivo_api.open()" style="width: 100%; background: #1a1a1e; border: 1px solid var(--border-color); border-radius: 8px; padding: 12px; color: #fff;">
                            <p style="font-size: 11px; color: var(--text-muted); margin-top: 5px;">Use <code>javascript:jivo_api.open()</code> para abrir o chat JivoChat.</p>
                        </div>
                    </div>
                </div>

                <!-- Configurações de Cores e Imagem -->
                <div style="background-color: #161619; border: 1px solid var(--border-color); border-radius: 12px; padding: 24px;">
                    <h4 style="font-family: var(--font-heading); font-size: 15px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 20px;">Cores e Visual</h4>
                    
                    <div style="display: flex; flex-direction: column; gap: 15px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 13px; color: #fff;">Cor de Fundo do Banner:</label>
                            <input type="color" name="support_banner_bg_color" value="{{ \App\Models\Setting::get('support_banner_bg_color', '#121215') }}" style="width: 60px; height: 35px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 13px; color: #fff;">Cor do Botão:</label>
                            <input type="color" name="support_banner_btn_bg" value="{{ \App\Models\Setting::get('support_banner_btn_bg', '#007bff') }}" style="width: 60px; height: 35px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <label style="font-size: 13px; color: #fff;">Cor do Texto do Botão:</label>
                            <input type="color" name="support_banner_btn_text" value="{{ \App\Models\Setting::get('support_banner_btn_text', '#ffffff') }}" style="width: 60px; height: 35px; border: none; border-radius: 4px; cursor: pointer; background: transparent;">
                        </div>

                        <div class="admin-form-group" style="margin-top: 15px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 15px;">
                            <label>Imagem da Atendente (PNG Recomendado)</label>
                            <input type="file" name="support_banner_image" accept="image/*" style="width: 100%; color: var(--text-secondary); font-size: 13px;">
                            @if(\App\Models\Setting::get('support_banner_image'))
                                <div style="margin-top: 10px; background: #000; padding: 10px; border-radius: 8px; display: inline-block;">
                                    <img src="{{ asset(\App\Models\Setting::get('support_banner_image')) }}" style="max-height: 80px; display: block;">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="admin-btn-save" style="margin-top: 30px; width: 100%; padding: 15px; font-weight: 700; background: var(--color-yellow); color: #000; border: none; border-radius: 8px; cursor: pointer;">
                <i class="fa-solid fa-save"></i> SALVAR CONFIGURAÇÕES DE SUPORTE
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function previewUpload(input) {
        const previewImg = document.getElementById('imagePreview');
        const placeholder = document.getElementById('previewPlaceholder');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
                placeholder.style.display = 'none';
            }
            
            reader.readAsDataURL(input.files[0]);
        } else {
            previewImg.src = '#';
            previewImg.style.display = 'none';
            placeholder.style.display = 'block';
        }
    }

    function toggleEditBanner(id) {
        const panel = document.getElementById(id);
        if (panel) {
            panel.style.display = panel.style.display === 'block' ? 'none' : 'block';
        }
    }

    function handleTypeChange(val) {
        const helpText = document.getElementById('dimensionHelp');
        const orderGroup = document.getElementById('orderGroup');
        const urlGroup = document.getElementById('urlGroup');
        
        if (val === 'home_carousel') {
            helpText.innerText = 'Dimensão recomendada: 1200x400 para Carrossel Desktop.';
            orderGroup.style.display = 'block';
            urlGroup.style.display = 'block';
        } else if (val === 'login_modal') {
            helpText.innerText = 'Dimensão recomendada: 450x650 (Vertical). Imagem de lado no desktop.';
            orderGroup.style.display = 'none';
            urlGroup.style.display = 'none';
        } else if (val === 'register_modal') {
            helpText.innerText = 'Dimensão recomendada: 450x650 (Vertical). Imagem de lado no desktop.';
            orderGroup.style.display = 'none';
            urlGroup.style.display = 'none';
        } else if (val === 'login_modal_mobile') {
            helpText.innerText = 'Dimensão recomendada: 500x200 (Horizontal). Imagem no topo do modal no mobile.';
            orderGroup.style.display = 'none';
            urlGroup.style.display = 'none';
        } else if (val === 'register_modal_mobile') {
            helpText.innerText = 'Dimensão recomendada: 500x200 (Horizontal). Imagem no topo do modal no mobile.';
            orderGroup.style.display = 'none';
            urlGroup.style.display = 'none';
        }
    }

    let iframeInitialized = false;

    function showTab(tabId) {
        document.getElementById('banners-tab').style.display = tabId === 'banners-tab' ? 'block' : 'none';
        document.getElementById('colors-tab').style.display = tabId === 'colors-tab' ? 'block' : 'none';
        document.getElementById('support-tab').style.display = tabId === 'support-tab' ? 'block' : 'none';
        
        const btnBanners = document.getElementById('btn-banners-tab');
        const btnColors = document.getElementById('btn-colors-tab');
        const btnSupport = document.getElementById('btn-support-tab');
        const card = document.getElementById('personalization-card');
        
        // Reset buttons
        [btnBanners, btnColors, btnSupport].forEach(btn => {
            if (btn) {
                btn.style.background = 'rgba(255,255,255,0.05)';
                btn.style.color = '#fff';
                btn.style.border = '1px solid var(--border-color)';
            }
        });

        // Set active button
        const activeBtn = document.getElementById('btn-' + tabId);
        if (activeBtn) {
            activeBtn.style.background = 'var(--color-yellow)';
            activeBtn.style.color = '#000';
            activeBtn.style.border = 'none';
        }

        if (tabId === 'banners-tab') {
            if (card) card.style.maxWidth = '1000px';
        } else if (tabId === 'colors-tab') {
            if (card) card.style.maxWidth = '1400px';
            if (!iframeInitialized) {
                const iframe = document.getElementById('site-preview-iframe');
                if (iframe) {
                    iframe.src = "{{ route('home') }}";
                    iframeInitialized = true;
                }
            }
        } else {
            if (card) card.style.maxWidth = '1000px';
        }
    }

    // Toggle Preview Size (Desktop / Mobile)
    function setPreviewSize(mode) {
        const holder = document.getElementById('preview-frame-holder');
        const btnDesktop = document.getElementById('btn-preview-desktop');
        const btnMobile = document.getElementById('btn-preview-mobile');
        
        if (mode === 'desktop') {
            holder.style.width = '100%';
            holder.style.maxWidth = 'none';
            holder.style.height = '620px';
            holder.style.border = '1px solid var(--border-color)';
            holder.style.borderRadius = '8px';
            holder.style.padding = '0';
            
            btnDesktop.style.background = 'var(--color-yellow)';
            btnDesktop.style.color = '#000';
            btnDesktop.style.border = 'none';
            
            btnMobile.style.background = 'transparent';
            btnMobile.style.color = '#fff';
            btnMobile.style.border = 'none';
        } else {
            // Mobile simulation frame
            holder.style.width = '375px';
            holder.style.maxWidth = '375px';
            holder.style.height = '667px';
            holder.style.border = '12px solid #222'; // Phone shell border
            holder.style.borderRadius = '32px';
            holder.style.padding = '0';
            
            btnMobile.style.background = 'var(--color-yellow)';
            btnMobile.style.color = '#000';
            btnMobile.style.border = 'none';
            
            btnDesktop.style.background = 'transparent';
            btnDesktop.style.color = '#fff';
            btnDesktop.style.border = 'none';
        }
    }

    // Real-time dynamic preview synchronization via HTML5 postMessage
    document.addEventListener('DOMContentLoaded', () => {
        const colorInputs = document.querySelectorAll('#colors-tab input[type="color"]');
        const iframe = document.getElementById('site-preview-iframe');

        function hexToRgbaJs(hex, alpha) {
            hex = hex.replace('#', '');
            let r, g, b;
            if (hex.length === 3) {
                r = parseInt(hex.substring(0, 1) + hex.substring(0, 1), 16);
                g = parseInt(hex.substring(1, 2) + hex.substring(1, 2), 16);
                b = parseInt(hex.substring(2, 3) + hex.substring(2, 3), 16);
            } else if (hex.length === 6) {
                r = parseInt(hex.substring(0, 2), 16);
                g = parseInt(hex.substring(2, 4), 16);
                b = parseInt(hex.substring(4, 6), 16);
            } else {
                return hex;
            }
            return `rgba(${r}, ${g}, ${b}, ${alpha})`;
        }

        function updateIframePreview() {
            if (!iframe || !iframe.contentWindow) return;

            const variables = {};

            colorInputs.forEach(input => {
                const varName = input.name.replace('color_', '--').replace(/_/g, '-');
                variables[varName] = input.value;

                // Dynamically compute glows and transparencies in real-time
                if (input.name === 'color_neon_green') {
                    variables['--neon-green-glow'] = hexToRgbaJs(input.value, 0.4);
                    variables['--border-color'] = hexToRgbaJs(input.value, 0.1);
                    variables['--modal-border'] = hexToRgbaJs(input.value, 0.1);
                    variables['--modal-glow'] = hexToRgbaJs(input.value, 0.15);
                    variables['--btn-auth-submit-glow'] = hexToRgbaJs(input.value, 0.25);
                    variables['--btn-auth-submit-hover-glow'] = hexToRgbaJs(input.value, 0.35);
                    variables['--btn-cadastrar-glow'] = hexToRgbaJs(input.value, 0.2);
                    variables['--btn-cadastrar-hover-glow'] = hexToRgbaJs(input.value, 0.3);
                }
                if (input.name === 'color_gold') {
                    variables['--gold-glow'] = hexToRgbaJs(input.value, 0.4);
                }
                if (input.name === 'color_red') {
                    variables['--red-glow'] = hexToRgbaJs(input.value, 0.4);
                }
                if (input.name === 'color_btn_entrar_bg') {
                    variables['--btn-entrar-bg-hover'] = hexToRgbaJs(input.value, 0.15);
                }
                if (input.name === 'color_btn_entrar_border') {
                    variables['--btn-entrar-border-hover'] = hexToRgbaJs(input.value, 0.35);
                }
                if (input.name === 'color_btn_cadastrar_bg_start') {
                    variables['--btn-cadastrar-hover-start'] = hexToRgbaJs(input.value, 0.9);
                }
                if (input.name === 'color_btn_cadastrar_bg_end') {
                    variables['--btn-cadastrar-hover-end'] = hexToRgbaJs(input.value, 0.9);
                }
                if (input.name === 'color_btn_auth_submit_bg_start') {
                    variables['--btn-auth-submit-hover-start'] = hexToRgbaJs(input.value, 0.9);
                }
                if (input.name === 'color_btn_auth_submit_bg_end') {
                    variables['--btn-auth-submit-hover-end'] = hexToRgbaJs(input.value, 0.9);
                }
            });

            iframe.contentWindow.postMessage({
                type: 'update-css-variables',
                variables: variables
            }, '*');
        }

        colorInputs.forEach(input => {
            input.addEventListener('input', updateIframePreview);
            input.addEventListener('change', updateIframePreview);
        });

        // Trigger updates when iframe completely loads (reloads or navigates)
        iframe.addEventListener('load', () => {
            setTimeout(updateIframePreview, 100);
        });
    });
</script>
@endsection
