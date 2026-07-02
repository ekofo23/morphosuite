<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>MorphoSuite</title>

    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            /* Configuration dynamique depuis la base de données avec valeur de repli d'origine */
            --color-dark: {{ $appSettings['theme_sidebar_color'] ?? '#141619' }}; 
            --color-sidebar-hover: rgba(255, 255, 255, 0.08);
            --color-bg-light: #F8F9FA;     
            --color-gold: {{ $appSettings['theme_gold_color'] ?? '#D4AF37' }}; 
            
            /* Variable pour la couleur de fond des blocs, cartes et formulaires */
            --color-card-bg: {{ $appSettings['theme_card_color'] ?? '#FFFFFF' }};
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--color-bg-light);
            color: #111111;
            -webkit-font-smoothing: antialiased;
            position: relative;
            min-height: 100vh;
        }

        /* INJECTION DE LA COULEUR DYNAMIQUE SUR LES BLOCS ET CARTES */
        .card, 
        .tab-content, 
        .bg-white,
        .table,
        .form-control:not(.form-control-color),
        .form-select {
            background-color: var(--color-card-bg) !important;
        }

        /* LOGIQUE INTELLIGENTE : Adaptation des textes si le fond devient sombre ou noir */
        @php
            $hexColor = $appSettings['theme_card_color'] ?? '#FFFFFF';
            $hexColor = str_replace('#', '', $hexColor);
            
            // Extraction des valeurs RVB
            $r = hexdec(substr($hexColor, 0, 2));
            $g = hexdec(substr($hexColor, 2, 2));
            $b = hexdec(substr($hexColor, 4, 2));
            
            // Calcul de la luminance (Standard international)
            $luminance = ($r * 0.299 + $g * 0.587 + $b * 0.114);
        @endphp

        @if($luminance < 140)
            /* 1. Si les cartes sont sombres, on force TOUT le texte intérieur en blanc/clair */
            .card, .tab-content, .table, .card .form-label, 
            .card h1, .card h2, .card h3, .card h4, .card h5, .card h6, 
            .card th, .card td, .card p, .card span, .card li, .card div:not(.user-avatar) {
                color: #FFFFFF !important;
            }
            
            /* 2. Correction des titres de haut de page (qui sont hors des cartes) pour rester sombres et lisibles sur fond blanc/image claire */
            .main-content > h1, .main-content > h2, .main-content > div > h1, .main-content > div > h2, .main-content p.text-muted {
                color: #1a1a1a !important;
                text-shadow: 0px 1px 2px rgba(255, 255, 255, 0.6);
            }

            /* Adaptation des champs de saisie (inputs et select) pour le mode sombre */
            .form-control, .form-select, .form-control:focus, .form-select:focus {
                color: #FFFFFF !important;
                border-color: rgba(255, 255, 255, 0.25) !important;
            }
            
            /* Correction de la visibilité du texte d'exemple (placeholder) */
            .form-control::placeholder {
                color: rgba(255, 255, 255, 0.5) !important;
                opacity: 1;
            }
            
            /* Textes secondaires plus clairs et lisibles sur fond noir */
            .card .text-muted, .card .form-text, .card small.text-muted {
                color: #CBCDCF !important;
            }
            
            /* Ajustement des bordures internes pour éviter les lignes blanches dures */
            .border, .border-bottom, .border-top, td, th, hr {
                border-color: rgba(255, 255, 255, 0.12) !important;
            }
            
            /* Ajustement des onglets inactifs pour la configuration */
            .nav-tabs .nav-link:not(.active) {
                color: rgba(255, 255, 255, 0.6) !important;
                background-color: rgba(255, 255, 255, 0.05) !important;
                border-color: rgba(255, 255, 255, 0.1) !important;
            }
        @else
            /* Si le fond est clair/blanc, on garde la configuration classique */
            .form-control, .form-select {
                color: #111111 !important;
                border-color: rgba(0, 0, 0, 0.15) !important;
            }
        @endif

        .app-wrapper {
            display: flex;
            min-height: 100vh;
            position: relative;
            z-index: 1; /* Permet au contenu de se placer au-dessus du fond multimédia */
        }

        /* Sidebar fidèle à la maquette, dynamique et DÉFILANTE */
        .sidebar {
            width: 260px;
            background-color: var(--color-dark) !important;
            color: #FFFFFF;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            padding: 2.5rem 1.25rem 1.5rem 1.25rem;
            
            /* Activation du défilement vertical intelligent */
            overflow-y: auto;
            max-height: 100vh;
        }

        /* Masquage esthétique de la scrollbar pour préserver le design */
        .sidebar::-webkit-scrollbar {
            width: 0px;
            background: transparent;
        }
        .sidebar {
            scrollbar-width: none; /* Firefox */
        }

        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 2.5rem;
            background: transparent !important; /* Force la transparence pour laisser voir le fond */
        }

        /* En-tête de la maquette : ATELIER SUR-MESURE */
        .brand-container {
            text-align: center;
            margin-bottom: 3rem;
        }
        .brand-logo-a {
            font-family: 'Playfair Display', serif;
            font-size: 2.8rem;
            font-weight: 400;
            color: var(--color-gold);
            line-height: 1;
            margin-bottom: 0.2rem;
        }
        .brand-title-text {
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #FFFFFF;
            line-height: 1.1;
        }
        .brand-sub-text {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 4px;
            color: #A0AEC0;
            font-weight: 500;
            margin-top: 0.3rem;
        }

        /* Liens de la navigation */
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.65);
            font-size: 0.95rem;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.2s ease;
            margin-bottom: 0.3rem;
            text-decoration: none;
        }

        .sidebar .nav-link i {
            font-size: 1.1rem;
            opacity: 0.7;
        }

        .sidebar .nav-link:hover {
            color: #FFFFFF;
            background-color: var(--color-sidebar-hover);
        }

        .sidebar .nav-link.active {
            color: #FFFFFF;
            background-color: rgba(255, 255, 255, 0.12);
            font-weight: 500;
        }

        /* Bas de la Sidebar (Profil) */
        .sidebar-footer {
            margin-top: auto;
            padding-top: 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            border: 2px solid rgba(255, 255, 255, 0.2);
            background-color: #4A5568;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: #FFFFFF;
        }

        @media (max-width: 992px) {
            .sidebar { width: 75px; padding: 2rem 0.5rem; }
            .brand-title-text, .brand-sub-text, .sidebar .nav-link span, .user-info, .dropdown-toggle::after { display: none; }
            .main-content { margin-left: 75px; padding: 1.5rem; }
            .sidebar .nav-link { justify-content: center; padding: 0.8rem; }
        }
    </style>
</head>
<body>

    @if(isset($appSettings['app_background_type']) && $appSettings['app_background_type'] !== 'none' && isset($appSettings['app_background_file']))
        <div class="app-background-container" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 0; overflow: hidden; pointer-events: none; background-color: rgba(0, 0, 0, 0.05); backdrop-filter: blur(2px); -webkit-backdrop-filter: blur(2px);">
            
            @if($appSettings['app_background_type'] === 'image')
                <img src="{{ asset('storage/' . ltrim($appSettings['app_background_file'], '/')) }}" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.40;">
            @elseif($appSettings['app_background_type'] === 'video')
                <video autoplay muted loop playsinline src="{{ asset('storage/' . ltrim($appSettings['app_background_file'], '/')) }}" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.55;">
                </video>
            @endif

        </div>
    @endif

    <div id="app" class="app-wrapper">
        
        <nav class="sidebar">
            <div class="brand-container">
                <div class="brand-logo-a">LAM</div>
                <div class="brand-title-text">MorphoSuite</div>
                <div class="brand-sub-text">Sur-Mesure</div>
            </div>

            <ul class="nav flex-column w-100">
                @auth
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="bi bi-house-door"></i>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('orders') || (Request::is('orders/*') && !Request::is('orders/create')) ? 'active' : '' }}" href="{{ route('orders.index') }}">
                            <i class="bi bi-calendar-check"></i>
                            <span>Commandes</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('orders/create') ? 'active' : '' }}" href="{{ route('orders.create') }}">
                            <i class="bi bi-rulers"></i>
                            <span>Mesures</span>
                        </a>
                    </li>

                    @if(Auth::user()->hasRole('admin'))
                        <li class="nav-item">
                            <a class="nav-link {{ Request::is('admin/employees*') ? 'active' : '' }}" href="{{ route('admin.employees.index') }}">
                                <i class="bi bi-people"></i>
                                <span>Employés</span>
                            </a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-layers"></i>
                            <span>Modèles</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-calendar3"></i>
                            <span>Calendrier</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                            <i class="bi bi-bar-chart-line"></i>
                            <span>Rapports</span>
                        </a>
                    </li>
                    <li class="nav-item">
                       <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                            <i class="bi bi-gear"></i>
                            <span>Paramètres</span>
                        </a>
                    </li>
                @endauth

                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right"></i> <span>Connexion</span></a>
                    </li>
                @endauth
            </ul>

            @auth
                <div class="sidebar-footer">
                    <div class="user-profile dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle w-100 text-white" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar">
                                {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                            </div>
                            <div class="user-info ms-3 text-truncate">
                                <div class="fw-semibold small lh-1 text-white">{{ Auth::user()->name }}</div>
                                <small class="text-muted text-capitalize" style="font-size: 11px;">
                                    {{ Auth::user()->role ? Auth::user()->role->name : 'Aucun rôle' }}
                                </small>
                            </div>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="userDropdown">
                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    Déconnexion
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            @endauth
        </nav>

        <main class="main-content">
            @yield('content')
        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>