<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ \App\Models\Setting::get('site_name', 'Vorex') }} - Painel Administrativo</title>
    <link rel="icon" type="image/x-icon" href="{{ \App\Models\Setting::get('site_favicon') ? asset(\App\Models\Setting::get('site_favicon')) : asset('favicon.ico') }}">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    @yield('styles')
</head>
<body>
    <div class="admin-layout-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar">
            <div class="admin-sidebar-header" style="padding: 20px;">
                <div class="admin-logo" style="display: flex; flex-direction: column; align-items: flex-start; justify-content: center; width: 100%;">
                    @if(\App\Models\Setting::get('site_logo'))
                        <img src="{{ asset(\App\Models\Setting::get('site_logo')) }}" alt="Logo" style="max-height: 48px; max-width: 100%; object-fit: contain;">
                    @else
                        <span class="logo-text">{{ strtoupper(\App\Models\Setting::get('site_name', 'Vorex')) }}</span>
                        <span class="logo-sub">PAINEL ADMIN</span>
                    @endif
                </div>
            </div>



            <nav class="admin-sidebar-menu">
                <!-- Navigation section: Overview -->
                <div class="admin-menu-section">
                    <ul class="admin-menu-list">
                        <li class="admin-menu-item {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="fa-solid fa-table-cells-large"></i>
                                <span>Painel de Controle</span>
                            </a>
                        </li>
                        <li class="admin-menu-item {{ Route::is('admin.metrics') ? 'active' : '' }}">
                            <a href="{{ route('admin.metrics') }}">
                                <i class="fa-solid fa-chart-simple"></i>
                                <span>Métricas</span>
                            </a>
                        </li>
                        <li class="admin-menu-item {{ Request::is('admin/settings*') ? 'active' : '' }}">
                            <a href="{{ route('admin.settings') }}">
                                <i class="fa-solid fa-gear"></i>
                                <span>Configuração</span>
                            </a>
                        </li>
                        <li class="admin-menu-item">
                            <a href="#" onclick="alert('Funcionalidade demonstrativa')">
                                <i class="fa-solid fa-bullhorn"></i>
                                <span>Promoções</span>
                            </a>
                        </li>
                        <li class="admin-menu-item {{ Request::is('admin/personalization*') ? 'active' : '' }}">
                            <a href="{{ route('admin.personalization') }}">
                                <i class="fa-solid fa-palette"></i>
                                <span>Personalização</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Navigation section: Gestão Financeira -->
                <div class="admin-menu-section">
                    <h4 class="admin-menu-section-title">Gestão Financeira</h4>
                    <ul class="admin-menu-list">
                        <li class="admin-menu-item {{ Request::is('admin/withdrawals*') ? 'active' : '' }}">
                            <a href="{{ route('admin.withdrawals') }}">
                                <i class="fa-regular fa-circle-dot"></i>
                                <span>Saques</span>
                            </a>
                        </li>
                        <li class="admin-menu-item {{ Request::is('admin/deposits*') ? 'active' : '' }}">
                            <a href="{{ route('admin.deposits') }}">
                                <i class="fa-regular fa-circle-dot"></i>
                                <span>Depósitos</span>
                            </a>
                        </li>
                        <li class="admin-menu-item">
                            <a href="#" onclick="alert('Todas as apostas podem ser visualizadas a nível de usuário ou no banco de dados.')">
                                <i class="fa-regular fa-circle-dot"></i>
                                <span>Apostas</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Navigation section: Geral -->
                <div class="admin-menu-section">
                    <h4 class="admin-menu-section-title">Geral</h4>
                    <ul class="admin-menu-list">
                        <li class="admin-menu-item {{ Request::is('admin/users*') ? 'active' : '' }}">
                            <a href="{{ route('admin.users') }}">
                                <i class="fa-regular fa-user"></i>
                                <span>Usuários</span>
                            </a>
                        </li>
                        <li class="admin-menu-item {{ Request::is('admin/kyc*') ? 'active' : '' }}">
                            <a href="{{ route('admin.kyc') }}">
                                <i class="fa-solid fa-user-shield"></i>
                                <span>Verificações KYC</span>
                            </a>
                        </li>
                        <li class="admin-menu-item {{ Request::is('admin/categories*') ? 'active' : '' }}">
                            <a href="{{ route('admin.categories') }}">
                                <i class="fa-regular fa-folder"></i>
                                <span>Categorias</span>
                            </a>
                        </li>
                        <li class="admin-menu-item {{ Request::is('admin/games*') ? 'active' : '' }}">
                            <a href="{{ route('admin.games') }}">
                                <i class="fa-solid fa-gamepad"></i>
                                <span>Gestão de Jogos</span>
                            </a>
                        </li>
                        <li class="admin-menu-item {{ Request::is('admin/coupons*') ? 'active' : '' }}">
                            <a href="{{ route('admin.coupons') }}">
                                <i class="fa-solid fa-tag"></i>
                                <span>Gestão de Cupons</span>
                            </a>
                        </li>
                        <li class="admin-menu-item {{ Request::is('admin/vip-levels*') ? 'active' : '' }}">
                            <a href="{{ route('admin.vip-levels') }}">
                                <i class="fa-solid fa-crown"></i>
                                <span>Níveis VIP</span>
                            </a>
                        </li>
                        <li class="admin-menu-item {{ Request::is('admin/tournaments*') ? 'active' : '' }}">
                            <a href="{{ route('admin.tournaments') }}">
                                <i class="fa-solid fa-trophy"></i>
                                <span>Torneios</span>
                            </a>
                        </li>
                        <li class="admin-menu-item {{ Request::is('admin/missions*') ? 'active' : '' }}">
                            <a href="{{ route('admin.missions') }}">
                                <i class="fa-solid fa-crosshairs"></i>
                                <span>Missões</span>
                            </a>
                        </li>
                        <li class="admin-menu-item">
                            <a href="#" onclick="alert('Registros de auditoria do sistema')">
                                <i class="fa-regular fa-file-lines"></i>
                                <span>Auditoria</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Navigation section: Gestão de Afiliados -->
                <div class="admin-menu-section">
                    <h4 class="admin-menu-section-title">Gestão de Afiliados</h4>
                    <ul class="admin-menu-list">
                        <li class="admin-menu-item {{ Request::is('admin/affiliates*') ? 'active' : '' }}">
                            <a href="{{ route('admin.affiliates') }}">
                                <i class="fa-solid fa-file-invoice-dollar"></i>
                                <span>Relatório & Indicados</span>
                            </a>
                        </li>
                        <li class="admin-menu-item">
                            <a href="#" onclick="alert('Aprovação de novos afiliados')">
                                <i class="fa-solid fa-user-check"></i>
                                <span>Aprovar Afiliação</span>
                            </a>
                        </li>
                        <li class="admin-menu-item">
                            <a href="#" onclick="alert('Repasses financeiros para afiliados')">
                                <i class="fa-solid fa-dollar-sign"></i>
                                <span>Financeiro</span>
                            </a>
                        </li>
                        <li class="admin-menu-item">
                            <a href="#" onclick="alert('Configurações de comissão de afiliado')">
                                <i class="fa-solid fa-sliders"></i>
                                <span>Configurações</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="admin-sidebar-footer">
                <a href="{{ route('admin.logout') }}" class="admin-btn-logout">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Sair do Painel</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="admin-main-container">
            <!-- Top Header -->
            <header class="admin-top-header">
                <div class="admin-page-title">
                    <span style="font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">VISÃO GERAL</span>
                    <h1>@yield('page_title', 'Painel de Controle')</h1>
                </div>
                <div class="admin-header-actions">
                    <div class="admin-live-badge">
                        <span class="admin-live-dot"></span>
                        <span>Tempo Real</span>
                    </div>
                    @php
                        $startParam = request('start_date');
                        $endParam = request('end_date');
                        if ($startParam && $endParam) {
                            try {
                                $displayDate = \Carbon\Carbon::parse($startParam)->format('d/m/Y') . ' - ' . \Carbon\Carbon::parse($endParam)->format('d/m/Y');
                            } catch (\Exception $e) {
                                $displayDate = now()->subDays(30)->format('d/m/Y') . ' - ' . now()->format('d/m/Y');
                            }
                        } else {
                            $displayDate = now()->subDays(30)->format('d/m/Y') . ' - ' . now()->format('d/m/Y');
                        }
                    @endphp
                    <div class="admin-date-picker" id="admin-date-range-picker" style="cursor: pointer;">
                        <i class="fa-regular fa-calendar"></i>
                        <span id="admin-date-range-display">{{ $displayDate }}</span>
                        <i class="fa-solid fa-chevron-down" style="font-size: 10px;"></i>
                        <input type="hidden" id="admin-date-range-input" value="{{ $startParam ?? '' }} to {{ $endParam ?? '' }}">
                    </div>
                </div>
            </header>

            <!-- Inner Content -->
            <main class="admin-content-inner">
                @if (session('success'))
                    <div class="admin-alert admin-alert-success">
                        <i class="fa-solid fa-circle-check"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="admin-alert admin-alert-danger">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/pt.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const datePickerEl = document.getElementById('admin-date-range-picker');
            const dateInputEl = document.getElementById('admin-date-range-input');
            const displayEl = document.getElementById('admin-date-range-display');

            if (datePickerEl && dateInputEl) {
                flatpickr(datePickerEl, {
                    mode: "range",
                    locale: "pt",
                    dateFormat: "Y-m-d",
                    theme: "dark",
                    defaultDate: [
                        "{{ $startParam ?? now()->subDays(30)->format('Y-m-d') }}",
                        "{{ $endParam ?? now()->format('Y-m-d') }}"
                    ],
                    onClose: function(selectedDates, dateStr, instance) {
                        if (selectedDates.length === 2) {
                            const start = instance.formatDate(selectedDates[0], "Y-m-d");
                            const end = instance.formatDate(selectedDates[1], "Y-m-d");
                            
                            // Format display
                            const startDisplay = instance.formatDate(selectedDates[0], "d/m/Y");
                            const endDisplay = instance.formatDate(selectedDates[1], "d/m/Y");
                            displayEl.textContent = startDisplay + ' - ' + endDisplay;

                            // Redirect/Reload with parameters
                            const url = new URL(window.location.href);
                            url.searchParams.set('start_date', start);
                            url.searchParams.set('end_date', end);
                            window.location.href = url.toString();
                        }
                    }
                });
            }
        });
    </script>
    @yield('scripts')
</body>
</html>
