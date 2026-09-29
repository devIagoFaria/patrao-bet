@extends('layouts.admin')

@section('page_title', 'Configurações do Sistema')

@section('styles')
<style>
    /* Tabs styles */
    .settings-tabs-header {
        display: flex;
        gap: 8px;
        border-bottom: 1px solid var(--border-color);
        margin-bottom: 30px;
        padding-bottom: 10px;
        overflow-x: auto;
    }
    .settings-tab-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 600;
        color: var(--text-secondary);
        background: transparent;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        white-space: nowrap;
    }
    .settings-tab-btn i {
        font-size: 15px;
        transition: transform 0.2s ease;
    }
    .settings-tab-btn:hover {
        color: var(--text-primary);
        background-color: rgba(255, 255, 255, 0.03);
    }
    .settings-tab-btn:hover i {
        transform: translateY(-1px);
    }
    .settings-tab-btn.active {
        color: var(--color-yellow);
        background-color: rgba(248, 204, 27, 0.05);
        border-color: rgba(248, 204, 27, 0.15);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    .settings-tab-pane {
        display: none;
        animation: fadeInTab 0.35s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    }
    .settings-tab-pane.active {
        display: block;
    }
    @keyframes fadeInTab {
        from {
            opacity: 0;
            transform: translateY(6px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection

@section('content')
<div class="admin-card-table" style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 24px; border-bottom: 1px solid var(--border-color); padding-bottom: 16px;">
        <h3 style="font-family: var(--font-heading); font-size: 18px; font-weight: 700;">Configurações Globais</h3>
        <p style="font-size: 12.5px; color: var(--text-secondary); margin-top: 4px;">Gerencie a identidade visual, limites financeiros, regras de bônus e integrações do sistema.</p>
    </div>

    <!-- Tabs Navigation -->
    <div class="settings-tabs-header">
        <button type="button" class="settings-tab-btn active" data-tab="tab-geral">
            <i class="fa-solid fa-palette"></i>
            <span>Identidade Visual</span>
        </button>
        <button type="button" class="settings-tab-btn" data-tab="tab-limites">
            <i class="fa-solid fa-wallet"></i>
            <span>Limites Financeiros</span>
        </button>
        <button type="button" class="settings-tab-btn" data-tab="tab-bonus">
            <i class="fa-solid fa-gift"></i>
            <span>Regras de Bônus</span>
        </button>
        <button type="button" class="settings-tab-btn" data-tab="tab-kyc">
            <i class="fa-solid fa-user-shield"></i>
            <span>Integração Didit KYC</span>
        </button>
        <button type="button" class="settings-tab-btn" data-tab="tab-playfiver">
            <i class="fa-solid fa-gamepad"></i>
            <span>Integração PlayFiver</span>
        </button>
        <button type="button" class="settings-tab-btn" data-tab="tab-maxapigames">
            <i class="fa-solid fa-dice"></i>
            <span>Integração MAX API GAMES</span>
        </button>
        <button type="button" class="settings-tab-btn" data-tab="tab-deposit-modal">
            <i class="fa-solid fa-money-bill-transfer"></i>
            <span>Modal de Depósito</span>
        </button>
        <button type="button" class="settings-tab-btn" data-tab="tab-heartpay">
            <i class="fa-solid fa-credit-card"></i>
            <span>Integração HeartPay</span>
        </button>
        <button type="button" class="settings-tab-btn" data-tab="tab-bottom-nav">
            <i class="fa-solid fa-mobile-screen-button"></i>
            <span>Menu Inferior</span>
        </button>
        <button type="button" class="settings-tab-btn" data-tab="tab-affiliates-jivo">
            <i class="fa-solid fa-users"></i>
            <span>Afiliados & Chat</span>
        </button>
        <button type="button" class="settings-tab-btn" data-tab="tab-roleta">
            <i class="fa-solid fa-arrows-spin"></i>
            <span>Roleta Diária</span>
        </button>
        <button type="button" class="settings-tab-btn" data-tab="tab-footer">
            <i class="fa-solid fa-window-maximize"></i>
            <span>Rodapé (Footer)</span>
        </button>
        <button type="button" class="settings-tab-btn" data-tab="tab-header">
            <i class="fa-solid fa-window-restore"></i>
            <span>Cabeçalho (Header)</span>
        </button>
        <button type="button" class="settings-tab-btn" data-tab="tab-email">
            <i class="fa-solid fa-envelope"></i>
            <span>E-mail (SMTP)</span>
        </button>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <!-- TAB CONTENT 1: Identidade Visual -->
        <div id="tab-geral" class="settings-tab-pane active">
            <h4 style="font-family: var(--font-heading); font-size: 14px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 16px; letter-spacing: 0.5px;">1. Identidade Visual</h4>
            
            <div class="admin-form-group">
                <label for="site_name">Nome do Site</label>
                <input type="text" id="site_name" name="site_name" value="{{ $siteName }}" required placeholder="ex: patrao.bet">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                <!-- Logo Upload & Preview -->
                <div class="admin-form-group" style="background-color: #161619; padding: 20px; border-radius: 8px; border: 1px solid var(--border-color);">
                    <label style="margin-bottom: 12px; display: block;">Logotipo do Cassino</label>
                    <input type="file" id="site_logo" name="site_logo" accept="image/*" onchange="previewImage(this, 'logoPreviewContainer', 'logoPreviewImg')">
                    <span class="admin-form-help">Envie uma imagem transparente (PNG recomendado). Tamanho máx: 2MB.</span>
                    
                    <div id="logoPreviewContainer" style="margin-top: 16px; text-align: center; border: 1px dashed var(--border-color); padding: 12px; border-radius: 8px; background-color: #0c0c0e; display: {{ $siteLogo ? 'block' : 'none' }}">
                        <p style="font-size: 10px; color: var(--text-muted); margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Visualização do Logotipo</p>
                        <img id="logoPreviewImg" src="{{ $siteLogo ? asset($siteLogo) : '#' }}" alt="Prévia do Logo" style="max-height: 50px; max-width: 100%; object-fit: contain;">
                    </div>
                </div>

                <!-- Favicon Upload & Preview -->
                <div class="admin-form-group" style="background-color: #161619; padding: 20px; border-radius: 8px; border: 1px solid var(--border-color);">
                    <label style="margin-bottom: 12px; display: block;">Ícone Favicon</label>
                    <input type="file" id="site_favicon" name="site_favicon" accept="image/x-icon,image/png,image/jpeg" onchange="previewImage(this, 'faviconPreviewContainer', 'faviconPreviewImg')">
                    <span class="admin-form-help">Favicon do navegador (formato .ico ou .png). Tamanho máx: 1MB.</span>
                    
                    <div id="faviconPreviewContainer" style="margin-top: 16px; text-align: center; border: 1px dashed var(--border-color); padding: 12px; border-radius: 8px; background-color: #0c0c0e; display: {{ $siteFavicon ? 'block' : 'none' }}">
                        <p style="font-size: 10px; color: var(--text-muted); margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Visualização do Favicon</p>
                        <img id="faviconPreviewImg" src="{{ $siteFavicon ? asset($siteFavicon) : '#' }}" alt="Prévia do Favicon" style="width: 32px; height: 32px; object-fit: contain;">
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB CONTENT 2: Limites Financeiros -->
        <div id="tab-limites" class="settings-tab-pane">
            <h4 style="font-family: var(--font-heading); font-size: 14px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 16px; letter-spacing: 0.5px;">2. Limites Financeiros</h4>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="admin-form-group">
                    <label for="min_deposit">Depósito Mínimo (R$)</label>
                    <input type="number" id="min_deposit" name="min_deposit" value="{{ $minDeposit }}" step="0.01" min="0.01" required>
                </div>
                
                <div class="admin-form-group">
                    <label for="max_deposit">Depósito Máximo (R$)</label>
                    <input type="number" id="max_deposit" name="max_deposit" value="{{ $maxDeposit }}" step="0.01" min="0.01" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="admin-form-group">
                    <label for="min_withdrawal">Saque Mínimo (R$)</label>
                    <input type="number" id="min_withdrawal" name="min_withdrawal" value="{{ $minWithdrawal }}" step="0.01" min="0.01" required>
                </div>
                
                <div class="admin-form-group">
                    <label for="max_withdrawal">Saque Máximo (R$)</label>
                    <input type="number" id="max_withdrawal" name="max_withdrawal" value="{{ $maxWithdrawal }}" step="0.01" min="0.01" required>
                </div>
            </div>
        </div>

        <!-- TAB CONTENT 3: Regras de Bônus -->
        <div id="tab-bonus" class="settings-tab-pane">
            <h4 style="font-family: var(--font-heading); font-size: 14px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 16px; letter-spacing: 0.5px;">3. Regras de Bônus</h4>
            
            <div class="admin-form-group">
                <label for="bonus_rollover">Rollover de Bônus (Multiplicador)</label>
                <input type="number" id="bonus_rollover" name="bonus_rollover" value="{{ $bonusRollover }}" min="1" required>
                <span class="admin-form-help">Número de vezes que o jogador deve apostar o valor do bônus antes de liberá-lo para saque (ex: 10 = rollover de 10x).</span>
            </div>

            <div class="admin-form-group">
                <label for="welcome_bonus_amount">Bônus de Entrada / Boas-vindas (R$)</label>
                <input type="number" id="welcome_bonus_amount" name="welcome_bonus_amount" value="{{ $welcomeBonusAmount }}" step="0.01" min="0" required>
                <span class="admin-form-help">Valor em reais creditado na conta do usuário no momento do cadastro.</span>
            </div>

            <div style="background-color: #161619; padding: 20px; border-radius: 8px; border: 1px solid var(--border-color); margin-bottom: 24px;">
                <label style="margin-bottom: 12px; display: block; font-weight: 700; color: #fff;">Bônus de Depósitos Progressivos (%)</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                    <div class="admin-form-group">
                        <label for="bonus_progressive_1">1º Depósito (%)</label>
                        <input type="number" id="bonus_progressive_1" name="bonus_progressive_1" value="{{ $bonusProgressive1 }}" min="0" required>
                    </div>
                    <div class="admin-form-group">
                        <label for="bonus_progressive_2">2º Depósito (%)</label>
                        <input type="number" id="bonus_progressive_2" name="bonus_progressive_2" value="{{ $bonusProgressive2 }}" min="0" required>
                    </div>
                    <div class="admin-form-group">
                        <label for="bonus_progressive_3">3º Depósito (%)</label>
                        <input type="number" id="bonus_progressive_3" name="bonus_progressive_3" value="{{ $bonusProgressive3 }}" min="0" required>
                    </div>
                    <div class="admin-form-group">
                        <label for="bonus_progressive_4">4º Depósito (%)</label>
                        <input type="number" id="bonus_progressive_4" name="bonus_progressive_4" value="{{ $bonusProgressive4 }}" min="0" required>
                    </div>
                </div>
                <span class="admin-form-help" style="margin-top: 8px; display: block;">Percentual extra creditado sobre cada depósito (ex: 100 = 100% de bônus sobre o depósito).</span>
            </div>
        </div>

        <!-- TAB CONTENT 4: Integração Didit KYC -->
        <div id="tab-kyc" class="settings-tab-pane">
            <h4 style="font-family: var(--font-heading); font-size: 14px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 16px; letter-spacing: 0.5px;">4. Integração Didit KYC</h4>
            
            <div class="admin-form-group">
                <label for="didit_api_key">Chave de API do Didit (x-api-key)</label>
                <input type="text" id="didit_api_key" name="didit_api_key" value="{{ $diditApiKey }}" placeholder="Insira a API Key secreta obtida na Didit">
                <span class="admin-form-help">Chave secreta de produção gerada na aba de "Programadores" da Consola Didit.</span>
            </div>

            <div class="admin-form-group">
                <label for="didit_workflow_id">ID do Fluxo de Trabalho (Workflow ID)</label>
                <input type="text" id="didit_workflow_id" name="didit_workflow_id" value="{{ $diditWorkflowId }}" placeholder="ex: bcf57002-cb39-49fe-9707-46e32650275b">
                <span class="admin-form-help">ID do fluxo KYC criado na Didit. Padrão: bcf57002-cb39-49fe-9707-46e32650275b.</span>
            </div>
        </div>

        <!-- TAB CONTENT 5: Integração PlayFiver -->
        <div id="tab-playfiver" class="settings-tab-pane">
            <h4 style="font-family: var(--font-heading); font-size: 14px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 16px; letter-spacing: 0.5px;">5. Integração PlayFiver</h4>
            
            <div class="admin-form-group">
                <label for="playfiver_agent_token">Token do Agente (agentToken)</label>
                <input type="text" id="playfiver_agent_token" name="playfiver_agent_token" value="{{ $playfiverAgentToken }}" placeholder="Insira o Agent Token da PlayFiver">
                <span class="admin-form-help">Obtido no painel da PlayFiver em Integrações.</span>
            </div>

            <div class="admin-form-group">
                <label for="playfiver_secret_key">Chave Secreta (secretKey)</label>
                <input type="password" id="playfiver_secret_key" name="playfiver_secret_key" value="{{ $playfiverSecretKey }}" placeholder="Insira a Secret Key da PlayFiver">
                <span class="admin-form-help">Chave secreta para autenticação das chamadas de API.</span>
            </div>
        </div>

        <!-- TAB CONTENT 5B: Integração MAX API GAMES -->
        <div id="tab-maxapigames" class="settings-tab-pane">
            <h4 style="font-family: var(--font-heading); font-size: 14px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 16px; letter-spacing: 0.5px;">Integração MAX API GAMES</h4>

            <div class="admin-form-group">
                <label for="maxapigames_agent_code">Código do Agente (agent_code)</label>
                <input type="text" id="maxapigames_agent_code" name="maxapigames_agent_code" value="{{ $maxapigamesAgentCode }}" placeholder="Insira o Agent Code da MAX API GAMES">
                <span class="admin-form-help">Obtido no painel da MAX API GAMES em "Meus Agentes".</span>
            </div>

            <div class="admin-form-group">
                <label for="maxapigames_agent_token">Token do Agente (agent_token)</label>
                <input type="password" id="maxapigames_agent_token" name="maxapigames_agent_token" value="{{ $maxapigamesAgentToken }}" placeholder="Insira o Agent Token da MAX API GAMES">
                <span class="admin-form-help">Chave usada para autenticar as chamadas feitas à API (game_launch, game_list, etc).</span>
            </div>

            <div class="admin-form-group">
                <label for="maxapigames_agent_secret">Chave Secreta do Webhook (agent_secret)</label>
                <input type="password" id="maxapigames_agent_secret" name="maxapigames_agent_secret" value="{{ $maxapigamesAgentSecret }}" placeholder="Insira o Agent Secret da MAX API GAMES">
                <span class="admin-form-help">Usada para validar os webhooks recebidos da MAX API GAMES (modo seamless). Diferente do agent_token.</span>
            </div>

            <div class="admin-form-group">
                <label>URL de Callback (Webhook)</label>
                <input type="text" value="{{ route('webhook.maxapigames') }}" readonly onclick="this.select()">
                <span class="admin-form-help">Configure esta URL como webhook_url do agente no painel da MAX API GAMES.</span>
            </div>
        </div>

        <!-- TAB CONTENT 6: Modal de Depósito Customization -->
        <div id="tab-deposit-modal" class="settings-tab-pane">
            <h4 style="font-family: var(--font-heading); font-size: 14px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 16px; letter-spacing: 0.5px;">6. Customização do Modal de Depósito</h4>
            
            <div class="admin-form-group">
                <label for="deposit_promo_title">Título da Oferta Promocional</label>
                <input type="text" id="deposit_promo_title" name="deposit_promo_title" value="{{ $depositPromoTitle }}" required placeholder="ex: OFERTA ÚNICA, NÃO SAIA! 🔥">
            </div>

            <div class="admin-form-group">
                <label for="deposit_promo_desc">Descrição da Oferta Promocional</label>
                <input type="text" id="deposit_promo_desc" name="deposit_promo_desc" value="{{ $depositPromoDesc }}" required placeholder="ex: Deposite 50 ou mais e ganhe 20 giros no fortune rabbit!">
                <span class="admin-form-help">Nota: Você pode usar links ou sublinhar termos adicionando elementos HTML se necessário, ou texto simples.</span>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="admin-form-group">
                    <label for="deposit_promo_timer">Duração do Cronômetro (minutos)</label>
                    <input type="number" id="deposit_promo_timer" name="deposit_promo_timer" value="{{ $depositPromoTimer }}" min="1" required placeholder="ex: 5">
                </div>
                
                <div class="admin-form-group">
                    <label for="deposit_promo_terms">Termos / Condição da Oferta</label>
                    <input type="text" id="deposit_promo_terms" name="deposit_promo_terms" value="{{ $depositPromoTerms }}" required placeholder="ex: CONDIÇÃO VÁLIDA PARA PRIMEIRO DEPÓSITO">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="admin-form-group">
                    <label for="deposit_quick_amounts">Valores Rápidos de Depósito (Separados por vírgula)</label>
                    <input type="text" id="deposit_quick_amounts" name="deposit_quick_amounts" value="{{ $depositQuickAmounts }}" required placeholder="ex: 20, 50, 100, 250, 500, 1000">
                    <span class="admin-form-help">Valores em R$ disponíveis nos botões de clique rápido.</span>
                </div>
                
                <div class="admin-form-group">
                    <label for="deposit_hot_amounts">Valores em Destaque "HOT" (Separados por vírgula)</label>
                    <input type="text" id="deposit_hot_amounts" name="deposit_hot_amounts" value="{{ $depositHotAmounts }}" required placeholder="ex: 50, 250, 1000">
                    <span class="admin-form-help">Dentre os valores acima, quais devem exibir a badge "HOT" amarela.</span>
                </div>
            </div>

            <!-- Promo Image Upload & Preview -->
            <div class="admin-form-group" style="background-color: #161619; padding: 20px; border-radius: 8px; border: 1px solid var(--border-color); margin-bottom: 24px;">
                <label style="margin-bottom: 12px; display: block;">Imagem Promocional (Mascote)</label>
                <input type="file" id="deposit_promo_image" name="deposit_promo_image" accept="image/*" onchange="previewImage(this, 'promoPreviewContainer', 'promoPreviewImg')">
                <span class="admin-form-help">Mascote flutuando à direita do banner (Ex: Fortune Rabbit). Tamanho máx: 2MB. Se vazio, o padrão do coelho do Fortune Rabbit será exibido.</span>
                
                <div id="promoPreviewContainer" style="margin-top: 16px; text-align: center; border: 1px dashed var(--border-color); padding: 12px; border-radius: 8px; background-color: #0c0c0e; display: {{ $depositPromoImage ? 'block' : 'none' }}">
                    <p style="font-size: 10px; color: var(--text-muted); margin-bottom: 8px; font-weight: 700; text-transform: uppercase;">Visualização da Imagem</p>
                    <img id="promoPreviewImg" src="{{ $depositPromoImage ? asset($depositPromoImage) : '#' }}" alt="Prévia do Mascote" style="max-height: 120px; max-width: 100%; object-fit: contain;">
                </div>
            </div>
        </div>

        <!-- TAB CONTENT 7: Integração HeartPay -->
        <div id="tab-heartpay" class="settings-tab-pane">
            <h4 style="font-family: var(--font-heading); font-size: 14px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 16px; letter-spacing: 0.5px;">7. Integração HeartPay</h4>
            
            <div class="admin-form-group">
                <label for="heartpay_api_key">Chave de API (hpay_...)</label>
                <input type="text" id="heartpay_api_key" name="heartpay_api_key" value="{{ $heartpayApiKey }}" placeholder="Insira a chave secreta da HeartPay">
                <span class="admin-form-help">Token Bearer gerado no painel da HeartPay (ex: hpay_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx).</span>
            </div>

            <div class="admin-form-group">
                <label for="heartpay_webhook_token">Token de Validação do Webhook (whsec_...)</label>
                <input type="text" id="heartpay_webhook_token" name="heartpay_webhook_token" value="{{ $heartpayWebhookToken }}" placeholder="Insira o Token do Webhook">
                <span class="admin-form-help">Chave secreta obtida na configuração de Webhooks da HeartPay para validar a assinatura HMAC-SHA256.</span>
            </div>
            
            <div class="admin-form-group" style="background-color: #161619; padding: 20px; border-radius: 8px; border: 1px solid var(--border-color); margin-top: 20px;">
                <label style="margin-bottom: 8px; display: block; font-weight: 600; color: var(--color-yellow);">Configuração do Webhook na HeartPay</label>
                <p style="font-size: 12.5px; color: var(--text-secondary); line-height: 1.5;">
                    Para receber as confirmações de depósito e saques automaticamente, você deve cadastrar a seguinte URL de Webhook no painel da HeartPay:
                </p>
                <div style="background-color: #0c0c0e; border: 1px solid var(--border-color); padding: 12px; border-radius: 6px; font-family: monospace; font-size: 12px; color: #fff; margin-top: 10px; display: flex; justify-content: space-between; align-items: center;">
                    <span>{{ url('/webhook/heartpay') }}</span>
                    <button type="button" onclick="navigator.clipboard.writeText('{{ url('/webhook/heartpay') }}').then(() => alert('URL copiada!'))" style="background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); color: #fff; padding: 4px 8px; border-radius: 4px; font-size: 11px; cursor: pointer; font-family: var(--font-sans); font-weight: 600;">COPIAR</button>
                </div>
            </div>
        </div>

        <!-- TAB CONTENT 8: Menu Inferior Mobile -->
        <div id="tab-bottom-nav" class="settings-tab-pane">
            <h4 style="font-family: var(--font-heading); font-size: 14px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 16px; letter-spacing: 0.5px;">8. Customização do Menu Inferior Mobile</h4>
            
            <p style="font-size: 12.5px; color: var(--text-secondary); margin-bottom: 20px;">
                Configure os links e ações para cada um dos 5 botões do menu inferior de dispositivos móveis.
            </p>

            @for($i = 1; $i <= 5; $i++)
                @php
                    $defaultNames = [
                        1 => 'MENU',
                        2 => 'RECOMPENSAS',
                        3 => 'CASSINO',
                        4 => 'ESPORTES',
                        5 => 'LOJA'
                    ];
                    $defaultActions = [
                        1 => 'sidebar',
                        2 => 'openAccountModal:premios',
                        3 => '#cassino',
                        4 => '/esportes',
                        5 => "javascript:closeProfileDrawer(); alert('Loja: Troque seus pontos por recompensas!');"
                    ];
                    $defaultIcons = [
                        1 => 'hamburger',
                        2 => asset('imagens/rewards.png'),
                        3 => asset('imagens/cassino.png'),
                        4 => asset('imagens/sports.png'),
                        5 => asset('imagens/loja.png')
                    ];
                    
                    $btnTextVar = "bottomNavBtn{$i}Text";
                    $btnActionVar = "bottomNavBtn{$i}Action";
                    $btnIconVar = "bottomNavBtn{$i}Icon";
                    
                    $btnText = $$btnTextVar;
                    $btnAction = $$btnActionVar;
                    $btnIcon = $$btnIconVar;
                @endphp
                <div style="background-color: #161619; padding: 15px; border-radius: 8px; border: 1px solid var(--border-color); margin-bottom: 15px;">
                    <h5 style="margin-bottom: 12px; font-weight: 600; color: #fff;">Botão {{ $i }}</h5>
                    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; align-items: start;">
                        <div style="display: flex; flex-direction: column; gap: 15px;">
                            <div class="admin-form-group" style="margin-bottom: 0;">
                                <label>Texto do Botão</label>
                                <input type="text" name="bottom_nav_btn_{{ $i }}_text" value="{{ $btnText }}" required placeholder="Ex: {{ $defaultNames[$i] }}" oninput="document.getElementById('btn_{{ $i }}_preview_text').textContent = this.value">
                            </div>
                            <div class="admin-form-group" style="margin-bottom: 0;">
                                <label>Ação / Link do Botão</label>
                                <input type="text" name="bottom_nav_btn_{{ $i }}_action" value="{{ $btnAction }}" required placeholder="Ex: {{ $defaultActions[$i] }}">
                                <span class="admin-form-help" style="font-size: 10px;">
                                    Use <strong>sidebar</strong> para abrir o menu lateral, 
                                    <strong>openAccountModal:nome_da_aba</strong> para abrir aba do perfil (ex: <i>premios, carteira, dados, historico</i>), 
                                    <strong>#cassino</strong> para ir aos slots, ou links/scripts.
                                </span>
                            </div>
                        </div>
                        
                        <div style="background-color: #0c0c0e; padding: 15px; border-radius: 8px; border: 1px dashed var(--border-color); text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px;">
                            <span style="font-size: 11px; font-weight: 600; color: var(--text-secondary); text-transform: uppercase;">Ícone / Imagem</span>
                            
                            <!-- Icon preview wrapper styled like bottom nav card -->
                            <div style="width: 70px; height: 50px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: #161619; border: 1px solid var(--border-color); border-radius: 6px; padding: 5px; position: relative;">
                                <div id="btn_{{ $i }}_preview_icon_container" style="display: flex; align-items: center; justify-content: center; width: 22px; height: 22px;">
                                    @if($defaultIcons[$i] === 'hamburger')
                                        <div id="btn_{{ $i }}_preview_hamburger" style="display: {{ $btnIcon ? 'none' : 'flex' }}; flex-direction: column; gap: 3px; width: 18px; justify-content: center; align-items: center;">
                                            <span style="display: block; width: 100%; height: 2px; background-color: var(--color-yellow); border-radius: 1px;"></span>
                                            <span style="display: block; width: 100%; height: 2px; background-color: var(--color-yellow); border-radius: 1px;"></span>
                                            <span style="display: block; width: 100%; height: 2px; background-color: var(--color-yellow); border-radius: 1px;"></span>
                                        </div>
                                        <img id="btn_{{ $i }}_preview_img" src="{{ $btnIcon ? asset($btnIcon) : '#' }}" style="width: 22px; height: 22px; object-fit: contain; display: {{ $btnIcon ? 'block' : 'none' }};">
                                    @else
                                        <img id="btn_{{ $i }}_preview_img" src="{{ $btnIcon ? asset($btnIcon) : $defaultIcons[$i] }}" style="width: 22px; height: 22px; object-fit: contain;">
                                    @endif
                                </div>
                                <span id="btn_{{ $i }}_preview_text" style="font-size: 8px; font-weight: 700; color: #fff; margin-top: 4px; text-transform: uppercase; max-width: 60px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $btnText }}</span>
                            </div>

                            <input type="file" id="bottom_nav_btn_{{ $i }}_icon" name="bottom_nav_btn_{{ $i }}_icon" accept="image/*" style="display: none;" onchange="previewNavIcon(this, {{ $i }})">
                            <button type="button" onclick="document.getElementById('bottom_nav_btn_{{ $i }}_icon').click()" style="background: rgba(248, 204, 27, 0.1); border: 1px solid rgba(248, 204, 27, 0.2); color: var(--color-yellow); font-size: 11px; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: 600; width: 100%;">Mudar Imagem</button>
                            
                            <input type="hidden" name="remove_bottom_nav_btn_{{ $i }}_icon" id="remove_bottom_nav_btn_{{ $i }}_icon" value="0">
                            <button type="button" id="btn_{{ $i }}_remove_btn" onclick="removeNavIcon({{ $i }})" style="background: rgba(255, 61, 0, 0.1); border: 1px solid rgba(255, 61, 0, 0.2); color: #ff3d00; font-size: 11px; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: 600; width: 100%; display: {{ $btnIcon ? 'block' : 'none' }};">Remover Ícone</button>
                        </div>
                    </div>
                </div>
            @endfor
        </div>

        <!-- TAB CONTENT: Afiliados & Chat -->
        <div id="tab-affiliates-jivo" class="settings-tab-pane">
            <h4 style="font-family: var(--font-heading); font-size: 14px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 16px; letter-spacing: 0.5px;">Afiliados & Chat de Suporte</h4>
            
            <div class="admin-form-group">
                <label for="affiliate_commission_percent">Comissão de Indicação (%)</label>
                <input type="number" id="affiliate_commission_percent" name="affiliate_commission_percent" value="{{ $affiliateCommissionPercent }}" min="0" max="100" required>
                <span class="admin-form-help">Percentual de comissão que o afiliado padrinho recebe sobre os depósitos dos seus indicados.</span>
            </div>

            <div class="admin-form-group">
                <label for="jivochat_widget_id">ID do Widget JivoChat</label>
                <input type="text" id="jivochat_widget_id" name="jivochat_widget_id" value="{{ $jivoChatWidgetId }}" placeholder="ex: abCd12ef3G">
                <span class="admin-form-help">Identificador do widget da JivoChat. Deixe vazio para desativar o chat de suporte.</span>
            </div>
        </div>

        <!-- TAB CONTENT: Roleta Diária -->
        <div id="tab-roleta" class="settings-tab-pane">
            <h4 style="font-family: var(--font-heading); font-size: 14px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 16px; letter-spacing: 0.5px;">Configurações da Roleta Diária</h4>
            <p style="font-size: 12.5px; color: var(--text-secondary); margin-bottom: 20px;">Defina os prêmios e as probabilidades (pesos) para cada uma das 6 fatias da roleta.</p>

            <div style="display: grid; grid-template-columns: 1fr; gap: 20px;">
                @for ($i = 0; $i <= 4; $i++)
                    <div style="background-color: #161619; padding: 16px; border-radius: 8px; border: 1px solid var(--border-color); display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                        <div class="admin-form-group" style="margin-bottom: 0;">
                            <label>Prêmio Fatia {{ $i + 1 }} (R$ Bônus)</label>
                            <input type="number" name="spin_reward_{{ $i }}_amount" value="{{ ${'spinReward' . $i . 'Amount'} }}" step="0.01" min="0" required>
                        </div>
                        <div class="admin-form-group" style="margin-bottom: 0;">
                            <label>Peso / Probabilidade Fatia {{ $i + 1 }}</label>
                            <input type="number" name="spin_reward_{{ $i }}_weight" value="{{ ${'spinReward' . $i . 'Weight'} }}" min="0" required>
                        </div>
                    </div>
                @endfor

                <!-- Fatia 6 (Sem sorte / Sem prêmio) -->
                <div style="background-color: #161619; padding: 16px; border-radius: 8px; border: 1px solid var(--border-color); display: grid; grid-template-columns: 1fr; gap: 16px;">
                    <div class="admin-form-group" style="margin-bottom: 0;">
                        <label>Fatia 6 (Sem Sorte hoje) - Peso / Probabilidade</label>
                        <input type="number" name="spin_reward_5_weight" value="{{ $spinReward5Weight }}" min="0" required>
                    </div>
                </div>
            </div>
            <span class="admin-form-help" style="margin-top: 12px; display: block;">Quanto maior o peso de uma fatia em relação à soma total dos pesos, maior a probabilidade do jogador cair nela.</span>
        </div>

        <!-- TAB CONTENT: Rodapé -->
        <div id="tab-footer" class="settings-tab-pane">
            <h4 style="font-family: var(--font-heading); font-size: 14px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 16px; letter-spacing: 0.5px;">Configurações do Rodapé</h4>
            
            <div class="admin-form-group">
                <label for="footer_description">Descrição da Marca</label>
                <textarea id="footer_description" name="footer_description" rows="3" style="width: 100%; background: #161619; border: 1px solid var(--border-color); color: #fff; border-radius: 8px; padding: 12px; font-size: 14px;">{{ $footerDescription }}</textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                <div class="admin-form-group">
                    <label>Título Coluna 1</label>
                    <input type="text" name="footer_col1_title" value="{{ $footerCol1Title }}" required>
                </div>
                <div class="admin-form-group">
                    <label>Título Coluna 2</label>
                    <input type="text" name="footer_col2_title" value="{{ $footerCol2Title }}" required>
                </div>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr; gap: 20px; margin-bottom: 24px;">
                <div class="admin-form-group">
                    <label>Título Coluna 3</label>
                    <input type="text" name="footer_col3_title" value="{{ $footerCol3Title }}" required>
                </div>
            </div>

            <!-- Links Column 1 -->
            <h5 style="margin-bottom: 12px; font-weight: 600; color: #fff;">Links Coluna 1</h5>
            @for($i = 1; $i <= 5; $i++)
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                    <input type="text" name="footer_col1_link_{{ $i }}_text" value="{{ \App\Models\Setting::get('footer_col1_link_'.$i.'_text') }}" placeholder="Texto Link {{ $i }}">
                    <input type="text" name="footer_col1_link_{{ $i }}_url" value="{{ \App\Models\Setting::get('footer_col1_link_'.$i.'_url', '#') }}" placeholder="URL Link {{ $i }}">
                </div>
            @endfor

            <!-- Links Column 2 -->
            <h5 style="margin: 20px 0 12px 0; font-weight: 600; color: #fff;">Links Coluna 2</h5>
            @for($i = 1; $i <= 5; $i++)
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                    <input type="text" name="footer_col2_link_{{ $i }}_text" value="{{ \App\Models\Setting::get('footer_col2_link_'.$i.'_text') }}" placeholder="Texto Link {{ $i }}">
                    <input type="text" name="footer_col2_link_{{ $i }}_url" value="{{ \App\Models\Setting::get('footer_col2_link_'.$i.'_url', '#') }}" placeholder="URL Link {{ $i }}">
                </div>
            @endfor

            <!-- Links Column 3 -->
            <h5 style="margin: 20px 0 12px 0; font-weight: 600; color: #fff;">Links Coluna 3</h5>
            @for($i = 1; $i <= 5; $i++)
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                    <input type="text" name="footer_col3_link_{{ $i }}_text" value="{{ \App\Models\Setting::get('footer_col3_link_'.$i.'_text') }}" placeholder="Texto Link {{ $i }}">
                    <input type="text" name="footer_col3_link_{{ $i }}_url" value="{{ \App\Models\Setting::get('footer_col3_link_'.$i.'_url', '#') }}" placeholder="URL Link {{ $i }}">
                </div>
            @endfor

            <h5 style="margin: 20px 0 12px 0; font-weight: 600; color: #fff;">Redes Sociais</h5>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                <div class="admin-form-group">
                    <label>X (Twitter)</label>
                    <input type="text" name="footer_social_twitter" value="{{ $footerSocialTwitter }}" placeholder="#">
                </div>
                <div class="admin-form-group">
                    <label>YouTube</label>
                    <input type="text" name="footer_social_youtube" value="{{ $footerSocialYoutube }}" placeholder="#">
                </div>
                <div class="admin-form-group">
                    <label>Facebook</label>
                    <input type="text" name="footer_social_facebook" value="{{ $footerSocialFacebook }}" placeholder="#">
                </div>
                <div class="admin-form-group">
                    <label>Instagram</label>
                    <input type="text" name="footer_social_instagram" value="{{ $footerSocialInstagram }}" placeholder="#">
                </div>
            </div>

            <div class="admin-form-group">
                <label for="footer_disclaimer">Texto de Disclaimer (Legal)</label>
                <textarea id="footer_disclaimer" name="footer_disclaimer" rows="6" style="width: 100%; background: #161619; border: 1px solid var(--border-color); color: #fff; border-radius: 8px; padding: 12px; font-size: 13px; line-height: 1.5;">{{ $footerDisclaimer }}</textarea>
            </div>
        </div>

        <!-- TAB CONTENT: Cabeçalho -->
        <div id="tab-header" class="settings-tab-pane">
            <h4 style="font-family: var(--font-heading); font-size: 14px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 16px; letter-spacing: 0.5px;">Configurações do Cabeçalho</h4>
            
            <h5 style="margin-bottom: 12px; font-weight: 600; color: #fff;">Links de Navegação</h5>
            @for($i = 1; $i <= 5; $i++)
                <div style="background-color: #161619; padding: 15px; border-radius: 8px; border: 1px solid var(--border-color); margin-bottom: 15px;">
                    <h6 style="margin-bottom: 10px; font-weight: 600; color: var(--color-yellow);">Link {{ $i }}</h6>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 10px;">
                        <div class="admin-form-group" style="margin-bottom: 0;">
                            <label>Texto</label>
                            <input type="text" name="header_link_{{ $i }}_text" value="{{ ${'headerLink' . $i . 'Text'} }}" placeholder="Ex: Início">
                        </div>
                        <div class="admin-form-group" style="margin-bottom: 0;">
                            <label>URL / Link</label>
                            <input type="text" name="header_link_{{ $i }}_url" value="{{ ${'headerLink' . $i . 'Url'} }}" placeholder="Ex: /home ou #secao">
                        </div>
                    </div>
                    <div class="admin-form-group" style="margin-bottom: 0;">
                        <label>Ação JavaScript (onclick) - Opcional</label>
                        <input type="text" name="header_link_{{ $i }}_onclick" value="{{ ${'headerLink' . $i . 'Onclick'} }}" placeholder="Ex: scrollToSection('id')">
                    </div>
                </div>
            @endfor

            <h5 style="margin: 20px 0 12px 0; font-weight: 600; color: #fff;">Botões de Ação</h5>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px;">
                <div class="admin-form-group">
                    <label>Texto Botão Entrar</label>
                    <input type="text" name="header_btn_login_text" value="{{ $headerBtnLoginText }}" placeholder="ENTRAR">
                </div>
                <div class="admin-form-group">
                    <label>Texto Botão Cadastrar</label>
                    <input type="text" name="header_btn_register_text" value="{{ $headerBtnRegisterText }}" placeholder="CADASTRAR">
                </div>
                <div class="admin-form-group">
                    <label>Texto Badge PIX</label>
                    <input type="text" name="header_pix_badge_text" value="{{ $headerPixBadgeText }}" placeholder="PIX">
                </div>
            </div>
        </div>

        <!-- TAB CONTENT: E-mail (SMTP) -->
        <div id="tab-email" class="settings-tab-pane">
            <h4 style="font-family: var(--font-heading); font-size: 14px; font-weight: 700; color: var(--color-yellow); text-transform: uppercase; margin-bottom: 16px; letter-spacing: 0.5px;">Servidor de E-mail (SMTP)</h4>
            <p style="font-size: 12.5px; color: var(--text-secondary); margin-bottom: 20px;">
                Usado para enviar o código de recuperação de senha quando um jogador clica em "Esqueci minha senha". Preencha com os dados do seu provedor de SMTP (Gmail, SendGrid, Amazon SES, Hostinger, etc.).
            </p>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                <div class="admin-form-group">
                    <label for="mail_host">Servidor SMTP (Host)</label>
                    <input type="text" id="mail_host" name="mail_host" value="{{ $mailHost }}" placeholder="Ex: smtp.hostinger.com">
                </div>
                <div class="admin-form-group">
                    <label for="mail_port">Porta</label>
                    <input type="number" id="mail_port" name="mail_port" value="{{ $mailPort }}" placeholder="587">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="admin-form-group">
                    <label for="mail_username">Usuário SMTP</label>
                    <input type="text" id="mail_username" name="mail_username" value="{{ $mailUsername }}" placeholder="Ex: contato@seusite.com">
                </div>
                <div class="admin-form-group">
                    <label for="mail_password">Senha SMTP</label>
                    <input type="password" id="mail_password" name="mail_password" value="{{ $mailPassword }}" placeholder="Senha ou senha de app">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <div class="admin-form-group">
                    <label for="mail_encryption">Criptografia</label>
                    <select id="mail_encryption" name="mail_encryption">
                        <option value="tls" {{ $mailEncryption === 'tls' ? 'selected' : '' }}>TLS (porta 587, recomendado)</option>
                        <option value="ssl" {{ $mailEncryption === 'ssl' ? 'selected' : '' }}>SSL (porta 465)</option>
                        <option value="none" {{ $mailEncryption === 'none' ? 'selected' : '' }}>Nenhuma</option>
                    </select>
                </div>
                <div class="admin-form-group">
                    <label for="mail_from_address">E-mail Remetente</label>
                    <input type="email" id="mail_from_address" name="mail_from_address" value="{{ $mailFromAddress }}" placeholder="contato@seusite.com">
                </div>
                <div class="admin-form-group">
                    <label for="mail_from_name">Nome Remetente</label>
                    <input type="text" id="mail_from_name" name="mail_from_name" value="{{ $mailFromName }}" placeholder="Vibra">
                </div>
            </div>

            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px dashed rgba(255,255,255,0.08);">
                <h5 style="margin-bottom: 12px; font-weight: 600; color: #fff;">Testar Envio</h5>
                <p style="font-size: 12px; color: var(--text-secondary); margin-bottom: 12px;">
                    Salve as configurações acima primeiro. Depois, informe um e-mail abaixo e clique em enviar para confirmar que o SMTP está funcionando.
                </p>
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; align-items: end;">
                    <div class="admin-form-group" style="margin-bottom: 0;">
                        <label for="test_email">E-mail para teste</label>
                        <input type="email" id="test_email" name="test_email" placeholder="seuemail@exemplo.com">
                    </div>
                    <button type="submit" formaction="{{ route('admin.settings.test-email') }}" formnovalidate class="admin-btn-save" style="margin-top: 0;">
                        <i class="fa-solid fa-paper-plane"></i> ENVIAR TESTE
                    </button>
                </div>
            </div>
        </div>

        <button type="submit" class="admin-btn-save" style="margin-top: 24px; font-size: 14px; padding: 14px;">SALVAR CONFIGURAÇÕES DO SISTEMA</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    function previewImage(input, containerId, imgId) {
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

    function previewNavIcon(input, index) {
        const previewImg = document.getElementById(`btn_${index}_preview_img`);
        const hamburger = document.getElementById(`btn_${index}_preview_hamburger`);
        const removeBtn = document.getElementById(`btn_${index}_remove_btn`);
        const removeInput = document.getElementById(`remove_bottom_nav_btn_${index}_icon`);
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
                if (hamburger) {
                    hamburger.style.display = 'none';
                }
                removeBtn.style.display = 'block';
                removeInput.value = '0';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    function removeNavIcon(index) {
        const previewImg = document.getElementById(`btn_${index}_preview_img`);
        const hamburger = document.getElementById(`btn_${index}_preview_hamburger`);
        const removeBtn = document.getElementById(`btn_${index}_remove_btn`);
        const removeInput = document.getElementById(`remove_bottom_nav_btn_${index}_icon`);
        const fileInput = document.getElementById(`bottom_nav_btn_${index}_icon`);
        
        fileInput.value = '';
        removeInput.value = '1';
        
        const defaultIcons = {
            1: 'hamburger',
            2: "{{ asset('imagens/rewards.png') }}",
            3: "{{ asset('imagens/cassino.png') }}",
            4: "{{ asset('imagens/sports.png') }}",
            5: "{{ asset('imagens/loja.png') }}"
        };
        
        if (defaultIcons[index] === 'hamburger') {
            previewImg.style.display = 'none';
            if (hamburger) {
                hamburger.style.display = 'flex';
            }
        } else {
            previewImg.src = defaultIcons[index];
            previewImg.style.display = 'block';
            if (hamburger) {
                hamburger.style.display = 'none';
            }
        }
        
        removeBtn.style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        const tabBtns = document.querySelectorAll('.settings-tab-btn');
        const tabPanes = document.querySelectorAll('.settings-tab-pane');
        
        // Load active tab from localStorage, default to first tab if not set
        const activeTab = localStorage.getItem('admin_settings_active_tab') || 'tab-geral';
        
        // Activate saved tab
        switchTab(activeTab);
        
        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const tabId = this.getAttribute('data-tab');
                switchTab(tabId);
                // Save active tab to localStorage
                localStorage.setItem('admin_settings_active_tab', tabId);
            });
        });
        
        function switchTab(tabId) {
            // Remove active class from all buttons
            tabBtns.forEach(btn => {
                if (btn.getAttribute('data-tab') === tabId) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
            
            // Remove active class from all panes
            tabPanes.forEach(pane => {
                if (pane.getAttribute('id') === tabId) {
                    pane.classList.add('active');
                } else {
                    pane.classList.remove('active');
                }
            });
        }
    });
</script>
@endsection
