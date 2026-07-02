@extends('layouts.app')

@section('content')
<div class="container-fluid px-0" style="max-width: 1000px;">
    <div class="row">
        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center mb-5 pb-3 border-bottom" style="border-bottom: 1px solid rgba(255,255,255,0.1) !important;">
                <div>
                    <h2 class="mb-1" style="font-family: 'Playfair Display', serif; font-weight: 700; color: #FFFFFF !important;">Fiche Client & Analyse</h2>
                    <p class="text-muted small mb-0" style="color: #9CA3AF !important;">Détails des mesures anatomiques et directives de coupe personnalisées.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm d-flex align-items-center gap-2" style="border-color: rgba(255,255,255,0.1) !important;">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                    <a href="{{ route('orders.pdf', $order->id) }}" class="btn btn-secondary btn-sm d-flex align-items-center gap-2" style="border-color: rgba(255,255,255,0.1) !important;">
                        <i class="bi bi-file-earmark-pdf"></i> Exporter PDF
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-4">
                    
                    <div class="card border-0 shadow-sm p-4 mb-4" style="background-color: #2d2a2a !important; background: #2d2a2a !important; box-shadow: 0 4px 20px rgba(0,0,0,0.2) !important;">
                        <div class="text-center mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center fw-semibold mx-auto mb-3" style="width: 60px; height: 60px; background-color: rgba(255,255,255,0.06) !important; color: #FFFFFF !important; font-size: 1.2rem; border: 1px solid rgba(255,255,255,0.1);">
                                {{ strtoupper(substr($order->client_name, 0, 1)) }}
                            </div>
                            <h4 class="fw-semibold mb-1" style="font-size: 1.1rem; color: #FFFFFF !important;">{{ $order->client_name }}</h4>
                            <p class="small mb-0" style="color: #9CA3AF !important;">{{ $order->client_phone ?? 'Aucun numéro enregistré' }}</p>
                        </div>
                        
                        <div class="border-top pt-3 mt-2" style="font-size: 0.85rem; border-top: 1px solid rgba(255,255,255,0.08) !important;">
                            <div class="d-flex justify-content-between mb-2">
                                <span style="color: #9CA3AF !important;">Artisan :</span>
                                <span class="fw-medium" style="color: #FFFFFF !important;">{{ $order->user ? $order->user->name : 'Non assigné' }}</span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span style="color: #9CA3AF !important;">Statut :</span>
                                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'couturier')
                                    <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="form-select form-select-sm py-0 px-2" onchange="this.form.submit()" style="font-size: 0.8rem; width: auto; max-width: 130px; height: 26px; background-color: rgba(255,255,255,0.08) !important; border-color: rgba(255,255,255,0.15) !important; color: #FFFFFF !important; font-weight: 500; cursor: pointer;">
                                            <option value="En attente" {{ $order->status == 'En attente' ? 'selected' : '' }} style="background-color: #2d2a2a; color: #fff;">En attente</option>
                                            <option value="En coupe" {{ $order->status == 'En coupe' ? 'selected' : '' }} style="background-color: #2d2a2a; color: #fff;">En coupe</option>
                                            <option value="En couture" {{ $order->status == 'En couture' ? 'selected' : '' }} style="background-color: #2d2a2a; color: #fff;">En couture</option>
                                            <option value="Prêt" {{ $order->status == 'Prêt' ? 'selected' : '' }} style="background-color: #2d2a2a; color: #fff;">Prêt</option>
                                        </select>
                                    </form>
                                @else
                                    <span class="fw-medium" style="color: #FFFFFF !important;">{{ $order->status }}</span>
                                @endif
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <span style="color: #9CA3AF !important;">Créée le :</span>
                                <span class="fw-medium" style="color: #E5E7EB !important;">{{ $order->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm p-4" style="background-color: #2d2a2a !important; background: #2d2a2a !important; box-shadow: 0 4px 20px rgba(0,0,0,0.2) !important;">
                        <h5 class="fw-semibold mb-3" style="font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px; color: #FFFFFF !important;">Mensurations</h5>
                        <div class="row g-3 text-center">
                            <div class="col-6">
                                <div class="p-2 rounded" style="background-color: rgba(255, 255, 255, 0.05) !important;">
                                    <small class="d-block line-height-1" style="font-size: 0.7rem; color: #9CA3AF !important;">ÉPAULES</small>
                                    <span class="fw-semibold" style="font-size: 1.05rem; color: #FFFFFF !important;">{{ $order->shoulder_measurement }} cm</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 rounded" style="background-color: rgba(255, 255, 255, 0.05) !important;">
                                    <small class="d-block" style="font-size: 0.7rem; color: #9CA3AF !important;">POITRINE</small>
                                    <span class="fw-semibold" style="font-size: 1.05rem; color: #FFFFFF !important;">{{ $order->chest_measurement }} cm</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 rounded" style="background-color: rgba(255, 255, 255, 0.05) !important;">
                                    <small class="d-block" style="font-size: 0.7rem; color: #9CA3AF !important;">TAILLE</small>
                                    <span class="fw-semibold" style="font-size: 1.05rem; color: #FFFFFF !important;">{{ $order->waist_measurement }} cm</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 rounded" style="background-color: rgba(255, 255, 255, 0.05) !important;">
                                    <small class="d-block" style="font-size: 0.7rem; color: #9CA3AF !important;">HANCHES</small>
                                    <span class="fw-semibold" style="font-size: 1.05rem; color: #FFFFFF !important;">{{ $order->hip_measurement }} cm</span>
                                </div>
                            </div>
                        </div>
                        <div class="border-top pt-3 mt-3" style="font-size: 0.85rem; border-top: 1px solid rgba(255,255,255,0.08) !important;">
                            <div class="d-flex justify-content-between mb-2">
                                <span style="color: #9CA3AF !important;">Buste :</span>
                                <span class="fw-medium text-capitalize" style="color: #FFFFFF !important;">{{ $order->buste_length }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span style="color: #9CA3AF !important;">Posture :</span>
                                <span class="fw-medium text-capitalize" style="color: #FFFFFF !important;">{{ $order->posture_type }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-8 mb-4">
                    
                    <div class="card border-0 shadow-sm p-4 mb-4" style="background-color: #2d2a2a !important; background: #2d2a2a !important; box-shadow: 0 4px 20px rgba(0,0,0,0.2) !important;">
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-4" style="border-bottom: 1px solid rgba(255,255,255,0.08) !important;">
                            <h5 class="fw-semibold mb-0" style="font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px; color: #FFFFFF !important;">Analyse MorphoCore</h5>
                            <div>
                                <span class="small" style="color: #9CA3AF !important;">Silhouette dominante :</span>
                                <span class="fw-bold px-2 py-1 rounded ms-1" style="font-size: 0.9rem; background-color: rgba(255,255,255,0.1) !important; color: #FFFFFF !important;">{{ $order->dominant_morphology }}</span>
                            </div>
                        </div>
                        
                        <div class="row align-items-center">
                            <div class="col-12">
                                @if($order->morphology_percentages)
                                    @foreach($order->morphology_percentages as $morpho => $percentage)
                                        @php
                                            // Utilisation de codes HEXA fixes pour garantir l'affichage des couleurs
                                            if ($morpho == $order->dominant_morphology) {
                                                $gaugeColor = '#10B981'; // Vert Émeraude pour la silhouette dominante (Garanti !)
                                            } elseif ($percentage >= 21) {
                                                $gaugeColor = '#F59E0B'; // Orange moyen
                                            } else {
                                                $gaugeColor = '#EF4444'; // Rouge faible
                                            }
                                        @endphp
                                        <div class="mb-3">
                                            <div class="d-flex justify-content-between mb-1" style="font-size: 0.8rem;">
                                                <span style="color: {{ $morpho == $order->dominant_morphology ? '#FFFFFF' : '#9CA3AF' }} !important; font-weight: {{ $morpho == $order->dominant_morphology ? '600' : '400' }};">Silhouette {{ $morpho }}</span>
                                                <span style="color: {{ $morpho == $order->dominant_morphology ? '#FFFFFF' : '#9CA3AF' }} !important; font-weight: {{ $morpho == $order->dominant_morphology ? '600' : '400' }};">{{ $percentage }}%</span>
                                            </div>
                                            <div class="progress" style="height: 4px; background-color: rgba(255,255,255,0.1) !important;">
                                                <div class="progress-bar" 
                                                     role="progressbar" 
                                                     style="width: {{ $percentage }}%; background-color: {{ $gaugeColor }} !important;" 
                                                     aria-valuenow="{{ $percentage }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>

                    @php
                        $conseils = \App\Services\StyleAdvisorService::generateAdvisor($order);
                    @endphp
                    <div class="card border-0 shadow-sm p-4" style="background-color: #2d2a2a !important; background: #2d2a2a !important; box-shadow: 0 4px 20px rgba(0,0,0,0.2) !important;">
                        <h5 class="fw-semibold border-bottom pb-2 mb-4" style="font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px; color: #FFFFFF !important; border-bottom: 1px solid rgba(255,255,255,0.08) !important;">Directives d'Atelier</h5>
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="small fw-semibold mb-2" style="color: #FFFFFF !important;">Coupes Recommandées</div>
                                <ul class="small ps-3 mb-0" style="color: #E5E7EB !important;">
                                    @foreach($conseils['coupes_recommandees'] as $coupe)
                                        <li class="mb-2">{{ $coupe }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <div class="small fw-semibold mb-2" style="color: #FFFFFF !important;">Configurations à Éviter</div>
                                <ul class="small ps-3 mb-0" style="color: #FCA5A5 !important;">
                                    @foreach($conseils['a_eviter'] as $eviter)
                                        <li class="mb-2">{{ $eviter }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <div class="col-12">
                                <div class="p-3 rounded" style="background-color: rgba(255, 255, 255, 0.05) !important; border: 1px solid rgba(255,255,255,0.05);">
                                    <div class="small fw-semibold mb-2" style="color: #FFFFFF !important;">Ajustements Techniques (Structure de Coupe)</div>
                                    <ul class="small ps-3 mb-0" style="color: #E5E7EB !important;">
                                        @if(empty($conseils['ajustements_atelier']))
                                            <li class="text-muted list-unstyled" style="color: #9CA3AF !important;">Aucun ajustement structurel requis pour cette configuration.</li>
                                        @else
                                            @foreach($conseils['ajustements_atelier'] as $ajustement)
                                                <li class="mb-1 fw-medium">{{ $ajustement }}</li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const pdfBtn = document.querySelector('a[href*="pdf"]');
        if (pdfBtn) {
            pdfBtn.addEventListener('click', function(e) {
                setTimeout(() => {
                    this.style.pointerEvents = 'none';
                    this.alpha = '0.7';
                    this.innerHTML = '<i class="bi bi-hourglass-split"></i> Génération...';
                }, 50);
                
                setTimeout(() => {
                    this.style.pointerEvents = 'auto';
                    this.innerHTML = '<i class="bi bi-file-earmark-pdf"></i> Exporter PDF';
                }, 4000);
            });
        }
    });
</script>
@endsection