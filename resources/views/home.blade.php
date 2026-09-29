@extends('layouts.app')

@section('content')
<!-- Banners Row (Carousel Wrapper) -->
<div class="banners-carousel-wrapper">
    <button type="button" class="banners-arrow prev" onclick="moveBanners(-1)">
        <i class="fa-solid fa-chevron-left"></i>
    </button>
    
    <div class="banners-viewport">
        <div class="banners-track" id="bannersTrack">
            @if(isset($carouselBanners) && $carouselBanners->count() > 0)
                @foreach($carouselBanners as $banner)
                    <div class="banner-item" 
                        @if($banner->link_url) 
                            onclick="window.location.href='{{ $banner->link_url }}'" 
                        @else 
                            onclick="checkAuthAndPlay('fortune_tiger')" 
                        @endif>
                        <picture>
                            @php
                                $mob = isset($mobileBanners) ? $mobileBanners->where('order', $banner->order)->first() ?? $mobileBanners->first() : null;
                            @endphp
                            @if($mob)
                                <source media="(max-width: 768px)" srcset="{{ asset($mob->image_path) }}">
                            @endif
                            <img src="{{ asset($banner->image_path) }}" alt="{{ $banner->title }}">
                        </picture>
                    </div>
                @endforeach
            @else
                <div class="banner-item" onclick="checkAuthAndPlay('fortune_tiger')">
                    <img src="{{ asset('imagens/banners/1.jpg') }}" alt="A Patrao chegou na App Store">
                </div>
                <div class="banner-item" onclick="alert('Promoção Cashback de até 25%! Receba de volta uma parte das suas jogadas toda semana!')">
                    <img src="{{ asset('imagens/banners/2.jpg') }}" alt="Cashback até 25%">
                </div>
                <div class="banner-item" onclick="checkAuthAndPlay('fortune_tiger')">
                    <img src="{{ asset('imagens/banners/3.jpg') }}" alt="Inferno Fortune">
                </div>
                <div class="banner-item" onclick="checkAuthAndPlay('fortune_tiger')">
                    <img src="{{ asset('imagens/banners/4.jpg') }}" alt="Muitos Prêmios Esperam Por Você">
                </div>
                <div class="banner-item" onclick="alert('Novos Bônus Exclusivos de Cassino!')">
                    <img src="{{ asset('imagens/banners/5.jpg') }}" alt="Bônus Exclusivo 5">
                </div>
                <div class="banner-item" onclick="checkAuthAndPlay('fortune_tiger')">
                    <img src="{{ asset('imagens/banners/6.jpg') }}" alt="Grande Prêmio da Semana">
                </div>
                <div class="banner-item" onclick="openTournamentsModal()">
                    <img src="{{ asset('imagens/banners/7.jpg') }}" alt="Torneios 7">
                </div>
                <div class="banner-item" onclick="checkAuthAndPlay('fortune_tiger')">
                    <img src="{{ asset('imagens/banners/8.jpg') }}" alt="Bônus Especial 8">
                </div>
            @endif
        </div>
    </div>

    <button type="button" class="banners-arrow next" onclick="moveBanners(1)">
        <i class="fa-solid fa-chevron-right"></i>
    </button>
</div>

<div class="home-main-layout">
    <div class="home-center-column">
        <!-- Game Search Section -->
        <div class="search-bar-container">
            <div class="search-input-wrapper">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" id="gameSearchInput" onkeyup="searchGames()" placeholder="Buscar jogos, provedores...">
            </div>
        </div>

        <!-- Sport/Category Quick Navigation -->
        @php
            // 1. Buscamos a categoria pelo slug ou title
            $navCategory = \App\Models\GameCategory::where('slug', 'sports')
                ->orWhere('title', 'nav_esportes')
                ->orWhere('title', 'Navegação de Esportes')
                ->first();

            $navItems = collect();
            
            // 2. Se achou a categoria, pegamos os botões que pertencem a ela
            // Como vimos que 'category_id' não existe na tabela category_buttons,
            // vamos usar o campo 'group' que é o padrão de vinculação deste site.
            $navItems = \App\Models\CategoryButton::where('is_active', true)
                ->where(function($q) use ($navCategory) {
                    $q->where('group', 'nav_esportes')
                      ->orWhere('group', 'sports')
                      ->orWhere('group', 'Navegação de Esportes');
                    
                    if($navCategory) {
                        $q->orWhere('group', $navCategory->slug)
                          ->orWhere('group', $navCategory->title);
                    }
                })
                ->orderBy('order', 'asc')
                ->get();
        @endphp
        
        <!-- DEBUG: Total de botões: {{ $navItems->count() }} -->
        
        @if($navItems->count() > 0)
            <div class="quick-nav-wrapper">
                <div class="quick-nav-scroll">
                    @foreach($navItems as $item)
                        @php
                            $clickAction = '';
                            if ($item->action_type === 'link') {
                                $clickAction = "window.location.href='" . $item->action_value . "'";
                            } elseif ($item->action_type === 'scroll') {
                                $clickAction = "scrollToSection('" . $item->action_value . "')";
                            } elseif ($item->action_type === 'alert') {
                                $clickAction = "alert('" . addslashes($item->action_value) . "')";
                            } elseif ($item->action_type === 'play') {
                                if (!empty($item->distribution)) {
                                    $clickAction = "launchExternalGame('" . $item->slug . "', this)";
                                } else {
                                    $clickAction = "checkAuthAndPlay('" . $item->action_value . "')";
                                }
                            }
                        @endphp
                        <div class="quick-nav-item" onclick="{{ $clickAction }}">
                            <div class="quick-nav-icon">
                                @if($item->image_path)
                                    <img src="{{ asset($item->image_path) }}" alt="{{ $item->title }}" style="width: 100%; height: 100%; object-fit: contain;">
                                @elseif($item->icon_class && str_contains($item->icon_class, '<svg'))
                                    {!! $item->icon_class !!}
                                @elseif($item->icon_class)
                                    <i class="{{ $item->icon_class }}"></i>
                                @else
                                    <i class="fa-solid fa-dice"></i>
                                @endif
                            </div>
                            <span class="quick-nav-text">{{ $item->title }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Mobile "Maiores Ganhos" Real-time Feed -->
        <div class="mobile-win-feed-wrapper">
            <div class="sidebar-right-header">
                <div class="header-win-title">
                    <h3>MAIORES GANHOS</h3>
                    <p>CONFIRA AS MELHORES PREMIAÇÕES DOS ÚLTIMOS DIAS</p>
                </div>
                <div class="trophy-icon">
                    <img src="{{ asset('imagens/trofeu.png') }}" alt="Troféu" class="trophy-img">
                </div>
            </div>
            
            <div class="win-feed-container">
                <div class="win-feed">
                    @php
                        $mobileWinCards = [
                            ['user' => 'LUANA AR**', 'amount' => 'R$ 7.532,50'],
                            ['user' => 'MARIA ED**', 'amount' => 'R$ 7.000,00'],
                            ['user' => 'HENRIQUE DE**', 'amount' => 'R$ 6.100,00'],
                        ];
                    @endphp
                    @foreach($mobileWinCards as $i => $card)
                        @if($winFeedGames->get($i))
                            @php $g = $winFeedGames->get($i); @endphp
                            <div class="win-card">
                                <div class="win-card-icon {{ $g['class'] }}"><img src="{{ $g['image'] }}" class="win-game-thumb"></div>
                                <div class="win-card-details">
                                    <span class="win-username">{{ $card['user'] }}</span>
                                    <span class="win-game">{{ $g['title'] }}</span>
                                    <span class="win-amount">{{ $card['amount'] }}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Dynamic Categories -->
        @foreach($categories as $category)
            @php
                $games = \App\Models\CategoryButton::where('group', $category->slug)
                    ->where('is_active', true)
                    ->orderBy('order', 'asc')
                    ->get();
            @endphp
            @if($games->count() > 0)
                @if($category->layout_type === 'recommended' || $category->layout_type === 'payouts')
                    <!-- JOGOS RECOMENDADOS OU ANTIGO PAYOUTS (AGORA GRID) -->
                    <section class="home-section" data-layout="recommended" id="recommended-section-{{ $category->id }}">
                        <div class="section-header-title">
                            <h2 style="font-size: 1.1rem; font-weight: 800; display: flex; align-items: center; gap: 10px;">
                                @if($category->icon_class && str_contains($category->icon_class, '<svg'))
                                    <span class="category-icon-svg">{!! $category->icon_class !!}</span>
                                @elseif($category->icon_class)
                                    <i class="{{ $category->icon_class }} category-icon-fa"></i>
                                @else
                                    <i class="fa-solid fa-dice category-icon-fa"></i>
                                @endif
                                {{ strtoupper($category->title) }}
                            </h2>
                            <div style="display: flex; gap: 8px; align-items: center;">
                                @if($category->show_ver_todos)
                                    <a href="javascript:void(0)" onclick="toggleCategoryExpand('recommendedGrid-{{ $category->id }}', this)" class="ver-todos-btn">VER TODOS <i class="fa-solid fa-chevron-right text-xs"></i></a>
                                @endif
                                <div class="carousel-nav-arrows" id="arrows-recommendedGrid-{{ $category->id }}">
                                    <button class="arrow-btn" onclick="scrollSection('recommendedGrid-{{ $category->id }}', -1)"><i class="fa-solid fa-chevron-left"></i></button>
                                    <button class="arrow-btn" onclick="scrollSection('recommendedGrid-{{ $category->id }}', 1)"><i class="fa-solid fa-chevron-right"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="games-grid" id="recommendedGrid-{{ $category->id }}">
                            @php
                                $classMap = [
                                    'rec_aviator' => 'aviator',
                                    'rec_tiger' => 'tiger',
                                    'rec_mines' => 'mines',
                                    'rec_roleta' => 'roulette',
                                    'rec_ox' => 'ox',
                                    'rec_bacbo' => 'bacbo',
                                ];
                            @endphp
                            @foreach($games as $game)
                                @php
                                    $clickAction = '';
                                    if ($game->action_type === 'link') {
                                        $clickAction = "window.location.href='" . $game->action_value . "'";
                                    } elseif ($game->action_type === 'scroll') {
                                        $clickAction = "scrollToSection('" . $game->action_value . "')";
                                    } elseif ($game->action_type === 'alert') {
                                        $clickAction = "alert('" . addslashes($game->action_value) . "')";
                                    } elseif ($game->action_type === 'play') {
                                        if (!empty($game->distribution)) {
                                            $clickAction = "launchExternalGame('" . $game->slug . "', this)";
                                        } else {
                                            $clickAction = "checkAuthAndPlay('" . $game->action_value . "')";
                                        }
                                    }
                                    $cardClass = $classMap[$game->slug] ?? $game->slug;
                                    $cardStyle = $clickAction ? 'cursor: pointer' : 'cursor: default';
                                @endphp
                                <div class="game-card item-{{ $cardClass }}" onclick="{{ $clickAction }}" style="{{ $cardStyle }}">
                                    <div class="game-thumbnail">
                                        <button class="card-fav-btn" onclick="event.stopPropagation(); toggleLike(this)">
                                            <i class="fa-regular fa-heart"></i>
                                        </button>
                                        @php
                                            $onlineCount = $game->badge_value;
                                            $onlineCount = str_replace(['º', '1', '2', '3', '4', '5', '6', '7', '8', '9'], '', $onlineCount);
                                            
                                            // Forçamos um número entre 110 e 195 para essa seção especificamente
                                            $seed = crc32($game->id . 'payout_online_seed');
                                            $onlineCount = (abs($seed) % 85) + 110;
                                        @endphp
                                        <div class="card-online-count">
                                            <span class="online-dot"></span>
                                            {{ $onlineCount }}
                                        </div>
                                        @if($game->image_path)
                                            <img src="{{ asset($game->image_path) }}" alt="{{ $game->title }}" class="game-img-cover">
                                        @endif
                                        <div class="game-play-hover" onclick="{{ $clickAction }}">
                                            <span class="play-btn-custom"><i class="fa-solid fa-play"></i> JOGAR</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                @elseif($category->layout_type === 'weekly')
                    <!-- OS JOGOS MAIS JOGADOS DA SEMANA (ESTILO VERA.BET DEFINITIVO) -->
                    <style>
                        .vb-grid-wrapper {
                            display: grid !important;
                            grid-template-columns: repeat(8, 1fr) !important;
                            grid-template-areas: 
                                "main main side side side side2 side2 side2"
                                "main main bot1 bot1 bot2 bot2 bot3 bot3" !important;
                            gap: 10px !important;
                            width: 100% !important;
                            margin-bottom: 30px !important;
                        }
                        .vb-item {
                            position: relative !important;
                            border-radius: 10px !important;
                            overflow: hidden !important;
                            background: #0c1a15 !important;
                            cursor: pointer !important;
                            display: flex !important;
                        }
                        .vb-item img {
                            width: 100% !important;
                            height: 100% !important;
                            object-fit: cover !important;
                            display: block !important;
                            transition: transform 0.4s ease !important;
                        }
                        .vb-item:hover img {
                            transform: scale(1.06) !important;
                        }
                        
                        /* Mapeamento das Áreas */
                        .vb-area-0 { grid-area: main !important; }
                        .vb-area-1 { grid-area: side !important; aspect-ratio: 3.37 / 1.05 !important; }
                        .vb-area-2 { grid-area: side2 !important; aspect-ratio: 3.37 / 1.05 !important; }
                        .vb-area-3 { grid-area: bot1 !important; aspect-ratio: 2 / 0.8 !important; }
                        .vb-area-4 { grid-area: bot2 !important; aspect-ratio: 2 / 0.8 !important; }
                        .vb-area-5 { grid-area: bot3 !important; aspect-ratio: 2 / 0.8 !important; }

                        @media (max-width: 768px) {
                            .vb-grid-wrapper {
                                grid-template-columns: repeat(6, 1fr) !important;
                                grid-template-rows: 55px 55px 46px !important;
                                grid-template-areas:
                                    "main main main side side side"
                                    "main main main side2 side2 side2"
                                    "bot1 bot1 bot2 bot2 bot3 bot3" !important;
                                gap: 8px !important;
                            }
                            .vb-area-0, .vb-area-1, .vb-area-2, .vb-area-3, .vb-area-4, .vb-area-5 {
                                aspect-ratio: unset !important;
                            }
                        }
                    </style>

                    <section class="home-section" data-layout="weekly" id="weekly-section-{{ $category->id }}">
                        <div class="section-header-title">
                            <h2 style="font-size: 1.1rem; font-weight: 800; display: flex; align-items: center; gap: 10px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                @if($category->icon_class && str_contains($category->icon_class, '<svg'))
                                    <span class="category-icon-svg">{!! $category->icon_class !!}</span>
                                @elseif($category->icon_class)
                                    <i class="{{ $category->icon_class }} category-icon-fa"></i>
                                @else
                                    <i class="fa-solid fa-fire category-icon-fa"></i>
                                @endif
                                {{ strtoupper($category->title) }}
                            </h2>
                            @if($category->show_ver_todos)
                                <a href="javascript:void(0)" onclick="toggleCategoryExpand('weeklyGrid-{{ $category->id }}', this)" class="ver-todos-btn">VER TODOS <i class="fa-solid fa-chevron-right text-xs"></i></a>
                            @endif
                        </div>

                        <div class="vb-grid-wrapper" id="weeklyGrid-{{ $category->id }}">
                            @foreach($games as $index => $game)
                                @if($index > 5) @continue @endif
                                @php
                                    $clickAction = '';
                                    if ($game->action_type === 'link') {
                                        $clickAction = "window.location.href='" . $game->action_value . "'";
                                    } elseif ($game->action_type === 'scroll') {
                                        $clickAction = "scrollToSection('" . $game->action_value . "')";
                                    } elseif ($game->action_type === 'alert') {
                                        $clickAction = "alert('" . addslashes($game->action_value) . "')";
                                    } elseif ($game->action_type === 'play') {
                                        if (!empty($game->distribution)) {
                                            $clickAction = "launchExternalGame('" . $game->slug . "', this)";
                                        } else {
                                            $clickAction = "checkAuthAndPlay('" . $game->action_value . "')";
                                        }
                                    }
                                @endphp
                                <div class="vb-item vb-area-{{ $index }}" onclick="{{ $clickAction }}">
                                    @php
                                        $onlineCountWeekly = $game->badge_value;
                                        $onlineCountWeekly = str_replace('º', '', $onlineCountWeekly);
                                        if (!$onlineCountWeekly || !is_numeric(preg_replace('/[^0-9]/', '', $onlineCountWeekly))) {
                                            $seed = crc32($game->id . 'weekly_v5');
                                            $onlineCountWeekly = (abs($seed) % 150) + 50;
                                        }
                                    @endphp
                                    <div class="card-online-count" style="z-index: 10; font-size: 9px; padding: 2px 6px; top: 8px; right: 8px; background: rgba(0,0,0,0.6); border: 1px solid rgba(255,255,255,0.1);">
                                        <span class="online-dot"></span>
                                        {{ $onlineCountWeekly }}
                                    </div>
                                    
                                    <button class="card-fav-btn" onclick="event.stopPropagation(); toggleLike(this)" style="z-index: 10; width: 26px; height: 26px; top: 8px; left: 8px; background: rgba(0,0,0,0.4);">
                                        <i class="fa-regular fa-heart" style="font-size: 11px;"></i>
                                    </button>

                                    @if($game->image_path)
                                        <img src="{{ asset($game->image_path) }}" alt="{{ $game->title }}">
                                    @endif
                                    
                                    <div class="game-play-hover" style="z-index: 5; background: rgba(0,0,0,0.2);">
                                        <span class="play-btn-custom" style="transform: skewX(-15deg) scale(0.8); background: #00e676; color: #000; padding: 6px 12px; font-weight: 900; border-radius: 5px; font-size: 10px; box-shadow: 0 0 15px rgba(0,230,118,0.3);"><i class="fa-solid fa-play"></i> JOGAR</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>

                @elseif($category->layout_type === 'live')
                    <!-- CASSINO AO VIVO POPULAR -->
                    <section class="home-section" data-layout="live" id="live-section-{{ $category->id }}">
                        <div class="section-header-title">
                            <h2 style="font-size: 1.1rem; font-weight: 800; display: flex; align-items: center; gap: 10px;">
                                @if($category->icon_class && str_contains($category->icon_class, '<svg'))
                                    <span class="category-icon-svg">{!! $category->icon_class !!}</span>
                                @elseif($category->icon_class)
                                    <i class="{{ $category->icon_class }} category-icon-fa"></i>
                                @else
                                    <i class="fa-solid fa-tower-broadcast category-icon-fa"></i>
                                @endif
                                {{ strtoupper($category->title) }}
                            </h2>
                            <div style="display: flex; gap: 8px; align-items: center;">
                                @if($category->show_ver_todos)
                                    <a href="javascript:void(0)" onclick="toggleCategoryExpand('liveGrid-{{ $category->id }}', this)" class="ver-todos-btn">VER TODOS <i class="fa-solid fa-chevron-right text-xs"></i></a>
                                @endif
                                <div class="carousel-nav-arrows" id="arrows-liveGrid-{{ $category->id }}">
                                    <button class="arrow-btn" onclick="scrollSection('liveGrid-{{ $category->id }}', -1)"><i class="fa-solid fa-chevron-left"></i></button>
                                    <button class="arrow-btn" onclick="scrollSection('liveGrid-{{ $category->id }}', 1)"><i class="fa-solid fa-chevron-right"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="games-grid" id="liveGrid-{{ $category->id }}">
                            @php
                                $classMap = [
                                    'live_roulette' => 'roulette',
                                    'live_bacbo' => 'bacbo',
                                ];
                            @endphp
                            @foreach($games as $game)
                                @php
                                    $clickAction = '';
                                    if ($game->action_type === 'link') {
                                        $clickAction = "window.location.href='" . $game->action_value . "'";
                                    } elseif ($game->action_type === 'scroll') {
                                        $clickAction = "scrollToSection('" . $game->action_value . "')";
                                    } elseif ($game->action_type === 'alert') {
                                        $clickAction = "alert('" . addslashes($game->action_value) . "')";
                                    } elseif ($game->action_type === 'play') {
                                        if (!empty($game->distribution)) {
                                            $clickAction = "launchExternalGame('" . $game->slug . "', this)";
                                        } else {
                                            $clickAction = "checkAuthAndPlay('" . $game->action_value . "')";
                                        }
                                    }
                                    $cardClass = $classMap[$game->slug] ?? $game->slug;
                                    $cardStyle = $clickAction ? 'cursor: pointer' : 'cursor: default';
                                @endphp
                                <div class="game-card item-{{ $cardClass }}" onclick="{{ $clickAction }}" style="{{ $cardStyle }}">
                                    <div class="game-thumbnail">
                                        <button class="card-fav-btn" onclick="event.stopPropagation(); toggleLike(this)">
                                            <i class="fa-regular fa-heart"></i>
                                        </button>
                                        @php
                                            $onlineCountLive = $game->badge_value;
                                            $onlineCountLive = str_replace('º', '', $onlineCountLive);
                                            
                                            if (!$onlineCountLive || !is_numeric(preg_replace('/[^0-9]/', '', $onlineCountLive))) {
                                                $seed = crc32($game->id . 'live_seed');
                                                $onlineCountLive = (abs($seed) % 150) + 30;
                                            } elseif (is_numeric($onlineCountLive) && $onlineCountLive > 200) {
                                                $onlineCountLive = 170 + ($game->id % 30);
                                            }
                                        @endphp
                                        <div class="card-online-count">
                                            <span class="online-dot"></span>
                                            {{ $onlineCountLive }}
                                        </div>
                                        @if($game->image_path)
                                            <img src="{{ asset($game->image_path) }}" alt="{{ $game->title }}" class="game-img-cover">
                                        @endif
                                        <div class="game-play-hover" onclick="{{ $clickAction }}">
                                            <span class="play-btn-custom"><i class="fa-solid fa-play"></i> JOGAR</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endif
        @endforeach

        <!-- BANNER DE SUPORTE CUSTOMIZÁVEL -->
        @php
            $supportBannerImage = \App\Models\Setting::get('support_banner_image');
            $supportBannerTitle = \App\Models\Setting::get('support_banner_title', 'Suporte');
            $supportBannerSubtitle = \App\Models\Setting::get('support_banner_subtitle', 'Entre em contato se ainda tiver dúvidas');
            $supportBannerBadge = \App\Models\Setting::get('support_banner_badge_text', '24/7');
            $supportBannerLink = \App\Models\Setting::get('support_banner_link_url', 'javascript:void(0)');
        @endphp
        <div class="home-support-banner" style="margin-left: auto; margin-right: auto; width: 100%; max-width: 1200px;">
            <div class="support-banner-content">
                <div class="support-banner-title-row">
                    <span class="support-banner-title">{{ $supportBannerTitle }}</span>
                    <span class="support-banner-badge">{{ $supportBannerBadge }}</span>
                </div>
                <span class="support-banner-subtitle">{{ $supportBannerSubtitle }}</span>
            </div>

            <div class="support-banner-image-container">
                @if($supportBannerImage)
                    <img src="{{ asset($supportBannerImage) }}" alt="Suporte" class="support-banner-img">
                @else
                    <img src="{{ asset('imagens/suporte_default.png') }}" alt="Suporte" class="support-banner-img">
                @endif
            </div>

            <a href="{{ $supportBannerLink }}" class="support-banner-button">
                Fale com o suporte
            </a>
        </div>
    </div>

    <!-- Right Sidebar "Maiores Ganhos" -->
    <aside class="sidebar-right">
        <div class="sidebar-right-header">
            <div class="header-win-title">
                <h3>MAIORES GANHOS</h3>
                <p>CONFIRA AS MELHORES<br>PREMIAÇÕES DOS ÚLTIMOS<br>DIAS</p>
            </div>
            <div class="trophy-icon">
                <img src="{{ asset('imagens/trofeu.png') }}" alt="Troféu" class="trophy-img">
            </div>
        </div>
        
        <!-- Real-time scroll feed -->
        <div class="win-feed-container">
            <div class="win-feed" id="winFeed">
                @php
                    $desktopWinCards = [
                        ['user' => 'LUANA AR**', 'amount' => 'R$ 7.532,50'],
                        ['user' => 'MARIA ED**', 'amount' => 'R$ 7.000,00'],
                        ['user' => 'HENRIQUE DE**', 'amount' => 'R$ 6.100,00'],
                        ['user' => 'RODRIGO OL**', 'amount' => 'R$ 13.467,95'],
                        ['user' => 'BRUNO FR**', 'amount' => 'R$ 12.500,00'],
                    ];
                @endphp
                @foreach($desktopWinCards as $i => $card)
                    @if($winFeedGames->get($i))
                        @php($g = $winFeedGames->get($i))
                        <div class="win-card">
                            <div class="win-card-icon {{ $g['class'] }}"><img src="{{ $g['image'] }}" class="win-game-thumb"></div>
                            <div class="win-card-details">
                                <span class="win-username">{{ $card['user'] }}</span>
                                <span class="win-game">{{ $g['title'] }}</span>
                                <span class="win-amount">{{ $card['amount'] }}</span>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </aside>
</div>
<script>window.winFeedGamesPool = @json($winFeedGames->values());</script>
    <script>
        function scrollSection(gridId, direction) {
            const grid = document.getElementById(gridId);
            if (!grid) return;
            const scrollAmount = window.innerWidth <= 768 ? 200 : 350;
            grid.scrollBy({
                left: direction * scrollAmount,
                behavior: 'smooth'
            });
        }

        function toggleCategoryExpand(gridId, button) {
            const grid = document.getElementById(gridId);
            if (!grid) return;

            grid.classList.toggle('expanded-grid');
            const isExpanded = grid.classList.contains('expanded-grid');
            const arrows = document.getElementById('arrows-' + gridId);

            if (isExpanded) {
                button.innerHTML = 'VER MENOS <i class="fa-solid fa-chevron-up text-xs"></i>';
                if (arrows) arrows.style.display = 'none';
            } else {
                button.innerHTML = 'VER TODOS <i class="fa-solid fa-chevron-right text-xs"></i>';
                if (arrows) arrows.style.display = 'flex';
            }
        }
    </script>
@endsection
