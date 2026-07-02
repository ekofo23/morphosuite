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

            <div class="d-flex justify-content-between align-items-center mb-5 pb-3 border-bottom">
                <div>
                    <h2 class="mb-1" style="font-family: 'Playfair Display', serif; font-weight: 700; color: var(--color-dark);">Carnet de Commandes</h2>
                    <p class="text-muted small mb-0">Suivi de la production de l'atelier et analyses morphologiques de coupes.</p>
                </div>
            </div>

            @if($orders->isEmpty())
                <div class="card text-center p-5 border-0 shadow-sm" style="background-color: #2d2a2a !important; border: 1px solid rgba(255,255,255,0.05) !important;">
                    <div class="card-body py-5">
                        <h4 class="text-muted mb-2 fw-light" style="color: #FFFFFF !important;">Aucune commande enregistrée pour le moment.</h4>
                        <p class="text-secondary small mb-4" style="color: #9CA3AF !important;">Lancez-vous en créant votre première fiche de mesures client.</p>
                        <a href="{{ route('orders.create') }}" class="btn btn-primary btn-sm">Créer une fiche</a>
                    </div>
                </div>
            @else
                
                <style>
                    .atelier-table {
                        width: 100%;
                        border-collapse: separate;
                        border-spacing: 0 12px;
                        background: transparent !important;
                        background-color: transparent !important;
                    }
                    .select-status-atelier:focus {
                        border-color: var(--color-gold);
                        outline: none;
                    }
                </style>

                <table class="atelier-table" style="background: transparent !important;">
                    <tbody style="background: transparent !important;">
                        @foreach($orders as $order)
                        <tr style="background-color: #2d2a2a !important; background: #2d2a2a !important; box-shadow: 0 4px 20px rgba(0,0,0,0.2) !important;">
                            
                            <td style="width: 22%; padding: 1.25rem 1rem; vertical-align: middle; border-top: 1px solid rgba(255, 255, 255, 0.05) !important; border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important; border-left: 1px solid rgba(255, 255, 255, 0.05) !important; border-top-left-radius: 6px; border-bottom-left-radius: 6px; background: transparent !important;">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-semibold" style="width: 40px; height: 40px; background-color: rgba(255,255,255,0.08) !important; color: #E5E7EB !important; font-size: 0.9rem; border: 1px solid rgba(255,255,255,0.1) !important;">
                                        {{ strtoupper(substr($order->client_name, 0, 1)) }}
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
                                <small class="text-muted d-block" style="font-size: 0.75rem; color: #9CA3AF !important;">Date : {{ $order->created_at->format('d/m/Y') }}</small>
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
                                                // Détermination dynamique de la couleur de la barre
                                                if ($morpho == $order->dominant_morphology) {
                                                    $barColor = '#10B981'; // Vert moyen pour la dominante / forte
                                                } elseif ($percentage < 30) {
                                                    $barColor = '#EF4444'; // Rouge pour faible
                                                } else {
                                                    $barColor = '#F59E0B'; // Orange pour moyenne
                                                }
                                            @endphp
                                            <div class="mb-1">
                                                <div class="d-flex justify-content-between" style="font-size: 0.75rem;">
                                                    <span style="color: @if($morpho == $order->dominant_morphology) #FFFFFF @else #9CA3AF @endif !important; font-weight: @if($morpho == $order->dominant_morphology) 600 @else 400 @endif;">Silhouette {{ $morpho }}</span>
                                                    <span style="color: @if($morpho == $order->dominant_morphology) #FFFFFF @else #9CA3AF @endif !important; font-weight: @if($morpho == $order->dominant_morphology) 600 @else 400 @endif;">{{ $percentage }}%</span>
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
                                    @endif
                                </div>
                            </td>

                            <td style="width: 15%; text-align: right; padding: 1.25rem 1rem; vertical-align: middle; border-top: 1px solid rgba(255, 255, 255, 0.05) !important; border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important; border-right: 1px solid rgba(255, 255, 255, 0.05) !important; border-top-right-radius: 6px; border-bottom-right-radius: 6px; background: transparent !important;">
                                <div class="d-flex flex-column align-items-end gap-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <small class="text-muted" style="font-size: 0.75rem; color: #9CA3AF !important;">Dominante :</small>
                                        <span class="fw-bold px-2 py-1" style="font-size: 0.85rem; background-color: rgba(16, 185, 129, 0.2) !important; color: #A7F3D0 !important; border-radius: 4px; border: 1px solid rgba(16, 185, 129, 0.25);">{{ $order->dominant_morphology }}</span>
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