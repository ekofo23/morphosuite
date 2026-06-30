@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                    ✨ {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                    ⚠️ {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>📋 Carnet de Commandes & Analyses Morphologiques</h2>
                <a href="{{ route('orders.create') }}" class="btn btn-primary">📐 Nouvelle Fiche Mesures</a>
            </div>

            @if($orders->isEmpty())
                <div class="card shadow-sm text-center p-5">
                    <div class="card-body">
                        <h4 class="text-muted">Aucune commande enregistrée pour le moment.</h4>
                        <p class="text-secondary">Lancez-vous en créant votre première fiche de mesures client !</p>
                        <a href="{{ route('orders.create') }}" class="btn btn-outline-primary mt-2">Créer une fiche</a>
                    </div>
                </div>
            @else
                @foreach($orders as $order)
                <div class="card shadow-sm mb-4 border-start border-4 @if($order->dominant_morphology == 'X' || $order->dominant_morphology == '8') border-danger @elseif($order->dominant_morphology == 'A') border-primary @else border-warning @endif">
                    <div class="card-body">
                        <div class="row align-items-center">
                            
                            <div class="col-md-3 border-end">
                                <h4 class="mb-1 text-dark"><strong>{{ $order->client_name }}</strong></h4>
                                <p class="text-muted small mb-2">📞 {{ $order->client_phone ?? 'Non renseigné' }}</p>
                                
                                <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="mt-2 mb-2">
                                    @csrf
                                    @method('PATCH')
                                    <label class="form-label small text-muted mb-1">Statut Atelier :</label>
                                    <select name="status" class="form-select form-select-sm shadow-sm" onchange="this.form.submit()">
                                        <option value="En attente" {{ $order->status == 'En attente' ? 'selected' : '' }}>⏳ En attente</option>
                                        <option value="En coupe" {{ $order->status == 'En coupe' ? 'selected' : '' }}>✂️ En coupe</option>
                                        <option value="En couture" {{ $order->status == 'En couture' ? 'selected' : '' }}>🪡 En couture</option>
                                        <option value="Prêt" {{ $order->status == 'Prêt' ? 'selected' : '' }}>👗 Prêt</option>
                                    </select>
                                </form>

                                <small class="text-muted d-block mb-1">📅 Analyse du : <strong>{{ $order->created_at->format('d/m/Y à H:i') }}</strong></small>
                                <small class="text-muted">Assigné à : <strong>{{ $order->user ? $order->user->name : 'Non assigné' }}</strong></small>
                            </div>

                            <div class="col-md-3 border-end text-center">
                                <span class="text-uppercase tracking-wider text-muted small d-block mb-2">📐 Mensurations (cm)</span>
                                <div class="row g-2">
                                    <div class="col-6"><span class="badge bg-light text-dark w-100">Ép: {{ $order->shoulder_measurement }}</span></div>
                                    <div class="col-6"><span class="badge bg-light text-dark w-100">Poi: {{ $order->chest_measurement }}</span></div>
                                    <div class="col-6"><span class="badge bg-light text-dark w-100">Tai: {{ $order->waist_measurement }}</span></div>
                                    <div class="col-6"><span class="badge bg-light text-dark w-100">Han: {{ $order->hip_measurement }}</span></div>
                                </div>
                                <div class="mt-2 text-start small text-secondary px-2">
                                    <span>Buste: <strong>{{ ucfirst($order->buste_length) }}</strong></span> | 
                                    <span>Posture: <strong>{{ ucfirst($order->posture_type) }}</strong></span>
                                </div>
                            </div>

                            <div class="col-md-4 border-end">
                                <span class="text-uppercase tracking-wider text-muted small d-block mb-2">🧠 Analyse MorphoCore (Similarité)</span>
                                
                                @if($order->morphology_percentages)
                                    @foreach($order->morphology_percentages as $morpho => $percentage)
                                        <div class="mb-1">
                                            <div class="d-flex justify-content-between small mb-0">
                                                <span class="fw-bold">Type {{ $morpho }}</span>
                                                <span>{{ $percentage }}%</span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar @if($morpho == $order->dominant_morphology) bg-success fw-bold @else bg-secondary opacity-50 @endif" 
                                                     role="progressbar" 
                                                     style="width: {{ $percentage }}%;" 
                                                     aria-valuenow="{{ $percentage }}" 
                                                     aria-valuemin="0" 
                                                     aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-danger small">Erreur lors du calcul des scores.</p>
                                @endif
                            </div>

                            <div class="col-md-2 text-center">
                                <span class="text-uppercase tracking-wider text-muted small d-block mb-1">Dominante</span>
                                <div class="display-4 fw-bold text-success mb-2">{{ $order->dominant_morphology }}</div>
                                
                                @php
                                    // Extraction dynamique des conseils croisés depuis notre service de style
                                    $conseils = \App\Services\StyleAdvisorService::generateAdvisor($order);
                                @endphp

                                <button class="btn btn-sm btn-dark w-100 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#style-{{ $order->id }}">
                                    ✨ Voir le Style
                                </button>
                                <a href="{{ route('orders.pdf', $order->id) }}" class="btn btn-sm btn-outline-danger w-100 shadow-sm mt-2">
                                    📄 Exporter PDF
                                </a>
                            </div>

                        </div>

                        <div class="collapse mt-3 pt-3 border-top" id="style-{{ $order->id }}">
                            <div class="row">
                                <div class="col-md-4">
                                    <h6 class="text-success fw-bold">👗 Coupes Recommandées :</h6>
                                    <ul class="small ps-3 text-secondary mb-0">
                                        @foreach($conseils['coupes_recommandees'] as $coupe)
                                            <li class="mb-1">{{ $coupe }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-md-4">
                                    <h6 class="text-danger fw-bold">⚠️ À Éviter :</h6>
                                    <ul class="small ps-3 text-secondary mb-0">
                                        @foreach($conseils['a_eviter'] as $eviter)
                                            <li class="mb-1">{{ $eviter }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-md-4 bg-light p-2 rounded">
                                    <h6 class="text-dark fw-bold">🪡 Ajustements de Coupe (Atelier) :</h6>
                                    <ul class="small ps-3 text-dark mb-0">
                                        @if(empty($conseils['ajustements_atelier']))
                                            <li class="text-muted list-unstyled">Aucun ajustement structurel requis pour cette configuration.</li>
                                        @else
                                            @foreach($conseils['ajustements_atelier'] as $ajustement)
                                                <li class="mb-1 fw-semibold">{{ $ajustement }}</li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                @endforeach
            @endif

        </div>
    </div>
</div>
@endsection