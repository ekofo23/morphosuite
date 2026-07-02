@extends('layouts.app')

@section('content')
<div class="container-fluid px-0" style="max-width: 1000px;">
    <div class="d-flex justify-content-between align-items-center mb-5 pb-3 border-bottom">
        <div>
            <h2 class="mb-1" style="font-family: 'Playfair Display', serif; font-weight: 700; color: var(--color-dark);">Tableau de Bord</h2>
            <p class="text-muted small mb-0">Pilotage en temps réel de la production et analyse des silhouettes.</p>
        </div>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm d-flex align-items-center gap-2">
            <i class="bi bi-collection"></i> Voir le Carnet
        </a>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-4 h-100" style="background-color: var(--color-dark) !important; color: #FFFFFF !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <h6 class="text-uppercase small mb-0" style="letter-spacing: 0.5px; opacity: 0.8; font-size: 0.75rem; font-weight: 600;">Total Fiches</h6>
                    <i class="bi bi-layers small opacity-75"></i>
                </div>
                <div class="mt-3">
                    <h2 class="display-6 fw-bold mb-0" style="font-family: 'Playfair Display', serif;">{{ $totalOrders }}</h2>
                    <small class="opacity-75" style="font-size: 0.75rem;">Enregistrées au total</small>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-left: 3px solid #D97706 !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <h6 class="text-uppercase text-muted small mb-0" style="letter-spacing: 0.5px; font-size: 0.75rem; font-weight: 600;">En Attente</h6>
                    <i class="bi bi-clock text-warning"></i>
                </div>
                <div class="mt-3">
                    <h2 class="fw-bold mb-2 dashboard-stat-number" style="font-size: 1.8rem;">{{ $statuses['En attente'] }}</h2>
                    <div class="progress" style="height: 3px; background-color: #ECECEC;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $totalOrders > 0 ? ($statuses['En attente'] / $totalOrders) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-left: 3px solid #2563EB !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <h6 class="text-uppercase text-muted small mb-0" style="letter-spacing: 0.5px; font-size: 0.75rem; font-weight: 600;">En Confection</h6>
                    <i class="bi bi-scissors text-primary"></i>
                </div>
                <div class="mt-3">
                    <h2 class="fw-bold mb-2 dashboard-stat-number" style="font-size: 1.8rem;">{{ $statuses['En coupe'] + $statuses['En couture'] }}</h2>
                    <div class="progress" style="height: 3px; background-color: #ECECEC;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $totalOrders > 0 ? (($statuses['En coupe'] + $statuses['En couture']) / $totalOrders) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-left: 3px solid #059669 !important;">
                <div class="d-flex justify-content-between align-items-start">
                    <h6 class="text-uppercase text-muted small mb-0" style="letter-spacing: 0.5px; font-size: 0.75rem; font-weight: 600;">Prêt Essayage</h6>
                    <i class="bi bi-check-circle text-success"></i>
                </div>
                <div class="mt-3">
                    <h2 class="fw-bold mb-2 dashboard-stat-number" style="font-size: 1.8rem;">{{ $statuses['Prêt'] }}</h2>
                    <div class="progress" style="height: 3px; background-color: #ECECEC;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $totalOrders > 0 ? ($statuses['Prêt'] / $totalOrders) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-4 h-100 custom-dashboard-card">
                <h5 class="fw-semibold border-bottom pb-2 mb-4 card-title-text" style="font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px;">Tendances Morphologiques</h5>
                
                <div class="d-flex flex-column justify-content-center h-100">
                    @if($morphologyCounts->isEmpty())
                        <p class="text-muted text-center small my-4">Aucune donnée morphologique disponible.</p>
                    @else
                        @foreach($morphologyCounts as $item)
                            @php
                                $percent = $totalOrders > 0 ? round(($item->total / $totalOrders) * 100) : 0;
                            @endphp
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center small mb-1">
                                    <span class="fw-semibold card-body-text" style="font-size: 0.85rem;">Silhouette en {{ $item->dominant_morphology }}</span>
                                    <span class="text-muted text-indicator" style="font-size: 0.8rem;">{{ $item->total }} fiches ({{ $percent }}%)</span>
                                </div>
                                <div class="progress" style="height: 4px; background-color: rgba(0,0,0,0.05);">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $percent }}%; background-color: var(--color-gold, #D97706);" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm p-4 h-100 custom-dashboard-card">
                <h5 class="fw-semibold border-bottom pb-2 mb-4 card-title-text" style="font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px;">Charge de l'Atelier</h5>
                
                @if($artisanLoads->isEmpty())
                    <p class="text-muted text-center small my-4">Aucun artisan enregistré dans le système.</p>
                @else
                    <div class="table-responsive" style="background: transparent !important;">
                        <table class="table table-borderless align-middle mb-0 custom-dashboard-table" style="font-size: 0.85rem; background-color: transparent !important;">
                            <thead>
                                <tr class="text-muted border-bottom table-header-row" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                    <th class="ps-0 pb-2 style-th" style="background-color: transparent !important;">Artisan</th>
                                    <th class="text-center pb-2 style-th" style="background-color: transparent !important;">Rôle</th>
                                    <th class="text-end pe-0 pb-2 style-th" style="background-color: transparent !important;">Fiches Actives</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($artisanLoads as $artisan)
                                    <tr class="border-bottom-subtle" style="background-color: transparent !important;">
                                        <td class="ps-0 fw-semibold py-3 card-body-text style-td" style="background-color: transparent !important;">{{ $artisan->name }}</td>
                                        
                                        <td class="text-center py-3 style-td" style="background-color: transparent !important;">
                                            <span class="badge px-2 py-1 text-capitalize role-badge" 
                                                  style="font-weight: 500; font-size: 0.75rem; background-color: rgba(255, 255, 255, 0.12) !important; color: #FFFFFF !important; border: 1px solid rgba(255, 255, 255, 0.15) !important;">
                                                {{ is_object($artisan->role) ? ($artisan->role->name ?? 'Artisan') : $artisan->role }}
                                            </span>
                                        </td>
                                        
                                        <td class="text-end pe-0 py-3 style-td" style="background-color: transparent !important;">
                                            @if($artisan->orders_count > 3)
                                                <span class="fw-bold px-2 py-1 rounded load-badge-danger" 
                                                      style="font-size: 0.8rem; background-color: rgba(239, 68, 68, 0.25) !important; color: #FCA5A5 !important; border: 1px solid rgba(239, 68, 68, 0.2) !important; display: inline-block;">
                                                    {{ $artisan->orders_count }} en cours
                                                </span>
                                            @else
                                                <span class="fw-bold px-2 py-1 rounded load-badge-success" 
                                                      style="font-size: 0.8rem; background-color: rgba(16, 185, 129, 0.25) !important; color: #A7F3D0 !important; border: 1px solid rgba(16, 185, 129, 0.2) !important; display: inline-block;">
                                                    {{ $artisan->orders_count }} en cours
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    /* Forcer la transparence de la table */
    .custom-dashboard-table, 
    .custom-dashboard-table th, 
    .custom-dashboard-table td, 
    .custom-dashboard-table tr,
    .style-th,
    .style-td {
        background-color: transparent !important;
        background: transparent !important;
    }

    /* Thème Clair Global */
    .dashboard-stat-number { color: #111111; }
    .card-title-text { color: #212529; }
    .card-body-text { color: #212529; }
    .table-header-row th { color: #6c757d !important; border-bottom: 1px solid rgba(0, 0, 0, 0.1) !important; }
    .border-bottom-subtle { border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important; }
    .border-bottom-subtle:last-child { border-bottom: none !important; }

    /* Thème Sombre Global */
    .dark-theme .dashboard-stat-number { color: #FFFFFF !important; }
    .dark-theme .card-title-text { color: #FFFFFF !important; }
    .dark-theme .card-body-text { color: #E5E7EB !important; }
    .dark-theme .table-header-row th { color: #9CA3AF !important; border-bottom: 1px solid rgba(255, 255, 255, 0.15) !important; }
    .dark-theme .border-bottom-subtle { border-bottom: 1px solid rgba(255, 255, 255, 0.08) !important; }
    .dark-theme .text-indicator { color: #9CA3AF !important; }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const bodyBg = window.getComputedStyle(document.body).backgroundColor;
        const rgb = bodyBg.match(/\d+/g);
        
        let isDark = false;
        if (rgb && rgb.length >= 3) {
            const r = parseInt(rgb[0]), g = parseInt(rgb[1]), b = parseInt(rgb[2]);
            const luminance = 0.299 * r + 0.587 * g + 0.114 * b;
            if (luminance < 140) {
                isDark = true;
            }
        } else if (bodyBg === 'transparent' || bodyBg.includes('rgba(0, 0, 0, 0)')) {
            isDark = true;
        }
        
        if (isDark) {
            document.querySelector('.container-fluid').classList.add('dark-theme');
        }
    });
</script>
@endsection