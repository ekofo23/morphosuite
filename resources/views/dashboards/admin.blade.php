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

    <div class="d-flex justify-content-between align-items-center mb-5 pb-3">
        <div>
            <h2 class="mb-1" style="font-family: 'Playfair Display', serif; font-weight: 700; color: #b09652 !important; font-size: 2.2rem;">Table de Contrôle Administrateur</h2>
            <p style="color: #ffffff !important; font-size: 0.9rem; margin-bottom: 0;">Bienvenue dans l'espace de gestion globale de MorphoSuite.</p>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 p-4 h-100" style="background-color: #262322 !important; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                <div class="d-flex justify-content-between align-items-start">
                    <span style="letter-spacing: 0.5px; font-size: 0.75rem; font-weight: 700; color: #ffffff !important; text-transform: uppercase;">Total Analyses</span>
                    <i class="bi bi-calculator" style="font-size: 1.1rem; color: #ffffff !important;"></i>
                </div>
                <div class="mt-4">
                    <h2 class="fw-bold mb-1" style="font-size: 3rem; font-family: sans-serif; color: #ffffff !important;">{{ $stats['total_orders'] }}</h2>
                    <span style="font-size: 0.8rem; color: #ffffff !important;">Fiches enregistrées</span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 p-4 h-100" style="border-left: 4px solid #FF9800 !important; background-color: #262322 !important; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                <div class="d-flex justify-content-between align-items-start">
                    <span style="letter-spacing: 0.5px; font-size: 0.75rem; font-weight: 700; color: #ffffff !important; text-transform: uppercase;">En Attente de Coupe</span>
                    <i class="bi bi-hourglass-split" style="color: #FF9800 !important; font-size: 1.1rem;"></i>
                </div>
                <div class="mt-4">
                    <h2 class="fw-bold mb-1" style="font-size: 3rem; font-family: sans-serif; color: #ffffff !important;">{{ $stats['pending_orders'] }}</h2>
                    <span style="font-size: 0.8rem; color: #ffffff !important;">Modèles en attente de traitement</span>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 p-4 h-100" style="border-left: 4px solid #10B981 !important; background-color: #262322 !important; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                <div class="d-flex justify-content-between align-items-start">
                    <span style="letter-spacing: 0.5px; font-size: 0.75rem; font-weight: 700; color: #ffffff !important; text-transform: uppercase;">Commandes Prêtes</span>
                    <i class="bi bi-check-circle" style="color: #10B981 !important; font-size: 1.1rem;"></i>
                </div>
                <div class="mt-4">
                    <h2 class="fw-bold mb-1" style="font-size: 3rem; font-family: sans-serif; color: #ffffff !important;">{{ $stats['completed_orders'] }}</h2>
                    <span style="font-size: 0.8rem; color: #ffffff !important;">Prêtes pour l'essayage client</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 p-4 d-flex flex-column justify-content-between" style="background-color: #262322 !important; min-height: 240px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                <div>
                    <h5 class="fw-bold text-uppercase pb-2 mb-3 border-bottom" style="font-size: 0.9rem; letter-spacing: 0.5px; color: #ffffff !important; border-color: rgba(255,255,255,0.08) !important;">Gestion des Équipes</h5>
                    <p style="line-height: 1.6; color: #ffffff !important; font-size: 0.88rem; margin-bottom: 0;">Supervisez vos collaborateurs, attribuez les rôles (Admin, Styliste, Couturier) et gérez de manière sécurisée leurs accès système.</p>
                </div>
                <div class="mt-4">
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-dark btn-sm px-3 py-2 fw-semibold" style="background-color: #1A1818 !important; border: 1px solid rgba(255,255,255,0.2); color: #ffffff !important; font-size: 0.8rem;">
                        Accéder au personnel
                    </a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 p-4 d-flex flex-column justify-content-between" style="background-color: #262322 !important; min-height: 240px; border-radius: 8px; box-shadow: 0 4px 20px rgba(0,0,0,0.3);">
                <div>
                    <h5 class="fw-bold text-uppercase pb-2 mb-3 border-bottom" style="font-size: 0.9rem; letter-spacing: 0.5px; color: #ffffff !important; border-color: rgba(255,255,255,0.08) !important;">Suivi des Ateliers</h5>
                    <p style="line-height: 1.6; color: #ffffff !important; font-size: 0.88rem; margin-bottom: 0;">Consultez l'historique complet des fiches de mesures, observez les analyses MorphoCore en temps réel et coordonnez les ordres de coupe.</p>
                </div>
                <div class="mt-4">
                    <a href="{{ route('orders.index') }}" class="btn btn-dark btn-sm px-3 py-2 fw-semibold" style="background-color: #1A1818 !important; border: 1px solid rgba(255,255,255,0.2); color: #ffffff !important; font-size: 0.8rem;">
                        Voir toutes les fiches
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection