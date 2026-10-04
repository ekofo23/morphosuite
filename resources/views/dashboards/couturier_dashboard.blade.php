@extends('layouts.app')

@section('content')
<div class="container-fluid px-0" style="max-width: 1000px;">
    @if (session('status'))
        <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
            {{ session('status') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert">
            🚫 {{ session('error') }}
        </div>
    @endif

    <!-- EN-TÊTE DE BIENVENUE & FILTRES TEMPORELS -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5 pb-3 gap-3">
        <div>
            <!-- Titre modifié avec le doré initial harmonisé -->
            <h2 class="mb-1" style="font-family: 'Playfair Display', serif; font-weight: 700; color: #D4AF37 !important; font-size: 2.2rem;">Mon Espace Confection</h2>
            <p style="color: #ffffff !important; font-size: 0.9rem; margin-bottom: 0;">Bienvenue dans votre atelier personnel, {{ Auth::user()->name }}. Suivez vos performances en temps réel.</p>
        </div>
        
        <!-- BARRE DE FILTRES TEMPORELS COMPLÈTE -->
        @php
            $current_period = request('period', 'all');
        @endphp
        <div class="d-flex rounded-3 p-1" style="background-color: #262322; border: 1px solid rgba(255,255,255,0.08); flex-wrap: wrap;">
            <!-- 🔍 FILTRE DE LA JOURNÉE PARFAITEMENT INTÉGRÉ -->
            <a href="{{ route('home', ['period' => 'all']) }}" class="btn btn-sm px-3 py-1.5 fw-semibold transition-all {{ $current_period == 'all' ? 'active-filter' : 'text-white-50' }}">Tous</a>
           <a href="{{ route('home', ['period' => 'today']) }}" class="btn btn-sm px-3 py-1.5 fw-semibold transition-all {{ $current_period == 'today' ? 'active-filter' : 'text-white-50' }}">Aujourd'hui</a>
            <a href="{{ route('home', ['period' => 'week']) }}" class="btn btn-sm px-3 py-1.5 fw-semibold transition-all {{ $current_period == 'week' ? 'active-filter' : 'text-white-50' }}">Semaine</a>
            <a href="{{ route('home', ['period' => 'month']) }}" class="btn btn-sm px-3 py-1.5 fw-semibold transition-all {{ $current_period == 'month' ? 'active-filter' : 'text-white-50' }}">Mois</a>
            <a href="{{ route('home', ['period' => 'quarter']) }}" class="btn btn-sm px-3 py-1.5 fw-semibold transition-all {{ $current_period == 'quarter' ? 'active-filter' : 'text-white-50' }}">Trimestre</a>
            <a href="{{ route('home', ['period' => 'year']) }}" class="btn btn-sm px-3 py-1.5 fw-semibold transition-all {{ $current_period == 'year' ? 'active-filter' : 'text-white-50' }}">Annuel</a>
        </div>
    </div>

    <!-- STYLE CSS LOCAL POUR LE BOUTON ACTIF -->
    <style>
        /* Couleur du bouton de filtrage actif synchronisée avec le doré d'origine */
        .active-filter {
            background-color: #D4AF37 !important;
            color: #1A1818 !important;
            border-radius: 6px;
        }
        .transition-all {
            transition: all 0.2s ease-in-out;
            border: none;
            font-size: 0.8rem;
        }
    </style>

    <!-- TROIS CARTES STATISTIQUES PERSONNELLES -->
    <div class="row g-4 mb-5">
        <!-- TOTAL CONFECTIONS CONFIEES -->
        <div class="col-md-4">
            <div class="card border-0 p-4 h-100" style="background-color: #262322 !important; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                <div class="d-flex justify-content-between align-items-start">
                    <span style="letter-spacing: 0.5px; font-size: 0.75rem; font-weight: 700; color: #ffffff !important; text-transform: uppercase;">Mes Fiches</span>
                    <i class="bi bi-journal-scissors" style="font-size: 1.2rem; color: #D4AF37 !important;"></i>
                </div>
                <div class="mt-4">
                    <h2 class="fw-bold mb-1" style="font-size: 3rem; font-family: sans-serif; color: #ffffff !important;">{{ $stats['total_orders'] }}</h2>
                    <span style="font-size: 0.8rem; color: rgba(255,255,255,0.6) !important;">Attribuées sur la période</span>
                </div>
            </div>
        </div>

        <!-- EN ATTENTE DE TRAITEMENT -->
        <div class="col-md-4">
            <div class="card border-0 p-4 h-100" style="border-left: 4px solid #FF9800 !important; background-color: #262322 !important; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                <div class="d-flex justify-content-between align-items-start">
                    <span style="letter-spacing: 0.5px; font-size: 0.75rem; font-weight: 700; color: #ffffff !important; text-transform: uppercase;">En Cours / Attente</span>
                    <i class="bi bi-clock-history" style="color: #FF9800 !important; font-size: 1.2rem;"></i>
                </div>
                <div class="mt-4">
                    <h2 class="fw-bold mb-1" style="font-size: 3rem; font-family: sans-serif; color: #ffffff !important;">{{ $stats['pending_orders'] }}</h2>
                    <span style="font-size: 0.8rem; color: rgba(255,255,255,0.6) !important;">Modèles à confectionner</span>
                </div>
            </div>
        </div>

        <!-- CONFECTIONS TERMINÉES -->
        <div class="col-md-4">
            <div class="card border-0 p-4 h-100" style="border-left: 4px solid #10B981 !important; background-color: #262322 !important; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                <div class="d-flex justify-content-between align-items-start">
                    <span style="letter-spacing: 0.5px; font-size: 0.75rem; font-weight: 700; color: #ffffff !important; text-transform: uppercase;">Terminées</span>
                    <i class="bi bi-patch-check" style="color: #10B981 !important; font-size: 1.2rem;"></i>
                </div>
                <div class="mt-4">
                    <h2 class="fw-bold mb-1" style="font-size: 3rem; font-family: sans-serif; color: #ffffff !important;">{{ $stats['completed_orders'] }}</h2>
                    <span style="font-size: 0.8rem; color: rgba(255,255,255,0.6) !important;">Prêtes sur cette période</span>
                </div>
            </div>
        </div>
    </div>

    <!-- BLOC D'ACCÈS DIRECT À L'ATELIER -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 p-4 d-flex flex-row justify-content-between align-items-center" style="background-color: #262322 !important; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                <div class="d-flex align-items-center gap-4">
                    <div class="p-3 rounded-3" style="background-color: #1A1818; border: 1px solid rgba(255, 255, 255, 0.1);">
                        <i class="bi bi-activity" style="font-size: 2rem; color: #D4AF37;"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-uppercase mb-1" style="font-size: 0.95rem; letter-spacing: 0.5px; color: #ffffff !important;">Accéder à mes travaux de couture</h5>
                        <p style="color: rgba(255,255,255,0.7) !important; font-size: 0.85rem; margin-bottom: 0;">Consultez vos mesures, mettez à jour l'avancement de vos pièces et gérez vos priorités de coupe.</p>
                    </div>
                </div>
                <div>
                    <!-- Bouton synchronisé également avec la même couleur dorée -->
                    <a href="{{ route('couturier.rapport') }}" class="btn btn-dark px-4 py-2.5 fw-semibold shadow-sm" style="background-color: #D4AF37 !important; color: #1A1818 !important; border: none; font-size: 0.85rem; border-radius: 6px;">
                        Ouvrir mon Atelier <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection