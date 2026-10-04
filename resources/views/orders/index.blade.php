@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <div class="row">
        <div class="col-12">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="background-color: var(--status-ready-bg); color: var(--status-ready-color);">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="background-color: var(--status-waiting-bg); color: var(--status-waiting-color);">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-5 pb-3 border-bottom" style="border-color: rgba(255,255,255,0.1) !important; gap: 15px;">
                <div>
                    <h2 class="mb-1" style="font-family: 'Playfair Display', serif; font-weight: 700; color: #D4AF37;">Carnet de Commandes</h2>
                    <p class="text-muted small mb-0">Suivi de la production de l'atelier et analyses morphologiques de coupes.</p>
                </div>
                <div>
                    <a href="{{ route('orders.create') }}" class="btn btn-sm px-3 py-2 fw-semibold shadow-sm" style="background-color: #D4AF37 !important; color: #1A1818 !important; border: none; font-size: 0.85rem; border-radius: 6px;">
                        <i class="bi bi-plus-lg me-1"></i> Nouvelle Fiche
                    </a>
                </div>
            </div>

            @php
                $current_period = request('period', 'all');
                $search_value = request('search', '');
            @endphp
            
            <form action="{{ route('orders.index') }}" method="GET" class="row g-3 mb-4 align-items-center">
                <input type="hidden" name="period" value="{{ $current_period }}">

                <div class="col-md-5 col-lg-4">
                    <div class="input-group">
                        <span class="input-group-text border-0" style="background-color: #262322; color: rgba(255,255,255,0.4);">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-0 text-white" placeholder="Rechercher un client..." value="{{ $search_value }}" style="background-color: #262322; font-size: 0.85rem; padding: 0.55rem 0.75rem;" onchange="this.form.submit()">
                        @if($search_value)
                            <a href="{{ route('orders.index', ['period' => $current_period]) }}" class="btn border-0 d-flex align-items-center" style="background-color: #262322; color: rgba(255,255,255,0.4);">
                                <i class="bi bi-x-circle-fill"></i>
                            </a>
                        @endif
                    </div>
                </div>

                <div class="col-md d-none d-md-block"></div>

                <div class="col-md-auto">
                    <div class="d-flex rounded-3 p-1" style="background-color: #262322; border: 1px solid rgba(255,255,255,0.08); flex-wrap: wrap;">
                    
                        <a href="{{ route('orders.index', ['period' => 'all', 'search' => $search_value]) }}" class="btn btn-sm px-3 py-1.5 fw-semibold transition-all {{ $current_period == 'all' ? 'active-filter' : 'text-white-50' }}">Tous</a>
                        <a href="{{ route('orders.index', ['period' => 'today', 'search' => $search_value]) }}" class="btn btn-sm px-3 py-1.5 fw-semibold transition-all {{ $current_period == 'today' ? 'active-filter' : 'text-white-50' }}">Aujourd'hui</a>
                        <a href="{{ route('orders.index', ['period' => 'week', 'search' => $search_value]) }}" class="btn btn-sm px-3 py-1.5 fw-semibold transition-all {{ $current_period == 'week' ? 'active-filter' : 'text-white-50' }}">Semaine</a>
                        <a href="{{ route('orders.index', ['period' => 'month', 'search' => $search_value]) }}" class="btn btn-sm px-3 py-1.5 fw-semibold transition-all {{ $current_period == 'month' ? 'active-filter' : 'text-white-50' }}">Mois</a>
                        <a href="{{ route('orders.index', ['period' => 'quarter', 'search' => $search_value]) }}" class="btn btn-sm px-3 py-1.5 fw-semibold transition-all {{ $current_period == 'quarter' ? 'active-filter' : 'text-white-50' }}">Trimestre</a>
                        <a href="{{ route('orders.index', ['period' => 'year', 'search' => $search_value]) }}" class="btn btn-sm px-3 py-1.5 fw-semibold transition-all {{ $current_period == 'year' ? 'active-filter' : 'text-white-50' }}">Annuel</a>
                    </div>
                </div>
            </form>

            @if($orders->isEmpty())
                <div class="card text-center p-5 border-0 shadow-sm" style="background-color: #2d2a2a !important; border: 1px solid rgba(255,255,255,0.05) !important;">
                    <div class="card-body py-5">
                        <h4 class="text-muted mb-2 fw-light" style="color: #FFFFFF !important;">Aucune commande trouvée.</h4>
                        <p class="text-secondary small mb-4" style="color: #9CA3AF !important;">Modifiez vos critères de recherche ou créez une nouvelle fiche.</p>
                        <a href="{{ route('orders.create') }}" class="btn btn-sm fw-semibold px-3" style="background-color: #D4AF37; color: #1A1818;">Créer une fiche</a>
                    </div>
                </div>
            @else
                
                <style>
                    .atelier-table {
                        width: 100%;
                        border-collapse: separate;
                        border-spacing: 0 12px;
                        background: transparent !important;
                    }
                    .select-status-atelier:focus {
                        border-color: #D4AF37;
                        outline: none;
                    }
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
                    .form-control:focus {
                        box-shadow: 0 0 0 0.25rem rgba(212, 175, 55, 0.25) !important;
                        background-color: #262322 !important;
                    }
                </style>

                <table class="atelier-table" style="background: transparent !important;">
                    <tbody style="background: transparent !important;">
                        @foreach($orders as $order)
                        <tr style="background-color: #2d2a2a !important; background: #2d2a2a !important; box-shadow: 0 4px 20px rgba(0,0,0,0.2) !important;">
                            
                            <td style="width: 22%; padding: 1.25rem 1rem; vertical-align: middle; border-top: 1px solid rgba(255, 255, 255, 0.05) !important; border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important; border-left: 1px solid rgba(255, 255, 255, 0.05) !important; border-top-left-radius: 6px; border-bottom-left-radius: 6px; background: transparent !important;">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-semibold" style="width: 40px; height: 40px; background-color: rgba(255,255,255,0.08) !important; color: #E5E7EB !important; font-size: 0.9rem; border: 1px solid rgba(255,255,255,0.1) !important;">
                                        {{ $order->client_name ? strtoupper(substr($order->client_name, 0, 1)) : 'C' }}
                                    </div>
                                    <div>
                                        <div class="fw-semibold mb-0" style="font-size: 0.95rem; color: #FFFFFF !important;">{{ $order->client_name }}</div>
                                        <small class="text-muted d-block" style="font-size: 0.8rem; color: #9CA3AF !important;">{{ $order->client_phone ?? 'Aucun contact' }}</small>
                                    </div>
                                </div>
                            </td>

                            <td style="width: 18%; padding: 1.25rem 1rem; vertical-align: middle; border-top: 1px solid rgba(255, 255, 255, 0.05) !important; border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important; background: transparent !important;">
                                <small class="text-muted d-block" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; color: #9CA3AF !important;">Atelier</small>
                                <div class="small fw-medium mt-0" style="color: #E5E7EB !important;">Artisan : {{ $order->user ? $order->user->name : 'Non assigné' }}</div>
                                <small class="text-muted d-block" style="font-size: 0.75rem; color: #9CA3AF !important;">Date : {{ $order->created_at ? $order->created_at->format('d/m/Y') : '-' }}</small>
                            </td>

                            <td style="width: 18%; padding: 1.25rem 1rem; vertical-align: middle; border-top: 1px solid rgba(255, 255, 255, 0.05) !important; border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important; background: transparent !important;">
                                <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="select-status-atelier" style="background-color: rgba(255, 255, 255, 0.08) !important; border: 1px solid rgba(255, 255, 255, 0.15) !important; color: #FFFFFF !important; font-size: 0.8rem; font-weight: 500; padding: 0.4rem 0.75rem; border-radius: 6px; cursor: pointer;" onchange="this.form.submit()">
                                        <option value="En attente" {{ $order->status == 'En attente' || $order->status == 'En splinter' ? 'selected' : '' }} style="background-color: #2d2a2a; color: #fff;">En attente</option>
                                        <option value="En coupe" {{ $order->status == 'En coupe' ? 'selected' : '' }} style="background-color: #2d2a2a; color: #fff;">En coupe</option>
                                        <option value="En couture" {{ $order->status == 'En couture' ? 'selected' : '' }} style="background-color: #2d2a2a; color: #fff;">En couture</option>
                                        <option value="Prêt" {{ $order->status == 'Prêt' ? 'selected' : '' }} style="background-color: #2d2a2a; color: #fff;">Prêt</option>
                                    </select>
                                </form>
                            </td>

                            <td style="width: 27%; padding: 1.25rem 1rem; vertical-align: middle; border-top: 1px solid rgba(255, 255, 255, 0.05) !important; border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important; background: transparent !important;">
                                <div class="pe-3">
                                    @if($order->morphology_percentages)
                                        @foreach($order->morphology_percentages as $morpho => $percentage)
                                            @php
                                                if ($morpho == $order->dominant_morphology) {
                                                    $barColor = '#10B981'; 
                                                } elseif ($percentage < 30) {
                                                    $barColor = '#EF4444'; 
                                                } else {
                                                    $barColor = '#F59E0B'; 
                                                }
                                            @endphp
                                            <div class="mb-1">
                                                <div class="d-flex justify-content-between" style="font-size: 0.75rem;">
                                                    <span style="color: {{ $morpho == $order->dominant_morphology ? '#FFFFFF' : '#9CA3AF' }} !important; font-weight: {{ $morpho == $order->dominant_morphology ? '600' : '400' }};">Silhouette {{ $morpho }}</span>
                                                    <span style="color: {{ $morpho == $order->dominant_morphology ? '#FFFFFF' : '#9CA3AF' }} !important; font-weight: {{ $morpho == $order->dominant_morphology ? '600' : '400' }};">{{ $percentage }}%</span>
                                                </div>
                                                <div class="progress" style="height: 3px; background-color: rgba(255,255,255,0.08) !important;">
                                                    <div class="progress-bar" 
                                                         role="progressbar" 
                                                         style="width: {{ $percentage }}%; background-color: {{ $barColor }} !important;" 
                                                         aria-valuenow="{{ $percentage }}" 
                                                         aria-valuemin="0" 
                                                         aria-valuemax="100">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <span class="text-muted small">Aucune analyse</span>
                                    @endif
                                </div>
                            </td>

                            <td style="width: 15%; text-align: right; padding: 1.25rem 1rem; vertical-align: middle; border-top: 1px solid rgba(255, 255, 255, 0.05) !important; border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important; border-right: 1px solid rgba(255, 255, 255, 0.05) !important; border-top-right-radius: 6px; border-bottom-right-radius: 6px; background: transparent !important;">
                                <div class="d-flex flex-column align-items-end gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <small class="text-muted" style="font-size: 0.75rem; color: #9CA3AF !important;">Dominante :</small>
                                        <span class="fw-bold px-2 py-1" style="font-size: 0.85rem; background-color: rgba(16, 185, 129, 0.2) !important; color: #A7F3D0 !important; border-radius: 4px; border: 1px solid rgba(16, 185, 129, 0.25);">{{ $order->dominant_morphology ?? 'N/A' }}</span>
                                    </div>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-secondary py-1 px-2" style="font-size: 0.75rem;">
                                            <i class="bi bi-eye"></i> Voir
                                        </a>
                                        <a href="{{ route('orders.pdf', $order->id) }}" class="btn btn-sm btn-secondary py-1 px-2" style="font-size: 0.75rem; border-color: rgba(255,255,255,0.1) !important;">
                                            <i class="bi bi-file-earmark-pdf"></i> PDF
                                        </a>
                                    </div>
                                </div>
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

        </div>
    </div>
</div>
@endsection