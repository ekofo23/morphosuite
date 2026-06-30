@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h2">👑 Table de Contrôle Administrateur</h1>
            <p class="text-muted">Bienvenue dans l'espace de gestion globale de MorphoSuite.</p>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-md-4 mb-3">
            <div class="card bg-primary text-white shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 opacity-75">Total Analyses</h6>
                        <h2 class="display-5 fw-bold mb-0">{{ $stats['total_orders'] }}</h2>
                    </div>
                    <span class="fs-1">📐</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-warning text-dark shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 opacity-75">En Attente de Coupe/Style</h6>
                        <h2 class="display-5 fw-bold mb-0">{{ $stats['pending_orders'] }}</h2>
                    </div>
                    <span class="fs-1">⏳</span>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white shadow-sm border-0">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-uppercase mb-1 opacity-75">Commandes Prêtes</h6>
                        <h2 class="display-5 fw-bold mb-0">{{ $stats['completed_orders'] }}</h2>
                    </div>
                    <span class="fs-1">👗</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold">👥 Gestion des Équipes</h5>
                    <p class="text-secondary card-text">Supervisez vos collaborateurs, attribuez les rôles (Admin, Styliste, Couturier) et modifiez leurs accès système.</p>
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-dark">Accéder au personnel</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title fw-bold">👁️ Suivi des Ateliers</h5>
                    <p class="text-secondary card-text">Consultez l'historique complet des fiches de mesures, observez les analyses MorphoCore en temps réel et assignez les confections.</p>
                    <a href="{{ route('orders.index') }}" class="btn btn-outline-dark">Voir toutes les fiches</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection