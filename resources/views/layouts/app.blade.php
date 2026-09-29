@php
    if (!function_exists('hex2rgba')) {
        // Safe hex-to-rgba converter for dynamic glows/alphas
        function hex2rgba($hex, $alpha = 1) {
            $hex = str_replace('#', '', $hex);
            if (strlen($hex) == 3) {
                $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
                $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
                $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
            } else if (strlen($hex) == 6) {
                $r = hexdec(substr($hex, 0, 2));
                $g = hexdec(substr($hex, 2, 2));
                $b = hexdec(substr($hex, 4, 2));
            } else {
                return $hex;
            }
            return "rgba($r, $g, $b, $alpha)";
        }
    }

    if (!function_exists('hex2rgb_raw')) {
        function hex2rgb_raw($hex) {
            $hex = str_replace('#', '', $hex);
            if (strlen($hex) == 3) {
                $r = hexdec(substr($hex, 0, 1) . substr($hex, 0, 1));
                $g = hexdec(substr($hex, 1, 1) . substr($hex, 1, 1));
                $b = hexdec(substr($hex, 2, 1) . substr($hex, 2, 1));
            } else if (strlen($hex) == 6) {
                $r = hexdec(substr($hex, 0, 2));
                $g = hexdec(substr($hex, 2, 2));
                $b = hexdec(substr($hex, 4, 2));
            } else {
                return "0, 230, 118";
            }
            return "$r, $g, $b";
        }
    }

    // Support Banner Settings
    $supportBannerBg = \App\Models\Setting::get('support_banner_bg_color', '#121215');
    $supportBannerBtnBg = \App\Models\Setting::get('support_banner_btn_bg', '#007bff');
    $supportBannerBtnText = \App\Models\Setting::get('support_banner_btn_text', '#ffffff');
    $supportBannerImage = \App\Models\Setting::get('support_banner_image');
    $supportBannerTitle = \App\Models\Setting::get('support_banner_title', 'Suporte');
    $supportBannerSubtitle = \App\Models\Setting::get('support_banner_subtitle', 'Entre em contato se ainda tiver dúvidas');
    $supportBannerBadge = \App\Models\Setting::get('support_banner_badge_text', '24/7');
    $supportBannerLink = \App\Models\Setting::get('support_banner_link_url', 'javascript:void(0)');

    // Retrieve customized colors
    $customBgColor = \App\Models\Setting::get('color_bg_color');
    $customHeaderBg = \App\Models\Setting::get('color_header_bg');
    $customSidebarBg = \App\Models\Setting::get('color_sidebar_bg');
    $customCardBg = \App\Models\Setting::get('color_card_bg');
    $customCardHover = \App\Models\Setting::get('color_card_hover');
    $customBorderColor = \App\Models\Setting::get('color_border_color');
    $customNeonGreen = \App\Models\Setting::get('color_neon_green');
    $customGold = \App\Models\Setting::get('color_gold');
    $customRed = \App\Models\Setting::get('color_red');
    $customTextPrimary = \App\Models\Setting::get('color_text_primary');
    $customTextSecondary = \App\Models\Setting::get('color_text_secondary');
    $customTextMuted = \App\Models\Setting::get('color_text_muted');

    $customBtnEntrarBg = \App\Models\Setting::get('color_btn_entrar_bg');
    $customBtnEntrarBorder = \App\Models\Setting::get('color_btn_entrar_border');
    $customBtnEntrarText = \App\Models\Setting::get('color_btn_entrar_text');

    $customBtnCadastrarBgStart = \App\Models\Setting::get('color_btn_cadastrar_bg_start');
    $customBtnCadastrarBgEnd = \App\Models\Setting::get('color_btn_cadastrar_bg_end');
    $customBtnCadastrarText = \App\Models\Setting::get('color_btn_cadastrar_text');

    $customModalBg = \App\Models\Setting::get('color_modal_bg');
    $customModalBorder = \App\Models\Setting::get('color_modal_border');
    $customModalInputBg = \App\Models\Setting::get('color_modal_input_bg');
    $customModalInputBorder = \App\Models\Setting::get('color_modal_input_border');
    $customModalInputFocus = \App\Models\Setting::get('color_modal_input_focus');

    $customBtnAuthSubmitBgStart = \App\Models\Setting::get('color_btn_auth_submit_bg_start');
    $customBtnAuthSubmitBgEnd = \App\Models\Setting::get('color_btn_auth_submit_bg_end');
    $customBtnAuthSubmitText = \App\Models\Setting::get('color_btn_auth_submit_text');

    $customSidebarBtnBgStart = \App\Models\Setting::get('color_sidebar_btn_bg_start');
    $customSidebarBtnBgEnd = \App\Models\Setting::get('color_sidebar_btn_bg_end');
    $customSidebarBtnBorder = \App\Models\Setting::get('color_sidebar_btn_border');
    $customSidebarBtnText = \App\Models\Setting::get('color_sidebar_btn_text');
    $customSidebarBtnShowV = \App\Models\Setting::get('sidebar_btn_show_v', '1');

    $customBottomNavBtnBgStart = \App\Models\Setting::get('color_bottom_nav_btn_bg_start');
    $customBottomNavBtnBgEnd = \App\Models\Setting::get('color_bottom_nav_btn_bg_end');
    $customBottomNavBtnBorder = \App\Models\Setting::get('color_bottom_nav_btn_border');
    $customBottomNavBtnText = \App\Models\Setting::get('color_bottom_nav_btn_text');
    $customBottomNavBtnShowV = \App\Models\Setting::get('bottom_nav_btn_show_v', '1');

    $customSidebarMenuItemBg = \App\Models\Setting::get('color_sidebar_menu_item_bg');
    $customSidebarMenuItemText = \App\Models\Setting::get('color_sidebar_menu_item_text');
    $customSidebarMenuItemIcon = \App\Models\Setting::get('color_sidebar_menu_item_icon');

    $customHeaderDepositBtnBg = \App\Models\Setting::get('color_header_deposit_btn_bg');
    $customHeaderDepositBtnText = \App\Models\Setting::get('color_header_deposit_btn_text');
    $customHeaderBalanceBg = \App\Models\Setting::get('color_header_balance_bg');
    $customHeaderBalanceText = \App\Models\Setting::get('color_header_balance_text');
    $customHeaderProfileMenuBg = \App\Models\Setting::get('color_header_profile_menu_bg');
    $customHeaderProfileMenuItemHoverBg = \App\Models\Setting::get('color_header_profile_menu_item_hover_bg');
    $customHeaderProfileMenuText = \App\Models\Setting::get('color_header_profile_menu_text');

    $customHeaderProfileBtnBg = \App\Models\Setting::get('color_header_profile_btn_bg');
    $customHeaderNotifBtnBg = \App\Models\Setting::get('color_header_notif_btn_bg');

    $customSeeAllBg = \App\Models\Setting::get('color_see_all_bg');
    $customSeeAllBorder = \App\Models\Setting::get('color_see_all_border');
    $customSeeAllText = \App\Models\Setting::get('color_see_all_text');

    $customBottomNavFullBg = \App\Models\Setting::get('color_bottom_nav_full_bg');
    $customBannerNeonHover = \App\Models\Setting::get('color_banner_neon_hover');
@endphp
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ \App\Models\Setting::get('site_name', 'patrao.bet') }} - Cassino Online e Apostas Esportivas</title>
    <link rel="icon" type="image/x-icon" href="{{ \App\Models\Setting::get('site_favicon') ? asset(\App\Models\Setting::get('site_favicon')) : asset('favicon.ico') }}">
    <!-- Google Fonts: Outfit and Inter for modern typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- FontAwesome for high-fidelity icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Didit KYC Web SDK -->
    <script src="https://unpkg.com/@didit-protocol/sdk-web/dist/didit-sdk.umd.min.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ time() }}">
    <!-- Dynamic Custom Style Overrides -->
    <style>
        :root {
            @if($customBgColor) --bg-color: {{ $customBgColor }} !important; @endif
            @if($customHeaderBg) 
                --header-bg: {{ hex2rgba($customHeaderBg, 0.95) }} !important; 
            @endif
            @if($customSidebarBg) --sidebar-bg: {{ $customSidebarBg }} !important; @endif
            @if($customCardBg) --card-bg: {{ $customCardBg }} !important; @endif
            @if($customCardHover) --card-hover: {{ $customCardHover }} !important; @endif
            @if($customNeonGreen) 
                --neon-green: {{ $customNeonGreen }} !important;
                --neon-green-glow-raw: {{ hex2rgb_raw($customNeonGreen) }} !important;
                --neon-green-glow: rgba(var(--neon-green-glow-raw), 0.4) !important;
            @else
                --neon-green-glow-raw: 0, 230, 118 !important;
            @endif
            @if($customGold) 
                --gold: {{ $customGold }} !important;
                --gold-glow: {{ hex2rgba($customGold, 0.4) }} !important;
            @endif
            @if($customRed) 
                --red: {{ $customRed }} !important;
                --red-glow: {{ hex2rgba($customRed, 0.4) }} !important;
            @endif
            @if($customTextPrimary) --text-primary: {{ $customTextPrimary }} !important; @endif
            @if($customTextSecondary) --text-secondary: {{ $customTextSecondary }} !important; @endif
            @if($customTextMuted) --text-muted: {{ $customTextMuted }} !important; @endif
            @if($customBorderColor) 
                --border-color: {{ $customBorderColor }} !important;
            @elseif($customNeonGreen)
                --border-color: {{ hex2rgba($customNeonGreen, 0.1) }} !important;
            @endif

            /* Entrar button overrides */
            @if($customBtnEntrarBg) 
                --btn-entrar-bg: {{ $customBtnEntrarBg }} !important;
                --btn-entrar-bg-hover: {{ hex2rgba($customBtnEntrarBg, 0.15) }} !important;
            @endif
            @if($customBtnEntrarBorder) 
                --btn-entrar-border: {{ $customBtnEntrarBorder }} !important;
                --btn-entrar-border-hover: {{ hex2rgba($customBtnEntrarBorder, 0.35) }} !important;
            @endif
            @if($customBtnEntrarText) --btn-entrar-text: {{ $customBtnEntrarText }} !important; @endif

            /* Cadastrar button overrides */
            @if($customBtnCadastrarBgStart) --btn-cadastrar-bg-start: {{ $customBtnCadastrarBgStart }} !important; @endif
            @if($customBtnCadastrarBgEnd) --btn-cadastrar-bg-end: {{ $customBtnCadastrarBgEnd }} !important; @endif
            @if($customBtnCadastrarBgStart && $customBtnCadastrarBgEnd)
                --btn-cadastrar-hover-start: {{ hex2rgba($customBtnCadastrarBgStart, 0.9) }} !important;
                --btn-cadastrar-hover-end: {{ hex2rgba($customBtnCadastrarBgEnd, 0.9) }} !important;
            @endif
            @if($customBtnCadastrarText) --btn-cadastrar-text: {{ $customBtnCadastrarText }} !important; @endif
            @if($customNeonGreen)
                --btn-cadastrar-glow: {{ hex2rgba($customNeonGreen, 0.2) }} !important;
                --btn-cadastrar-hover-glow: {{ hex2rgba($customNeonGreen, 0.3) }} !important;
            @endif
            
            /* Modal overrides */
            @if($customModalBg) --modal-bg: {{ $customModalBg }} !important; @endif
            @if($customModalBorder) 
                --modal-border: {{ $customModalBorder }} !important; 
            @elseif($customNeonGreen)
                --modal-border: {{ hex2rgba($customNeonGreen, 0.1) }} !important;
            @endif
            @if($customNeonGreen)
                --modal-glow: {{ hex2rgba($customNeonGreen, 0.15) }} !important;
            @endif
            @if($customModalInputBg) --modal-input-bg: {{ $customModalInputBg }} !important; @endif
            @if($customModalInputBorder) 
                --modal-input-border: {{ $customModalInputBorder }} !important; 
            @elseif($customNeonGreen)
                --modal-input-border: {{ hex2rgba($customNeonGreen, 0.08) }} !important;
            @endif
            @if($customModalInputFocus) 
                --modal-input-focus: {{ $customModalInputFocus }} !important;
                --modal-input-focus-glow: {{ hex2rgba($customModalInputFocus, 0.1) }} !important;
            @endif

            /* Auth submit button overrides */
            @if($customBtnAuthSubmitBgStart) --btn-auth-submit-bg-start: {{ $customBtnAuthSubmitBgStart }} !important; @endif
            @if($customBtnAuthSubmitBgEnd) --btn-auth-submit-bg-end: {{ $customBtnAuthSubmitBgEnd }} !important; @endif
            @if($customBtnAuthSubmitBgStart && $customBtnAuthSubmitBgEnd)
                --btn-auth-submit-hover-start: {{ hex2rgba($customBtnAuthSubmitBgStart, 0.9) }} !important;
                --btn-auth-submit-hover-end: {{ hex2rgba($customBtnAuthSubmitBgEnd, 0.9) }} !important;
            @endif
            @if($customBtnAuthSubmitText) --btn-auth-submit-text: {{ $customBtnAuthSubmitText }} !important; @endif
            @if($customNeonGreen)
                --btn-auth-submit-glow: {{ hex2rgba($customNeonGreen, 0.25) }} !important;
                --btn-auth-submit-hover-glow: {{ hex2rgba($customNeonGreen, 0.35) }} !important;
            @endif

            /* Sidebar buttons overrides */
            @if($customSidebarBtnBgStart) --sidebar-btn-bg-start: {{ $customSidebarBtnBgStart }} !important; @endif
            @if($customSidebarBtnBgEnd) --sidebar-btn-bg-end: {{ $customSidebarBtnBgEnd }} !important; @endif
            @if($customSidebarBtnBorder) --sidebar-btn-border: {{ $customSidebarBtnBorder }} !important; @endif
            @if($customSidebarBtnText) --sidebar-btn-text: {{ $customSidebarBtnText }} !important; @endif

            /* Bottom nav buttons overrides */
            @if($customBottomNavBtnBgStart) --bottom-nav-btn-bg-start: {{ $customBottomNavBtnBgStart }} !important; @endif
            @if($customBottomNavBtnBgEnd) --bottom-nav-btn-bg-end: {{ $customBottomNavBtnBgEnd }} !important; @endif
            @if($customBottomNavBtnBorder) --bottom-nav-btn-border: {{ $customBottomNavBtnBorder }} !important; @endif
            @if($customBottomNavBtnText) --bottom-nav-btn-text: {{ $customBottomNavBtnText }} !important; @endif

            /* Sidebar Menu Items */
            @if($customSidebarMenuItemBg) --sidebar-menu-item-bg: {{ $customSidebarMenuItemBg }} !important; @endif
            @if($customSidebarMenuItemText) --sidebar-menu-item-text: {{ $customSidebarMenuItemText }} !important; @endif
            @if($customSidebarMenuItemIcon) --sidebar-menu-item-icon: {{ $customSidebarMenuItemIcon }} !important; @endif

            /* Header Buttons */
            @if($customHeaderDepositBtnBg) --header-deposit-btn-bg: {{ $customHeaderDepositBtnBg }} !important; @endif
            @if($customHeaderDepositBtnText) --header-deposit-btn-text: {{ $customHeaderDepositBtnText }} !important; @endif
            @if($customHeaderBalanceBg) --header-balance-bg: {{ $customHeaderBalanceBg }} !important; @endif
            @if($customHeaderBalanceText) --header-balance-text: {{ $customHeaderBalanceText }} !important; @endif

            /* Profile Menu */
            @if($customHeaderProfileMenuBg) --header-profile-menu-bg: {{ $customHeaderProfileMenuBg }} !important; @endif
            @if($customHeaderProfileMenuItemHoverBg) --header-profile-menu-item-hover-bg: {{ $customHeaderProfileMenuItemHoverBg }} !important; @endif
            @if($customHeaderProfileMenuText) --header-profile-menu-text: {{ $customHeaderProfileMenuText }} !important; @endif

            /* Extras */
            @if($customBottomNavFullBg) --bottom-nav-full-bg: {{ $customBottomNavFullBg }} !important; @endif
            @if($customBannerNeonHover) 
                --banner-neon-hover: {{ $customBannerNeonHover }} !important;
                --banner-neon-hover-glow: {{ hex2rgba($customBannerNeonHover, 0.4) }} !important;
            @endif
        }

        /* Global Neon Overrides */
        @if($customNeonGreen)
            .text-green { color: var(--neon-green) !important; }
            .bg-green { background-color: var(--neon-green) !important; }
            .sidebar-toggle:hover { color: var(--neon-green) !important; }
            .nav-item:hover, .nav-item.active { color: var(--neon-green) !important; }
            .game-card.item-roulette, .game-card.item-bacbo {
                border-bottom: 3px solid var(--neon-green) !important;
                box-shadow: 0 4px 15px var(--neon-green-glow) !important;
            }
        @endif

        @if($customBannerNeonHover)
            .banner-item:hover {
                box-shadow: 0 8px 25px var(--banner-neon-hover-glow) !important;
                border-color: var(--banner-neon-hover) !important;
            }
            .banners-arrow:hover {
                background: var(--banner-neon-hover) !important;
                box-shadow: 0 0 15px var(--banner-neon-hover-glow) !important;
            }
            .game-card:hover {
                box-shadow: 0 5px 15px var(--banner-neon-hover-glow) !important;
            }
            .play-btn-custom {
                background: var(--banner-neon-hover) !important;
                box-shadow: 0 0 20px var(--banner-neon-hover-glow) !important;
            }
        @endif

        .bottom-nav {
            @if($customBottomNavFullBg) background-color: var(--bottom-nav-full-bg) !important; @endif
        }

        .carousel-item img:hover {
            @if($customBannerNeonHover) 
                filter: drop-shadow(0 0 10px {{ hex2rgba($customBannerNeonHover, 0.5) }}) !important;
                border-color: var(--banner-neon-hover) !important;
            @endif
        }

        .btn-deposit-glow {
            position: relative !important;
            overflow: hidden !important;
            z-index: 1 !important;
            @if($customHeaderDepositBtnBg) 
                background: {{ $customHeaderDepositBtnBg }} !important; 
                box-shadow: 0 0 15px {{ hex2rgba($customHeaderDepositBtnBg, 0.4) }} !important;
            @endif
            @if($customHeaderDepositBtnText) color: var(--header-deposit-btn-text) !important; @endif
        }

        .btn-deposit-glow .skew-content {
            position: relative !important;
            z-index: 2 !important;
        }

        .btn-flash-new {
            position: absolute !important;
            top: 0 !important;
            left: -150% !important;
            width: 80px !important;
            height: 100% !important;
            background: linear-gradient(
                to right,
                rgba(255, 255, 255, 0) 0%,
                rgba(255, 255, 255, 0.6) 50%,
                rgba(255, 255, 255, 0) 100%
            ) !important;
            transform: skewX(-20deg) !important;
            animation: flash-move-final 2s infinite linear !important;
            z-index: 1 !important; /* Voltando para trás do texto */
            pointer-events: none !important;
        }

        @keyframes flash-move-final {
            0% { left: -150%; }
            100% { left: 250%; }
        }

        .btn-deposit-glow:hover {
            @if($customHeaderDepositBtnBg) 
                background: {{ hex2rgba($customHeaderDepositBtnBg, 0.9) }} !important;
                box-shadow: 0 0 20px {{ hex2rgba($customHeaderDepositBtnBg, 0.6) }} !important;
            @endif
        }

        .card-balance {
            @if($customHeaderBalanceBg) background-color: var(--header-balance-bg) !important; @endif
            @if($customNeonGreen) border-color: {{ hex2rgba($customNeonGreen, 0.2) }} !important; @endif
        }

        .card-balance:hover {
            @if($customNeonGreen) border-color: {{ hex2rgba($customNeonGreen, 0.4) }} !important; @endif
        }

        .balance-text {
            @if($customHeaderBalanceText) color: var(--header-balance-text); @endif
            transition: color 0.3s ease;
        }

        /* Classes para cores dinâmicas do saldo */
        .balance-low { color: #ff1111 !important; }
        .balance-medium { color: #ffeb3b !important; }
        .balance-high { color: #00e676 !important; }

        .mobile-nav-label {
            transition: color 0.3s ease;
        }

        .card-balance .fa-rotate {
            @if($customNeonGreen) color: {{ hex2rgba($customNeonGreen, 0.7) }} !important; @endif
        }

        .card-balance:hover .fa-rotate {
            @if($customNeonGreen) color: var(--neon-green) !important; @endif
        }

        .btn-profile-skew, .btn-plus-skew {
            @if($customNeonGreen) 
                border-color: {{ hex2rgba($customNeonGreen, 0.2) }} !important;
                color: var(--neon-green) !important;
            @endif
        }

        .btn-profile-skew:hover, .btn-plus-skew:hover {
            @if($customNeonGreen) border-color: {{ hex2rgba($customNeonGreen, 0.4) }} !important; @endif
        }

        .notification-bell-badge {
            @if($customNeonGreen) border-color: {{ hex2rgba($customNeonGreen, 0.3) }} !important; @endif
        }

        .profile-drawer {
            @if($customHeaderProfileMenuBg) background-color: var(--header-profile-menu-bg) !important; @endif
        }

        .profile-drawer-section {
            @if($customHeaderProfileMenuBg) background-color: var(--header-profile-menu-bg) !important; @endif
        }

        .profile-drawer-item:hover {
            @if($customHeaderProfileMenuItemHoverBg) background-color: var(--header-profile-menu-item-hover-bg) !important; @endif
        }

        .profile-drawer-item {
            @if($customHeaderProfileMenuText) color: var(--header-profile-menu-text) !important; @endif
        }

        .profile-dropdown-menu, .quick-access-dropdown {
            @if($customHeaderProfileMenuBg) background-color: var(--header-profile-menu-bg) !important; @endif
        }

        .profile-dropdown-item:hover, .qa-item:hover {
            @if($customHeaderProfileMenuItemHoverBg) background-color: var(--header-profile-menu-item-hover-bg) !important; @endif
        }

        .profile-dropdown-item, .qa-label {
            @if($customHeaderProfileMenuText) color: var(--header-profile-menu-text) !important; @endif
        }

        .qa-arrow {
            @if($customHeaderProfileMenuText) color: var(--header-profile-menu-text) !important; opacity: 0.5; @endif
        }

        .side-link:hover, .side-link.active, .side-link:focus {
            @if($customSidebarMenuItemBg)
                background-color: var(--sidebar-menu-item-bg) !important;
            @endif
        }

        .side-link {
            @if($customSidebarMenuItemText)
                color: var(--sidebar-menu-item-text) !important;
            @endif
        }

        .side-link i, .side-link svg {
            @if($customSidebarMenuItemIcon)
                color: var(--sidebar-menu-item-icon) !important;
                fill: var(--sidebar-menu-item-icon) !important;
            @endif
        }

        /* Banner Neon Hover Fix Final */
        .banners-carousel .carousel-item img, 
        .banners-carousel .carousel-item picture img {
            border: 2px solid transparent !important;
            transition: all 0.3s ease !important;
        }

        .banners-carousel .carousel-item:hover img, 
        .banners-carousel .carousel-item:hover picture img {
            @if($customBannerNeonHover) 
                box-shadow: 0 0 25px {{ $customBannerNeonHover }} !important;
                border-color: {{ $customBannerNeonHover }} !important;
            @else
                box-shadow: 0 0 25px var(--neon-green) !important;
                border-color: var(--neon-green) !important;
            @endif
        }

        .banner-item:hover picture img, .banner-item:hover img {
            @if($customBannerNeonHover) 
                box-shadow: 0 0 20px {{ $customBannerNeonHover }} !important;
                border-color: {{ $customBannerNeonHover }} !important;
            @endif
        }

        .sidebar-box-card {
            @if($customSidebarBtnBgStart && $customSidebarBtnBgEnd)
                background: linear-gradient(180deg, var(--sidebar-btn-bg-start) 0%, var(--sidebar-btn-bg-end) 100%) !important;
            @endif
            @if($customSidebarBtnBorder)
                border-color: var(--sidebar-btn-border) !important;
            @elseif($customNeonGreen)
                border-color: var(--neon-green) !important;
            @endif
        }
        
        .sidebar-box-card .card-title {
            @if($customSidebarBtnText)
                color: var(--sidebar-btn-text) !important;
            @endif
        }

        @if($customSidebarBtnShowV === '0')
        .sidebar-box-card::before {
            display: none !important;
        }
        @endif

        /* Bottom Nav Custom Styles */
        .mobile-bottom-nav {
            @if($customBottomNavFullBg) background-color: var(--bottom-nav-full-bg) !important; @endif
            @if($customBottomNavFullBg) border-top-color: {{ $customBottomNavFullBg }} !important; @endif
        }

        .mobile-nav-card {
            @if($customBottomNavBtnBgStart && $customBottomNavBtnBgEnd)
                background: linear-gradient(180deg, {{ $customBottomNavBtnBgStart }} 0%, {{ $customBottomNavBtnBgEnd }} 100%) !important;
            @else
                background: #1a1a1e !important; /* Fundo padrão caso não escolha cor */
            @endif
            @if($customBottomNavBtnBorder)
                border-color: var(--bottom-nav-btn-border) !important;
            @else
                border-color: transparent !important;
            @endif
        }

        .mobile-nav-card-title {
            @if($customBottomNavBtnText)
                color: var(--bottom-nav-btn-text) !important;
            @endif
        }

        @if($customBottomNavBtnShowV === '0')
        .mobile-nav-card::before {
            display: none !important;
        }
        @endif

        .game-card {
            border-bottom: 3px solid rgba(255, 255, 255, 0.1);
            background: var(--card-bg) !important;
            border-radius: 12px !important;
            display: flex !important;
            flex-direction: column !important;
            height: 100% !important;
            margin-bottom: 10px !important;
        }
        .game-card:hover {
            border-bottom-color: var(--neon-green);
            box-shadow: 0 4px 15px rgba(var(--neon-green-glow-raw), 0.3) !important;
            transform: translateY(-5px) !important;
        }
        .game-thumbnail {
            width: 100% !important;
            aspect-ratio: 4/5 !important;
            height: auto !important;
            position: relative !important;
            overflow: hidden !important;
            border-radius: 12px !important;
        }
        .game-img-cover {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover !important;
            display: block !important;
            transition: transform 0.5s ease !important;
        }
        .game-card:hover .game-img-cover {
            transform: scale(1.1) !important;
        }

        /* Estilo para ícones das categorias */
        .category-icon-fa, .category-icon-svg svg {
            color: var(--neon-green) !important;
            fill: var(--neon-green) !important;
            filter: drop-shadow(0 0 5px var(--neon-green-glow)) !important;
        }
        .category-icon-svg {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 8px;
        }
        .category-icon-svg svg {
            width: 1.2em;
            height: 1.2em;
        }

        .card-online-count {
            position: absolute !important;
            top: 10px !important;
            right: 10px !important;
            background: rgba(0, 0, 0, 0.6) !important;
            padding: 4px 10px !important;
            border-radius: 20px !important;
            font-size: 0.7rem !important;
            font-weight: 800 !important;
            display: flex !important;
            align-items: center !important;
            gap: 6px !important;
            z-index: 5 !important;
            color: #fff !important;
            backdrop-filter: blur(4px) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
        .card-online-count i {
            color: var(--neon-green) !important;
            font-size: 0.75rem !important;
        }
        .online-dot {
            width: 8px !important;
            height: 8px !important;
            background: var(--neon-green) !important;
            border-radius: 50% !important;
            box-shadow: 0 0 8px var(--neon-green-glow) !important;
            display: inline-block !important;
        }

        /* Forçar remoção de ranking */
        .card-online-count::after {
            display: none !important;
        }

        .game-play-hover {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background: rgba(0, 0, 0, 0.5) !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            opacity: 0 !important;
            transition: all 0.3s ease !important;
            z-index: 10 !important;
        }
        .game-card:hover .game-play-hover {
            opacity: 1 !important;
        }
        .play-btn-custom {
            transform: skewX(-15deg) scale(0.8) !important;
            transition: all 0.3s ease !important;
        }
        .game-card:hover .play-btn-custom {
            transform: skewX(-15deg) scale(1) !important;
        }
        .weekly-card {
            border-bottom: 3px solid rgba(255, 255, 255, 0.1);
        }
        .weekly-card:hover {
            border-bottom-color: var(--neon-green);
            box-shadow: 0 5px 20px rgba(0, 230, 118, 0.1);
        }

        /* See All Buttons */
        .ver-todos-btn, .sidebar-see-all, .section-link, .sidebar-section-header .section-link {
            @if($customSeeAllBg) background: {{ $customSeeAllBg }} !important; @endif
            @if($customSeeAllBorder) border: 1px solid {{ $customSeeAllBorder }} !important; @endif
            @if($customSeeAllText) color: {{ $customSeeAllText }} !important; @endif
            padding: 4px 10px !important;
            border-radius: 20px !important;
            font-size: 10px !important;
            font-weight: 800 !important;
            text-transform: uppercase !important;
            transition: all 0.3s ease !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            text-decoration: none !important;
            line-height: 1 !important;
            height: fit-content !important;
            min-width: 80px !important;
        }
        .ver-todos-btn:hover, .sidebar-see-all:hover, .section-link:hover, .sidebar-section-header .section-link:hover {
            @if($customSeeAllBg) background: {{ hex2rgba($customSeeAllBg, 0.8) }} !important; @endif
            @if($customSeeAllBorder) 
                border-color: {{ $customSeeAllBorder }} !important;
                box-shadow: 0 0 10px {{ hex2rgba($customSeeAllBorder, 0.4) }} !important;
            @endif
            transform: scale(1.05) !important;
        }

        /* Trophy Glow */
        .sidebar-box-card img.card-image[src*="rewards.png"], 
        .sidebar-section-header img,
        .maiores-ganhos-title-row img,
        img[src*="trofeu"],
        img[src*="trophy"] {
            filter: drop-shadow(0 0 8px var(--neon-green-glow)) hue-rotate(0deg) !important;
        }

        /* Se a imagem original tiver verde nos pixels, esse filtro tenta rotacionar para a cor neon atual */
        @if($customNeonGreen)
        img[src*="trofeu"], img[src*="trophy"] {
            filter: drop-shadow(0 0 10px {{ $customNeonGreen }}) !important;
        }
        @endif

        /* Header Profile and Notification Buttons */
         .btn-profile-skew {
             @if($customHeaderProfileBtnBg) background: {{ $customHeaderProfileBtnBg }} !important; @endif
         }
         .btn-plus-skew {
            @if($customHeaderNotifBtnBg) background: {{ $customHeaderNotifBtnBg }} !important; @endif
        }
        .notification-bell-badge {
            @if($customHeaderNotifBtnBg) background: {{ $customHeaderNotifBtnBg }} !important; @endif
        }

        /* Quick Navigation (Circular Style) */
        .quick-nav-wrapper {
            margin: 0px 0 5px 0; /* Reduzido de 5px 0 15px 0 */
            width: 100%;
            overflow: hidden;
        }

        .quick-nav-scroll {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            padding: 5px 5px 5px 5px; /* Reduzido padding vertical */
            scrollbar-width: none;
            -ms-overflow-style: none;
            justify-content: flex-start;
        }

        .quick-nav-scroll::-webkit-scrollbar {
            display: none;
        }

        .quick-nav-item {
            flex: 0 0 auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 85px;
        }

        .quick-nav-icon {
            width: 70px;
            height: 70px;
            background: radial-gradient(circle at center, rgba(0, 230, 118, 0.15) 0%, rgba(0, 0, 0, 0.4) 70%);
            border: 2px solid rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: visible;
            box-shadow: inset 0 0 15px rgba(0, 0, 0, 0.5);
        }

        .quick-nav-icon img, .quick-nav-icon i, .quick-nav-icon svg {
            width: 45px;
            height: 45px;
            object-fit: contain;
            filter: drop-shadow(0 5px 10px rgba(0,0,0,0.5));
            transition: all 0.3s ease;
            z-index: 2;
        }

        .quick-nav-text {
            font-size: 0.7rem;
            font-weight: 800;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: center;
            line-height: 1.2;
            max-width: 100%;
            transition: color 0.3s ease;
        }

        /* Hover Effects */
        .quick-nav-item:hover .quick-nav-icon {
            border-color: var(--neon-green);
            background: radial-gradient(circle at center, rgba(0, 230, 118, 0.25) 0%, rgba(0, 0, 0, 0.6) 80%);
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 230, 118, 0.15), inset 0 0 10px rgba(0, 230, 118, 0.1);
        }

        .quick-nav-item:hover .quick-nav-icon img, 
        .quick-nav-item:hover .quick-nav-icon i {
            transform: scale(1.1);
            filter: drop-shadow(0 0 8px var(--neon-green-glow));
        }

        .quick-nav-item:hover .quick-nav-text {
            color: var(--neon-green);
        }

        @media (max-width: 768px) {
            .quick-nav-scroll {
                gap: 15px;
            }
            .quick-nav-item {
                width: 75px;
            }
            .quick-nav-icon {
                width: 60px;
                height: 60px;
            }
            .quick-nav-icon img, .quick-nav-icon i {
                width: 38px;
                height: 38px;
            }
            .quick-nav-text {
                font-size: 0.65rem;
            }
        }

        /* Search Results Overlay */
        .search-results-overlay {
            position: relative;
            width: 100%;
            display: none;
        }

        .search-container-inner {
            max-width: 1200px;
            margin: 0 auto;
        }

        .search-results-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            padding-bottom: 20px;
        }

        .search-input-wrapper-overlay {
            position: relative;
            flex: 1;
            max-width: 800px;
            margin-right: 20px;
        }

        .search-input-wrapper-overlay i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--neon-green);
            font-size: 1.2rem;
        }

        .close-search-results {
            flex-shrink: 0;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: var(--text-secondary);
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .close-search-results:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .search-input-overlay {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 15px 15px 15px 50px;
            color: #fff;
            font-size: 1.1rem;
            outline: none;
            transition: all 0.3s;
        }

        .search-input-overlay:focus {
            border-color: var(--neon-green);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 15px rgba(0, 230, 118, 0.1);
        }

        .search-results-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .search-results-title span {
            color: var(--neon-green);
            font-size: 0.9rem;
            opacity: 0.7;
        }

        .search-results-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 20px;
        }

        @media (max-width: 768px) {
            .search-results-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 10px;
            }
            .search-input-wrapper-overlay {
                margin-right: 10px;
            }
        }

        .no-results-found {
            text-align: center;
            padding: 50px 0;
            color: var(--text-secondary);
        }

        .no-results-found i {
            font-size: 3rem;
            margin-bottom: 15px;
            opacity: 0.3;
        }
    </style>
    <style>
        @media (min-width: 769px) {
            .auth-mobile-banner-top {
                display: none !important;
            }
        }
        @media (max-width: 768px) {
            .auth-mobile-banner-top {
                display: block !important;
                width: calc(100% + 40px);
                margin-left: -20px;
                margin-right: -20px;
                margin-top: -30px;
                margin-bottom: 20px;
                height: 140px;
                background-size: cover;
                background-position: center;
                border-bottom: 1px solid rgba(0, 230, 118, 0.1);
            }
            body {
                padding-bottom: 74px !important;
            }
            .mobile-bottom-nav {
                display: flex !important;
            }
            .sidebar-toggle {
                display: none !important;
            }
            .app-header {
                padding: 0 10px !important;
                height: 60px !important;
            }
            .app-layout {
                margin-top: 60px !important;
                min-height: calc(100vh - 60px) !important;
            }
            .header-left {
                gap: 8px !important;
            }
            .header-right {
                gap: 6px !important;
            }
            .brand-logo {
                font-size: 1.25rem !important;
            }
            .brand-logo img {
                max-height: 28px !important;
            }
            .btn-entrar {
                padding: 7px 12px !important;
                font-size: 0.72rem !important;
            }
            .btn-cadastrar {
                padding: 7px 12px !important;
                font-size: 0.72rem !important;
            }
            .pix-badge {
                font-size: 0.52rem !important;
                padding: 2px 6px !important;
                top: -8px !important;
                right: 3px !important;
            }
            .main-content {
                padding: 12px 10px !important;
                min-width: 0 !important;
                max-width: 100% !important;
                overflow-x: hidden !important;
            }
            .home-main-layout, 
            .home-center-column, 
            .home-section, 
            .payout-cards-wrapper,
            .search-bar-container {
                min-width: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
                box-sizing: border-box !important;
            }
            #gameSearchInput {
                height: 42px !important;
                font-size: 0.82rem !important;
                padding-left: 40px !important;
                border-radius: 10px !important;
            }
            .search-icon {
                left: 14px !important;
                font-size: 0.9rem !important;
            }
            .banners-carousel-wrapper {
                margin-bottom: 15px !important;
                padding: 0 !important;
                width: 100% !important;
            }
            .section-header-title h2 {
                font-size: 0.95rem !important;
                white-space: normal !important;
                line-height: 1.3 !important;
            }
            .carousel-nav-arrows.desktop-hide {
                display: flex !important;
            }
            .games-grid, .weekly-cards-grid, .payout-cards-grid {
                display: flex !important;
                flex-direction: row !important;
                flex-wrap: nowrap !important;
                overflow-x: auto !important;
                scroll-behavior: smooth !important;
                gap: 8px !important;
                padding-bottom: 20px !important;
                scrollbar-width: none !important;
                -ms-overflow-style: none !important;
                -webkit-overflow-scrolling: touch !important;
                touch-action: auto !important;
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
            }
            .games-grid::-webkit-scrollbar, .weekly-cards-grid::-webkit-scrollbar, .payout-cards-grid::-webkit-scrollbar {
                display: none !important;
            }
            .games-grid.expanded-grid, .weekly-cards-grid.expanded-grid, .payout-cards-grid.expanded-grid {
                display: grid !important;
                grid-template-columns: repeat(2, 1fr) !important;
                overflow-x: visible !important;
                flex-wrap: wrap !important;
            }
            .games-grid.expanded-grid .game-card,
            .weekly-cards-grid.expanded-grid .game-card,
            .payout-cards-grid.expanded-grid .game-card {
                flex: 0 0 calc(50% - 4px) !important;
                width: calc(50% - 4px) !important;
                margin-bottom: 0 !important;
                background: transparent !important;
                border: none !important;
                box-shadow: none !important;
            }
            .games-grid.expanded-grid .game-thumbnail,
            .weekly-cards-grid.expanded-grid .game-thumbnail,
            .payout-cards-grid.expanded-grid .game-thumbnail,
            #searchResultsGrid .game-thumbnail {
                height: auto !important;
                aspect-ratio: 4/5 !important;
                width: 100% !important;
                border-radius: 12px !important;
            }
            .games-grid.expanded-grid .game-img-cover,
            .weekly-cards-grid.expanded-grid .game-img-cover,
            .payout-cards-grid.expanded-grid .game-img-cover,
            #searchResultsGrid .game-img-cover {
                border-radius: 12px !important;
            }
            .games-grid.expanded-grid .game-name,
            .games-grid.expanded-grid .game-provider,
            .weekly-cards-grid.expanded-grid .game-name,
            .weekly-cards-grid.expanded-grid .game-provider,
            .payout-cards-grid.expanded-grid .game-name,
            .payout-cards-grid.expanded-grid .game-provider {
                display: none !important;
            }
            .home-section {
                margin-bottom: 25px !important;
            }
            .ver-todos-btn {
                display: inline-flex !important;
            }
            .card-payout-info {
                bottom: -15px !important;
                width: 92% !important;
            }
            .card-payout-info .inline-flex {
                padding-right: 4px !important;
                padding-left: 2px !important;
                padding-top: 2px !important;
                padding-bottom: 2px !important;
                gap: 4px !important;
            }
            .card-fav-btn {
                width: 26px !important;
                height: 26px !important;
                top: 6px !important;
                left: 6px !important;
                font-size: 0.75rem !important;
            }
            .card-online-count {
                top: 6px !important;
                right: 6px !important;
                padding: 2px 6px !important;
                font-size: 0.65rem !important;
            }
            .weekly-emoji {
                font-size: 2.8rem !important;
            }
            .weekly-title {
                font-size: 0.8rem !important;
            }
            .sidebar-left {
                top: 60px !important;
                height: calc(100vh - 60px - 64px) !important;
                padding-top: 15px !important;
            }
            .mobile-win-feed-wrapper {
                display: flex !important;
                flex-direction: column !important;
                background: var(--sidebar-bg);
                border: 1px solid var(--border-color);
                border-radius: 16px;
                height: 310px !important;
                overflow: hidden;
                margin-bottom: 25px;
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
                box-sizing: border-box !important;
            }
            .mobile-win-feed-wrapper .sidebar-right-header {
                padding: 15px !important;
            }
            .mobile-win-feed-wrapper .header-win-title p {
                white-space: normal !important;
                line-height: 1.2 !important;
            }
            .mobile-win-feed-wrapper .trophy-img {
                height: 48px !important;
            }
        }
        @media (min-width: 769px) {
            .mobile-bottom-nav {
                display: none !important;
            }
            .carousel-nav-arrows.desktop-hide {
                display: none !important;
            }
            .mobile-win-feed-wrapper {
                display: none !important;
            }
        }
        .mobile-bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 68px;
            background: rgba(3, 18, 12, 0.95);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-top: 1.5px solid rgba(0, 230, 118, 0.25);
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
            display: none;
            justify-content: space-between;
            align-items: center;
            padding: 0 8px;
            gap: 6px;
            z-index: 9999;
            box-shadow: 0 -4px 30px rgba(0, 0, 0, 0.7);
            box-sizing: border-box;
        }
        .mobile-nav-card {
            position: relative;
            flex: 1;
            height: 52px;
            background: linear-gradient(180deg, rgba(0, 40, 20, 0.75) 0%, rgba(0, 15, 8, 0.9) 100%);
            border: 1.2px solid rgba(0, 230, 118, 0.2);
            border-radius: 10px;
            text-decoration: none;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 6px 8px;
            box-sizing: border-box;
            overflow: hidden; /* Keep watermark V contained in card */
            transition: all 0.2s ease-in-out;
            cursor: pointer;
            outline: none;
        }
        .mobile-nav-card::before {
            content: 'V';
            position: absolute;
            left: 4px;
            bottom: -22px;
            font-size: 3.8rem;
            font-weight: 900;
            font-family: 'Outfit', sans-serif;
            color: transparent;
            -webkit-text-stroke: 1px rgba(0, 230, 118, 0.08);
            transform: skewX(-15deg) rotate(-5deg);
            pointer-events: none;
            z-index: 1;
            transition: all 0.2s ease;
        }
        .mobile-nav-card:active {
            transform: scale(0.95);
            border-color: rgba(0, 230, 118, 0.5);
            background: linear-gradient(180deg, rgba(0, 50, 25, 0.85) 0%, rgba(0, 20, 10, 0.95) 100%);
        }
        .mobile-nav-card:active::before, .mobile-nav-card.active::before {
            -webkit-text-stroke-color: rgba(0, 230, 118, 0.15);
            transform: skewX(-15deg) rotate(-5deg) scale(1.05);
        }
        .mobile-nav-card-title {
            font-family: var(--font-heading), 'Montserrat', 'Inter', sans-serif;
            font-size: 7.5px;
            font-weight: 900;
            color: #ffffff;
            text-align: left;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            text-shadow: 0 1px 2px rgba(0,0,0,0.6);
            margin: 0;
            z-index: 2;
            white-space: nowrap;
        }
        .mobile-nav-card-icon-wrapper {
            position: absolute;
            bottom: -3px;
            right: -2px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1;
            pointer-events: none;
        }
        .mobile-nav-card-icon-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.6));
        }
        .mobile-nav-card-menu-icon {
            position: absolute;
            bottom: 8px;
            right: 8px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 16px;
            height: 10px;
            z-index: 1;
            pointer-events: none;
        }
        .mobile-nav-card-menu-icon span {
            display: block;
            height: 2px;
            width: 100%;
            background-color: #fff;
            border-radius: 1px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.5);
        }
        .mobile-nav-card.active {
            border-color: rgba(0, 230, 118, 0.6);
            box-shadow: 0 0 10px rgba(0, 230, 118, 0.2);
            background: linear-gradient(180deg, rgba(0, 50, 25, 0.8) 0%, rgba(0, 20, 10, 0.95) 100%);
        }

        /* Account Modal styles */
        .account-modal-content {
            max-width: 820px !important;
            padding: 0 !important;
            background: rgba(18, 18, 22, 0.98) !important;
            border: 1px solid rgba(0, 230, 118, 0.15) !important;
            box-shadow: 0 10px 40px rgba(0, 230, 118, 0.1) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        .account-modal-body {
            display: flex;
            min-height: 520px;
            width: 100%;
        }
        .account-modal-sidebar {
            width: 240px;
            background: rgba(0, 0, 0, 0.25);
            border-right: 1px solid var(--border-color);
            padding: 24px 0;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }
        .account-modal-title {
            font-family: var(--font-heading);
            font-size: 15px;
            font-weight: 800;
            color: #fff;
            padding: 0 24px 20px 24px;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 16px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .account-modal-tabs {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .account-modal-tabs li {
            padding: 12px 24px;
            color: var(--text-secondary);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }
        .account-modal-tabs li i {
            font-size: 15px;
            width: 20px;
            text-align: center;
            color: var(--text-muted);
            transition: color 0.2s ease;
        }
        .account-modal-tabs li:hover {
            color: #fff;
            background: rgba(255,255,255,0.02);
        }
        .account-modal-tabs li.active {
            color: #00e676;
            background: rgba(0, 230, 118, 0.04);
            border-left-color: #00e676;
        }
        .account-modal-tabs li.active i {
            color: #00e676;
        }
        .account-modal-content-panel {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
            max-height: 580px;
            background: rgba(0, 0, 0, 0.1);
        }
        .account-tab-pane {
            display: none;
            animation: fadeInTab 0.3s ease forwards;
        }
        .account-tab-pane.active {
            display: block;
        }
        .account-tab-pane h3 {
            font-family: var(--font-heading);
            font-size: 18px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 20px;
        }
        .acc-balance-card {
            background: linear-gradient(135deg, rgba(0,230,118,0.1), rgba(0,230,118,0.02));
            border: 1px solid rgba(0,230,118,0.2);
            padding: 20px;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
        }
        .acc-balance-label {
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 700;
            color: var(--text-secondary);
            letter-spacing: 0.5px;
        }
        .acc-balance-value {
            font-size: 28px;
            font-weight: 800;
            color: #00e676;
            text-shadow: 0 0 10px rgba(0, 230, 118, 0.2);
        }
        .acc-sub-title {
            font-family: var(--font-heading);
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 8px;
        }
        .acc-text {
            font-size: 12.5px;
            line-height: 1.6;
        }
        
        /* Transaction History styles */
        .history-table-container table th,
        .history-table-container table td {
            padding: 12px 10px;
            border-bottom: 1px solid rgba(255,255,255,0.03);
        }
        .history-table-container table tbody tr:hover {
            background: rgba(255,255,255,0.01);
        }
        .history-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
        }
        .history-badge.pending {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }
        .history-badge.approved {
            background: rgba(0, 200, 83, 0.1);
            color: #00c853;
        }
        .history-badge.rejected {
            background: rgba(255, 61, 0, 0.1);
            color: #ff3d00;
        }
        
        @keyframes fadeInTab {
            from { opacity: 0; transform: translateY(5px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Mobile adaptation */
        @media (max-width: 768px) {
            .account-modal-body {
                flex-direction: column;
            }
            .account-modal-sidebar {
                width: 100%;
                padding: 15px 0 10px 0;
                border-right: none;
                border-bottom: 1px solid var(--border-color);
            }
            .account-modal-title {
                padding: 0 15px 10px 15px;
                margin-bottom: 10px;
            }
            .account-modal-tabs {
                flex-direction: row;
                overflow-x: auto;
                padding: 0 15px;
                gap: 5px;
                scrollbar-width: none;
            }
            .account-modal-tabs::-webkit-scrollbar {
                display: none;
            }
            .account-modal-tabs li {
                padding: 8px 16px;
                white-space: nowrap;
                border-left: none;
                border-bottom: 2px solid transparent;
                border-radius: 6px;
            }
            .account-modal-tabs li.active {
                border-left-color: transparent;
                border-bottom-color: #00e676;
            }
            .account-modal-content-panel {
                padding: 20px;
                max-height: 400px;
            }
        }
    </style>

    <style>
        .home-support-banner {
            background-color: {{ $supportBannerBg }};
            border-radius: 16px;
            padding: 20px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 40px auto 20px auto; /* Centralizado horizontalmente */
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,0.05);
            min-height: 120px;
            width: calc(100% - 40px); /* Ajuste para não colar nas bordas */
            max-width: 1200px;
        }

        .support-banner-content {
            display: flex;
            flex-direction: column;
            gap: 4px;
            z-index: 2;
        }

        .support-banner-title-row {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .support-banner-title {
            font-family: var(--font-heading);
            font-size: 22px;
            font-weight: 800;
            color: #fff;
        }

        .support-banner-badge {
            background-color: {{ $supportBannerBtnBg }};
            color: {{ $supportBannerBtnText }};
            font-size: 11px;
            font-weight: 800;
            padding: 2px 10px;
            border-radius: 20px;
        }

        .support-banner-subtitle {
            font-size: 14px;
            color: var(--text-secondary);
            max-width: 250px;
        }

        .support-banner-image-container {
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            height: 100%;
            z-index: 1;
            display: flex;
            align-items: flex-end;
        }

        .support-banner-img {
            height: 110%;
            width: auto;
            object-fit: contain;
        }

        .support-banner-button {
            background-color: {{ $supportBannerBtnBg }};
            color: {{ $supportBannerBtnText }};
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 800;
            font-size: 15px;
            text-decoration: none;
            transition: all 0.2s ease;
            z-index: 2;
            box-shadow: 0 4px 15px {{ hex2rgba($supportBannerBtnBg, 0.3) }};
        }

        .support-banner-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px {{ hex2rgba($supportBannerBtnBg, 0.5) }};
            filter: brightness(1.1);
        }

        @media (max-width: 768px) {
            .home-support-banner {
                flex-direction: column;
                text-align: center;
                padding: 30px 15px;
                gap: 0;
                min-height: auto;
                background: linear-gradient(180deg, {{ $supportBannerBg }} 0%, {{ hex2rgba($supportBannerBg, 0.8) }} 100%);
            }
            .support-banner-content {
                margin-bottom: 20px;
            }
            .support-banner-title-row {
                justify-content: center;
                margin-bottom: 8px;
            }
            .support-banner-title {
                font-size: 24px;
            }
            .support-banner-subtitle {
                max-width: none;
                font-size: 13px;
                opacity: 0.8;
            }
            .support-banner-image-container {
                position: relative;
                left: auto;
                transform: none;
                height: 180px;
                margin: 0 auto -10px auto;
                order: 2;
            }
            .support-banner-img {
                height: 100%;
                width: auto;
            }
            .support-banner-button {
                width: 100%;
                max-width: 280px;
                margin: 15px auto 0 auto;
                order: 3;
                font-size: 16px;
                padding: 14px;
            }
        }
    </style>
</head>
<body>
    <!-- Top Header -->
    <header class="app-header">
        <div class="header-left">
            <button class="sidebar-toggle" id="sidebarToggleBtn">
                <i class="fa-solid fa-bars"></i>
            </button>
            <a href="{{ route('home') }}" class="brand-logo">
                @if(\App\Models\Setting::get('site_logo'))
                    <img src="{{ asset(\App\Models\Setting::get('site_logo')) }}" alt="Logo" style="max-height: 36px; max-width: 150px; object-fit: contain;">
                @else
                    <span class="logo-v">P</span><span class="logo-text">{{ \App\Models\Setting::get('site_name', 'patrao.bet') }}</span>
                @endif
            </a>
            <nav class="header-nav">
                @for($i = 1; $i <= 5; $i++)
                    @php
                        $text = \App\Models\Setting::get("header_link_{$i}_text");
                        $url = \App\Models\Setting::get("header_link_{$i}_url", '#');
                        $onclick = \App\Models\Setting::get("header_link_{$i}_onclick");
                    @endphp
                    @if($text)
                        <a href="{{ $url }}" class="nav-item {{ $i == 1 ? 'active' : '' }}" @if($onclick) onclick="{{ $onclick }}" @endif>{{ $text }}</a>
                    @else
                        @if($i == 1) <a href="{{ route('home') }}" class="nav-item active">Início</a> @endif
                        @if($i == 2) <a href="#cassino" class="nav-item" onclick="scrollToSection('cassino-section')">Cassino</a> @endif
                        @if($i == 3) <a href="#cassino-ao-vivo" class="nav-item" onclick="scrollToSection('recommended-section')">Cassino ao Vivo</a> @endif
                        @if($i == 4) <a href="{{ route('game.sports') }}" class="nav-item">Esportes</a> @endif
                        @if($i == 5) <a href="{{ route('game.sports') }}" class="nav-item">Esportes ao Vivo</a> @endif
                    @endif
                @endfor
            </nav>
        </div>
        
        <div class="header-right">
            @auth
                <!-- Logged In Header Buttons matching user mockup exactly -->
                <div class="logged-in-buttons-wrapper">
                    <!-- 1. DEPOSITAR Button -->
                    <button class="header-btn btn-deposit-glow" onclick="openDepositModal()">
                        <div class="btn-flash-new"></div>
                        <span class="skew-content">DEPOSITAR</span>
                    </button>
                    
                    <!-- 2. SALDO Card -->
                    <div class="header-card card-balance" onclick="refreshUserBalance()">
                        <span class="skew-content">
                            <i class="fa-solid fa-rotate" id="balanceRefreshIcon"></i>
                            <span class="balance-text" id="headerUserBalance">R$ {{ number_format(Auth::user()->balance, 2, ',', '.') }}</span>
                        </span>
                    </div>
                    
                    <!-- 3. PROFILE Button (Triggers Drawer) -->
                    <button class="header-btn btn-profile-skew" id="profileDrawerBtn" onclick="toggleProfileDrawer()" style="padding: 0;">
                        <span class="skew-content" style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                            <img src="{{ Auth::user()->avatar_url }}" alt="Profile" style="width: 26px; height: 26px; object-fit: cover; border-radius: 50%;">
                        </span>
                    </button>
                    
                    <!-- 4. PLUS/NOTIFICATION Button + Quick Access Dropdown -->
                    <div class="notification-wrapper" id="quickAccessWrapper">
                        <button class="header-btn btn-plus-skew" id="quickAccessBtn" onclick="toggleQuickAccess()">
                            <span class="skew-content"><i class="fa-solid fa-plus"></i></span>
                        </button>
                        <span class="notification-bell-badge" id="notificationBadgeBtn" onclick="toggleNotificationsDropdown(event)">
                            <i class="fa-solid fa-bell"></i>
                            <span class="badge-dot" id="bellBadgeDot" style="display: none;"></span>
                        </span>

                        <!-- Notifications Dropdown -->
                        <div class="quick-access-dropdown" id="notificationsDropdown" style="display: none; width: 300px; padding: 15px; right: 0; left: auto; max-height: 400px; overflow-y: auto; z-index: 1000; border: 1px solid var(--border-color); border-radius: 12px; background: rgba(18,18,22,0.98); box-shadow: 0 10px 30px rgba(0,0,0,0.8); backdrop-filter: blur(10px);">
                            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 10px; margin-bottom: 10px;">
                                <span style="font-weight: 800; font-size: 13.5px; text-transform: uppercase; color: #fff; letter-spacing: 0.5px;">Notificações</span>
                                <a href="javascript:void(0)" onclick="readAllNotifications(event)" style="font-size: 11px; color: var(--color-yellow); text-decoration: none; font-weight: 700; text-transform: uppercase;">Limpar</a>
                            </div>
                            <div id="notificationsList" style="display: flex; flex-direction: column; gap: 8px; max-height: 280px; overflow-y: auto; padding-right: 5px;">
                                <div style="text-align: center; padding: 20px; color: var(--text-muted); font-size: 12px;">Nenhuma notificação encontrada.</div>
                            </div>
                        </div>

                        <!-- Quick Access Dropdown -->
                        <div class="quick-access-dropdown" id="quickAccessDropdown" style="background-color: var(--header-profile-menu-bg, rgba(18,18,22,0.98)) !important;">
                            <ul class="qa-menu">
                                <li>
                                    <a href="javascript:void(0)" class="qa-item" onclick="closeQuickAccess(); handleWithdrawalClick()">
                                        <span class="qa-icon qa-sacar"><i class="fa-solid fa-arrow-up-from-bracket"></i></span>
                                        <span class="qa-label">Sacar</span>
                                        <i class="fa-solid fa-chevron-right qa-arrow"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="qa-item" onclick="closeQuickAccess(); alert('Favoritos: Seus jogos favoritos!')">
                                        <span class="qa-icon qa-favoritos"><i class="fa-regular fa-heart"></i></span>
                                        <span class="qa-label">Favoritos</span>
                                        <i class="fa-solid fa-chevron-right qa-arrow"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="qa-item" onclick="closeQuickAccess(); alert('Últimos jogados: Retome de onde parou!')">
                                        <span class="qa-icon qa-recentes"><i class="fa-regular fa-clock"></i></span>
                                        <span class="qa-label">Últimos jogados</span>
                                        <i class="fa-solid fa-chevron-right qa-arrow"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="qa-item" onclick="closeQuickAccess(); alert('Notificações: Você tem novas notificações!')">
                                        <span class="qa-icon qa-notif"><i class="fa-regular fa-bell"></i></span>
                                        <span class="qa-label">Notificações</span>
                                        <i class="fa-solid fa-chevron-right qa-arrow"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="qa-item" onclick="closeQuickAccess(); alert('Prêmios e Recompensas: Confira seus prêmios disponíveis!')">
                                        <span class="qa-icon qa-premios"><i class="fa-solid fa-gift"></i></span>
                                        <span class="qa-label">Prêmios e Recompensas</span>
                                        <i class="fa-solid fa-chevron-right qa-arrow"></i>
                                    </a>
                                </li>
                                <li>
                                    <a href="javascript:void(0)" class="qa-item" onclick="closeQuickAccess(); alert('Promoções: Aproveite nossas promoções exclusivas!')">
                                        <span class="qa-icon qa-promo"><i class="fa-solid fa-bullhorn"></i></span>
                                        <span class="qa-label">Promoções</span>
                                        <i class="fa-solid fa-chevron-right qa-arrow"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @else
                <button class="btn-entrar" onclick="openModal('loginModal')"><span class="btn-text">{{ \App\Models\Setting::get('header_btn_login_text', 'ENTRAR') }}</span></button>
                <div class="cadastrar-wrapper">
                    <span class="pix-badge"><span class="btn-text"><i class="fa-brands fa-pix" style="color: #00b0ff;"></i> {{ \App\Models\Setting::get('header_pix_badge_text', 'PIX') }}</span></span>
                    <button class="btn-cadastrar" onclick="openModal('registerModal')"><span class="btn-text">{{ \App\Models\Setting::get('header_btn_register_text', 'CADASTRAR') }}</span></button>
                </div>
            @endauth
        </div>
    </header>

    <div class="app-layout">
        <!-- Left Sidebar Navigation -->
        <aside class="sidebar-left" id="sidebarLeft">
            <div class="sidebar-boxes-grid">
                @php
                    $sidebarButtons = \App\Models\CategoryButton::where('group', 'sidebar')
                        ->where('is_active', true)
                        ->orderBy('order', 'asc')
                        ->get();
                @endphp
                @foreach($sidebarButtons as $btn)
                    @php
                        $clickAction = '';
                        if ($btn->slug === 'torneios') {
                            $clickAction = "openTournamentsModal()";
                        } elseif ($btn->slug === 'missoes') {
                            $clickAction = "openMissionsModal()";
                        } elseif ($btn->slug === 'indique_ganhe') {
                            $clickAction = "openAccountModal('indique')";
                        } elseif ($btn->action_type === 'link') {
                            $clickAction = "window.location.href='" . $btn->action_value . "'";
                        } elseif ($btn->action_type === 'scroll') {
                            $clickAction = "scrollToSection('" . $btn->action_value . "')";
                        } elseif ($btn->action_type === 'alert') {
                            $clickAction = "alert('" . addslashes($btn->action_value) . "')";
                        } elseif ($btn->action_type === 'play') {
                            if ($btn->distribution === 'playfiver') {
                                $clickAction = "launchExternalGame('" . $btn->slug . "', this)";
                            } else {
                                $clickAction = "checkAuthAndPlay('" . $btn->action_value . "')";
                            }
                        }
                    @endphp
                    <a href="javascript:void(0)" class="sidebar-box-card" @if($clickAction) onclick="{{ $clickAction }}" @endif>
                        <span class="card-title">{{ $btn->title }}</span>
                        @if($btn->image_path)
                            <img src="{{ asset($btn->image_path) }}" class="card-image" alt="{{ $btn->title }}">
                        @endif
                    </a>
                @endforeach
            </div>

            <!-- CASSINO Navigation Section -->
            <div class="sidebar-section">
                <div class="sidebar-section-header">
                    <span class="section-title">
                        <svg viewBox="0 0 24 24" class="sidebar-svg-icon" aria-hidden="true">
                            <path fill="currentColor" d="M19 5v14H5V5zm0-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2M7.5 6C6.7 6 6 6.7 6 7.5S6.7 9 7.5 9S9 8.3 9 7.5S8.3 6 7.5 6m9 9c-.8 0-1.5.7-1.5 1.5s.7 1.5 1.5 1.5s1.5-.7 1.5-1.5s-.7-1.5-1.5-1.5m0-9c-.8 0-1.5.7-1.5 1.5S15.7 9 16.5 9S18 8.3 18 7.5S17.3 6 16.5 6M12 10.5c-.8 0-1.5.7-1.5 1.5s.7 1.5 1.5 1.5s1.5-.7 1.5-1.5s-.7-1.5-1.5-1.5M7.5 15c-.8 0-1.5.7-1.5 1.5S6.7 18 7.5 18S9 17.3 9 16.5S8.3 15 7.5 15"></path>
                        </svg>
                        CASSINO
                    </span>
                    <a href="#cassino" class="section-link">VER TODOS</a>
                </div>
                <ul class="sidebar-menu">
                    @php
                        $cassinoMenuButtons = \App\Models\CategoryButton::where('group', 'cassino_menu')
                            ->where('is_active', true)
                            ->orderBy('order', 'asc')
                            ->get();
                    @endphp
                    @foreach($cassinoMenuButtons as $btn)
                        @php
                            $clickAction = '';
                            if ($btn->action_type === 'scroll') {
                                $clickAction = "scrollToLayout('" . $btn->action_value . "')";
                            } elseif ($btn->action_type === 'link') {
                                $clickAction = "window.location.href='" . $btn->action_value . "'";
                            } elseif ($btn->action_type === 'alert') {
                                $clickAction = "alert('" . addslashes($btn->action_value) . "')";
                            } elseif ($btn->action_type === 'play') {
                                $clickAction = "checkAuthAndPlay('" . $btn->action_value . "')";
                            }
                        @endphp
                        <li><a href="#" @if($clickAction) onclick="{{ $clickAction }}" @endif><i class="{{ $btn->icon_class }}"></i> <span>{{ $btn->title }}</span></a></li>
                    @endforeach
                </ul>
            </div>

            <!-- ESPORTES Navigation Section -->
            <div class="sidebar-section">
                <div class="sidebar-section-header">
                    <span class="section-title"><i class="fa-regular fa-futbol text-green"></i> ESPORTES</span>
                    <a href="{{ route('game.sports') }}" class="section-link">VER TODOS</a>
                </div>
                <ul class="sidebar-menu">
                    <li><a href="{{ route('game.sports') }}"><i class="fa-regular fa-circle-dot"></i> <span>AO VIVO</span></a></li>
                    <li><a href="{{ route('game.sports') }}"><i class="fa-regular fa-clock"></i> <span>COMEÇA EM BREVE</span></a></li>
                    <li><a href="{{ route('game.sports') }}"><i class="fa-regular fa-paper-plane"></i> <span>E-SPORTS</span></a></li>
                    <li><a href="{{ route('game.sports') }}"><i class="fa-regular fa-file-lines"></i> <span>REGRAS DE APOSTAS</span></a></li>
                </ul>
            </div>

        </aside>

        <!-- Main Middle Content Pane -->
        <main class="main-content">
            <!-- Search Results Overlay -->
            <div class="search-results-overlay" id="searchResultsOverlay">
                <div class="search-container-inner">
                    <div class="search-results-header">
                        <div class="search-input-wrapper-overlay">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" class="search-input-overlay" id="overlaySearchInput" placeholder="Buscar jogos, provedores..." oninput="handleOverlaySearch(this)">
                        </div>
                        <button class="close-search-results" onclick="closeSearchOverlay()">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    
                    <div id="searchNoResults" class="no-results-found" style="display: none;">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <p>Nenhum resultado para <span id="searchQueryText"></span></p>
                        <span style="font-size: 0.85rem; opacity: 0.6;">Tente buscar por termos diferentes</span>
                    </div>

                    <div id="searchContent" style="display: none;">
                        <div class="search-results-title">
                            CASSINO <span id="searchCount">0 resultados</span>
                        </div>
                        <div class="search-results-grid" id="searchResultsGrid">
                            <!-- Results populated by JS -->
                        </div>
                    </div>
                </div>
            </div>

            <div id="pageContentWrapper">
                @yield('content')
            </div>
        </main>

    </div>
    <!-- END app-layout -->

    <!-- Full-width Footer -->
    <footer class="app-footer">
                <div class="footer-top-row">
                    <div class="footer-brand-col">
                        <a href="{{ route('home') }}" class="brand-logo footer-logo">
                            @if(\App\Models\Setting::get('site_logo'))
                                <img src="{{ asset(\App\Models\Setting::get('site_logo')) }}" alt="Logo" style="max-height: 36px; max-width: 150px; object-fit: contain;">
                            @else
                                <span class="logo-v">P</span><span class="logo-text">{{ \App\Models\Setting::get('site_name', 'patrao.bet') }}</span>
                            @endif
                        </a>
                        <p class="footer-description">
                            {{ \App\Models\Setting::get('footer_description', 'Descubra o melhor cassino online do Brasil com a ' . \App\Models\Setting::get('site_name', 'patrao.bet') . '! Slots, roletas ao vivo, blackjack e bônus exclusivos. Jogue com segurança e confiança.') }}
                        </p>
                        <div class="social-links">
                            @php
                                $twitter = \App\Models\Setting::get('footer_social_twitter', '#');
                                $youtube = \App\Models\Setting::get('footer_social_youtube', '#');
                                $facebook = \App\Models\Setting::get('footer_social_facebook', '#');
                                $instagram = \App\Models\Setting::get('footer_social_instagram', '#');
                            @endphp
                            @if($twitter && $twitter !== '#') <a href="{{ $twitter }}" target="_blank"><i class="fa-brands fa-x-twitter"></i></a> @endif
                            @if($youtube && $youtube !== '#') <a href="{{ $youtube }}" target="_blank"><i class="fa-brands fa-youtube"></i></a> @endif
                            @if($facebook && $facebook !== '#') <a href="{{ $facebook }}" target="_blank"><i class="fa-brands fa-facebook-f"></i></a> @endif
                            @if($instagram && $instagram !== '#') <a href="{{ $instagram }}" target="_blank"><i class="fa-brands fa-instagram"></i></a> @endif
                        </div>
                    </div>
                    
                    @for($col = 1; $col <= 3; $col++)
                        @php
                            $colTitle = \App\Models\Setting::get("footer_col{$col}_title", $col == 1 ? 'APOSTE' : ($col == 2 ? 'COMUNIDADE' : 'REGRAS'));
                            $hasLinks = false;
                            for($i = 1; $i <= 5; $i++) {
                                if(\App\Models\Setting::get("footer_col{$col}_link_{$i}_text")) {
                                    $hasLinks = true;
                                    break;
                                }
                            }
                        @endphp
                        <div class="footer-links-col">
                            <h3>{{ $colTitle }}</h3>
                            <ul>
                                @if($hasLinks)
                                    @for($i = 1; $i <= 5; $i++)
                                        @php
                                            $linkText = \App\Models\Setting::get("footer_col{$col}_link_{$i}_text");
                                            $linkUrl = \App\Models\Setting::get("footer_col{$col}_link_{$i}_url", '#');
                                        @endphp
                                        @if($linkText)
                                            <li><a href="{{ $linkUrl }}">{{ $linkText }}</a></li>
                                        @endif
                                    @endfor
                                @else
                                    @if($col == 1)
                                        <li><a href="#">Jogos de Cassino</a></li>
                                        <li><a href="#">Cassino ao Vivo</a></li>
                                        <li><a href="#">Todos os Jogos</a></li>
                                        <li><a href="{{ route('game.sports') }}">Apostas Esportivas</a></li>
                                    @elseif($col == 2)
                                        <li><a href="#">Promoções</a></li>
                                        <li><a href="#">Blog</a></li>
                                        <li><a href="#">FAQ</a></li>
                                        <li><a href="#">Central de Ajuda</a></li>
                                    @elseif($col == 3)
                                        <li><a href="#">Termos & Condições</a></li>
                                        <li><a href="#">Política de PLD-FTP</a></li>
                                        <li><a href="#">Política de Privacidade</a></li>
                                        <li><a href="#">Política KYC</a></li>
                                    @endif
                                @endif
                                <li><a href="#" class="text-green">Ver mais <i class="fa-solid fa-chevron-down text-xs"></i></a></li>
                            </ul>
                        </div>
                    @endfor
                </div>

                <!-- Partner & Regulatory Badges -->
                <div class="footer-certificates">
                    <div class="cert-logos">
                        <span class="cert-logo-mock">IBIA</span>
                        <span class="cert-logo-mock">sportradar</span>
                        <span class="cert-logo-mock text-bold text-green">✓ EBAC</span>
                        <span class="cert-logo-mock font-serif text-cyan">COMISAFE</span>
                        <span class="cert-logo-mock text-red">GambleAware</span>
                        <span class="cert-logo-mock text-cyan">gt</span>
                        <span class="cert-age-badge" style="border: none !important; background: transparent !important; width: 30px !important; height: 30px !important; display: inline-flex !important; align-items: center; justify-content: center; color: var(--neon-green) !important; padding: 0 !important;">
                            <svg fill="none" height="30" viewBox="0 0 200 200" width="30" xmlns="http://www.w3.org/2000/svg" style="height: 100%; width: auto; display: block;"><title>18+</title><circle cx="100" cy="100" fill="currentColor" r="100"></circle><circle cx="99.9231" cy="99.9231" fill="black" r="76.9231"></circle><path d="M45 91.304V76.2294C53.0312 74.8205 58.788 70.3122 62.2706 62.7044H72.7182V136.951H58.6459V84.4007C55.803 87.3123 51.2544 89.6134 45 91.304Z" fill="white"></path><path d="M82.9527 97.0803C78.9726 93.4173 76.9826 88.2985 76.9826 81.7239C76.9826 76.5581 78.5462 72.0028 81.6733 68.058C84.8716 64.0193 89.7756 62 96.3853 62C102.711 62 107.508 63.9724 110.777 67.9172C114.118 71.768 115.788 76.3702 115.788 81.7239C115.788 88.2985 113.798 93.4173 109.818 97.0803C115.575 101.213 118.453 107.365 118.453 115.536C118.453 122.205 116.428 127.793 112.377 132.301C108.325 136.716 102.995 138.923 96.3853 138.923C89.7045 138.923 84.3386 136.669 80.2874 132.161C76.3074 127.652 74.3174 122.111 74.3174 115.536C74.3174 111.122 75.1702 107.365 76.876 104.265C78.5817 101.072 80.6073 98.677 82.9527 97.0803ZM96.3853 91.5858C98.02 91.5858 99.477 90.9753 100.756 89.7543C102.036 88.4394 102.675 86.5139 102.675 83.978C102.675 81.3482 102.036 79.3758 100.756 78.0609C99.477 76.7459 98.02 76.0885 96.3853 76.0885C94.7507 76.0885 93.2581 76.7459 91.9078 78.0609C90.6285 79.3758 89.9888 81.3482 89.9888 83.978C89.9888 86.42 90.6285 88.2985 91.9078 89.6134C93.2581 90.9283 94.7507 91.5858 96.3853 91.5858ZM96.3853 124.271C98.8018 124.271 100.721 123.379 102.142 121.594C103.635 119.81 104.381 117.509 104.381 114.691C104.381 111.685 103.599 109.243 102.036 107.365C100.543 105.393 98.6596 104.406 96.3853 104.406C94.0399 104.406 92.121 105.393 90.6285 107.365C89.1359 109.243 88.3897 111.685 88.3897 114.691C88.3897 117.603 89.1004 119.951 90.5219 121.735C92.0144 123.426 93.9689 124.271 96.3853 124.271Z" fill="white"></path><path d="M143.66 85.0769V84.0769H142.66H138.264H137.264V85.0769V97.3553H125.077H124.077V98.3553V102.659V103.659H125.077H137.264V115.846V116.846H138.264H142.66H143.66V115.846V103.659H155.846H156.846V102.659V98.3553V97.3553H155.846H143.66V85.0769Z" fill="white" stroke="white" stroke-width="2"></path></svg>
                        </span>
                        <span class="cert-responsibility-text">+18 • JOGUE COM RESPONSABILIDADE</span>
                    </div>
                    <div class="payment-method">
                        <span class="payment-label">PAGAMENTO:</span>
                        <span class="pix-logo-white"><i class="fa-brands fa-pix text-green"></i> pix</span>
                    </div>
                </div>

                <hr class="footer-divider">

                <div class="footer-disclaimer">
                    <div class="disclaimer-text">
                        @php
                            $disclaimer = \App\Models\Setting::get('footer_disclaimer');
                        @endphp
                        @if($disclaimer)
                            <div style="font-size: 11.5px; color: var(--text-muted); line-height: 1.6; text-align: justify;">
                                {!! nl2br(e($disclaimer)) !!}
                            </div>
                        @else
                            <p>
                                <strong>{{ strtoupper(\App\Models\Setting::get('site_name', 'patrao.bet')) }}</strong> é um site de entretenimento online que oferece aos seus usuários uma experiência única em apostas esportivas e jogos online. Este site é operado pela <strong>Ana Gaming Brasil S/A</strong>, uma empresa registrada no Brasil, sob o número CNPJ <strong>55.933.850/0001-34</strong>, com sede em AL OSCAR NIEMEYER, 132 - ANDAR 12 SALA 1202 - CEP 34.006-049 - Vale do Sereno - Nova Lima/MG, entidade devidamente autorizada a operar a modalidade lotérica de apostas de quota fixa no Brasil, em temática desportiva e jogos online, conjuntamente, pela Secretaria de Prêmios e Apostas do Ministério da Fazenda, conforme Portaria SPA/MF n.º 322/2025, published em 17 de fevereiro de 2025 no Diário Oficial da União.
                            </p>
                        @endif
                        <a href="#" class="text-green">Ver mais <i class="fa-solid fa-chevron-down text-xs"></i></a>
                    </div>
                    <div class="authorized-seal" style="border: none !important; background: transparent !important; padding: 0 !important;">
                        <img src="{{ asset('autorizado.webp') }}" alt="Autorizado pelo Ministério da Fazenda" style="max-height: 56px; width: auto; display: block;">
                    </div>
                </div>

                <div class="footer-bottom-bar">
                    <div class="footer-bottom-links">
                        <a href="#">DENÚNCIAS</a>
                        <a href="#">PRIVACIDADE</a>
                        <a href="#">OUVIDORIA</a>
                    </div>
                    <div class="footer-copyright">
                        © 2026 {{ \App\Models\Setting::get('site_name', 'patrao.bet') }}. Todos os direitos reservados.
                    </div>
                    <div class="footer-developer">
                        Feito com 💚 para brasileiros
                    </div>
                </div>
    </footer>
    <!-- END Full-width Footer -->

    <!-- Scroll to top button -->
    <button class="scroll-top-btn" id="scrollTopBtn" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
        <i class="fa-solid fa-chevron-up"></i>
    </button>

    <!-- AUTHENTICATION MODALS -->
    <!-- Login Modal -->
    <div class="modal" id="loginModal">
        <div class="modal-content auth-modal-split">
            <button type="button" class="close-modal-round" onclick="closeModal('loginModal')">
                <i class="fa-solid fa-xmark"></i>
            </button>
            @php
                $loginModalBanner = \App\Models\Banner::where('type', 'login_modal')->where('is_active', true)->first();
                $loginImage = $loginModalBanner ? asset($loginModalBanner->image_path) : asset('imagens/img/login.jpg');
            @endphp
            <div class="auth-modal-left" style="background-image: url('{{ $loginImage }}');">
            </div>
            <div class="auth-modal-right">
                @php
                    $loginModalMobileBanner = \App\Models\Banner::where('type', 'login_modal_mobile')->where('is_active', true)->first();
                    $loginMobileImage = $loginModalMobileBanner ? asset($loginModalMobileBanner->image_path) : null;
                @endphp
                @if($loginMobileImage)
                    <div class="auth-mobile-banner-top" style="background-image: url('{{ $loginMobileImage }}');"></div>
                @endif
                <div class="auth-brand-logo">
                    @if(\App\Models\Setting::get('site_logo'))
                        <img src="{{ asset(\App\Models\Setting::get('site_logo')) }}" alt="Logo" style="max-height: 40px; max-width: 160px; object-fit: contain; display: block; margin: 0 auto 10px auto;">
                    @else
                        <span class="logo-v">P</span><span class="logo-text">{{ \App\Models\Setting::get('site_name', 'patrao.bet') }}</span>
                    @endif
                </div>
                <form id="loginForm" class="auth-form-split">
                    @csrf
                    <div class="form-group-split">
                        <label for="login_email">E-mail / CPF</label>
                        <input type="text" id="login_email" name="email" required placeholder="seu@email.com / CPF">
                    </div>
                    <div class="form-group-split">
                        <label for="login_password">Senha</label>
                        <div class="password-field-wrapper">
                            <input type="password" id="login_password" name="password" required placeholder="Sua senha">
                            <button type="button" class="password-toggle-eye" onclick="togglePasswordVisibility('login_password', this)">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="forgot-password-align">
                        <a href="javascript:void(0)" class="forgot-password-link" onclick="closeModal('loginModal'); openModal('forgotPasswordModal')">Esqueci minha senha</a>
                    </div>
                    <div class="error-message" id="loginError"></div>
                    <button type="submit" class="btn-auth-submit-split">
                        <span class="btn-auth-text">ENTRAR</span>
                    </button>
                </form>
                <div class="auth-footer-split">
                    Não tem conta? <a href="javascript:void(0)" onclick="closeModal('loginModal'); openModal('registerModal')">Criar conta</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Register Modal -->
    <div class="modal" id="registerModal">
        <div class="modal-content auth-modal-split">
            <button type="button" class="close-modal-round" onclick="closeModal('registerModal')">
                <i class="fa-solid fa-xmark"></i>
            </button>
            @php
                $registerModalBanner = \App\Models\Banner::where('type', 'register_modal')->where('is_active', true)->first();
                $registerImage = $registerModalBanner ? asset($registerModalBanner->image_path) : asset('imagens/img/registro.jpg');
            @endphp
            <div class="auth-modal-left" style="background-image: url('{{ $registerImage }}');">
            </div>
            <div class="auth-modal-right">
                @php
                    $registerModalMobileBanner = \App\Models\Banner::where('type', 'register_modal_mobile')->where('is_active', true)->first();
                    $registerMobileImage = $registerModalMobileBanner ? asset($registerModalMobileBanner->image_path) : null;
                @endphp
                @if($registerMobileImage)
                    <div class="auth-mobile-banner-top" style="background-image: url('{{ $registerMobileImage }}');"></div>
                @endif
                <form id="registerForm" class="auth-form-split">
                    @csrf
                    <!-- Hidden fields to satisfy backend validation -->
                    <input type="hidden" id="reg_name" name="name" value="">
                    <input type="hidden" id="reg_password_confirmation" name="password_confirmation" value="">
                    
                    <div class="form-group-split">
                        <label for="reg_cpf">CPF</label>
                        <div class="input-with-action-btn">
                            <input type="text" id="reg_cpf" name="cpf" required placeholder="000.000.000-00" maxlength="14">
                            <button type="button" class="btn-input-action-validate" onclick="validateCPFAction()">Validar</button>
                        </div>
                    </div>
                    <div class="form-group-split">
                        <label for="reg_email">E-mail</label>
                        <input type="email" id="reg_email" name="email" required placeholder="seu@email.com">
                    </div>
                    <div class="form-group-split">
                        <label for="reg_phone">Telefone</label>
                        <div class="phone-input-row-split">
                            <div class="phone-country-code-pill">
                                <span class="inline-flex items-center gap-1" style="display: inline-flex; align-items: center; gap: 6px;">
                                    <span class="inline-block flex-shrink-0" style="width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center; vertical-align: middle;">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" style="width: 100%; height: 100%;">
                                            <circle cx="50" cy="50" r="50" fill="#009b3a"/>
                                            <polygon points="50,12 88,50 50,88 12,50" fill="#fedf00"/>
                                            <circle cx="50" cy="50" r="22" fill="#002776"/>
                                            <path d="M 29,54 Q 50,44 71,54" stroke="#ffffff" stroke-width="3.5" fill="none"/>
                                        </svg>
                                    </span>
                                    <span>+55</span>
                                </span>
                            </div>
                            <input type="tel" id="reg_phone" name="phone" required placeholder="Seu telefone" maxlength="15">
                        </div>
                    </div>
                    <div class="form-group-split">
                        <label for="reg_password">Senha</label>
                        <div class="password-field-wrapper">
                            <input type="password" id="reg_password" name="password" required placeholder="Crie uma senha">
                            <button type="button" class="password-toggle-eye" onclick="togglePasswordVisibility('reg_password', this)">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                            
                            <!-- Password Strength Indicator Popover -->
                            <div class="password-requirements-popover" id="passwordPopover">
                                <div class="popover-progress-bar-wrapper">
                                    <div class="popover-progress-bar" id="popoverProgressBar"></div>
                                </div>
                                <ul class="popover-requirements-list">
                                    <li class="req-item invalid" id="req-letter">
                                        <span class="req-icon"><i class="fa-solid fa-xmark"></i></span>
                                        <span class="req-text">Pelo menos 1 letra</span>
                                    </li>
                                    <li class="req-item invalid" id="req-number">
                                        <span class="req-icon"><i class="fa-solid fa-xmark"></i></span>
                                        <span class="req-text">Pelo menos 1 número</span>
                                    </li>
                                    <li class="req-item invalid" id="req-special">
                                        <span class="req-icon"><i class="fa-solid fa-xmark"></i></span>
                                        <span class="req-text">Pelo menos 1 caractere especial (@$!%*#?&.-)</span>
                                    </li>
                                    <li class="req-item invalid" id="req-length">
                                        <span class="req-icon"><i class="fa-solid fa-xmark"></i></span>
                                        <span class="req-text">Pelo menos 8 caracteres</span>
                                    </li>
                                    <li class="req-item valid" id="req-chars">
                                        <span class="req-icon"><i class="fa-solid fa-check"></i></span>
                                        <span class="req-text">Somente letras, números e @$!%*#?&.-</span>
                                    </li>
                                </ul>
                                <div class="popover-footer">
                                    <button type="button" class="btn-popover-close" id="closePopoverBtn">Concluir</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="terms-acceptance-row-split">
                        <label class="custom-checkbox-split">
                            <input type="checkbox" id="reg_terms" required>
                            <span class="checkbox-checkmark-split"></span>
                            <span class="checkbox-label-text">
                                Confirmo que sou maior de 18 anos e aceito os <a href="javascript:void(0)" class="terms-link-highlight">Termos e Condições</a> e <a href="javascript:void(0)" class="terms-link-highlight">Política de Privacidade</a>
                            </span>
                        </label>
                    </div>
                    <div class="form-group-split inline-referral">
                        <label for="reg_referral">Código de indicação</label>
                        <input type="text" id="reg_referral" name="referral" value="0a450b95e2b4" readonly>
                    </div>
                    <div class="error-message" id="registerError"></div>
                    <button type="submit" class="btn-auth-submit-split">
                        <span class="btn-auth-text">FINALIZAR O CADASTRO</span>
                    </button>
                    <div class="limits-link-align">
                        <a href="javascript:void(0)" class="limits-link-muted" onclick="alert('Definição de limites demonstrativa.')">Quero definir meus limites agora</a>
                    </div>
                </form>
                <div class="auth-footer-split">
                    Já tem conta? <a href="javascript:void(0)" onclick="closeModal('registerModal'); openModal('loginModal')">Entrar</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Forgot Password Modal: Step 1 - Enter e-mail -->
    <div class="modal" id="forgotPasswordModal" style="display: none;">
        <div class="modal-content auth-modal-split">
            <button type="button" class="close-modal-round" onclick="closeModal('forgotPasswordModal')">
                <i class="fa-solid fa-xmark"></i>
            </button>
            @php
                $forgotModalBanner = \App\Models\Banner::where('type', 'login_modal')->where('is_active', true)->first();
                $forgotImage = $forgotModalBanner ? asset($forgotModalBanner->image_path) : asset('imagens/img/login.jpg');
            @endphp
            <div class="auth-modal-left" style="background-image: url('{{ $forgotImage }}');">
            </div>
            <div class="auth-modal-right">
                @php
                    $forgotModalMobileBanner = \App\Models\Banner::where('type', 'login_modal_mobile')->where('is_active', true)->first();
                    $forgotMobileImage = $forgotModalMobileBanner ? asset($forgotModalMobileBanner->image_path) : null;
                @endphp
                @if($forgotMobileImage)
                    <div class="auth-mobile-banner-top" style="background-image: url('{{ $forgotMobileImage }}');"></div>
                @endif
                <div class="auth-brand-logo">
                    @if(\App\Models\Setting::get('site_logo'))
                        <img src="{{ asset(\App\Models\Setting::get('site_logo')) }}" alt="Logo" style="max-height: 40px; max-width: 160px; object-fit: contain; display: block; margin: 0 auto 10px auto;">
                    @else
                        <span class="logo-v">P</span><span class="logo-text">{{ \App\Models\Setting::get('site_name', 'patrao.bet') }}</span>
                    @endif
                </div>
                <h3 style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 10px;">Esqueceu sua senha?</h3>
                <p style="font-size: 13.5px; color: var(--text-secondary); line-height: 1.5; margin-bottom: 20px;">Informe o e-mail cadastrado na sua conta. Vamos enviar um código de verificação para redefinir sua senha.</p>
                <form id="forgotPasswordForm" class="auth-form-split">
                    @csrf
                    <div class="form-group-split">
                        <label for="forgot_email">E-mail</label>
                        <input type="email" id="forgot_email" name="email" required placeholder="seu@email.com">
                    </div>
                    <div class="error-message" id="forgotPasswordError"></div>
                    <button type="submit" class="btn-auth-submit-split" id="forgotPasswordSubmitBtn">
                        <span class="btn-auth-text">RECUPERAR MINHA SENHA</span>
                    </button>
                </form>
                <div class="auth-footer-split">
                    <a href="javascript:void(0)" onclick="closeModal('forgotPasswordModal'); openModal('loginModal')">Voltar ao login</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Reset Password Modal: Step 2 - Enter code + new password -->
    <div class="modal" id="resetPasswordModal" style="display: none;">
        <div class="modal-content auth-modal-split">
            <button type="button" class="close-modal-round" onclick="closeModal('resetPasswordModal')">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div class="auth-modal-left" style="background-image: url('{{ $forgotImage }}');">
            </div>
            <div class="auth-modal-right">
                @if($forgotMobileImage)
                    <div class="auth-mobile-banner-top" style="background-image: url('{{ $forgotMobileImage }}');"></div>
                @endif
                <div class="auth-brand-logo">
                    @if(\App\Models\Setting::get('site_logo'))
                        <img src="{{ asset(\App\Models\Setting::get('site_logo')) }}" alt="Logo" style="max-height: 40px; max-width: 160px; object-fit: contain; display: block; margin: 0 auto 10px auto;">
                    @else
                        <span class="logo-v">P</span><span class="logo-text">{{ \App\Models\Setting::get('site_name', 'patrao.bet') }}</span>
                    @endif
                </div>
                <h3 style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 10px;">Digite o código</h3>
                <p style="font-size: 13.5px; color: var(--text-secondary); line-height: 1.5; margin-bottom: 20px;">Enviamos um código de 6 dígitos para <strong id="resetPasswordEmailLabel" style="color: #fff;"></strong>. Informe o código e sua nova senha abaixo.</p>
                <form id="resetPasswordForm" class="auth-form-split">
                    @csrf
                    <input type="hidden" id="reset_email" name="email" value="">
                    <div class="form-group-split">
                        <label for="reset_code">Código de verificação</label>
                        <input type="text" id="reset_code" name="code" required maxlength="6" placeholder="000000" style="letter-spacing: 4px; text-align: center; font-size: 18px;">
                    </div>
                    <div class="form-group-split">
                        <label for="reset_password">Nova senha</label>
                        <div class="password-field-wrapper">
                            <input type="password" id="reset_password" name="password" required placeholder="Mínimo 6 caracteres">
                            <button type="button" class="password-toggle-eye" onclick="togglePasswordVisibility('reset_password', this)">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group-split">
                        <label for="reset_password_confirmation">Confirme a nova senha</label>
                        <input type="password" id="reset_password_confirmation" name="password_confirmation" required placeholder="Repita a nova senha">
                    </div>
                    <div class="error-message" id="resetPasswordError"></div>
                    <button type="submit" class="btn-auth-submit-split" id="resetPasswordSubmitBtn">
                        <span class="btn-auth-text">REDEFINIR SENHA</span>
                    </button>
                </form>
                <div class="auth-footer-split">
                    Não recebeu o código? <a href="javascript:void(0)" onclick="closeModal('resetPasswordModal'); openModal('forgotPasswordModal')">Tentar novamente</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Password Reset Success Modal -->
    <div class="modal" id="passwordResetSuccessModal" style="display: none;">
        <div class="modal-content auth-modal-split" style="max-width: 420px; padding: 40px 35px; background: rgba(18, 18, 22, 0.97); border: 1px solid rgba(0, 230, 118, 0.25); box-shadow: 0 8px 40px rgba(0, 230, 118, 0.15); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); border-radius: 16px; text-align: center;">
            <button type="button" class="close-modal-round" onclick="closeModal('passwordResetSuccessModal')">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div style="width: 72px; height: 72px; border-radius: 50%; background: rgba(0, 230, 118, 0.1); display: flex; align-items: center; justify-content: center; margin: 0 auto 22px auto; box-shadow: 0 0 25px rgba(0, 230, 118, 0.2);">
                <i class="fa-solid fa-check" style="font-size: 30px; color: var(--neon-green);"></i>
            </div>
            <h3 style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 10px;">Senha redefinida!</h3>
            <p style="font-size: 13.5px; color: var(--text-secondary); line-height: 1.6; margin-bottom: 26px;">Sua senha foi alterada com sucesso. Agora você já pode entrar na sua conta com a nova senha.</p>
            <button type="button" class="btn-auth-submit-split" onclick="closeModal('passwordResetSuccessModal'); openModal('loginModal')">
                <span class="btn-auth-text">IR PARA O LOGIN</span>
            </button>
        </div>
    </div>

    <!-- GAME OVERLAYS -->
    <!-- Aviator Modal -->
    <div class="game-modal" id="aviatorModal">
        <div class="game-modal-content">
            <div class="game-modal-header">
                <span class="game-title"><i class="fa-solid fa-plane-departure text-red"></i> AVIATOR DEMO</span>
                <span class="close-game-modal" onclick="closeGameModal('aviatorModal')">&times;</span>
            </div>
            <div class="game-play-area">
                <div class="aviator-screen" id="aviatorScreen">
                    <div class="aviator-plane-wrapper" id="aviatorPlaneWrapper">
                        <div class="aviator-multiplier" id="aviatorMultiplierText">1.00x</div>
                        <div class="aviator-plane" id="aviatorPlane">
                            <i class="fa-solid fa-plane"></i>
                            <div class="plane-trail" id="planeTrail"></div>
                        </div>
                    </div>
                    <div class="aviator-message" id="aviatorMessage">FAÇA SUA APOSTA</div>
                </div>
                
                <div class="game-control-panel">
                    <div class="game-bet-controls">
                        <div class="input-with-buttons">
                            <label>Valor da Aposta (R$)</label>
                            <div class="control-row">
                                <button type="button" class="bet-adjust-btn" onclick="adjustBet('aviatorBetAmount', -10)">-10</button>
                                <input type="number" id="aviatorBetAmount" value="100" min="1" max="5000">
                                <button type="button" class="bet-adjust-btn" onclick="adjustBet('aviatorBetAmount', 10)">+10</button>
                            </div>
                        </div>
                        <button type="button" class="game-action-btn color-aviator" id="aviatorActionButton" onclick="startAviatorRound()">APOSTAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mines Modal -->
    <div class="game-modal" id="minesModal">
        <div class="game-modal-content">
            <div class="game-modal-header">
                <span class="game-title"><i class="fa-solid fa-land-mine-on text-red"></i> MINES DEMO</span>
                <span class="close-game-modal" onclick="closeGameModal('minesModal')">&times;</span>
            </div>
            <div class="game-play-area">
                <div class="mines-grid-container">
                    <div class="mines-grid" id="minesBoard">
                        <!-- 25 grid items will be inserted by JS -->
                    </div>
                </div>
                
                <div class="game-control-panel">
                    <div class="mines-setup-controls">
                        <div class="form-group">
                            <label for="minesCount">Quantidade de Minas</label>
                            <select id="minesCount">
                                <option value="2">2 minas</option>
                                <option value="3" selected>3 minas</option>
                                <option value="5">5 minas</option>
                                <option value="10">10 minas</option>
                                <option value="20">20 minas</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Valor da Aposta (R$)</label>
                            <div class="control-row">
                                <button type="button" class="bet-adjust-btn" onclick="adjustBet('minesBetAmount', -10)">-10</button>
                                <input type="number" id="minesBetAmount" value="100" min="1" max="5000">
                                <button type="button" class="bet-adjust-btn" onclick="adjustBet('minesBetAmount', 10)">+10</button>
                            </div>
                        </div>
                        <div class="mines-stats-row">
                            <div class="stat-box">
                                <span class="stat-lbl">Multiplicador</span>
                                <span class="stat-val text-green" id="minesMultiplierText">1.00x</span>
                            </div>
                            <div class="stat-box">
                                <span class="stat-lbl">Ganho Possível</span>
                                <span class="stat-val text-gold" id="minesPossiblePayout">R$ 0,00</span>
                            </div>
                        </div>
                        <button type="button" class="game-action-btn color-mines" id="minesActionButton" onclick="startMinesRound()">COMEÇAR JOGO</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fortune Tiger Modal -->
    <div class="game-modal" id="fortuneTigerModal">
        <div class="game-modal-content">
            <div class="game-modal-header">
                <span class="game-title"><i class="fa-solid fa-cat text-gold"></i> FORTUNE TIGER DEMO</span>
                <span class="close-game-modal" onclick="closeGameModal('fortuneTigerModal')">&times;</span>
            </div>
            <div class="game-play-area tiger-game-theme">
                <div class="tiger-container">
                    <div class="tiger-header-character">
                        <span class="tiger-emoji">🐯</span>
                        <div class="tiger-bubble">Gire e Ganhe! 10x Multiplicador!</div>
                    </div>
                    <div class="tiger-reels-box">
                        <div class="tiger-reels" id="tigerReelsContainer">
                            <div class="tiger-reel" id="reel0">
                                <div class="reel-symbol">🍊</div>
                                <div class="reel-symbol">🧧</div>
                                <div class="reel-symbol">🪙</div>
                            </div>
                            <div class="tiger-reel" id="reel1">
                                <div class="reel-symbol">🪙</div>
                                <div class="reel-symbol">🍊</div>
                                <div class="reel-symbol">🧧</div>
                            </div>
                            <div class="tiger-reel" id="reel2">
                                <div class="reel-symbol">🧧</div>
                                <div class="reel-symbol">🪙</div>
                                <div class="reel-symbol">🍊</div>
                            </div>
                        </div>
                    </div>
                    <div class="tiger-status-banner" id="tigerStatusBanner">BOA SORTE!</div>
                </div>

                <div class="game-control-panel">
                    <div class="tiger-setup-controls">
                        <div class="form-group">
                            <label>Valor da Aposta (R$)</label>
                            <div class="control-row">
                                <button type="button" class="bet-adjust-btn" onclick="adjustBet('tigerBetAmount', -10)">-10</button>
                                <input type="number" id="tigerBetAmount" value="50" min="1" max="5000">
                                <button type="button" class="bet-adjust-btn" onclick="adjustBet('tigerBetAmount', 10)">+10</button>
                            </div>
                        </div>
                        <button type="button" class="game-action-btn color-tiger" id="tigerSpinButton" onclick="spinTiger()">GIRAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== PROFILE DRAWER PANEL ===================== -->
    @auth
    <div class="profile-drawer-overlay" id="profileDrawerOverlay" onclick="closeProfileDrawer()"></div>
    <div class="profile-drawer" id="profileDrawer">
        <!-- Close X button -->
        <button class="profile-drawer-close" onclick="closeProfileDrawer()">
            <i class="fa-solid fa-xmark"></i>
        </button>

        <!-- Header: Avatar + Name -->
        @php
            $user = Auth::user();
            $vipPoints = $user->vip_points ?? 0;
            $currentVip = $user->currentVipLevel();
            $nextVip = $user->nextVipLevel();
            
            if ($nextVip) {
                $nextLevelPoints = $nextVip->required_points;
                $prevLevelPoints = $currentVip->required_points;
                $levelProgress = $nextLevelPoints > $prevLevelPoints 
                    ? (($vipPoints - $prevLevelPoints) / ($nextLevelPoints - $prevLevelPoints)) * 100 
                    : 0;
            } else {
                $nextLevelPoints = $vipPoints;
                $levelProgress = 100;
            }
        @endphp
        <div class="pdrawer-header" style="flex-direction: column; gap: 8px;">
            <div class="pdrawer-avatar" style="overflow: hidden; display: flex; align-items: center; justify-content: center; background: none;">
                <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            </div>
            <div class="pdrawer-username">{{ Auth::user()->name }}</div>
            <div style="width: 100%; padding: 0 15px; box-sizing: border-box; margin-top: 4px;">
                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 10px; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;">
                    <span style="color: var(--color-yellow); font-weight: 800;"><i class="fa-solid fa-crown"></i> {{ $currentVip->name }}</span>
                    <span style="color: var(--text-muted);">{{ $vipPoints }} / {{ $nextLevelPoints }} XP</span>
                </div>
                <div style="width: 100%; height: 5px; background: rgba(255,255,255,0.06); border-radius: 3px; overflow: hidden; border: 1px solid rgba(255,255,255,0.02);">
                    <div style="width: {{ $levelProgress }}%; height: 100%; background: linear-gradient(90deg, var(--color-yellow), #ffd54f); border-radius: 3px;"></div>
                </div>
            </div>
        </div>

        <!-- Scrollable body -->
        <div class="pdrawer-body">

            <!-- Main menu items -->
            <div class="pdrawer-section">
                <ul class="pdrawer-menu">
                    <li>
                        <a href="javascript:void(0)" class="pdrawer-item" onclick="closeProfileDrawer(); openMissionsModal();">
                            <span class="pdrawer-item-icon pdi-missions"><i class="fa-solid fa-crosshairs"></i></span>
                            <span class="pdrawer-item-label">Missões</span>
                            <i class="fa-solid fa-chevron-right pdrawer-arrow"></i>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="pdrawer-item" onclick="closeProfileDrawer(); openTournamentsModal();">
                            <span class="pdrawer-item-icon pdi-tournaments"><i class="fa-solid fa-trophy"></i></span>
                            <span class="pdrawer-item-label">Torneios</span>
                            <i class="fa-solid fa-chevron-right pdrawer-arrow"></i>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="pdrawer-item" onclick="closeProfileDrawer(); alert('Loja: Troque seus pontos por recompensas!');">
                            <span class="pdrawer-item-icon pdi-loja"><i class="fa-solid fa-bag-shopping"></i></span>
                            <span class="pdrawer-item-label">Loja</span>
                            <i class="fa-solid fa-chevron-right pdrawer-arrow"></i>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="pdrawer-item" onclick="closeProfileDrawer(); openDailyRouletteModal();">
                            <span class="pdrawer-item-icon pdi-minigames"><i class="fa-solid fa-gamepad"></i></span>
                            <span class="pdrawer-item-label">Mini Games</span>
                            <i class="fa-solid fa-chevron-right pdrawer-arrow"></i>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="pdrawer-item" onclick="closeProfileDrawer(); openVipModal();">
                            <span class="pdrawer-item-icon pdi-niveis"><i class="fa-solid fa-crown" style="color: #ffd700;"></i></span>
                            <span class="pdrawer-item-label">Programa VIP</span>
                            <i class="fa-solid fa-chevron-right pdrawer-arrow"></i>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="pdrawer-item" onclick="closeProfileDrawer(); openNotificationsModal();">
                            <span class="pdrawer-item-icon pdi-notif"><i class="fa-solid fa-bell"></i></span>
                            <span class="pdrawer-item-label">Notificações</span>
                            <i class="fa-solid fa-chevron-right pdrawer-arrow"></i>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="pdrawer-item" onclick="closeProfileDrawer(); alert('Promocode: Insira seu código promocional!');">
                            <span class="pdrawer-item-icon pdi-promo"><i class="fa-solid fa-ticket"></i></span>
                            <span class="pdrawer-item-label">Promocode</span>
                            <i class="fa-solid fa-chevron-right pdrawer-arrow"></i>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- MINHA CONTA section -->
            <div class="pdrawer-section-title">MINHA CONTA</div>
            <div class="pdrawer-section">
                <ul class="pdrawer-menu">
                    <li>
                        <a href="javascript:void(0)" class="pdrawer-item" onclick="closeProfileDrawer(); openAccountModal('carteira');">
                            <span class="pdrawer-item-icon pdi-carteira"><i class="fa-regular fa-credit-card"></i></span>
                            <span class="pdrawer-item-label">Carteira</span>
                            <i class="fa-solid fa-chevron-right pdrawer-arrow"></i>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="pdrawer-item" onclick="closeProfileDrawer(); openAccountModal('premios');">
                            <span class="pdrawer-item-icon pdi-premios"><i class="fa-solid fa-gift"></i></span>
                            <span class="pdrawer-item-label">Prêmios e Recompensas</span>
                            <i class="fa-solid fa-chevron-right pdrawer-arrow"></i>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="pdrawer-item" onclick="closeProfileDrawer(); openAccountModal('dados');">
                            <span class="pdrawer-item-icon pdi-dados"><i class="fa-regular fa-user"></i></span>
                            <span class="pdrawer-item-label">Dados</span>
                            <i class="fa-solid fa-chevron-right pdrawer-arrow"></i>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="pdrawer-item" onclick="closeProfileDrawer(); openAccountModal('verificacao');">
                            <span class="pdrawer-item-icon pdi-verificacao" style="background-color: var(--neon-green-glow); color: var(--neon-green);"><i class="fa-solid fa-user-shield"></i></span>
                            <span class="pdrawer-item-label">Verificar Conta</span>
                            <span class="pdrawer-status-badge" id="drawerKycStatusBadge" style="margin-left: auto; margin-right: 8px;">
                                @if(Auth::user()->kyc_status === 'approved')
                                    <span style="color: #00c853; font-size: 10px; font-weight: 700;">Aprovado</span>
                                @elseif(Auth::user()->kyc_status === 'pending')
                                    <span style="color: #ffc107; font-size: 10px; font-weight: 700;">Em análise</span>
                                @elseif(Auth::user()->kyc_status === 'declined')
                                    <span style="color: #ff3d00; font-size: 10px; font-weight: 700;">Recusado</span>
                                @else
                                    <span style="color: var(--text-muted); font-size: 10px;">Não iniciada</span>
                                @endif
                            </span>
                            <i class="fa-solid fa-chevron-right pdrawer-arrow"></i>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="pdrawer-item" onclick="closeProfileDrawer(); openAccountModal('historico');">
                            <span class="pdrawer-item-icon pdi-historico"><i class="fa-regular fa-clock"></i></span>
                            <span class="pdrawer-item-label">Histórico</span>
                            <i class="fa-solid fa-chevron-right pdrawer-arrow"></i>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="pdrawer-item" onclick="closeProfileDrawer(); openAccountModal('config');">
                            <span class="pdrawer-item-icon pdi-config"><i class="fa-solid fa-gear"></i></span>
                            <span class="pdrawer-item-label">Config.</span>
                            <i class="fa-solid fa-chevron-right pdrawer-arrow"></i>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="pdrawer-item" onclick="closeProfileDrawer(); openAccountModal('indique');">
                            <span class="pdrawer-item-icon pdi-indique"><i class="fa-solid fa-gift"></i></span>
                            <span class="pdrawer-item-label">Indique e Ganhe</span>
                            <i class="fa-solid fa-chevron-right pdrawer-arrow"></i>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="pdrawer-item" onclick="closeProfileDrawer(); openAccountModal('irpf');">
                            <span class="pdrawer-item-icon pdi-irpf"><i class="fa-regular fa-file-lines"></i></span>
                            <span class="pdrawer-item-label">IRPF</span>
                            <i class="fa-solid fa-chevron-right pdrawer-arrow"></i>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="pdrawer-item" onclick="closeProfileDrawer(); openAccountModal('protecoes');">
                            <span class="pdrawer-item-icon pdi-protecoes"><i class="fa-solid fa-shield-halved"></i></span>
                            <span class="pdrawer-item-label">Proteções</span>
                            <i class="fa-solid fa-chevron-right pdrawer-arrow"></i>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- SESSÃO section -->
            <div class="pdrawer-section-title">SESSÃO</div>
            <div class="pdrawer-section">
                <form method="POST" action="{{ route('logout') }}" id="profileDrawerLogoutForm">
                    @csrf
                    <button type="button" class="pdrawer-logout-btn" onclick="showLogoutConfirm()">
                        <span class="pdrawer-logout-icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                        <span class="pdrawer-logout-text">
                            <span class="pdrawer-logout-title">Sair da conta</span>
                            <span class="pdrawer-logout-sub">Encerrar acesso neste dispositivo</span>
                        </span>
                        <i class="fa-solid fa-chevron-right pdrawer-arrow"></i>
                    </button>
                </form>
            </div>

            <!-- Footer trust badge -->
            <div class="pdrawer-trust-badge">
                <i class="fa-solid fa-shield-halved pdrawer-trust-icon"></i>
                <span>Sua conta e dados estão protegidos</span>
            </div>

        </div>

        <!-- Logout Confirmation Dialog (inside drawer) -->
        <div class="logout-confirm-overlay" id="logoutConfirmOverlay">
            <div class="logout-confirm-box">
                <p class="logout-confirm-text">Tem certeza que deseja sair?</p>
                <div class="logout-confirm-btns">
                    <button class="logout-confirm-yes" onclick="doLogout()">Sim</button>
                    <button class="logout-confirm-no"  onclick="hideLogoutConfirm()">Não</button>
                </div>
            </div>
        </div>

    </div>

    <!-- Tabbed Account Modal -->
    <div class="modal" id="accountModal" style="display: none;">
        <div class="modal-content account-modal-content">
            <button type="button" class="close-modal-round" onclick="closeModal('accountModal')">&times;</button>
            <div class="account-modal-body">
                <!-- Left Sidebar: Tabs -->
                <div class="account-modal-sidebar">
                    <div class="account-modal-title">Minha Conta</div>
                    <ul class="account-modal-tabs">
                        <li class="active" onclick="switchAccountTab('carteira', this)"><i class="fa-regular fa-credit-card"></i> Carteira</li>
                        <li onclick="switchAccountTab('premios', this)"><i class="fa-solid fa-gift"></i> Prêmios</li>
                        <li onclick="switchAccountTab('dados', this)"><i class="fa-regular fa-user"></i> Meus Dados</li>
                        <li onclick="switchAccountTab('verificacao', this)"><i class="fa-solid fa-user-shield"></i> Verificar Conta</li>
                        <li onclick="switchAccountTab('historico', this); loadTransactionHistory();"><i class="fa-regular fa-clock"></i> Histórico</li>
                        <li onclick="switchAccountTab('config', this)"><i class="fa-solid fa-gear"></i> Config.</li>
                        <li onclick="switchAccountTab('indique', this)"><i class="fa-solid fa-gift"></i> Indique e Ganhe</li>
                        <li onclick="switchAccountTab('irpf', this)"><i class="fa-regular fa-file-lines"></i> IRPF</li>
                        <li onclick="switchAccountTab('protecoes', this)"><i class="fa-solid fa-shield-halved"></i> Proteções</li>
                    </ul>
                </div>
                <!-- Right Content Panel -->
                <div class="account-modal-content-panel">
                    <!-- Tab 1: Carteira -->
                    <div id="acc-tab-carteira" class="account-tab-pane active">
                        <h3>Carteira</h3>
                        <div class="acc-balance-card">
                            <span class="acc-balance-label">Saldo Disponível</span>
                            <span class="acc-balance-value" id="accModalUserBalance">R$ {{ number_format(Auth::user()->balance, 2, ',', '.') }}</span>
                        </div>
                        <div class="acc-actions-row">
                            <button class="header-btn btn-deposit-glow" style="flex: 1;" onclick="closeModal('accountModal'); openDepositModal();">
                                <div class="btn-flash-new"></div>
                                <span class="skew-content">DEPOSITAR</span>
                            </button>
                        </div>
                        
                        <div class="acc-sub-title" style="margin-top: 25px;">Solicitar Saque (Pix)</div>
                        <form id="withdrawalForm" onsubmit="submitWithdrawalForm(event)">
                            <div class="error-message" id="withdrawalErrorMsg" style="margin-bottom: 10px; color: var(--red); font-size: 0.8rem; font-weight: 700;"></div>
                            <div class="success-message" id="withdrawalSuccessMsg" style="margin-bottom: 10px; color: #00ff88; font-size: 0.8rem; font-weight: 700;"></div>
                            
                            <div class="form-group" style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-size: 11px; font-weight: 600; text-transform: uppercase;">Tipo de Chave Pix</label>
                                <select class="pix-select" style="width: 100%; padding: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; outline: none;">
                                    <option value="cpf" style="background-color: #121216;">CPF</option>
                                    <option value="email" style="background-color: #121216;">E-mail</option>
                                    <option value="phone" style="background-color: #121216;">Telefone</option>
                                    <option value="random" style="background-color: #121216;">Chave Aleatória</option>
                                </select>
                            </div>
                            <div class="form-group" style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-size: 11px; font-weight: 600; text-transform: uppercase;">Chave Pix</label>
                                <input type="text" name="pix_key" placeholder="Digite sua Chave Pix" required style="width: 100%; padding: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; outline: none;">
                            </div>
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label style="display: block; margin-bottom: 5px; font-size: 11px; font-weight: 600; text-transform: uppercase;">Valor do Saque</label>
                                <input type="number" name="amount" step="0.01" min="0.01" placeholder="Ex: 50.00" required style="width: 100%; padding: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; outline: none;">
                            </div>
                            <button type="submit" class="btn-deposit-submit" style="width: 100%;">
                                <span aria-hidden="true" class="btn-skew-bg"></span>
                                <span class="btn-content">SOLICITAR SAQUE</span>
                            </button>
                        </form>
                    </div>
                    
                    <!-- Tab 2: Premios -->
                    <div id="acc-tab-premios" class="account-tab-pane">
                        <h3>Prêmios e Recompensas</h3>
                        <p class="acc-text" style="color: var(--text-secondary); margin-bottom: 20px;">Aqui você acompanha seus bônus ativos e o progresso para liberação do saldo de bônus (rollover).</p>
                        
                        <div class="acc-bonus-box" style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); padding: 20px; border-radius: 12px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <span style="font-weight: 600;">Progresso do Rollover (Apostas)</span>
                                <span style="color: var(--color-yellow); font-weight: 700;">R$ 150,00 / R$ 500,00</span>
                            </div>
                            <div style="width: 100%; height: 8px; background: rgba(255,255,255,0.05); border-radius: 4px; overflow: hidden; margin-bottom: 15px;">
                                <div style="width: 30%; height: 100%; background: linear-gradient(90deg, #ffc107, #ff9800); border-radius: 4px;"></div>
                            </div>
                            <span style="font-size: 11px; color: var(--text-muted);">Requisito necessário antes de poder sacar o bônus de cadastro.</span>
                        </div>
                    </div>
                    
                    <!-- Tab 3: Dados -->
                    <div id="acc-tab-dados" class="account-tab-pane">
                        <h3>Meus Dados</h3>
                        <form id="updateProfileForm" onsubmit="submitUpdateProfile(event)">
                            <div class="error-message" id="profileErrorMsg" style="margin-bottom: 10px; color: var(--red); font-size: 0.8rem; font-weight: 700;"></div>
                            <div class="success-message" id="profileSuccessMsg" style="margin-bottom: 10px; color: #00ff88; font-size: 0.8rem; font-weight: 700;"></div>
                            
                            <div class="form-group" style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-size: 11px; font-weight: 600; text-transform: uppercase;">Nome Completo</label>
                                <input type="text" name="name" value="{{ Auth::user()->name }}" required style="width: 100%; padding: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; outline: none;">
                            </div>
                            <div class="form-group" style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-size: 11px; font-weight: 600; text-transform: uppercase; color: var(--text-muted);">E-mail (Não editável)</label>
                                <input type="email" value="{{ Auth::user()->email }}" disabled style="width: 100%; padding: 12px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; color: var(--text-muted); cursor: not-allowed; outline: none;">
                            </div>
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label style="display: block; margin-bottom: 5px; font-size: 11px; font-weight: 600; text-transform: uppercase; color: var(--text-muted);">CPF (Não editável)</label>
                                <input type="text" value="{{ Auth::user()->cpf }}" disabled style="width: 100%; padding: 12px; background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); border-radius: 8px; color: var(--text-muted); cursor: not-allowed; outline: none;">
                            </div>
                            <button type="submit" class="btn-deposit-submit" style="width: 100%;">
                                <span aria-hidden="true" class="btn-skew-bg"></span>
                                <span class="btn-content">SALVAR DADOS</span>
                            </button>
                        </form>
                    </div>
                    
                    <!-- Tab 4: Verificacao -->
                    <div id="acc-tab-verificacao" class="account-tab-pane">
                        <h3>Verificar Conta</h3>
                        <div style="text-align: center; padding: 20px 0;">
                            @if(Auth::user()->kyc_status === 'approved')
                                <div style="color: #00c853; font-size: 48px; margin-bottom: 15px;"><i class="fa-solid fa-circle-check"></i></div>
                                <h4 style="color: #00c853; font-weight: 700; font-size: 1.1rem; margin-bottom: 10px;">CONTA VERIFICADA</h4>
                                <p style="color: var(--text-secondary); max-width: 320px; margin: 0 auto; font-size: 12px; line-height: 1.5;">Parabéns! Sua conta já foi verificada e aprovada pelo nosso sistema. Suas transferências e saques estão totalmente liberados.</p>
                            @elseif(Auth::user()->kyc_status === 'pending')
                                <div style="color: #ffc107; font-size: 48px; margin-bottom: 15px;"><i class="fa-solid fa-clock-rotate-left"></i></div>
                                <h4 style="color: #ffc107; font-weight: 700; font-size: 1.1rem; margin-bottom: 10px;">EM ANÁLISE</h4>
                                <p style="color: var(--text-secondary); max-width: 320px; margin: 0 auto; font-size: 12px; line-height: 1.5;">Seus documentos estão sendo validados por nossa equipe de conformidade. Este processo costuma levar alguns minutos.</p>
                            @else
                                <div style="color: var(--text-muted); font-size: 48px; margin-bottom: 15px;"><i class="fa-solid fa-user-shield"></i></div>
                                <h4 style="color: #fff; font-weight: 700; font-size: 1.1rem; margin-bottom: 10px;">VERIFICAÇÃO DE IDENTIDADE</h4>
                                <p style="color: var(--text-secondary); font-size: 12px; line-height: 1.5; margin-bottom: 20px;">Por motivos de segurança e cumprimento de regulamentações, é necessário realizar a validação de seus documentos antes de solicitar saques.</p>
                                <button type="button" class="btn-deposit-submit" style="width: 100%;" onclick="closeModal('accountModal'); handleKycButtonClick();">
                                    <span aria-hidden="true" class="btn-skew-bg"></span>
                                    <span class="btn-content">INICIAR VERIFICAÇÃO</span>
                                </button>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Tab 5: Historico -->
                    <div id="acc-tab-historico" class="account-tab-pane">
                        <h3>Histórico de Transações</h3>
                        <div class="history-table-container" style="max-height: 380px; overflow-y: auto; margin-top: 15px;">
                            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 12px;">
                                <thead style="background: rgba(255,255,255,0.03); border-bottom: 1px solid var(--border-color);">
                                    <tr>
                                        <th style="padding: 10px; font-weight: 600; color: var(--text-muted);">Data</th>
                                        <th style="padding: 10px; font-weight: 600; color: var(--text-muted);">Tipo</th>
                                        <th style="padding: 10px; font-weight: 600; color: var(--text-muted);">Valor</th>
                                        <th style="padding: 10px; font-weight: 600; color: var(--text-muted);">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="transactionHistoryBody">
                                    <tr>
                                        <td colspan="4" style="padding: 20px; text-align: center; color: var(--text-muted);">Carregando histórico...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Tab 6: Config -->
                    <div id="acc-tab-config" class="account-tab-pane">
                        <h3>Alterar Senha</h3>
                        <form id="changePasswordForm" onsubmit="submitChangePassword(event)">
                            <div class="error-message" id="passwordErrorMsg" style="margin-bottom: 10px; color: var(--red); font-size: 0.8rem; font-weight: 700;"></div>
                            <div class="success-message" id="passwordSuccessMsg" style="margin-bottom: 10px; color: #00ff88; font-size: 0.8rem; font-weight: 700;"></div>
                            
                            <div class="form-group" style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-size: 11px; font-weight: 600; text-transform: uppercase;">Senha Atual</label>
                                <input type="password" name="current_password" required style="width: 100%; padding: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; outline: none;">
                            </div>
                            <div class="form-group" style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-size: 11px; font-weight: 600; text-transform: uppercase;">Nova Senha</label>
                                <input type="password" name="password" required style="width: 100%; padding: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; outline: none;">
                            </div>
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label style="display: block; margin-bottom: 5px; font-size: 11px; font-weight: 600; text-transform: uppercase;">Confirmar Nova Senha</label>
                                <input type="password" name="password_confirmation" required style="width: 100%; padding: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; outline: none;">
                            </div>
                            <button type="submit" class="btn-deposit-submit" style="width: 100%;">
                                <span aria-hidden="true" class="btn-skew-bg"></span>
                                <span class="btn-content">ALTERAR SENHA</span>
                            </button>
                        </form>
                    </div>
                    
                    <!-- Tab 7: Indique -->
                    <div id="acc-tab-indique" class="account-tab-pane">
                        <h3>Indique e Ganhe</h3>
                        <p class="acc-text" style="color: var(--text-secondary); margin-bottom: 15px;">Convide seus amigos e receba bônus de aposta exclusivos assim que eles completarem seus primeiros depósitos!</p>
                        
                        <div class="acc-referral-box" style="background: rgba(255,255,255,0.02); border: 1px solid var(--border-color); padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                            <label style="display: block; font-size: 10px; text-transform: uppercase; color: var(--text-muted); margin-bottom: 5px;">Seu Link de Afiliado</label>
                            <div style="display: flex; gap: 10px;">
                                <input type="text" id="accReferralLink" readonly value="" style="flex: 1; padding: 10px; background: rgba(0,0,0,0.2); border: 1px solid var(--border-color); border-radius: 6px; color: #fff; font-size: 11px; outline: none;">
                                <button type="button" class="pix-copy-btn" onclick="copyReferralLink()" style="padding: 0 15px; font-size: 11px;">Copiar</button>
                            </div>
                        </div>
                        
                        <div style="font-size: 11.5px; color: var(--text-secondary); line-height: 1.5;">
                            <strong>Regras Gerais:</strong>
                            <ul style="margin-top: 5px; padding-left: 15px;">
                                <li>Seu amigo precisa criar uma conta usando o seu link.</li>
                                <li>O bônus de R$ 10,00 é ativado automaticamente após o primeiro depósito dele de R$ 20,00 ou mais.</li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Tab 8: IRPF -->
                    <div id="acc-tab-irpf" class="account-tab-pane">
                        <h3>IRPF</h3>
                        <p class="acc-text" style="color: var(--text-secondary); margin-bottom: 15px; line-height: 1.5;">No Brasil, prêmios líquidos obtidos em apostas acima da faixa de isenção anual do Imposto de Renda Pessoa Física (IRPF) são sujeitos à tributação direta conforme a legislação aplicável (Lei nº 14.790/2023).</p>
                        
                        <div style="background: rgba(255, 235, 59, 0.05); border: 1px solid rgba(255, 235, 59, 0.2); padding: 15px; border-radius: 8px; margin-bottom: 15px; font-size: 12px; color: #ffeb3b; line-height: 1.5;">
                            <i class="fa-solid fa-circle-info"></i> Retenção na fonte simplificada: Fique tranquilo, todos os valores pagos em seus saques já são debitados com as retenções legais aplicáveis recolhidas diretamente, facilitando a sua declaração anual.
                        </div>
                    </div>
                    
                    <!-- Tab 9: Protecoes -->
                    <div id="acc-tab-protecoes" class="account-tab-pane">
                        <h3>Proteções (Jogo Responsável)</h3>
                        <p class="acc-text" style="color: var(--text-secondary); margin-bottom: 15px;">Defina limites para apoiar o jogo consciente e responsável em nossa plataforma.</p>
                        
                        <form id="responsibleGamingForm" onsubmit="submitResponsibleGaming(event)">
                            <div class="success-message" id="gamingSuccessMsg" style="margin-bottom: 10px; color: #00ff88; font-size: 0.8rem; font-weight: 700;"></div>
                            
                            <div class="form-group" style="margin-bottom: 15px;">
                                <label style="display: block; margin-bottom: 5px; font-size: 11px; font-weight: 600; text-transform: uppercase;">Limite Diário de Depósito</label>
                                <input type="number" placeholder="Sem limite" style="width: 100%; padding: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; outline: none;">
                            </div>
                            <div class="form-group" style="margin-bottom: 20px;">
                                <label style="display: block; margin-bottom: 5px; font-size: 11px; font-weight: 600; text-transform: uppercase;">Limite Semanal de Depósito</label>
                                <input type="number" placeholder="Sem limite" style="width: 100%; padding: 12px; background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); border-radius: 8px; color: #fff; outline: none;">
                            </div>
                            <button type="submit" class="btn-deposit-submit" style="width: 100%;">
                                <span aria-hidden="true" class="btn-skew-bg"></span>
                                <span class="btn-content">SALVAR CONFIGURAÇÕES</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Affiliate Modal -->
    <div id="AFF_PANEL_REAL" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(0,0,0,0.9); z-index: 999999999; align-items: center; justify-content: center;">
        <div class="affiliate-modal-content" style="background: #031c12; border: 2px solid var(--neon-green); width: 95%; max-width: 1100px; height: 90vh; overflow-y: auto; position: relative; display: flex; flex-direction: column; border-radius: 20px;">
            <div class="affiliate-header">
                <button type="button" class="affiliate-back-btn" onclick="document.getElementById('AFF_PANEL_REAL').style.display='none'; document.body.style.overflow='';">
                    <i class="fa-solid fa-chevron-left"></i> Afilhado
                </button>
                <ul class="affiliate-tabs">
                    <li class="active" onclick="switchAffiliateTab('painel', this)">Painel</li>
                    <li onclick="switchAffiliateTab('referencias', this)">Referências</li>
                    <li onclick="switchAffiliateTab('relatorio', this)">Relatório</li>
                    <li onclick="switchAffiliateTab('pagamentos', this)">Pagamentos</li>
                    <li onclick="switchAffiliateTab('faq', this)">FAQ</li>
                    <li onclick="switchAffiliateTab('tutorial', this)">Tutorial</li>
                </ul>
            </div>
            
            <div class="affiliate-body">
                <!-- Tab 1: Painel -->
                <div id="aff-tab-painel" class="affiliate-tab-pane active">
                    <!-- Grid Row 1: Hoje & Total -->
                    <div class="aff-grid-row-1">
                        <!-- Card Hoje -->
                        <div class="aff-card">
                            <div class="aff-card-title">
                                <span class="dot-yellow"></span> Hoje
                            </div>
                            <div class="aff-metrics-grid">
                                <div class="aff-metric-item">
                                    <span class="aff-metric-value" id="aff-today-registrations">0</span>
                                    <span class="aff-metric-label">Inscrições</span>
                                </div>
                                <div class="aff-metric-item">
                                    <span class="aff-metric-value" id="aff-today-new-players">0</span>
                                    <span class="aff-metric-label">Novos jogadores</span>
                                </div>
                                <div class="aff-metric-item">
                                    <span class="aff-metric-value">R$ <span id="aff-today-valid-bets">0,00</span></span>
                                    <span class="aff-metric-label">Aposta válidas</span>
                                </div>
                                <div class="aff-metric-item">
                                    <span class="aff-metric-value">R$ <span id="aff-today-commission-value">0,00</span></span>
                                    <span class="aff-metric-label">Comissão</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Total -->
                        <div class="aff-card">
                            <div class="aff-card-title">
                                <span class="dot-orange"></span> Total
                            </div>
                            <div class="aff-metrics-grid">
                                <div class="aff-metric-item">
                                    <span class="aff-metric-value" id="aff-total-registrations">0</span>
                                    <span class="aff-metric-label">Inscrições</span>
                                </div>
                                <div class="aff-metric-item">
                                    <span class="aff-metric-value" id="aff-total-players">0</span>
                                    <span class="aff-metric-label">Jogadores totais</span>
                                </div>
                                <div class="aff-metric-item">
                                    <span class="aff-metric-value">R$ <span id="aff-total-valid-bets">0,00</span></span>
                                    <span class="aff-metric-label">Aposta válidas total</span>
                                </div>
                                <div class="aff-metric-item">
                                    <span class="aff-metric-value">R$ <span id="aff-total-commission-value">0,00</span></span>
                                    <span class="aff-metric-label">Comissão</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Grid Row 2: Comissão & Compartilhe -->
                    <div class="aff-grid-row-2">
                        <!-- Comissão Card -->
                        <div class="aff-card">
                            <div class="aff-card-title">Comissão</div>
                            <div class="aff-commission-box">
                                <div class="aff-agent-level">
                                    <i class="fa-solid fa-crown"></i>
                                    <span>Nível 1</span>
                                    <span style="font-size: 8px; color: var(--text-secondary); margin-top: 2px;">Agente</span>
                                </div>
                                <div class="aff-commission-stats">
                                    <div class="aff-commission-stat-item">
                                        <span class="aff-commission-value paid">R$ <span id="aff-paid-commission">0,00</span></span>
                                        <span class="aff-commission-label">Total pago</span>
                                    </div>
                                    <div class="aff-commission-stat-item">
                                        <span class="aff-commission-value unpaid" style="color: var(--neon-green) !important;">R$ <span id="aff-unpaid-commission">0,00</span></span>
                                        <span class="aff-commission-label">Não pago</span>
                                    </div>
                                </div>
                            </div>
                            <div class="aff-transfer-btn-wrapper">
                                <button type="button" class="aff-transfer-btn" onclick="transferAffiliateCommission()">
                                    Transferir para a Carteira
                                </button>
                                <span class="aff-transfer-subtext">Valor mínimo de transferência de 10 BRL</span>
                            </div>
                        </div>
                        
                        <!-- Compartilhe Card -->
                        <div class="aff-card" style="display: flex; flex-direction: column; justify-content: center;">
                            <div class="aff-card-title" style="margin-bottom: 15px;">Compartilhe com sua comunidade social</div>
                            <div class="aff-social-icons">
                                <a href="javascript:void(0)" class="aff-social-btn facebook" id="aff-share-facebook" target="_blank"><i class="fa-brands fa-facebook-f"></i></a>
                                <a href="javascript:void(0)" class="aff-social-btn whatsapp" id="aff-share-whatsapp" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
                                <a href="javascript:void(0)" class="aff-social-btn telegram" id="aff-share-telegram" target="_blank"><i class="fa-brands fa-telegram"></i></a>
                                <a href="javascript:void(0)" class="aff-social-btn twitter" id="aff-share-twitter" target="_blank"><i class="fa-brands fa-x-twitter"></i></a>
                                <a href="javascript:void(0)" class="aff-social-btn email" id="aff-share-email"><i class="fa-regular fa-envelope"></i></a>
                            </div>
                            
                            <label style="display: block; font-size: 0.72rem; color: var(--text-secondary); margin-bottom: 8px; font-weight: 700;">Compartilhe este link de indicação com seus amigos</label>
                            <div class="aff-share-link-box">
                                <input type="text" id="aff-referral-link-input" readonly value="" class="aff-share-link-input">
                                <button type="button" class="aff-copy-btn" onclick="copyAffiliateLink()">Copiar</button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Grid Row 3: Regras & Diagrama -->
                    <div class="aff-grid-row-3">
                        <!-- Regras -->
                        <div class="aff-card" style="display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <div class="aff-card-title">Regras de recompensas por comissão</div>
                                <p style="font-size: 0.8rem; color: var(--text-secondary); line-height: 1.5; margin-bottom: 15px;">
                                    Ganhe dinheiro indicando amigos para o {{ \App\Models\Setting::get('site_name', 'patrao.bet') }}. Você ganha mais de 25% em recompensas de comissão!
                                </p>
                            </div>
                            
                            <div style="text-align: center; background: rgba(0,0,0,0.15); padding: 15px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.02); margin-bottom: 15px;">
                                <div style="font-size: 0.78rem; font-weight: 700; color: var(--text-primary); margin-bottom: 5px;">Número de indicações > 100</div>
                                <div style="font-size: 1.1rem; font-weight: 900; color: var(--red);">Comissão > R$ 5.000 todo mês!</div>
                                <div style="position: relative; height: 4px; background: rgba(255,255,255,0.1); border-radius: 2px; margin-top: 10px; overflow: hidden;">
                                    <div style="position: absolute; left: 0; top: 0; height: 100%; width: 65%; background: var(--neon-green);"></div>
                                    <div style="position: absolute; left: 65%; top: -3px; width: 10px; height: 10px; border-radius: 50%; background: #fff; box-shadow: 0 0 5px #fff;"></div>
                                </div>
                            </div>
                            
                            <div class="aff-rules-text">
                                <div style="display: flex; gap: 8px; margin-bottom: 6px;">
                                    <span style="color: var(--neon-green); font-weight: 800;">1.</span>
                                    <span>Você receberá uma porcentagem de comissão diferente toda vez que um jogador indicado por você fizer uma aposta, <strong style="color: var(--red);">ganhar ou perder</strong>.</span>
                                </div>
                                <div style="display: flex; gap: 8px;">
                                    <span style="color: var(--neon-green); font-weight: 800;">2.</span>
                                    <span>O sistema calcula a comissão a cada 24 horas.</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Diagrama -->
                        <div class="aff-card" style="text-align: center;">
                            <div style="color: var(--neon-green); font-weight: 800; font-size: 0.85rem; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px;">Desenvolvimento ilimitado de subordinados</div>
                            <div class="diagram-container">
                                <svg width="100%" height="200" viewBox="0 0 400 200" style="max-width: 400px; display: block; margin: 0 auto;">
                                    <!-- Curved lines -->
                                    <path d="M200 40 Q130 90 90 120" stroke="rgba(0, 230, 118, 0.4)" stroke-width="2" fill="none" />
                                    <path d="M200 40 Q200 90 200 120" stroke="rgba(0, 230, 118, 0.4)" stroke-width="2" fill="none" />
                                    <path d="M200 40 Q270 90 310 120" stroke="rgba(0, 230, 118, 0.4)" stroke-width="2" fill="none" />
                                    
                                    <path d="M90 130 Q60 160 40 180" stroke="rgba(255,255,255,0.15)" stroke-width="1.5" fill="none" stroke-dasharray="3,3" />
                                    <path d="M90 130 Q90 160 90 180" stroke="rgba(255,255,255,0.15)" stroke-width="1.5" fill="none" stroke-dasharray="3,3" />
                                    <path d="M90 130 Q120 160 140 180" stroke="rgba(255,255,255,0.15)" stroke-width="1.5" fill="none" stroke-dasharray="3,3" />
                                    
                                    <path d="M310 130 Q280 160 260 180" stroke="rgba(255,255,255,0.15)" stroke-width="1.5" fill="none" stroke-dasharray="3,3" />
                                    <path d="M310 130 Q310 160 310 180" stroke="rgba(255,255,255,0.15)" stroke-width="1.5" fill="none" stroke-dasharray="3,3" />
                                    <path d="M310 130 Q340 160 360 180" stroke="rgba(255,255,255,0.15)" stroke-width="1.5" fill="none" stroke-dasharray="3,3" />

                                    <!-- Main node: VOCÊ -->
                                    <circle cx="200" cy="40" r="26" fill="var(--sidebar-bg, #031c12)" stroke="var(--neon-green)" stroke-width="3" />
                                    <text x="200" y="44" fill="#fff" font-size="10.5" font-weight="900" text-anchor="middle">Você</text>
                                    
                                    <!-- Level 1 nodes -->
                                    <!-- Left -->
                                    <circle cx="90" cy="130" r="18" fill="#161619" stroke="var(--gold)" stroke-width="2" />
                                    <text x="90" y="134" fill="#999" font-size="20" text-anchor="middle">👤</text>
                                    <circle cx="104" cy="118" r="7" fill="var(--gold)" />
                                    <text x="104" y="121" fill="#000" font-size="8" font-weight="900" text-anchor="middle">$</text>
                                    
                                    <!-- Center -->
                                    <circle cx="200" cy="130" r="18" fill="#161619" stroke="var(--gold)" stroke-width="2" />
                                    <text x="200" y="134" fill="#999" font-size="20" text-anchor="middle">👤</text>
                                    <circle cx="214" cy="118" r="7" fill="var(--gold)" />
                                    <text x="214" y="121" fill="#000" font-size="8" font-weight="900" text-anchor="middle">$</text>
                                    
                                    <!-- Right -->
                                    <circle cx="310" cy="130" r="18" fill="#161619" stroke="var(--gold)" stroke-width="2" />
                                    <text x="310" y="134" fill="#999" font-size="20" text-anchor="middle">👤</text>
                                    <circle cx="324" cy="118" r="7" fill="var(--gold)" />
                                    <text x="324" y="121" fill="#000" font-size="8" font-weight="900" text-anchor="middle">$</text>
                                    
                                    <!-- Level 2 nodes (Subordinates) -->
                                    <circle cx="40" cy="180" r="11" fill="rgba(255,255,255,0.05)" stroke="rgba(255,255,255,0.2)" stroke-width="1" />
                                    <text x="40" y="183" fill="#666" font-size="12" text-anchor="middle">👤</text>
                                    
                                    <circle cx="90" cy="180" r="11" fill="rgba(255,255,255,0.05)" stroke="rgba(255,255,255,0.2)" stroke-width="1" />
                                    <text x="90" y="183" fill="#666" font-size="12" text-anchor="middle">👤</text>
                                    
                                    <circle cx="140" cy="180" r="11" fill="rgba(255,255,255,0.05)" stroke="rgba(255,255,255,0.2)" stroke-width="1" />
                                    <text x="140" y="183" fill="#666" font-size="12" text-anchor="middle">👤</text>
                                    
                                    <circle cx="260" cy="180" r="11" fill="rgba(255,255,255,0.05)" stroke="rgba(255,255,255,0.2)" stroke-width="1" />
                                    <text x="260" y="183" fill="#666" font-size="12" text-anchor="middle">👤</text>
                                    
                                    <circle cx="310" cy="180" r="11" fill="rgba(255,255,255,0.05)" stroke="rgba(255,255,255,0.2)" stroke-width="1" />
                                    <text x="310" y="183" fill="#666" font-size="12" text-anchor="middle">👤</text>
                                    
                                    <circle cx="360" cy="180" r="11" fill="rgba(255,255,255,0.05)" stroke="rgba(255,255,255,0.2)" stroke-width="1" />
                                    <text x="360" y="183" fill="#666" font-size="12" text-anchor="middle">👤</text>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tab 2: Referencias -->
                <div id="aff-tab-referencias" class="affiliate-tab-pane">
                    <div class="aff-search-row">
                        <input type="text" id="aff-references-search" placeholder="Buscar por conta..." onkeyup="filterAffiliateReferences()" class="aff-search-input">
                    </div>
                    
                    <div class="aff-table-wrapper">
                        <table class="aff-table" id="aff-references-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Conta</th>
                                    <th>Apostas (R$)</th>
                                    <th>Aposta equipe (R$)</th>
                                    <th>Comissão</th>
                                    <th>Data de Registro</th>
                                </tr>
                            </thead>
                            <tbody id="aff-references-body">
                                <tr>
                                    <td colspan="6" class="aff-table-empty">
                                        <i class="fa-regular fa-folder-open"></i> Opa! Ainda não há dados!
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="aff-table-footer">
                        <span>Apostas válidas: R$ <span id="aff-refs-total-bets">0,00</span></span>
                        <span>Comissão acumulada: R$ <span id="aff-refs-total-commission">0,00</span></span>
                    </div>
                </div>
                
                <!-- Tab 3: Relatorio -->
                <div id="aff-tab-relatorio" class="affiliate-tab-pane">
                    <div class="aff-table-wrapper">
                        <table class="aff-table">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Inscrições</th>
                                    <th>Apostas Válidas</th>
                                    <th>Nível de Agente</th>
                                    <th>Comissão</th>
                                </tr>
                            </thead>
                            <tbody id="aff-reports-body">
                                <tr>
                                    <td colspan="5" class="aff-table-empty">
                                        <i class="fa-regular fa-folder-open"></i> Opa! Ainda não há dados!
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Tab 4: Pagamentos -->
                <div id="aff-tab-pagamentos" class="affiliate-tab-pane">
                    <div class="aff-table-wrapper">
                        <table class="aff-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Data</th>
                                    <th>Detalhe</th>
                                    <th>Comissão</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="aff-payments-body">
                                <tr>
                                    <td colspan="5" class="aff-table-empty">
                                        <i class="fa-regular fa-folder-open"></i> Opa! Ainda não há dados!
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Tab 5: FAQ -->
                <div id="aff-tab-faq" class="affiliate-tab-pane">
                    <div class="aff-rules-flex">
                        <div class="aff-rules-left">
                            <div class="aff-faq-list">
                                <div class="aff-faq-item">
                                    <div class="aff-faq-question">O que é o programa de afiliados {{ \App\Models\Setting::get('site_name', 'patrao.bet') }}?</div>
                                    <div class="aff-faq-answer">
                                        É um programa onde oferecemos a você a oportunidade de ganhar dinheiro indicando amigos para a nossa plataforma. Você recebe comissões a cada aposta feita por eles.
                                    </div>
                                </div>
                                <div class="aff-faq-item">
                                    <div class="aff-faq-question">Quanto custa para participar?</div>
                                    <div class="aff-faq-answer">
                                        Absolutamente nada. A participação no programa de afiliados é 100% gratuita.
                                    </div>
                                </div>
                                <div class="aff-faq-item">
                                    <div class="aff-faq-question">Posso obter benefícios mesmo se não tiver um site?</div>
                                    <div class="aff-faq-answer">
                                        Claro que sim! Você pode divulgar seu link de indicação diretamente para amigos, em grupos de WhatsApp, Telegram, redes sociais como Instagram, Facebook, ou por e-mail.
                                    </div>
                                </div>
                                <div class="aff-faq-item">
                                    <div class="aff-faq-question">Quanto eu ganho?</div>
                                    <div class="aff-faq-answer">
                                        Como parte da nossa rede de afiliados, você recebe uma comissão de indicação (configurada no painel de controle) sobre os depósitos efetuados pelos seus indicados.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="aff-rules-right">
                            <h4 style="font-size: 0.9rem; font-weight: 800; color: #fff; margin-bottom: 15px; text-transform: uppercase;">Definições de Termos</h4>
                            <div class="aff-rules-text" style="display: flex; flex-direction: column; gap: 12px;">
                                <div>
                                    <strong style="color: var(--neon-green); display: block; margin-bottom: 3px;">Inscrições:</strong>
                                    <span>O número total de usuários que se cadastraram utilizando o seu link de afiliado.</span>
                                </div>
                                <div>
                                    <strong style="color: var(--neon-green); display: block; margin-bottom: 3px;">Novos Jogadores:</strong>
                                    <span>Os jogadores indicados por você que realizaram um depósito na plataforma.</span>
                                </div>
                                <div>
                                    <strong style="color: var(--neon-green); display: block; margin-bottom: 3px;">Aposta válidas:</strong>
                                    <span>O volume financeiro total de apostas que foram feitas pelos jogadores da sua rede.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tab 6: Tutorial -->
                <div id="aff-tab-tutorial" class="affiliate-tab-pane">
                    <h3 style="font-family: var(--font-heading); font-size: 1.1rem; font-weight: 900; color: #fff; text-align: center; margin-bottom: 25px; text-transform: uppercase;">Como funciona o Programa de Afiliados?</h3>
                    <div class="aff-tutorial-steps">
                        <div class="aff-tutorial-step">
                            <div class="aff-tutorial-step-num">1</div>
                            <div class="aff-tutorial-step-title">Obtenha o Link</div>
                            <div class="aff-tutorial-step-desc">Copie o seu link de indicação único disponível no Painel.</div>
                        </div>
                        <div class="aff-tutorial-step">
                            <div class="aff-tutorial-step-num">2</div>
                            <div class="aff-tutorial-step-title">Compartilhe</div>
                            <div class="aff-tutorial-step-desc">Envie o link para amigos ou compartilhe nas redes sociais.</div>
                        </div>
                        <div class="aff-tutorial-step">
                            <div class="aff-tutorial-step-num">3</div>
                            <div class="aff-tutorial-step-title">Ganhe Comissões</div>
                            <div class="aff-tutorial-step-desc">Receba comissões automáticas assim que seus amigos depositarem.</div>
                        </div>
                        <div class="aff-tutorial-step">
                            <div class="aff-tutorial-step-num">4</div>
                            <div class="aff-tutorial-step-title">Resgate para Carteira</div>
                            <div class="aff-tutorial-step-desc">Transfira a comissão acumulada direto para seu saldo principal e jogue!</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DAILY ROULETTE MODAL -->
    <div class="modal" id="dailyRouletteModal" style="display: none;">
        <div class="modal-content auth-modal-split" style="max-width: 420px; padding: 30px; background: rgba(18, 18, 22, 0.95); border: 1px solid rgba(248, 204, 27, 0.2); box-shadow: 0 8px 32px rgba(248, 204, 27, 0.1); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); border-radius: 16px; text-align: center;">
            <button type="button" class="close-modal-round" onclick="closeModal('dailyRouletteModal')">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <h3 style="font-family: var(--font-heading); font-size: 20px; font-weight: 800; color: #fff; margin-bottom: 5px;">Roleta Diária</h3>
            <p style="font-size: 12.5px; color: var(--text-secondary); margin-bottom: 25px;">Gire a roleta uma vez por dia e ganhe bônus ou prêmios reais!</p>
            
            <div style="position: relative; width: 280px; height: 280px; margin: 0 auto 20px; display: flex; align-items: center; justify-content: center;">
                <div style="position: absolute; top: -14px; z-index: 10; width: 0; height: 0; border-left: 15px solid transparent; border-right: 15px solid transparent; border-top: 25px solid var(--color-yellow); filter: drop-shadow(0 2px 4px rgba(0,0,0,0.5));"></div>
                
                <div id="rouletteWheel" style="width: 100%; height: 100%; border-radius: 50%; border: 6px solid #1c1c22; box-shadow: 0 8px 25px rgba(0,0,0,0.6), 0 0 15px rgba(248, 204, 27, 0.2); background: conic-gradient(
                    #f8cc1b 0deg 60deg,
                    #121215 60deg 120deg,
                    #f8cc1b 120deg 180deg,
                    #121215 180deg 240deg,
                    #f8cc1b 240deg 300deg,
                    #121215 300deg 360deg
                ); transition: transform 4s cubic-bezier(0.1, 0.8, 0.1, 1); position: relative; overflow: hidden;">
                    <div style="position: absolute; width: 100%; height: 100%; transform: rotate(30deg); font-weight: 900; font-size: 10px; color: #000; pointer-events: none;">
                        <span style="position: absolute; top: 20px; left: 50%; transform: translateX(-50%); text-align: center; line-height: 1.2;">R$ 1.00<br>BÔNUS</span>
                    </div>
                    <div style="position: absolute; width: 100%; height: 100%; transform: rotate(90deg); font-weight: 900; font-size: 10px; color: #fff; pointer-events: none;">
                        <span style="position: absolute; top: 20px; left: 50%; transform: translateX(-50%); text-align: center; line-height: 1.2;">R$ 2.00<br>BÔNUS</span>
                    </div>
                    <div style="position: absolute; width: 100%; height: 100%; transform: rotate(150deg); font-weight: 900; font-size: 10px; color: #000; pointer-events: none;">
                        <span style="position: absolute; top: 20px; left: 50%; transform: translateX(-50%); text-align: center; line-height: 1.2;">R$ 5.00<br>BÔNUS</span>
                    </div>
                    <div style="position: absolute; width: 100%; height: 100%; transform: rotate(210deg); font-weight: 900; font-size: 10px; color: #fff; pointer-events: none;">
                        <span style="position: absolute; top: 20px; left: 50%; transform: translateX(-50%); text-align: center; line-height: 1.2;">R$ 10.00<br>BÔNUS</span>
                    </div>
                    <div style="position: absolute; width: 100%; height: 100%; transform: rotate(270deg); font-weight: 900; font-size: 10px; color: #000; pointer-events: none;">
                        <span style="position: absolute; top: 20px; left: 50%; transform: translateX(-50%); text-align: center; line-height: 1.2;">R$ 50.00<br>BÔNUS</span>
                    </div>
                    <div style="position: absolute; width: 100%; height: 100%; transform: rotate(330deg); font-weight: 900; font-size: 10px; color: #fff; pointer-events: none;">
                        <span style="position: absolute; top: 20px; left: 50%; transform: translateX(-50%); text-align: center; line-height: 1.2;">SEM<br>SORTE</span>
                    </div>
                </div>
                
                <button id="spinBtn" onclick="spinRoulette()" style="position: absolute; width: 65px; height: 65px; border-radius: 50%; background: #fff; border: 4px solid #1c1c22; color: #000; font-weight: 900; font-size: 12px; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.5); z-index: 5; display: flex; align-items: center; justify-content: center; outline: none;">GIRAR</button>
            </div>
            
            <div id="rouletteResult" style="min-height: 40px; margin-top: 15px; font-weight: 700; font-size: 14px; color: var(--color-yellow);"></div>
        </div>
    </div>

    <!-- NOTIFICATIONS MODAL -->
    <div class="modal" id="notificationsModal" style="display: none;">
        <div class="modal-content auth-modal-split" style="max-width: 450px; padding: 30px; background: rgba(18, 18, 22, 0.95); border: 1px solid rgba(255, 213, 79, 0.2); box-shadow: 0 8px 32px rgba(255, 213, 79, 0.1); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); border-radius: 16px;">
            <button type="button" class="close-modal-round" onclick="closeModal('notificationsModal')">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border-color); padding-bottom: 12px; margin-top: 10px;">
                <h3 style="font-family: var(--font-heading); font-size: 18px; font-weight: 800; color: #fff; margin: 0;"><i class="fa-solid fa-bell"></i> Notificações</h3>
                <a href="javascript:void(0)" onclick="readAllNotifications(event)" style="font-size: 11px; color: var(--color-yellow); text-decoration: none; font-weight: 700; text-transform: uppercase;">Limpar todas</a>
            </div>
            
            <div id="notificationsModalList" style="display: flex; flex-direction: column; gap: 10px; max-height: 350px; overflow-y: auto;">
                <div style="text-align: center; padding: 30px 10px; color: var(--text-muted); font-size: 13px;">Nenhuma notificação encontrada.</div>
            </div>
        </div>
    </div>

    <!-- Didit KYC Enrollment Modal -->
    <div class="modal" id="kycEnrollmentModal" style="display: none;">
        <div class="modal-content auth-modal-split" style="max-width: 480px; padding: 30px; background: rgba(18, 18, 22, 0.95); border: 1px solid rgba(0, 230, 118, 0.2); box-shadow: 0 8px 32px var(--neon-green-glow); backdrop-filter: blur(15px); -webkit-backdrop-filter: blur(15px); border-radius: 16px;">
            <button type="button" class="close-modal-round" onclick="closeModal('kycEnrollmentModal')">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <div style="text-align: center; margin-top: 15px; display: flex; flex-direction: column; align-items: center;">
                <div style="width: 70px; height: 70px; border-radius: 50%; background: rgba(0, 230, 118, 0.1); display: inline-flex; align-items: center; justify-content: center; color: var(--neon-green); font-size: 2.2rem; margin-bottom: 20px; box-shadow: 0 0 20px rgba(0, 230, 118, 0.2);">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <h3 style="font-family: var(--font-heading); font-size: 22px; font-weight: 800; color: #fff; margin-bottom: 12px; letter-spacing: 0.5px;">Verificação de Identidade</h3>
                <p style="font-size: 14px; color: var(--text-secondary); line-height: 1.6; margin-bottom: 20px; padding: 0 10px;">
                    Para manter a plataforma segura e cumprir com as diretrizes regulatórias, solicitamos a verificação de sua identidade antes de liberar depósitos e retiradas.
                </p>
                
                <div style="text-align: left; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); padding: 15px; border-radius: 10px; margin-bottom: 25px; width: 100%; box-sizing: border-box;">
                    <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 10px; font-size: 13px; color: var(--text-primary);">
                        <i class="fa-solid fa-circle-check" style="color: var(--neon-green); flex-shrink: 0;"></i>
                        <span>Saques instantâneos liberados</span>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center; margin-bottom: 10px; font-size: 13px; color: var(--text-primary);">
                        <i class="fa-solid fa-circle-check" style="color: var(--neon-green); flex-shrink: 0;"></i>
                        <span>Proteção extra contra fraudes e roubo de dados</span>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center; font-size: 13px; color: var(--text-primary);">
                        <i class="fa-solid fa-circle-check" style="color: var(--neon-green); flex-shrink: 0;"></i>
                        <span>Processamento automatizado em 2 minutos por IA</span>
                    </div>
                </div>

                <button type="button" class="btn-auth-submit-split" onclick="closeModal('kycEnrollmentModal'); startDiditKycFlow();" style="width: 100%; border-radius: 10px; padding: 14px; background: linear-gradient(135deg, var(--neon-green, #00e676), #00b0ff); color: #fff; font-weight: 700; border: none; cursor: pointer; box-shadow: 0 4px 15px rgba(0, 230, 118, 0.3); font-size: 13px; letter-spacing: 0.5px;">
                    INICIAR VERIFICAÇÃO AGORA
                </button>
                <div style="margin-top: 15px;">
                    <a href="javascript:void(0)" onclick="closeModal('kycEnrollmentModal')" style="font-size: 12.5px; color: var(--text-muted); text-decoration: none;">Verificar mais tarde</a>
                </div>
            </div>
        </div>
    </div>

    <!-- DEPOSIT MODAL -->
    <div class="deposit-modal" id="depositModal">
        <div class="deposit-modal-content">
            <div class="deposit-modal-header">
                <h3>Depositar</h3>
                <button type="button" class="deposit-modal-close" onclick="closeDepositModal()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            @php
                $depositPromoTitle = \App\Models\Setting::get('deposit_promo_title', 'OFERTA ÚNICA, NÃO SAIA! 🔥');
                $depositPromoDesc = \App\Models\Setting::get('deposit_promo_desc', 'Deposite 50 ou mais e ganhe 20 giros no fortune rabbit!');
                $depositPromoTimer = intval(\App\Models\Setting::get('deposit_promo_timer', '5'));
                $depositPromoTerms = \App\Models\Setting::get('deposit_promo_terms', 'CONDIÇÃO VÁLIDA PARA PRIMEIRO DEPÓSITO');
                $depositPromoImage = \App\Models\Setting::get('deposit_promo_image');
                $depositQuickAmounts = explode(',', \App\Models\Setting::get('deposit_quick_amounts', '20, 50, 100, 250, 500, 1000'));
                $depositHotAmounts = explode(',', \App\Models\Setting::get('deposit_hot_amounts', '50, 250, 1000'));
                $minDeposit = \App\Models\Setting::get('min_deposit', '5.00');
            @endphp

            <!-- Stage 1: Form -->
            <div id="depositFormStage">
                <!-- Promo offer banner -->
                <div class="deposit-promo-banner">
                    <div class="deposit-promo-text">
                        <h4>{{ $depositPromoTitle }}</h4>
                        <p>{!! $depositPromoDesc !!}</p>
                        
                        <!-- Offer Timer -->
                        <div class="deposit-timer-wrapper">
                            <div class="deposit-timer-block" id="promoTimerH">00<span class="lbl">H</span></div>
                            <div class="deposit-timer-divider">:</div>
                            <div class="deposit-timer-block" id="promoTimerM">00<span class="lbl">M</span></div>
                            <div class="deposit-timer-divider">:</div>
                            <div class="deposit-timer-block" id="promoTimerS">00<span class="lbl">S</span></div>
                        </div>
                    </div>
                    
                    @if($depositPromoImage)
                        <img src="{{ asset($depositPromoImage) }}" class="deposit-promo-character" alt="Promo Mascot">
                    @else
                        <img src="{{ asset('imagens/jogos/1.jpg') }}" class="deposit-promo-character" alt="Promo Mascot">
                    @endif

                    <div class="deposit-promo-footer-text">
                        {{ $depositPromoTerms }}
                    </div>
                </div>

                <!-- Gateway Carousel -->
                <div class="deposit-gateways-container">
                    <button type="button" class="gateway-arrow" onclick="scrollGateways(-1)">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <div class="gateways-viewport" id="gatewaysViewport">
                        <div class="gateways-track" id="gatewaysTrack">
                            <div class="gateway-card active" data-gateway="heartpay" onclick="selectGateway(this)">
                                <span class="gateway-name">HEARTPAY</span>
                            </div>
                            <div class="gateway-card" data-gateway="efibank" onclick="selectGateway(this)">
                                <span class="gateway-name">EFI BANK</span>
                            </div>
                            <div class="gateway-card" data-gateway="paag" onclick="selectGateway(this)">
                                <span class="gateway-name">PAAG</span>
                            </div>
                            <div class="gateway-card" data-gateway="oktopay" onclick="selectGateway(this)">
                                <span class="gateway-name">OKTOPAY</span>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="gateway-arrow" onclick="scrollGateways(1)">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>

                <!-- Form Fields -->
                <form id="depositForm" onsubmit="submitDepositForm(event)">
                    @csrf
                    <input type="hidden" id="depositGatewayInput" name="payment_method" value="HEARTPAY">
                    <input type="hidden" id="depositCouponIdInput" name="coupon_id" value="">
                    
                    <div class="deposit-form-row">
                        <div class="deposit-input-group">
                            <label>Método de Pagamento</label>
                            <div class="deposit-readonly-field">PIX</div>
                        </div>
                        <div class="deposit-input-group">
                            <label>Valor Mínimo</label>
                            <div class="deposit-readonly-field" id="depositMinDisplay">R$ {{ number_format($minDeposit, 2, ',', '.') }}</div>
                        </div>
                    </div>

                    <!-- Amount input -->
                    <div class="deposit-amount-wrapper">
                        <label for="depositAmount">Valor</label>
                        <div class="deposit-amount-input-box">
                            <div class="deposit-amount-left">
                                <span class="deposit-currency-symbol">R$</span>
                                <input type="number" id="depositAmount" name="amount" class="deposit-amount-field" value="50" min="{{ $minDeposit }}" step="0.01" required oninput="validateDepositInput()">
                            </div>
                            <div class="deposit-amount-right">
                                <img src="https://flagcdn.com/w40/br.png" class="brl-flag" alt="BRL">
                                <span class="currency-txt">BRL</span>
                            </div>
                        </div>
                    </div>

                    <!-- Coupon toggle -->
                    <div class="deposit-coupon-wrapper">
                        <a href="javascript:void(0)" class="deposit-coupon-toggle" onclick="toggleCouponInput()">
                            <i class="fa-solid fa-tag"></i> Tem um cupom?
                        </a>
                        <div class="deposit-coupon-input-box" id="couponInputBox">
                            <input type="text" id="depositCoupon" placeholder="Insira o código do cupom">
                            <button type="button" class="deposit-coupon-apply-btn" onclick="applyCoupon()">Aplicar</button>
                        </div>
                    </div>

                    <!-- Quick amount select grid -->
                    <div class="deposit-quick-grid">
                        @foreach($depositQuickAmounts as $val)
                            @php
                                $valClean = trim($val);
                                $isHot = in_array($valClean, array_map('trim', $depositHotAmounts));
                            @endphp
                            <button type="button" class="quick-btn {{ $valClean == 50 ? 'active' : '' }}" data-val="{{ $valClean }}" onclick="setDepositAmount({{ $valClean }}, this)">
                                R$ {{ $valClean }}
                                <i class="fa-solid fa-check btn-check-icon" style="margin-left: 4px;"></i>
                                @if($isHot)
                                    <span class="badge-hot"><i class="fa-solid fa-fire" style="font-size: 8px;"></i> HOT</span>
                                @endif
                            </button>
                        @endforeach
                    </div>

                    <!-- Error Alert -->
                    <div class="error-message" id="depositErrorMsg" style="margin-bottom: 15px; color: var(--red); font-size: 0.8rem; font-weight: 700; text-align: center;"></div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-deposit-submit">
                        <span aria-hidden="true" class="btn-skew-bg"></span>
                        <span class="btn-content">DEPOSITAR</span>
                    </button>
                </form>
            </div>

            <!-- Stage 2: QR Code & Pix Info -->
            <div id="depositPaymentStage" class="deposit-payment-stage">
                <p class="pix-instruction">Escaneie o QR Code abaixo usando o aplicativo do seu banco ou copie o código Pix para realizar o pagamento.</p>
                
                <div class="qr-code-box">
                    <img id="pixQrCodeImg" src="" alt="Pix QR Code">
                </div>

                <div class="pix-copy-box">
                    <label>Pix Copia e Cola</label>
                    <div class="pix-copy-row">
                        <input type="text" id="pixCopyPasteText" class="pix-copy-field" readonly value="">
                        <button type="button" class="pix-copy-btn" onclick="copyPixCode()">Copiar</button>
                    </div>
                </div>

                <div class="pix-payment-timer">
                    O QR Code expira em: <span class="time" id="pixExpiryTimer">10:00</span>
                </div>

                <button type="button" class="btn-payment-confirm" onclick="confirmPaymentAction()">
                    <span aria-hidden="true" class="btn-skew-bg"></span>
                    <span class="btn-content">Já realizei o pagamento</span>
                </button>
                <button type="button" class="btn-payment-back" onclick="backToFormStage()">Alterar valor / Voltar</button>
            </div>
        </div>
    </div>
    @endauth

    <!-- ===================== END PROFILE DRAWER ===================== -->

    <!-- Dynamic Routing Config for local subfolders/production environments -->
    <script>
        window.Laravel = {
            routes: {
                login: "{{ route('login') }}",
                register: "{{ route('register') }}",
                cpfValidate: "{{ route('cpf.validate') }}",
                passwordForgot: "{{ route('password.forgot') }}",
                passwordReset: "{{ route('password.reset') }}",
                gameBet: "{{ route('game.bet') }}",
                gamePayout: "{{ route('game.payout') }}",
                userBalance: "{{ route('user.balance') }}",
                kycSubmit: "{{ route('kyc.submit') }}"
            }
        };
    </script>

    @php
        $bottomNavButtons = [];
        for ($i = 1; $i <= 5; $i++) {
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

            $btnText = \App\Models\Setting::get("bottom_nav_btn_{$i}_text", $defaultNames[$i]);
            $btnAction = \App\Models\Setting::get("bottom_nav_btn_{$i}_action", $defaultActions[$i]);
            $btnIconSetting = \App\Models\Setting::get("bottom_nav_btn_{$i}_icon");
            $btnIcon = $btnIconSetting ? asset($btnIconSetting) : $defaultIcons[$i];
            
            $onClick = "";
            $href = "javascript:void(0)";
            
            if ($btnAction === 'sidebar') {
                $onClick = "event.preventDefault(); document.getElementById('sidebarToggleBtn').click();";
            } elseif (str_starts_with($btnAction, 'openAccountModal:')) {
                $tab = substr($btnAction, 17);
                $onClick = "event.preventDefault(); openAccountModal('{$tab}');";
            } elseif (str_starts_with($btnAction, 'javascript:')) {
                $onClick = substr($btnAction, 11);
            } elseif (str_starts_with($btnAction, 'alert:')) {
                $msg = substr($btnAction, 6);
                $onClick = "event.preventDefault(); alert('{$msg}');";
            } elseif (str_starts_with($btnAction, '#')) {
                $target = substr($btnAction, 1);
                if ($target === 'cassino') {
                    $onClick = "event.preventDefault(); scrollToLayout('recommended'); setActiveMobileNavItem(this);";
                } else {
                    $onClick = "event.preventDefault(); scrollToLayout('{$target}'); setActiveMobileNavItem(this);";
                }
            } else {
                $href = filter_var($btnAction, FILTER_VALIDATE_URL) ? $btnAction : url($btnAction);
            }

            $isActive = false;
            if ($btnAction === '/esportes' && Route::currentRouteName() == 'game.sports') {
                $isActive = true;
            } elseif ($btnAction === '/' && Route::currentRouteName() == 'home') {
                $isActive = true;
            }

            $bottomNavButtons[] = [
                'text' => $btnText,
                'href' => $href,
                'onClick' => $onClick,
                'icon' => $btnIcon,
                'isActive' => $isActive
            ];
        }
    @endphp

    <!-- Mobile Bottom Navigation Bar -->
    <div class="mobile-bottom-nav">
        @foreach($bottomNavButtons as $btn)
            <a href="{{ $btn['href'] }}" 
               @if(!empty($btn['onClick'])) onclick="{!! $btn['onClick'] !!}" @endif 
               class="mobile-nav-card {{ $btn['isActive'] ? 'active' : '' }}"
               aria-label="{{ $btn['text'] }}">
                <span class="mobile-nav-card-title">{{ $btn['text'] }}</span>
                @if($btn['icon'] === 'hamburger')
                    <div class="mobile-nav-card-menu-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                @else
                    <div class="mobile-nav-card-icon-wrapper">
                        <img src="{{ $btn['icon'] }}" alt="{{ $btn['text'] }} Icon">
                    </div>
                @endif
            </a>
        @endforeach
    </div>

    <script>
        function setActiveMobileNavItem(element) {
            const items = document.querySelectorAll('.mobile-bottom-nav .mobile-nav-card');
            items.forEach(item => item.classList.remove('active'));
            element.classList.add('active');
        }
    </script>

    <!-- Custom JavaScript files -->
    <script src="{{ asset('js/app.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/games.js') }}?v={{ time() }}"></script>

    @auth
    <!-- Didit KYC Logic -->
    <script>
        const userKycStatus = "{{ Auth::user()->kyc_status ?? 'not_started' }}";

        // Logic for dynamic balance colors using classes
        function updateBalanceColor(balance) {
            const balanceText = document.getElementById('headerUserBalance');
            if (!balanceText) return;
            
            // Extract ONLY digits
            let digits = String(balance).replace(/\D/g, '');
            if (!digits) return;

            // Brazilian format usually has 2 decimals (cents)
            let val = parseInt(digits) / 100;
            
            // If it's something like "110", we might not have cents
            if (val < 1 && parseInt(digits) > 0 && !String(balance).includes(',')) {
                val = parseInt(digits);
            }

            // Remove existing classes
            balanceText.classList.remove('balance-low', 'balance-medium', 'balance-high');

            // Add correct class
            if (val <= 15) {
                balanceText.classList.add('balance-low');
            } else if (val <= 100) {
                balanceText.classList.add('balance-medium');
            } else {
                balanceText.classList.add('balance-high');
            }
            
            // Debug log
            console.log('Saldo:', balance, 'Valor extraído:', val, 'Classe aplicada:', balanceText.className);
        }

        // Observer to watch for any changes in balance text automatically
        function initBalanceObserver() {
            const balanceText = document.getElementById('headerUserBalance');
            if (!balanceText) return;

            const observer = new MutationObserver(() => {
                updateBalanceColor(balanceText.textContent);
            });

            observer.observe(balanceText, { 
                childList: true, 
                characterData: true, 
                subtree: true 
            });
            
            // Initial trigger
            updateBalanceColor(balanceText.textContent);
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', () => {
            initBalanceObserver();
        });

        function openAccountModal(tabName) {
            console.log('openAccountModal called with tabName:', tabName);
            if (tabName && tabName.toLowerCase() === 'indique') {
                console.log('Redirecting to Affiliate Modal...');
                closeModal('accountModal');
                openAffiliateModal();
                return;
            }
            openModal('accountModal');
            // Find tab element and click it
            const tabElements = document.querySelectorAll('.account-modal-tabs li');
            let matched = false;
            tabElements.forEach(li => {
                const text = li.textContent.toLowerCase();
                if (text.includes(tabName.toLowerCase())) {
                    li.click();
                    matched = true;
                }
            });
            
            // Fallback by mapping
            if (!matched) {
                const mapping = {
                    'carteira': 0,
                    'premios': 1,
                    'dados': 2,
                    'verificacao': 3,
                    'historico': 4,
                    'config': 5,
                    'indique': 6,
                    'irpf': 7,
                    'protecoes': 8
                };
                const idx = mapping[tabName.toLowerCase()];
                if (idx !== undefined && tabElements[idx]) {
                    tabElements[idx].click();
                }
            }
        }

        function switchAccountTab(tabId, element) {
            // Remove active classes
            document.querySelectorAll('.account-modal-tabs li').forEach(li => li.classList.remove('active'));
            document.querySelectorAll('.account-tab-pane').forEach(pane => pane.classList.remove('active'));
            
            // Add active class
            element.classList.add('active');
            const targetPane = document.getElementById('acc-tab-' + tabId);
            if (targetPane) {
                targetPane.classList.add('active');
            }
        }

        function submitWithdrawalForm(event) {
            event.preventDefault();
            const form = document.getElementById('withdrawalForm');
            const formData = new FormData(form);
            
            const errorMsg = document.getElementById('withdrawalErrorMsg');
            const successMsg = document.getElementById('withdrawalSuccessMsg');
            if (errorMsg) errorMsg.textContent = '';
            if (successMsg) successMsg.textContent = '';

            const submitBtn = form.querySelector('button[type="submit"]');
            const btnContent = submitBtn.querySelector('.btn-content');
            const originalText = btnContent ? btnContent.textContent : submitBtn.textContent;
            
            if (btnContent) btnContent.textContent = 'PROCESSANDO...';
            else submitBtn.textContent = 'PROCESSANDO...';
            submitBtn.disabled = true;

            fetch("{{ route('withdrawal.request') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    if (successMsg) successMsg.textContent = data.message;
                    form.reset();
                    refreshUserBalance();
                    
                    // Update balance text in the modal too!
                    setTimeout(() => {
                        fetch(window.Laravel.routes.userBalance)
                            .then(r => r.json())
                            .then(d => {
                                if (d.balance_formatted) {
                                    document.getElementById('accModalUserBalance').textContent = `R$ ${d.balance_formatted}`;
                                }
                            });
                    }, 500);
                } else {
                    if (errorMsg) errorMsg.textContent = data.message || 'Erro ao solicitar saque.';
                }
            })
            .catch(error => {
                console.error(error);
                if (errorMsg) errorMsg.textContent = error.message || 'Erro de conexão. Tente novamente.';
            })
            .finally(() => {
                if (btnContent) btnContent.textContent = originalText;
                else submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        }

        function submitUpdateProfile(event) {
            event.preventDefault();
            const form = document.getElementById('updateProfileForm');
            const formData = new FormData(form);
            
            const errorMsg = document.getElementById('profileErrorMsg');
            const successMsg = document.getElementById('profileSuccessMsg');
            if (errorMsg) errorMsg.textContent = '';
            if (successMsg) successMsg.textContent = '';

            const submitBtn = form.querySelector('button[type="submit"]');
            const btnContent = submitBtn.querySelector('.btn-content');
            const originalText = btnContent ? btnContent.textContent : submitBtn.textContent;
            
            if (btnContent) btnContent.textContent = 'SALVANDO...';
            else submitBtn.textContent = 'SALVANDO...';
            submitBtn.disabled = true;

            fetch("{{ route('user.update-profile') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    if (successMsg) successMsg.textContent = data.message;
                    // Update user name in top bar and header drawer
                    const newName = form.querySelector('input[name="name"]').value;
                    const drawerName = document.querySelector('.pdrawer-username');
                    if (drawerName) drawerName.textContent = newName;
                } else {
                    if (errorMsg) errorMsg.textContent = data.message || 'Erro ao atualizar dados.';
                }
            })
            .catch(error => {
                console.error(error);
                if (errorMsg) errorMsg.textContent = error.message || 'Erro de conexão. Tente novamente.';
            })
            .finally(() => {
                if (btnContent) btnContent.textContent = originalText;
                else submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        }

        function submitChangePassword(event) {
            event.preventDefault();
            const form = document.getElementById('changePasswordForm');
            const formData = new FormData(form);
            
            const errorMsg = document.getElementById('passwordErrorMsg');
            const successMsg = document.getElementById('passwordSuccessMsg');
            if (errorMsg) errorMsg.textContent = '';
            if (successMsg) successMsg.textContent = '';

            const submitBtn = form.querySelector('button[type="submit"]');
            const btnContent = submitBtn.querySelector('.btn-content');
            const originalText = btnContent ? btnContent.textContent : submitBtn.textContent;
            
            if (btnContent) btnContent.textContent = 'ALTERANDO...';
            else submitBtn.textContent = 'ALTERANDO...';
            submitBtn.disabled = true;

            fetch("{{ route('user.change-password') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    if (successMsg) successMsg.textContent = data.message;
                    form.reset();
                } else {
                    if (errorMsg) errorMsg.textContent = data.message || 'Erro ao alterar senha.';
                }
            })
            .catch(error => {
                console.error(error);
                if (errorMsg) errorMsg.textContent = error.message || 'Erro de conexão. Tente novamente.';
            })
            .finally(() => {
                if (btnContent) btnContent.textContent = originalText;
                else submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        }

        function loadTransactionHistory() {
            const tbody = document.getElementById('transactionHistoryBody');
            if (!tbody) return;
            tbody.innerHTML = '<tr><td colspan="4" style="padding: 20px; text-align: center; color: var(--text-muted);">Carregando histórico...</td></tr>';
            
            fetch("{{ route('user.history') }}")
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.history && data.history.length > 0) {
                        let html = '';
                        data.history.forEach(item => {
                            html += `<tr>
                                <td style="padding: 12px 10px;">${item.date}</td>
                                <td style="padding: 12px 10px; font-weight: 600;">${item.type}</td>
                                <td style="padding: 12px 10px; font-weight: 700; color: ${item.amount_color || '#fff'};">${item.amount_formatted}</td>
                                <td style="padding: 12px 10px;"><span class="history-badge ${item.badge_class || 'pending'}">${item.status_text || 'Pendente'}</span></td>
                            </tr>`;
                        });
                        tbody.innerHTML = html;
                    } else {
                        tbody.innerHTML = '<tr><td colspan="4" style="padding: 20px; text-align: center; color: var(--text-muted);">Nenhuma transação encontrada.</td></tr>';
                    }
                })
                .catch(err => {
                    console.error(err);
                    tbody.innerHTML = '<tr><td colspan="4" style="padding: 20px; text-align: center; color: var(--red);">Erro ao carregar transações.</td></tr>';
                });
        }

        function copyReferralLink() {
            const input = document.getElementById('accReferralLink');
            if (!input) return;
            input.select();
            navigator.clipboard.writeText(input.value)
                .then(() => alert('Link de indicação copiado!'))
                .catch(err => alert('Erro ao copiar link: ' + err));
        }

        function submitResponsibleGaming(event) {
            event.preventDefault();
            const msg = document.getElementById('gamingSuccessMsg');
            if (msg) {
                msg.textContent = 'Configurações salvas com sucesso! Jogue com responsabilidade.';
                setTimeout(() => { msg.textContent = ''; }, 3000);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const linkInput = document.getElementById('accReferralLink');
            if (linkInput) {
                linkInput.value = window.location.origin + '/register?ref=' + "{{ Auth::check() ? Auth::user()->id : '' }}";
            }
        });

        function handleWithdrawalClick() {
            if (userKycStatus === 'approved') {
                openAccountModal('carteira');
            } else if (userKycStatus === 'pending') {
                alert('Sua verificação de identidade está em análise. Você poderá realizar saques assim que for aprovado(a)!');
            } else if (userKycStatus === 'declined') {
                alert('Sua verificação de identidade anterior foi recusada. Clique em "Verificar Conta" no menu do perfil para tentar novamente.');
                openModal('kycEnrollmentModal');
            } else {
                openModal('kycEnrollmentModal');
            }
        }

        function handleKycButtonClick() {
            if (userKycStatus === 'approved') {
                alert('Sua identidade já foi verificada e aprovada! Sua conta está totalmente liberada para saques.');
            } else if (userKycStatus === 'pending') {
                alert('Sua verificação de identidade já está em andamento. Aguarde alguns minutos.');
            } else {
                startDiditKycFlow();
            }
        }

        function startDiditKycFlow() {
            const { DiditSdk } = window.DiditSDK || {};
            if (!DiditSdk) {
                alert('Erro: O SDK de KYC da Didit não pôde ser carregado. Verifique sua conexão.');
                return;
            }

            // Create session via backend
            fetch("{{ route('kyc.session') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                    "Accept": "application/json"
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.url) {
                    // Setup Didit SDK callback
                    DiditSdk.shared.onComplete = (result) => {
                        console.log('Didit completed with: ', result);
                        if (result.type === 'completed') {
                            // Notify backend that user submitted documents
                            fetch(window.Laravel.routes.kycSubmit, {
                                method: "POST",
                                headers: {
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                    "Content-Type": "application/json",
                                    "Accept": "application/json"
                                }
                            })
                            .finally(() => {
                                alert('Sessão concluída! Seus documentos foram enviados para análise automatizada.');
                                window.location.reload();
                            });
                        } else if (result.type === 'failed') {
                            alert('Erro durante a verificação: ' + (result.error ? result.error.message : 'Erro desconhecido'));
                        }
                    };

                    // Open Didit flow
                    DiditSdk.shared.startVerification({ url: data.url });
                } else {
                    alert('Falha ao iniciar sessão de KYC: ' + (data.message || 'Erro desconhecido'));
                }
            })
            .catch(error => {
                console.error(error);
                alert(error.message || 'Ocorreu um erro ao conectar com o servidor. Tente novamente.');
            });
        }

        // =====================================================
        // DEPOSIT MODAL JAVASCRIPT LOGIC
        // =====================================================
        let offerTimerInterval;
        let pixTimerInterval;
        let depositPollInterval;

        function openDepositModal() {
            backToFormStage();
            clearInterval(offerTimerInterval);
            
            const modal = document.getElementById('depositModal');
            if (modal) {
                modal.style.display = 'flex';
            }

            const promoMinutes = parseInt("{{ $depositPromoTimer }}") || 5;
            startOfferCountdown(promoMinutes * 60);
        }

        function closeDepositModal() {
            const modal = document.getElementById('depositModal');
            if (modal) {
                modal.style.display = 'none';
            }
            clearInterval(offerTimerInterval);
            clearInterval(pixTimerInterval);
            clearInterval(depositPollInterval);
        }

        function startOfferCountdown(durationSeconds) {
            let timer = durationSeconds;
            
            const hBlock = document.getElementById('promoTimerH');
            const mBlock = document.getElementById('promoTimerM');
            const sBlock = document.getElementById('promoTimerS');

            if (!hBlock || !mBlock || !sBlock) return;

            function updateTimerDisplay() {
                let hours = Math.floor(timer / 3600);
                let minutes = Math.floor((timer % 3600) / 60);
                let seconds = timer % 60;

                hBlock.innerHTML = String(hours).padStart(2, '0') + '<span class="lbl">H</span>';
                mBlock.innerHTML = String(minutes).padStart(2, '0') + '<span class="lbl">M</span>';
                sBlock.innerHTML = String(seconds).padStart(2, '0') + '<span class="lbl">S</span>';
            }

            updateTimerDisplay();

            offerTimerInterval = setInterval(() => {
                if (timer <= 0) {
                    timer = durationSeconds;
                } else {
                    timer--;
                }
                updateTimerDisplay();
            }, 1000);
        }

        function selectGateway(card) {
            const cards = document.querySelectorAll('.gateway-card');
            cards.forEach(c => c.classList.remove('active'));

            card.classList.add('active');

            const gateway = card.getAttribute('data-gateway');
            document.getElementById('depositGatewayInput').value = gateway.toUpperCase();
        }

        function scrollGateways(direction) {
            const viewport = document.getElementById('gatewaysViewport');
            if (viewport) {
                const scrollAmount = 140 * direction;
                viewport.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            }
        }

        function setDepositAmount(value, button) {
            const amountInput = document.getElementById('depositAmount');
            if (amountInput) {
                amountInput.value = value;
            }

            const buttons = document.querySelectorAll('.quick-btn');
            buttons.forEach(btn => btn.classList.remove('active'));

            button.classList.add('active');
        }

        function validateDepositInput() {
            const amountInput = document.getElementById('depositAmount');
            if (!amountInput) return;
            const val = parseFloat(amountInput.value);

            const buttons = document.querySelectorAll('.quick-btn');
            buttons.forEach(btn => {
                const btnVal = parseFloat(btn.getAttribute('data-val'));
                if (btnVal === val) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }

        function toggleCouponInput() {
            const box = document.getElementById('couponInputBox');
            if (box) {
                box.style.display = box.style.display === 'flex' ? 'none' : 'flex';
            }
        }

        function applyCoupon() {
            const couponCode = document.getElementById('depositCoupon').value.trim();
            if (!couponCode) {
                alert('Por favor, digite um código de cupom.');
                return;
            }
            alert('Cupom "' + couponCode + '" validado com sucesso! Bônus correspondente será aplicado após a confirmação do pagamento.');
        }

        function submitDepositForm(event) {
            event.preventDefault();
            
            const errorMsg = document.getElementById('depositErrorMsg');
            if (errorMsg) errorMsg.textContent = '';

            const form = document.getElementById('depositForm');
            const formData = new FormData(form);

            const submitBtn = form.querySelector('.btn-deposit-submit');
            const btnContent = submitBtn.querySelector('.btn-content');
            if (btnContent) {
                btnContent.textContent = 'PROCESSANDO...';
            } else {
                submitBtn.textContent = 'PROCESSANDO...';
            }
            submitBtn.disabled = true;

            fetch("{{ route('deposit.create') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    document.getElementById('pixQrCodeImg').src = data.qr_code_url;
                    document.getElementById('pixCopyPasteText').value = data.pix_key;

                    document.getElementById('depositFormStage').style.display = 'none';
                    document.getElementById('depositPaymentStage').style.display = 'flex';

                    startPixExpiryCountdown(600);
                    if (data.deposit_id) {
                        startDepositPolling(data.deposit_id);
                    }
                } else {
                    if (errorMsg) errorMsg.textContent = data.message || 'Erro ao criar depósito. Tente novamente.';
                }
            })
            .catch(error => {
                console.error(error);
                if (errorMsg) errorMsg.textContent = error.message || 'Erro de conexão com o servidor. Tente novamente.';
            })
            .finally(() => {
                if (btnContent) {
                    btnContent.textContent = 'DEPOSITAR';
                } else {
                    submitBtn.textContent = 'DEPOSITAR';
                }
                submitBtn.disabled = false;
            });
        }

        function startPixExpiryCountdown(durationSeconds) {
            let timer = durationSeconds;
            const timerSpan = document.getElementById('pixExpiryTimer');

            clearInterval(pixTimerInterval);

            pixTimerInterval = setInterval(() => {
                let minutes = Math.floor(timer / 60);
                let seconds = timer % 60;

                if (timerSpan) {
                    timerSpan.textContent = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
                }

                if (--timer < 0) {
                    clearInterval(pixTimerInterval);
                    clearInterval(depositPollInterval);
                    if (timerSpan) timerSpan.textContent = 'EXPIRADO';
                    alert('O código Pix expirou. Por favor, crie uma nova solicitação de depósito.');
                    backToFormStage();
                }
            }, 1000);
        }

        function startDepositPolling(depositId) {
            clearInterval(depositPollInterval);
            depositPollInterval = setInterval(() => {
                fetch(`/deposit/status/${depositId}`)
                    .then(response => {
                        if (!response.ok) throw new Error();
                        return response.json();
                    })
                    .then(data => {
                        if (data.success && data.status === 'approved') {
                            clearInterval(depositPollInterval);
                            clearInterval(pixTimerInterval);
                            
                            // Visual feedback in the modal
                            const confirmBtn = document.querySelector('.btn-payment-confirm .btn-content');
                            if (confirmBtn) {
                                confirmBtn.textContent = 'PAGO!';
                                confirmBtn.style.color = '#00ff88';
                            }
                            
                            const timerText = document.querySelector('.pix-payment-timer');
                            if (timerText) {
                                timerText.innerHTML = '<span style="color: #00ff88; font-weight: bold; font-size: 1.1rem;"><i class="fa-solid fa-circle-check"></i> Depósito Pago com Sucesso!</span>';
                            }
                            
                            // Delay closing & redirect to let the user see the confirmation
                            setTimeout(() => {
                                closeDepositModal();
                                refreshUserBalance();
                                window.location.href = '/';
                            }, 1500);
                        }
                    })
                    .catch(err => console.log('Erro ao checar status do depósito:', err));
            }, 3000);
        }

        function copyPixCode() {
            const copyText = document.getElementById('pixCopyPasteText');
            if (!copyText) return;
            copyText.select();
            copyText.setSelectionRange(0, 99999);

            navigator.clipboard.writeText(copyText.value)
                .then(() => {
                    const copyBtn = document.querySelector('.pix-copy-btn');
                    if (copyBtn) {
                        const originalText = copyBtn.textContent;
                        copyBtn.textContent = 'Copiado!';
                        setTimeout(() => {
                            copyBtn.textContent = originalText;
                        }, 2000);
                    }
                })
                .catch(err => {
                    alert('Falha ao copiar código: ' + err);
                });
        }

        function backToFormStage() {
            document.getElementById('depositFormStage').style.display = 'block';
            document.getElementById('depositPaymentStage').style.display = 'none';
            const errorMsg = document.getElementById('depositErrorMsg');
            if (errorMsg) errorMsg.textContent = '';
            clearInterval(pixTimerInterval);
            clearInterval(depositPollInterval);
        }

        function confirmPaymentAction() {
            alert('Depósito solicitado com sucesso! O valor será creditado assim que a transação for aprovada pelo administrador no painel.');
            closeDepositModal();
            refreshUserBalance();
        }

        window.addEventListener('click', (e) => {
            const modal = document.getElementById('depositModal');
            if (e.target === modal) {
                closeDepositModal();
            }
        });
    </script>
    @endauth

    <!-- Live Preview Color Synchronizer -->
    <script>
        window.addEventListener('message', function(event) {
            if (event.data && event.data.type === 'update-css-variables') {
                const variables = event.data.variables;
                for (const [key, value] of Object.entries(variables)) {
                    document.documentElement.style.setProperty(key, value);
                }
            }
        });
    </script>

    @auth
    <!-- Custom Interactive Features Logic (Roulette, Notifications, Coupons) -->
    <script>
        // --- Coupon Application Logic ---
        function applyCoupon() {
            const couponCode = document.getElementById('depositCoupon').value.trim();
            const depositAmount = parseFloat(document.getElementById('depositAmount').value);
            const couponIdInput = document.getElementById('depositCouponIdInput');
            const errorMsg = document.getElementById('depositErrorMsg');

            if (!couponCode) {
                alert('Por favor, digite um código de cupom.');
                return;
            }

            if (isNaN(depositAmount) || depositAmount <= 0) {
                alert('Por favor, informe um valor de depósito válido antes de aplicar o cupom.');
                return;
            }

            if (errorMsg) errorMsg.textContent = '';

            fetch("{{ route('coupon.validate') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({
                    code: couponCode,
                    amount: depositAmount
                })
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    if (couponIdInput) {
                        couponIdInput.value = data.coupon.id;
                    }
                    alert(data.message + ' Bônus de ' + data.coupon.value_formatted + ' será aplicado ao concluir o depósito.');
                } else {
                    if (couponIdInput) couponIdInput.value = '';
                    alert(data.message || 'Erro ao validar cupom.');
                }
            })
            .catch(error => {
                console.error(error);
                if (couponIdInput) couponIdInput.value = '';
                alert(error.message || 'Erro de conexão ao validar cupom.');
            });
        }

        // --- Daily Roulette Mini-Game Logic ---
        let hasSpun = false;

        function openDailyRouletteModal() {
            if (hasSpun) {
                alert('Você já girou a roleta hoje!');
                return;
            }

            fetch("{{ route('daily-roulette.status') }}")
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        if (!data.can_spin) {
                            alert(data.message || 'Você já girou hoje. Volte amanhã!');
                            return;
                        }
                        const modal = document.getElementById('dailyRouletteModal');
                        if (modal) modal.style.display = 'flex';
                        document.getElementById('rouletteResult').textContent = '';
                        const wheel = document.getElementById('rouletteWheel');
                        if (wheel) wheel.style.transform = 'rotate(0deg)';
                        const spinBtn = document.getElementById('spinBtn');
                        if (spinBtn) {
                            spinBtn.disabled = false;
                            spinBtn.textContent = 'GIRAR';
                        }
                    } else {
                        alert(data.message || 'Erro ao carregar status da roleta.');
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Erro de conexão ao carregar roleta.');
                });
        }

        function spinRoulette() {
            const spinBtn = document.getElementById('spinBtn');
            if (spinBtn) spinBtn.disabled = true;

            fetch("{{ route('daily-roulette.spin') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const prizeIndex = data.prize_index;
                    const wheel = document.getElementById('rouletteWheel');
                    
                    // 3600 degrees (10 full turns) + offset to align target slice with top arrow
                    const degrees = 3600 - (30 + 60 * prizeIndex);
                    
                    if (wheel) {
                        wheel.style.transform = `rotate(${degrees}deg)`;
                    }

                    setTimeout(() => {
                        const resultDiv = document.getElementById('rouletteResult');
                        if (resultDiv) {
                            resultDiv.textContent = data.message;
                        }
                        if (spinBtn) {
                            spinBtn.textContent = 'FIM';
                        }
                        refreshUserBalance();
                        hasSpun = true;
                    }, 4000);
                } else {
                    alert(data.message || 'Erro ao girar roleta.');
                    if (spinBtn) spinBtn.disabled = false;
                }
            })
            .catch(error => {
                console.error(error);
                alert(error.message || 'Erro de conexão.');
                if (spinBtn) spinBtn.disabled = false;
            });
        }

        // --- Notification Actions & Dropdown Logic ---
        function toggleNotificationsDropdown(event) {
            if (event) event.stopPropagation();
            const dropdown = document.getElementById('notificationsDropdown');
            if (!dropdown) return;
            const isVisible = dropdown.style.display === 'block';
            
            document.querySelectorAll('.quick-access-dropdown').forEach(d => d.style.display = 'none');
            
            if (!isVisible) {
                dropdown.style.display = 'block';
                loadNotificationsList();
            } else {
                dropdown.style.display = 'none';
            }
        }

        function openNotificationsModal() {
            const modal = document.getElementById('notificationsModal');
            if (modal) {
                modal.style.display = 'flex';
                loadNotificationsModalList();
            }
        }

        function loadNotificationsList() {
            const container = document.getElementById('notificationsList');
            const badge = document.getElementById('notificationBadgeBtn');
            if (!container) return;

            fetch("{{ route('user.notifications') }}")
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.notifications && data.notifications.length > 0) {
                        let html = '';
                        data.notifications.forEach(item => {
                            const isUnread = !item.is_read;
                            const bg = isUnread ? 'rgba(255, 213, 79, 0.05)' : 'transparent';
                            const border = isUnread ? '1px solid rgba(255, 213, 79, 0.15)' : '1px solid rgba(255,255,255,0.05)';
                            html += `
                                <div style="background: ${bg}; border: ${border}; padding: 10px; border-radius: 8px; font-size: 12px; display: flex; flex-direction: column; gap: 4px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <strong style="color: #fff; font-weight: 700;">${item.title}</strong>
                                        <span style="font-size: 9.5px; color: var(--text-muted);">${item.created_at}</span>
                                    </div>
                                    <p style="margin: 0; color: var(--text-secondary); line-height: 1.4;">${item.message}</p>
                                </div>
                            `;
                        });
                        container.innerHTML = html;
                        
                        if (badge) {
                            badge.textContent = data.unread_count > 0 ? data.unread_count : '';
                            badge.style.display = data.unread_count > 0 ? 'flex' : 'none';
                        }
                    } else {
                        container.innerHTML = '<div style="text-align: center; padding: 20px; color: var(--text-muted); font-size: 12px;">Nenhuma notificação encontrada.</div>';
                        if (badge) {
                            badge.style.display = 'none';
                        }
                    }
                })
                .catch(err => console.error('Error fetching notifications:', err));
        }

        function loadNotificationsModalList() {
            const container = document.getElementById('notificationsModalList');
            if (!container) return;
            container.innerHTML = '<div style="text-align: center; padding: 20px; color: var(--text-muted);"><i class="fa-solid fa-spinner fa-spin"></i> Carregando...</div>';

            fetch("{{ route('user.notifications') }}")
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.notifications && data.notifications.length > 0) {
                        let html = '';
                        data.notifications.forEach(item => {
                            const isUnread = !item.is_read;
                            const bg = isUnread ? 'rgba(255, 213, 79, 0.05)' : 'transparent';
                            const border = isUnread ? '1px solid rgba(255, 213, 79, 0.15)' : '1px solid rgba(255,255,255,0.05)';
                            html += `
                                <div style="background: ${bg}; border: ${border}; padding: 12px; border-radius: 8px; font-size: 13px; display: flex; flex-direction: column; gap: 4px;">
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        <strong style="color: #fff; font-weight: 700;">${item.title}</strong>
                                        <span style="font-size: 10px; color: var(--text-muted);">${item.created_at}</span>
                                    </div>
                                    <p style="margin: 0; color: var(--text-secondary); line-height: 1.4;">${item.message}</p>
                                </div>
                            `;
                        });
                        container.innerHTML = html;
                    } else {
                        container.innerHTML = '<div style="text-align: center; padding: 30px 10px; color: var(--text-muted); font-size: 13px;">Nenhuma notificação encontrada.</div>';
                    }
                })
                .catch(err => {
                    console.error(err);
                    container.innerHTML = '<div style="text-align: center; padding: 20px; color: var(--red);">Erro ao carregar notificações.</div>';
                });
        }

        function readAllNotifications(event) {
            if (event) event.preventDefault();
            
            fetch("{{ route('user.notifications.read-all') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    loadNotificationsList();
                    const modalList = document.getElementById('notificationsModalList');
                    if (modalList && modalList.innerHTML.indexOf('Carregando') === -1) {
                        loadNotificationsModalList();
                    }
                }
            })
            .catch(err => console.error(err));
        }

        // Dropdown Click Handler
        document.addEventListener('click', () => {
            const dropdown = document.getElementById('notificationsDropdown');
            if (dropdown) dropdown.style.display = 'none';
        });

        document.addEventListener('DOMContentLoaded', () => {
            loadNotificationsList();
            setInterval(loadNotificationsList, 60000);
        });
    </script>
    @endauth

    @php
        $jivoChatId = \App\Models\Setting::get('jivochat_widget_id');
    @endphp
    @if(!empty($jivoChatId))
        <!-- JivoChat Widget -->
        <script src="//code.jivo.chat/widget/{{ $jivoChatId }}" async></script>
    @endif
    <!-- Toast/Notification Script -->
    <script>
        @if(session('error'))
            alert("{{ session('error') }}");
        @endif
        @if(session('success'))
            alert("{{ session('success') }}");
        @endif
    </script>
    <style>
        /* Sleek custom modals for tournaments and missions */
        .vibra-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(6, 6, 8, 0.85);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            z-index: 1050;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .vibra-modal.show {
            display: flex;
            opacity: 1;
        }
        .vibra-modal-content {
            background: rgba(22, 22, 25, 0.95);
            border: 1px solid var(--border-color);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.5);
            border-radius: 16px;
            width: 90%;
            max-width: 520px;
            max-height: 85vh;
            overflow-y: auto;
            padding: 24px;
            position: relative;
            transform: translateY(20px);
            transition: transform 0.3s ease;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.1) transparent;
        }
        .vibra-modal.show .vibra-modal-content {
            transform: translateY(0);
        }
        .vibra-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .vibra-modal-header h3 {
            font-family: var(--font-heading);
            font-size: 18px;
            font-weight: 800;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .vibra-close-btn {
            background: transparent;
            border: none;
            color: var(--text-muted);
            font-size: 20px;
            cursor: pointer;
            transition: color 0.2s;
        }
        .vibra-close-btn:hover {
            color: #fff;
        }
        
        /* Tournament elements */
        .t-banner-wrapper {
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 15px;
            border: 1px solid var(--border-color);
        }
        .t-banner-img {
            width: 100%;
            max-height: 140px;
            object-fit: cover;
            display: block;
        }
        .t-meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 15px;
        }
        .t-meta-box {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border-color);
            padding: 10px;
            border-radius: 8px;
            text-align: center;
        }
        .t-meta-box span {
            font-size: 10px;
            text-transform: uppercase;
            color: var(--text-muted);
            display: block;
            margin-bottom: 4px;
            font-weight: 700;
        }
        .t-meta-box strong {
            font-size: 14px;
            color: var(--color-yellow);
            font-weight: 800;
        }
        .t-ranking-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12.5px;
        }
        .t-ranking-table th {
            text-align: left;
            color: var(--text-muted);
            font-weight: 600;
            padding: 8px 10px;
            border-bottom: 1px solid var(--border-color);
        }
        .t-ranking-table td {
            padding: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.02);
        }
        .t-rank-num {
            font-weight: 800;
            text-align: center;
            width: 28px;
            display: inline-block;
        }
        .t-user-row-self {
            background: rgba(248, 204, 27, 0.05);
            border: 1px solid rgba(248, 204, 27, 0.15) !important;
            border-radius: 8px;
        }

        /* Missions progress elements */
        .m-card-item {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 12px;
            transition: border-color 0.25s;
        }
        .m-card-item:hover {
            border-color: rgba(255, 255, 255, 0.1);
        }
        .m-card-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 8px;
        }
        .m-card-title {
            font-weight: 700;
            color: #fff;
            font-size: 13.5px;
        }
        .m-card-desc {
            font-size: 11px;
            color: var(--text-secondary);
            margin-top: 2px;
            line-height: 1.4;
        }
        .m-card-reward {
            font-size: 11px;
            font-weight: 700;
            color: var(--color-yellow);
            background: rgba(248, 204, 27, 0.08);
            border: 1px solid rgba(248, 204, 27, 0.15);
            padding: 2px 6px;
            border-radius: 4px;
            white-space: nowrap;
        }
        .m-progress-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }
        .m-progress-bar-bg {
            flex: 1;
            height: 6px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 3px;
            overflow: hidden;
        }
        .m-progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--color-yellow), #ffd54f);
            border-radius: 3px;
            transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .m-progress-text {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-secondary);
            min-width: 45px;
            text-align: right;
        }
        .m-claim-btn {
            background: var(--color-yellow);
            border: none;
            color: #000;
            font-size: 11px;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 5px;
            cursor: pointer;
            transition: transform 0.2s, opacity 0.2s;
        }
        .m-claim-btn:hover {
            transform: scale(1.03);
        }
        .m-claim-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .m-claimed-badge {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            padding: 4px 10px;
            border-radius: 5px;
            background: rgba(255,255,255,0.03);
            border: 1px solid transparent;
            display: inline-block;
        }

        /* ===================== VIP MODAL STYLES ===================== */
        .vip-modal-grid {
            display: grid;
            grid-template-columns: 1.15fr 1.35fr;
            gap: 20px;
            margin-top: 10px;
        }
        @media (max-width: 991px) {
            .vip-modal-grid {
                grid-template-columns: 1fr;
            }
        }
        .vip-card-panel {
            background: rgba(26, 26, 30, 0.4);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .vip-badge-showcase {
            display: flex;
            align-items: center;
            gap: 16px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.01), rgba(255, 255, 255, 0.03));
            border: 1px solid rgba(255, 255, 255, 0.03);
            border-radius: 10px;
            padding: 16px;
            position: relative;
            overflow: hidden;
        }
        .vip-badge-showcase::after {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            background: radial-gradient(circle at 10% 20%, rgba(255, 215, 0, 0.04) 0%, transparent 60%);
            pointer-events: none;
        }
        .vip-badge-graphic {
            width: 68px;
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 28px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            background: #242428;
            flex-shrink: 0;
            border: 2px solid transparent;
        }
        .vip-badge-img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            border-radius: 50%;
        }
        .vip-badge-bronze {
            background: linear-gradient(135deg, #78350f, #d97706);
            color: #fff;
            box-shadow: 0 0 15px rgba(217, 119, 6, 0.2);
            border-color: #f59e0b;
        }
        .vip-badge-silver {
            background: linear-gradient(135deg, #4b5563, #9ca3af);
            color: #fff;
            box-shadow: 0 0 15px rgba(156, 163, 175, 0.2);
            border-color: #d1d5db;
        }
        .vip-badge-gold {
            background: linear-gradient(135deg, #ca8a04, #facc15);
            color: #1e1b4b;
            box-shadow: 0 0 20px rgba(250, 204, 21, 0.35);
            border-color: #fef08a;
            animation: vip-pulse-glow 2s infinite alternate;
        }
        @keyframes vip-pulse-glow {
            0% { box-shadow: 0 0 15px rgba(250, 204, 21, 0.2); }
            100% { box-shadow: 0 0 25px rgba(250, 204, 21, 0.45); }
        }
        .vip-xp-tracker {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .vip-xp-bar-bg {
            height: 8px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 4px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.02);
        }
        .vip-xp-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #f59e0b, #facc15);
            border-radius: 4px;
            box-shadow: 0 0 8px rgba(250, 204, 21, 0.4);
            transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .vip-coin-balance-card {
            background: rgba(248, 204, 27, 0.04);
            border: 1px solid rgba(248, 204, 27, 0.12);
            border-radius: 10px;
            padding: 14px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .vip-coin-count {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 22px;
            font-weight: 800;
            color: var(--color-yellow);
            font-family: var(--font-heading);
        }
        .vip-coin-count i {
            animation: coin-spin 5s linear infinite;
        }
        @keyframes coin-spin {
            0%, 80% { transform: rotateY(0deg); }
            90% { transform: rotateY(180deg); }
            100% { transform: rotateY(360deg); }
        }
        .vip-exchange-box {
            display: flex;
            flex-direction: column;
            gap: 12px;
            background: rgba(255,255,255,0.01);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 14px;
        }
        .vip-exchange-input-container {
            display: flex;
            background: #121215;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 4px 12px;
            align-items: center;
            justify-content: space-between;
            transition: border-color 0.2s;
        }
        .vip-exchange-input-container:focus-within {
            border-color: var(--color-yellow);
        }
        .vip-exchange-input {
            background: transparent;
            border: none;
            color: #fff;
            font-weight: 800;
            font-size: 18px;
            width: 100%;
            outline: none;
            padding: 6px 0;
            font-family: var(--font-heading);
        }
        .vip-exchange-preview {
            display: flex;
            justify-content: space-between;
            font-size: 11.5px;
            color: var(--text-secondary);
            font-weight: 600;
        }
        .vip-exchange-preview span strong {
            color: #fff;
        }
        .vip-exchange-btn {
            background: linear-gradient(90deg, var(--color-yellow), #ffd54f);
            color: #000;
            font-weight: 800;
            border: none;
            border-radius: 8px;
            padding: 12px;
            cursor: pointer;
            font-size: 13px;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-transform: uppercase;
        }
        .vip-exchange-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 15px rgba(250, 204, 21, 0.25);
        }
        .vip-exchange-btn:active {
            transform: scale(0.98);
        }
        .vip-exchange-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }
        .vip-roadmap-panel {
            background: rgba(26, 26, 30, 0.4);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            padding: 16px;
            max-height: 480px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.08) transparent;
        }
        .vip-roadmap-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .vip-roadmap-item {
            background: rgba(255, 255, 255, 0.01);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 12px 14px;
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 14px;
            align-items: center;
            transition: border-color 0.25s, background-color 0.25s;
        }
        .vip-roadmap-item.active {
            background: rgba(250, 204, 21, 0.02);
            border-color: rgba(250, 204, 21, 0.25);
            box-shadow: 0 4px 15px rgba(250, 204, 21, 0.04);
        }
        .vip-roadmap-item.completed {
            border-color: rgba(74, 222, 128, 0.12);
        }
        .vip-roadmap-badge {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
            border: 1px solid transparent;
        }
        .vip-roadmap-badge.bronze { background: rgba(217, 119, 6, 0.08); border-color: rgba(217, 119, 6, 0.3); color: #d97706; }
        .vip-roadmap-badge.silver { background: rgba(156, 163, 175, 0.08); border-color: rgba(156, 163, 175, 0.3); color: #9ca3af; }
        .vip-roadmap-badge.gold { background: rgba(250, 204, 21, 0.08); border-color: rgba(250, 204, 21, 0.3); color: #eab308; }
        
        .vip-roadmap-details {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .vip-roadmap-name {
            font-weight: 800;
            font-size: 13px;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .vip-roadmap-active-tag {
            font-size: 8.5px;
            font-weight: 800;
            background: var(--color-yellow);
            color: #000;
            padding: 1px 5px;
            border-radius: 3px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .vip-roadmap-benefits {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 4px;
        }
        .vip-benefit-tag {
            font-size: 9.5px;
            font-weight: 700;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            padding: 2px 6px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .vip-benefit-tag.unlocked {
            border-color: rgba(250, 204, 21, 0.15);
            color: var(--color-yellow);
            background: rgba(250, 204, 21, 0.02);
        }
        .vip-roadmap-wager {
            font-size: 10.5px;
            color: var(--text-muted);
            font-weight: 700;
            text-align: right;
            white-space: nowrap;
        }
        .vip-roadmap-wager strong {
            color: #fff;
            display: block;
            font-size: 11.5px;
            font-family: var(--font-heading);
        }
    </style>

    <!-- ===================== TOURNAMENTS MODAL ===================== -->
    <div class="vibra-modal" id="tournamentsModal">
        <div class="vibra-modal-content">
            <div class="vibra-modal-header">
                <h3><i class="fa-solid fa-trophy" style="color: var(--color-yellow);"></i> Torneios Ativos</h3>
                <button type="button" class="vibra-close-btn" onclick="closeTournamentsModal()">&times;</button>
            </div>
            
            <div id="tournamentsContent">
                <!-- Loaded dynamically -->
                <div style="text-align: center; color: var(--text-muted); padding: 40px 0;">
                    <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 24px; margin-bottom: 10px;"></i>
                    <p>Carregando torneios...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== MISSIONS MODAL ===================== -->
    <div class="vibra-modal" id="missionsModal">
        <div class="vibra-modal-content">
            <div class="vibra-modal-header">
                <h3><i class="fa-solid fa-crosshairs" style="color: var(--color-yellow);"></i> Missões de Recompensa</h3>
                <button type="button" class="vibra-close-btn" onclick="closeMissionsModal()">&times;</button>
            </div>
            
            <div id="missionsContent">
                <!-- Loaded dynamically -->
                <div style="text-align: center; color: var(--text-muted); padding: 40px 0;">
                    <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 24px; margin-bottom: 10px;"></i>
                    <p>                </div>
            </div>
        </div>
    </div>

    <!-- ===================== VIP MODAL ===================== -->
    <div class="vibra-modal" id="vipModal">
        <div class="vibra-modal-content" style="max-width: 1100px; width: 95%;">
            <div class="vibra-modal-header">
                <h3><i class="fa-solid fa-crown" style="color: var(--color-yellow);"></i> Programa de Fidelidade VIP</h3>
                <button type="button" class="vibra-close-btn" onclick="closeVipModal()">&times;</button>
            </div>
            
            <div id="vipModalContent">
                <!-- Loaded dynamically -->
                <div style="text-align: center; color: var(--text-muted); padding: 40px 0;">
                    <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 24px; margin-bottom: 10px;"></i>
                    <p>Carregando dados VIP...</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Tournaments and Missions Scripts -->
    <script>
        function openTournamentsModal() {
            window.location.href = '/torneios';
        }

        function closeTournamentsModal() {
            const modal = document.getElementById('tournamentsModal');
            modal.classList.remove('show');
            setTimeout(() => modal.style.display = 'none', 300);
        }

        function loadTournamentsData() {
            const container = document.getElementById('tournamentsContent');
            
            fetch('/tournaments/active')
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.tournaments && data.tournaments.length > 0) {
                        let html = '';
                        data.tournaments.forEach(t => {
                            let boardHtml = '';
                            if (t.leaderboard && t.leaderboard.length > 0) {
                                t.leaderboard.forEach(p => {
                                    let rBadge = p.rank;
                                    if (p.rank === 1) rBadge = '🥇';
                                    else if (p.rank === 2) rBadge = '🥈';
                                    else if (p.rank === 3) rBadge = '🥉';
                                    
                                    boardHtml += `
                                        <tr>
                                            <td style="font-weight: 700;">${rBadge}</td>
                                            <td>${p.name}</td>
                                            <td style="color: var(--color-yellow); font-weight: 700; text-align: right;">${p.score} pts</td>
                                        </tr>
                                    `;
                                });
                            } else {
                                boardHtml = `<tr><td colspan="3" style="text-align: center; color: var(--text-muted); padding: 20px;">Ninguém pontuou ainda. Seja o primeiro!</td></tr>`;
                            }

                            let endDateStr = new Date(t.end_date).toLocaleDateString('pt-BR', {day: '2-digit', month: '2-digit', hour: '2-digit', minute: '2-digit'});

                            html += `
                                <div style="margin-bottom: 25px; border-bottom: 1px solid rgba(255,255,255,0.05); padding-bottom: 20px;">
                                    <div class="t-banner-wrapper">
                                        <img src="${t.banner_url}" class="t-banner-img" alt="Banner">
                                    </div>
                                    <h4 style="font-weight: 800; color: #fff; font-size: 15px; margin-bottom: 6px;">${t.title}</h4>
                                    <p style="font-size: 11.5px; color: var(--text-secondary); margin-bottom: 12px;">${t.description || ''}</p>
                                    
                                    <div class="t-meta-grid">
                                        <div class="t-meta-box">
                                            <span>Prêmio Total</span>
                                            <strong>R$ ${t.prize_pool}</strong>
                                        </div>
                                        <div class="t-meta-box">
                                            <span>Encerramento</span>
                                            <strong style="color: #fff; font-size: 12px;">${endDateStr}</strong>
                                        </div>
                                    </div>

                                    @auth
                                    <div style="background: rgba(248, 204, 27, 0.04); border: 1px solid rgba(248, 204, 27, 0.12); padding: 10px 15px; border-radius: 8px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                                        <span style="font-size: 12px; color: var(--text-secondary);">Sua Posição atual:</span>
                                        <span style="font-size: 12px; font-weight: 800; color: #fff;">
                                            Rank: <strong style="color: var(--color-yellow);">${t.user_rank}</strong> | Pontos: <strong style="color: var(--color-yellow);">${t.user_score}</strong>
                                        </span>
                                    </div>
                                    @endauth

                                    <h5 style="font-weight: 700; color: #fff; font-size: 12px; margin-bottom: 8px; text-transform: uppercase;">Classificação Top 15</h5>
                                    <table class="t-ranking-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 50px;">Pos</th>
                                                <th>Jogador</th>
                                                <th style="text-align: right;">Pontos</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${boardHtml}
                                        </tbody>
                                    </table>
                                </div>
                            `;
                        });
                        container.innerHTML = html;
                    } else {
                        container.innerHTML = `
                            <div style="text-align: center; color: var(--text-muted); padding: 40px 0;">
                                <i class="fa-solid fa-trophy" style="font-size: 36px; color: var(--text-muted); margin-bottom: 15px; opacity: 0.3;"></i>
                                <p style="font-size: 13px;">Nenhum torneio ativo no momento. Fique atento às nossas novidades!</p>
                            </div>
                        `;
                    }
                })
                .catch(err => {
                    console.error(err);
                    container.innerHTML = `<p style="text-align: center; color: var(--red); padding: 20px;">Erro ao carregar dados. Tente novamente.</p>`;
                });
        }

        function openMissionsModal() {
            window.location.href = '/missoes';
        }

        function closeMissionsModal() {
            const modal = document.getElementById('missionsModal');
            modal.classList.remove('show');
            setTimeout(() => modal.style.display = 'none', 300);
        }

        function loadMissionsData() {
            const container = document.getElementById('missionsContent');
            
            @guest
                container.innerHTML = `
                    <div style="text-align: center; color: var(--text-muted); padding: 40px 0;">
                        <i class="fa-solid fa-lock" style="font-size: 32px; margin-bottom: 15px; opacity: 0.4;"></i>
                        <p style="font-size: 13px; margin-bottom: 15px;">Você precisa fazer login para ver e completar missões!</p>
                        <button type="button" class="btn-deposit-submit" style="display: inline-block; width: auto; padding: 10px 20px;" onclick="closeMissionsModal(); openModal('loginModal')">
                            <span aria-hidden="true" class="btn-skew-bg"></span>
                            <span class="btn-content">FAZER ENTRAR</span>
                        </button>
                    </div>
                `;
                return;
            @endguest

            fetch('/missions/active')
                .then(r => r.json())
                .then(data => {
                    if (data.success && data.missions && data.missions.length > 0) {
                        let html = '';
                        data.missions.forEach(m => {
                            let pct = (m.current_value / m.target_value) * 100;
                            if (pct > 100) pct = 100;
                            pct = pct.toFixed(0);

                            let valText = '';
                            let targetText = '';
                            if (m.type === 'bet_count') {
                                valText = `${m.current_value.toFixed(0)}`;
                                targetText = `${m.target_value.toFixed(0)}`;
                            } else {
                                valText = `R$ ${m.current_value.toFixed(2)}`;
                                targetText = `R$ ${m.target_value.toFixed(2)}`;
                            }

                            let actionBlock = '';
                            if (m.reward_claimed) {
                                actionBlock = `<span class="m-claimed-badge"><i class="fa-solid fa-check"></i> Reivindicado</span>`;
                            } else if (m.is_completed) {
                                actionBlock = `<button type="button" class="m-claim-btn" onclick="claimReward(${m.id}, this)">Resgatar</button>`;
                            } else {
                                actionBlock = `<span style="font-size: 11px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Em Andamento</span>`;
                            }

                            html += `
                                <div class="m-card-item">
                                    <div class="m-card-header">
                                        <div>
                                            <span class="m-card-title">${m.title}</span>
                                            <p class="m-card-desc">${m.description || ''}</p>
                                        </div>
                                        <span class="m-card-reward">R$ ${m.reward_amount}</span>
                                    </div>
                                    <div class="m-progress-container">
                                        <div class="m-progress-bar-bg">
                                            <div class="m-progress-bar-fill" style="width: ${pct}%"></div>
                                        </div>
                                        <span class="m-progress-text">${valText} / ${targetText}</span>
                                    </div>
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px;">
                                        <span style="font-size: 10px; color: var(--text-muted); font-weight: 700; text-transform: uppercase; background: rgba(255,255,255,0.02); padding: 3px 8px; border-radius: 4px; border: 1px solid var(--border-color);">
                                            ${m.period === 'daily' ? 'Diário' : (m.period === 'weekly' ? 'Semanal' : 'Único')}
                                        </span>
                                        ${actionBlock}
                                    </div>
                                </div>
                            `;
                        });
                        container.innerHTML = html;
                    } else {
                        container.innerHTML = `
                            <div style="text-align: center; color: var(--text-muted); padding: 40px 0;">
                                <i class="fa-solid fa-circle-check" style="font-size: 36px; color: var(--text-muted); margin-bottom: 15px; opacity: 0.3;"></i>
                                <p style="font-size: 13px;">Você completou todas as missões disponíveis por hoje. Volte amanhã!</p>
                            </div>
                        `;
                    }
                })
                .catch(err => {
                    console.error(err);
                    container.innerHTML = `<p style="text-align: center; color: var(--red); padding: 20px;">Erro ao carregar dados. Tente novamente.</p>`;
                });
        }

        function claimReward(missionId, button) {
            button.disabled = true;
            button.innerText = 'Processando...';

            fetch('/missions/claim', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ mission_id: missionId })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    refreshUserBalance();
                    loadMissionsData();
                } else {
                    alert(data.message || 'Erro ao resgatar prêmio.');
                    button.disabled = false;
                    button.innerText = 'Resgatar';
                }
            })
            .catch(err => {
                console.error(err);
                alert('Erro de conexão ao resgatar recompensa.');
                button.disabled = false;
                button.innerText = 'Resgatar';
            });
        }

        // --- VIP MODAL LOGIC ---
        function openVipModal() {
            @guest
                alert('Você precisa fazer login para acessar o programa VIP!');
                openModal('loginModal');
                return;
            @endguest

            const modal = document.getElementById('vipModal');
            if (modal) {
                modal.style.display = 'flex';
                // Force reflow
                modal.offsetHeight;
                modal.classList.add('show');
                loadVipData();
            }
        }

        function closeVipModal() {
            const modal = document.getElementById('vipModal');
            if (modal) {
                modal.classList.remove('show');
                setTimeout(() => modal.style.display = 'none', 300);
            }
        }

        function loadVipData() {
            const container = document.getElementById('vipModalContent');
            if (!container) return;

            // Show loading spinner
            container.innerHTML = `
                <div style="text-align: center; color: var(--text-muted); padding: 50px 0;">
                    <i class="fa-solid fa-circle-notch fa-spin" style="font-size: 30px; margin-bottom: 12px; color: var(--color-yellow);"></i>
                    <p style="font-size: 13.5px;">Carregando dados do programa VIP...</p>
                </div>
            `;

            fetch('/vip/status')
                .then(r => r.json())
                .then(data => {
                    if (!data.success) {
                        container.innerHTML = `<p style="text-align: center; color: var(--red); padding: 30px;">${data.message || 'Erro ao carregar dados VIP.'}</p>`;
                        return;
                    }

                    const xp = data.vip_points;
                    const coins = data.vip_coins;
                    const currentLevel = data.current_level;
                    const nextLevel = data.next_level;
                    const allLevels = data.all_levels;

                    // Progress bar calculations
                    let progressPct = 100;
                    let xpText = `${xp} XP (Nível Máximo)`;
                    let xpNeededText = '';

                    if (nextLevel) {
                        const prevReq = currentLevel.required_points || 0;
                        const nextReq = nextLevel.required_points;
                        const currentInLevel = xp - prevReq;
                        const range = nextReq - prevReq;
                        progressPct = range > 0 ? (currentInLevel / range) * 100 : 0;
                        if (progressPct < 0) progressPct = 0;
                        if (progressPct > 100) progressPct = 100;

                        xpText = `${currentInLevel} / ${range} XP`;
                        xpNeededText = `Faltam <strong>${nextReq - xp} XP</strong> para desbloquear <strong>${nextLevel.name}</strong>`;
                    }

                    // Fallback badge class
                    let badgeClass = 'vip-badge-bronze';
                    if (currentLevel.name.toLowerCase().includes('silver') || currentLevel.name.toLowerCase().includes('prata')) {
                        badgeClass = 'vip-badge-silver';
                    } else if (currentLevel.name.toLowerCase().includes('gold') || currentLevel.name.toLowerCase().includes('ouro') || currentLevel.name.toLowerCase().includes('diamante') || currentLevel.name.toLowerCase().includes('diamond')) {
                        badgeClass = 'vip-badge-gold';
                    }

                    // Badges fallback HTML
                    let badgeHtml = `<div class="vip-badge-graphic ${badgeClass}"><i class="fa-solid fa-crown"></i></div>`;
                    if (currentLevel.badge_path) {
                        badgeHtml = `
                            <div class="vip-badge-graphic" style="border: 2px solid var(--color-yellow);">
                                <img src="${currentLevel.badge_path}" onerror="this.outerHTML='<div class=\'vip-badge-graphic ${badgeClass}\'><i class=\'fa-solid fa-crown\'></i></div>'" class="vip-badge-img" alt="Badge">
                            </div>
                        `;
                    }

                    // Roadmap items HTML
                    let roadmapHtml = '';
                    allLevels.forEach(lvl => {
                        const isCurrent = lvl.id === currentLevel.id;
                        const isCompleted = lvl.required_points <= xp && !isCurrent;
                        const isLocked = lvl.required_points > xp;

                        let itemClass = '';
                        let statusBadge = '';
                        if (isCurrent) {
                            itemClass = 'active';
                            statusBadge = `<span class="vip-roadmap-active-tag">Atual</span>`;
                        } else if (isCompleted) {
                            itemClass = 'completed';
                            statusBadge = `<span style="color: #4ade80; font-size: 11px; font-weight: 700;"><i class="fa-solid fa-circle-check"></i> Desbloqueado</span>`;
                        } else {
                            statusBadge = `<span style="color: var(--text-muted); font-size: 11px; font-weight: 700;"><i class="fa-solid fa-lock"></i> Bloqueado</span>`;
                        }

                        // Determine level badge icon/path
                        let lvlBadgeClass = 'bronze';
                        if (lvl.name.toLowerCase().includes('silver') || lvl.name.toLowerCase().includes('prata')) {
                            lvlBadgeClass = 'silver';
                        } else if (lvl.name.toLowerCase().includes('gold') || lvl.name.toLowerCase().includes('ouro')) {
                            lvlBadgeClass = 'gold';
                        }

                        let lvlBadgeHtml = `<div class="vip-roadmap-badge ${lvlBadgeClass}"><i class="fa-solid fa-medal"></i></div>`;
                        if (lvl.badge_path) {
                            lvlBadgeHtml = `
                                <img src="${lvl.badge_path}" onerror="this.outerHTML='<div class=\'vip-roadmap-badge ${lvlBadgeClass}\'><i class=\'fa-solid fa-medal\'></i></div>'" style="width: 40px; height: 40px; object-fit: contain; border-radius: 50%;" alt="${lvl.name}">
                            `;
                        }

                        // Benefits tags HTML
                        let benefitsTagsHtml = '';
                        const benefitsList = lvl.benefits || [];
                        const benefitsMap = {
                            'cashback': 'Cashback',
                            'vip_club': 'VIP Club',
                            'vip_support': 'Suporte VIP',
                            'private_tournaments': 'Torneios VIP',
                            'vip_bonus': 'Bônus VIP',
                            'sports_limits': 'Apostas Max',
                            'exclusive_channels': 'Canais VIP',
                            'vip_manager': 'Gerente VIP',
                            'concierge': 'Concierge',
                            'manager_bonus': 'Bônus Gerente',
                            'vip_gifts': 'Brindes',
                            'vip_trips': 'Viagens VIP',
                            'personal_assistant': 'Assistente'
                        };

                        // Add cashback benefit tag first
                        benefitsTagsHtml += `
                            <span class="vip-benefit-tag unlocked">
                                <i class="fa-solid fa-coins"></i> ${lvl.cashback_percent}% Cashback
                            </span>
                        `;

                        benefitsList.forEach(b => {
                            if (b !== 'cashback' && benefitsMap[b]) {
                                benefitsTagsHtml += `
                                    <span class="vip-benefit-tag ${!isLocked ? 'unlocked' : ''}">
                                        <i class="fa-solid ${!isLocked ? 'fa-check-circle' : 'fa-circle'}"></i> ${benefitsMap[b]}
                                    </span>
                                `;
                            }
                        });

                        roadmapHtml += `
                            <div class="vip-roadmap-item ${itemClass}">
                                ${lvlBadgeHtml}
                                <div class="vip-roadmap-details">
                                    <div class="vip-roadmap-name">
                                        ${lvl.name} ${statusBadge}
                                    </div>
                                    <div class="vip-roadmap-benefits">
                                        ${benefitsTagsHtml}
                                    </div>
                                </div>
                                <div class="vip-roadmap-wager">
                                    XP Requerido
                                    <strong>${lvl.required_points.toLocaleString('pt-BR')} XP</strong>
                                </div>
                            </div>
                        `;
                    });

                    // Build Main Modal Grid
                    const gridHtml = `
                        <div class="vip-modal-grid">
                            <!-- Left: User Status and Exchange -->
                            <div class="vip-card-panel">
                                <h4 style="font-family: var(--font-heading); font-size: 15px; font-weight: 800; color: #fff; margin: 0; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-id-card" style="color: var(--color-yellow);"></i> Seu Status Atual
                                </h4>
                                
                                <div class="vip-badge-showcase">
                                    ${badgeHtml}
                                    <div style="display: flex; flex-direction: column; gap: 4px;">
                                        <span style="font-size: 10px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Nível Atual</span>
                                        <strong style="font-size: 18px; color: var(--color-yellow); font-family: var(--font-heading); font-weight: 800;">${currentLevel.name}</strong>
                                        <span style="font-size: 11.5px; color: var(--text-secondary); font-weight: 600;">Cashback Ativo: <strong>${parseFloat(currentLevel.cashback_percent).toFixed(1)}%</strong></span>
                                    </div>
                                </div>

                                <div class="vip-xp-tracker">
                                    <div style="display: flex; justify-content: space-between; font-size: 12px; font-weight: 700;">
                                        <span style="color: var(--text-secondary);">Progresso do Nível</span>
                                        <span style="color: #fff;">${xpText}</span>
                                    </div>
                                    <div class="vip-xp-bar-bg">
                                        <div class="vip-xp-bar-fill" style="width: ${progressPct}%"></div>
                                    </div>
                                    <span style="font-size: 11px; color: var(--text-muted);">${xpNeededText || 'Parabéns, você atingiu o nível máximo!'}</span>
                                </div>

                                <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 5px 0;">

                                <h4 style="font-family: var(--font-heading); font-size: 15px; font-weight: 800; color: #fff; margin: 0; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-money-bill-transfer" style="color: var(--color-yellow);"></i> Troca de Moedas VIP
                                </h4>

                                <div class="vip-coin-balance-card">
                                    <div style="display: flex; flex-direction: column; gap: 4px;">
                                        <span style="font-size: 10px; color: var(--text-muted); font-weight: 700; text-transform: uppercase;">Moedas Disponíveis</span>
                                        <div class="vip-coin-count">
                                            <i class="fa-solid fa-coins"></i>
                                            <span id="vipCoinsBalanceSpan">${parseFloat(coins).toLocaleString('pt-BR', {minimumFractionDigits: 0, maximumFractionDigits: 0})}</span>
                                        </div>
                                    </div>
                                    <div style="text-align: right; font-size: 11.5px; color: var(--text-secondary); font-weight: 600;">
                                        Sua taxa de câmbio:<br>
                                        <strong style="color: var(--color-yellow);">${parseInt(currentLevel.exchange_rate)} moedas</strong> = <strong style="color: #fff;">R$ 1,00</strong>
                                    </div>
                                </div>

                                <div class="vip-exchange-box">
                                    <div style="display: flex; flex-direction: column; gap: 6px;">
                                        <label style="font-size: 11px; color: var(--text-secondary); font-weight: 700; text-transform: uppercase;">Quantidade para Trocar</label>
                                        <div class="vip-exchange-input-container">
                                            <input type="number" id="vipExchangeAmountInput" class="vip-exchange-input" min="${currentLevel.min_exchange}" value="${coins >= currentLevel.min_exchange ? currentLevel.min_exchange : 0}" oninput="calculateVipExchange(${currentLevel.exchange_rate})">
                                            <button type="button" style="background: rgba(255,255,255,0.05); border: 1px solid var(--border-color); color: #fff; font-size: 10px; font-weight: 700; padding: 4px 8px; border-radius: 4px; cursor: pointer;" onclick="setVipExchangeMax(${coins}, ${currentLevel.min_exchange}, ${currentLevel.exchange_rate})">MAX</button>
                                        </div>
                                    </div>

                                    <div class="vip-exchange-preview">
                                        <span>Valor Estimado: <strong style="color: #4ade80;" id="vipExchangeRealValueSpan">R$ 0,00</strong></span>
                                        <span>Troca mínima: <strong>${currentLevel.min_exchange} moedas</strong></span>
                                    </div>

                                    <button type="button" class="vip-exchange-btn" id="vipExchangeBtn" onclick="exchangeVipCoins(${currentLevel.min_exchange})">
                                        <span id="vipExchangeBtnText">Trocar por Saldo Real</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Right: Levels Roadmap -->
                            <div style="display: flex; flex-direction: column; gap: 10px;">
                                <h4 style="font-family: var(--font-heading); font-size: 15px; font-weight: 800; color: #fff; margin: 0 0 5px 0; display: flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-road" style="color: var(--color-yellow);"></i> Linha do Tempo de Níveis VIP
                                </h4>
                                <div class="vip-roadmap-panel">
                                    <div class="vip-roadmap-list">
                                        ${roadmapHtml}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    container.innerHTML = gridHtml;

                    // Trigger initial calculations
                    const exchangeInput = document.getElementById('vipExchangeAmountInput');
                    if (exchangeInput) {
                        calculateVipExchange(currentLevel.exchange_rate);
                    }
                })
                .catch(err => {
                    console.error('Error fetching VIP status:', err);
                    container.innerHTML = `<p style="text-align: center; color: var(--red); padding: 30px;">Erro de conexão ao buscar dados VIP. Tente novamente.</p>`;
                });
        }

        function calculateVipExchange(rate) {
            const input = document.getElementById('vipExchangeAmountInput');
            const previewSpan = document.getElementById('vipExchangeRealValueSpan');
            if (!input || !previewSpan) return;

            const coins = parseInt(input.value) || 0;
            if (coins <= 0) {
                previewSpan.textContent = 'R$ 0,00';
                return;
            }

            const value = coins / parseFloat(rate);
            previewSpan.textContent = `R$ ${value.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        }

        function setVipExchangeMax(maxCoins, minExchange, rate) {
            const input = document.getElementById('vipExchangeAmountInput');
            if (!input) return;

            // Round down to whole coin values
            const val = Math.floor(maxCoins);
            input.value = val;
            calculateVipExchange(rate);
        }

        function exchangeVipCoins(minExchange) {
            const input = document.getElementById('vipExchangeAmountInput');
            const btn = document.getElementById('vipExchangeBtn');
            const btnText = document.getElementById('vipExchangeBtnText');
            if (!input || !btn) return;

            const amount = parseInt(input.value) || 0;

            if (amount < minExchange) {
                alert(`O valor mínimo para troca neste nível é de ${minExchange} moedas.`);
                return;
            }

            // Disable button during requests
            btn.disabled = true;
            if (btnText) btnText.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Processando...';

            fetch("{{ route('vip.exchange') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json"
                },
                body: JSON.stringify({ amount: amount })
            })
            .then(r => {
                if (!r.ok) {
                    return r.json().then(err => { throw err; });
                }
                return r.json();
            })
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    
                    // Refresh balance headers and other numbers
                    refreshUserBalance();
                    
                    // Re-load VIP data inside modal
                    loadVipData();
                } else {
                    alert(data.message || 'Erro ao realizar a troca.');
                    btn.disabled = false;
                    if (btnText) btnText.textContent = 'Trocar por Saldo Real';
                }
            })
            .catch(err => {
                console.error(err);
                alert(err.message || 'Erro de conexão ao processar troca VIP.');
                btn.disabled = false;
                if (btnText) btnText.textContent = 'Trocar por Saldo Real';
            });
        }

        window.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeTournamentsModal();
                closeMissionsModal();
                closeVipModal();
            }
        });

        window.addEventListener('click', (e) => {
            const modal1 = document.getElementById('tournamentsModal');
            const modal2 = document.getElementById('missionsModal');
            const modal3 = document.getElementById('vipModal');
            if (e.target === modal1) {
                closeTournamentsModal();
            }
            if (e.target === modal2) {
                closeMissionsModal();
            }
            if (e.target === modal3) {
                closeVipModal();
            }
        });

        // --- DYNAMIC SEARCH LOGIC ---
        let searchTimeout = null;

        function closeSearchOverlay() {
            const overlay = document.getElementById('searchResultsOverlay');
            const pageContent = document.getElementById('pageContentWrapper');
            const mainSearch = document.getElementById('gameSearchInput');
            const overlaySearch = document.getElementById('overlaySearchInput');
            if (overlay) overlay.style.display = 'none';
            if (pageContent) pageContent.style.display = '';
            if (mainSearch) mainSearch.value = '';
            if (overlaySearch) overlaySearch.value = '';
        }

        function handleOverlaySearch(input) {
            performSearch(input.value.trim());
        }

        const mainSearchInput = document.getElementById('gameSearchInput');
        if (mainSearchInput) {
            mainSearchInput.addEventListener('input', function(e) {
                const query = e.target.value.trim();
                const overlayInput = document.getElementById('overlaySearchInput');
                if (overlayInput) overlayInput.value = query;
                performSearch(query);
            });
        }

        function performSearch(query) {
            const overlay = document.getElementById('searchResultsOverlay');
            const pageContent = document.getElementById('pageContentWrapper');
            const grid = document.getElementById('searchResultsGrid');
            const noResults = document.getElementById('searchNoResults');
            const searchContent = document.getElementById('searchContent');
            const queryText = document.getElementById('searchQueryText');
            const countText = document.getElementById('searchCount');

            if (query.length < 2) {
                if (overlay) overlay.style.display = 'none';
                if (pageContent) pageContent.style.display = '';
                return;
            }

            if (overlay) overlay.style.display = 'block';
            if (pageContent) pageContent.style.display = 'none';
            if (queryText) queryText.innerText = `"${query}"`;
            if (grid) grid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 60px;"><i class="fa-solid fa-circle-notch fa-spin" style="font-size: 2.5rem; color: var(--neon-green);"></i></div>';
            if (noResults) noResults.style.display = 'none';
            if (searchContent) searchContent.style.display = 'none';

            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                fetch(`/search-games?q=${encodeURIComponent(query)}`)
                    .then(r => r.json())
                    .then(data => {
                        if (!grid) return;
                        grid.innerHTML = '';
                        
                        if (data.games && data.games.length > 0) {
                            if (searchContent) searchContent.style.display = 'block';
                            if (countText) countText.innerText = `${data.games.length} resultados`;
                            
                            data.games.forEach(game => {
                                let clickAction = '';
                                if (game.action_type === 'link') {
                                    clickAction = `window.location.href='${game.action_value}'`;
                                } else if (game.action_type === 'play') {
                                    if (game.distribution) {
                                        clickAction = `launchExternalGame('${game.slug}', this)`;
                                    } else {
                                        clickAction = `checkAuthAndPlay('${game.action_value}')`;
                                    }
                                }

                                const card = document.createElement('div');
                                card.className = `game-card item-${game.slug}`;
                                card.setAttribute('onclick', clickAction);
                                card.style.cursor = 'pointer';

                                const seed = query.length + game.slug.length;
                                const online = (seed % 150) + 40;

                                // Ensure image path starts with slash if not already
                                let imgSrc = game.image_path;
                                if (!imgSrc.startsWith('/') && !imgSrc.startsWith('http')) {
                                    imgSrc = '/' + imgSrc;
                                }

                                card.innerHTML = `
                                    <div class="game-thumbnail">
                                        <button class="card-fav-btn" onclick="event.stopPropagation(); toggleLike(this)">
                                            <i class="fa-regular fa-heart"></i>
                                        </button>
                                        <div class="card-online-count">
                                            <span class="online-dot"></span>
                                            ${online}
                                        </div>
                                        <img src="${imgSrc}" alt="${game.title}" class="game-img-cover" onerror="this.src='/imagens/placeholder_game.jpg'">
                                        <div class="game-play-hover">
                                            <span class="play-btn-custom"><i class="fa-solid fa-play"></i> JOGAR</span>
                                        </div>
                                    </div>
                                `;
                                grid.appendChild(card);
                            });
                        } else {
                            if (noResults) noResults.style.display = 'block';
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        if (grid) grid.innerHTML = '<p style="grid-column: 1/-1; text-align: center; color: var(--red);">Erro ao buscar jogos.</p>';
                    });
            }, 300);
        }

        // =====================================================
        // AFFILIATE SYSTEM JAVASCRIPT
        // =====================================================
        function openAffiliateModal() {
            console.log('openAffiliateModal: Start');
            
            // Forçar fechamento de tudo antes
            const drawer = document.getElementById('profileDrawer');
            const drawerOverlay = document.getElementById('profileDrawerOverlay');
            if (drawer) drawer.classList.remove('open');
            if (drawerOverlay) drawerOverlay.classList.remove('open');
            document.body.style.overflow = 'hidden'; // Travar scroll para o modal

            // Fetch stats from server
            fetch("/affiliate/stats", {
                method: "GET",
                headers: {
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                console.log('openAffiliateModal: Fetch response received', response.status);
                if (response.status === 401) {
                    openModal('loginModal');
                    throw new Error("Unauthenticated");
                }
                return response.json();
            })
            .then(data => {
                console.log('openAffiliateModal: Data processed', data);
                if (data.success) {
                    // Populate stats
                    const elements = {
                        'aff-today-registrations': data.today.registrations,
                        'aff-today-new-players': data.today.new_players,
                        'aff-today-valid-bets': data.today.valid_bets,
                        'aff-today-commission-value': data.today.commission,
                        'aff-total-registrations': data.total.registrations,
                        'aff-total-players': data.total.total_players,
                        'aff-total-valid-bets': data.total.valid_bets,
                        'aff-total-commission-value': data.total.commission,
                        'aff-paid-commission': data.paid_commission,
                        'aff-unpaid-commission': data.unpaid_commission
                    };

                    for (const [id, val] of Object.entries(elements)) {
                        const el = document.getElementById(id);
                        if (el) {
                            el.textContent = val;
                        } else {
                            console.warn(`openAffiliateModal: Element with ID ${id} not found.`);
                        }
                    }

                    // Set sharing details
                    const referralLink = data.referral_link;
                    const referralInput = document.getElementById('aff-referral-link-input');
                    if (referralInput) {
                        referralInput.value = referralLink;
                    }
                    
                    const encodedLink = encodeURIComponent(referralLink);
                    const encodedMsg = encodeURIComponent("Cadastre-se no fone777.online e jogue os melhores slots!");
                    
                    const shareButtons = {
                        'aff-share-facebook': `https://www.facebook.com/sharer/sharer.php?u=${encodedLink}`,
                        'aff-share-whatsapp': `https://api.whatsapp.com/send?text=${encodedMsg}%20${encodedLink}`,
                        'aff-share-telegram': `https://t.me/share/url?url=${encodedLink}&text=${encodedMsg}`,
                        'aff-share-twitter': `https://twitter.com/intent/tweet?url=${encodedLink}&text=${encodedMsg}`,
                        'aff-share-email': `mailto:?subject=Convite%20fone777.online&body=${encodedMsg}%20${encodedLink}`
                    };

                    for (const [id, url] of Object.entries(shareButtons)) {
                        const btn = document.getElementById(id);
                        if (btn) btn.href = url;
                    }

                    // Populate References Table
                    const refBody = document.getElementById('aff-references-body');
                    if (refBody) {
                        if (data.referrals && data.referrals.length > 0) {
                            refBody.innerHTML = '';
                            data.referrals.forEach(ref => {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                    <td>${ref.index}</td>
                                    <td>${ref.account}</td>
                                    <td>R$ ${ref.bets}</td>
                                    <td>R$ ${ref.team_bets}</td>
                                    <td style="color: var(--neon-green); font-weight: 700;">R$ ${ref.commission}</td>
                                    <td style="color: var(--text-secondary);">${ref.registered_at}</td>
                                `;
                                refBody.appendChild(tr);
                            });
                            const refTotalBets = document.getElementById('aff-refs-total-bets');
                            const refTotalComm = document.getElementById('aff-refs-total-commission');
                            if (refTotalBets) refTotalBets.textContent = data.total.valid_bets;
                            if (refTotalComm) refTotalComm.textContent = data.total.commission;
                        } else {
                            refBody.innerHTML = `
                                <tr>
                                    <td colspan="6" class="aff-table-empty">
                                        <i class="fa-regular fa-folder-open"></i> Opa! Ainda não há dados!
                                    </td>
                                </tr>
                            `;
                            const refTotalBets = document.getElementById('aff-refs-total-bets');
                            const refTotalComm = document.getElementById('aff-refs-total-commission');
                            if (refTotalBets) refTotalBets.textContent = '0,00';
                            if (refTotalComm) refTotalComm.textContent = '0,00';
                        }
                    }

                    // Populate Reports Table
                    const repBody = document.getElementById('aff-reports-body');
                    if (repBody) {
                        if (data.reports && data.reports.length > 0) {
                            repBody.innerHTML = '';
                            data.reports.forEach(rep => {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                    <td>${rep.date}</td>
                                    <td>${rep.registrations}</td>
                                    <td>R$ ${rep.valid_bets}</td>
                                    <td>${rep.agent_level}</td>
                                    <td style="color: var(--neon-green); font-weight: 700;">R$ ${rep.commission}</td>
                                `;
                                repBody.appendChild(tr);
                            });
                        } else {
                            repBody.innerHTML = `
                                <tr>
                                    <td colspan="5" class="aff-table-empty">
                                        <i class="fa-regular fa-folder-open"></i> Opa! Ainda não há dados!
                                    </td>
                                </tr>
                            `;
                        }
                    }

                    // Populate Payments Table
                    const payBody = document.getElementById('aff-payments-body');
                    if (payBody) {
                        if (data.payments && data.payments.length > 0) {
                            payBody.innerHTML = '';
                            data.payments.forEach(pay => {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                    <td>${pay.index}</td>
                                    <td>${pay.date}</td>
                                    <td>${pay.detail}</td>
                                    <td style="color: var(--neon-green); font-weight: 700;">R$ ${pay.commission}</td>
                                    <td><span style="color: #4ade80; background: rgba(74,222,128,0.1); padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 800;">${pay.status}</span></td>
                                `;
                                payBody.appendChild(tr);
                            });
                        } else {
                            payBody.innerHTML = `
                                <tr>
                                    <td colspan="5" class="aff-table-empty">
                                        <i class="fa-regular fa-folder-open"></i> Opa! Ainda não há dados!
                                    </td>
                                </tr>
                            `;
                        }
                    }

                    // Switch to default Panel tab
                    const firstTab = document.querySelector('.affiliate-tabs li');
                    if (firstTab) {
                        switchAffiliateTab('painel', firstTab);
                    }

                    // Show Modal
                    console.log('openAffiliateModal: OPENING REAL PANEL NOW');
                    const modal = document.getElementById('AFF_PANEL_REAL');
                    if (modal) {
                        modal.style.setProperty('display', 'flex', 'important');
                        console.log('REAL PANEL display set to flex !important');
                    } else {
                        alert("ERRO: Painel 'AFF_PANEL_REAL' sumiu da página!");
                    }
                }
            })
            .catch(err => {
                if (err.message !== "Unauthenticated") {
                    console.error("Affiliate Stats Error:", err);
                    alert("Erro ao carregar dados do painel de afiliados.");
                }
            });
        }

        function switchAffiliateTab(tabId, element) {
            // Remove active classes
            document.querySelectorAll('.affiliate-tabs li').forEach(li => li.classList.remove('active'));
            document.querySelectorAll('.affiliate-tab-pane').forEach(pane => pane.classList.remove('active'));
            
            // Add active class to tab
            element.classList.add('active');
            
            // Show active pane
            const targetPane = document.getElementById('aff-tab-' + tabId);
            if (targetPane) {
                targetPane.classList.add('active');
            }
        }

        function copyAffiliateLink() {
            const linkInput = document.getElementById('aff-referral-link-input');
            linkInput.select();
            linkInput.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(linkInput.value)
                .then(() => {
                    const copyBtn = document.querySelector('.aff-copy-btn');
                    const originalText = copyBtn.textContent;
                    copyBtn.textContent = "Copiado!";
                    copyBtn.style.background = "var(--neon-green)";
                    copyBtn.style.color = "#000";
                    setTimeout(() => {
                        copyBtn.textContent = originalText;
                        copyBtn.style.background = "";
                        copyBtn.style.color = "";
                    }, 2000);
                })
                .catch(() => {
                    alert("Falha ao copiar link de indicação.");
                });
        }

        function filterAffiliateReferences() {
            const filter = document.getElementById('aff-references-search').value.toLowerCase();
            const rows = document.querySelectorAll('#aff-references-body tr');
            
            rows.forEach(row => {
                const accountCell = row.cells[1];
                if (accountCell) {
                    const text = accountCell.textContent || accountCell.innerText;
                    if (text.toLowerCase().indexOf(filter) > -1) {
                        row.style.display = "";
                    } else {
                        // Skip if it's the empty placeholder row
                        if (row.cells.length > 1) {
                            row.style.display = "none";
                        }
                    }
                }
            });
        }

        function transferAffiliateCommission() {
            const unpaidText = document.getElementById('aff-unpaid-commission').textContent;
            const unpaidVal = parseFloat(unpaidText.replace('.', '').replace(',', '.'));
            
            if (isNaN(unpaidVal) || unpaidVal < 10.00) {
                alert("O valor mínimo para transferência de comissão é R$ 10,00.");
                return;
            }

            const transferBtn = document.querySelector('.aff-transfer-btn');
            const originalText = transferBtn.textContent;
            transferBtn.textContent = "PROCESSANDO...";
            transferBtn.disabled = true;

            fetch("/affiliate/transfer", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                transferBtn.textContent = originalText;
                transferBtn.disabled = false;

                if (data.success) {
                    alert(data.message);
                    
                    // Update stats
                    document.getElementById('aff-unpaid-commission').textContent = data.unpaid_commission;
                    document.getElementById('aff-paid-commission').textContent = data.paid_commission;

                    // Update main balance on standard header, account modal, and other places
                    const balanceElements = document.querySelectorAll('#headerUserBalance, #accModalUserBalance, .profile-balance-text');
                    balanceElements.forEach(el => {
                        el.textContent = "R$ " + data.new_balance;
                    });
                    
                    // Refresh data
                    openAffiliateModal();
                } else {
                    alert(data.message || "Erro ao transferir comissão.");
                }
            })
            .catch(err => {
                transferBtn.textContent = originalText;
                transferBtn.disabled = false;
                console.error("Transfer Commission Error:", err);
                alert("Falha de rede ao transferir comissão.");
            });
        }
    </script>
</body>
</html>
