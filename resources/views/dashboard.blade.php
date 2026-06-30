@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">📊 MorphoMetrics Dashboard</h2>
            <p class="text-muted mb-0">Pilotage en temps réel de la production et analyse des silhouettes.</p>
        </div>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-dark">📋 Voir le Carnet</a>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 bg-primary text-white h-100">
                <div class="card-body d-flex flex-column justify-content-between p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <h6 class="text-uppercase tracking-wider opacity-75 small">Total Fiches</h6>
                        <span class="fs-3">📐</span>
                    </div>
                    <div class="mt-3">
                        <h2 class="display-5 fw-bold mb-0">{{ $totalOrders }}</h2>
                        <small class="opacity-75">Enregistrées au total</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 border-start border-4 border-warning h-100">
                <div class="card-body p-4">
                    <span class="text-muted text-uppercase small fw-bold">⏳ En Attente</span>
                    <h2 class="fw-bold display-6 mt-2 mb-1">{{ $statuses['En attente'] }}</h2>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-warning" style="width: {{ $totalOrders > 0 ? ($statuses['En attente'] / $totalOrders) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 border-start border-4 border-primary h-100">
                <div class="card-body p-4">
                    <span class="text-muted text-uppercase small fw-bold">✂️ En Confection</span>
                    <h2 class="fw-bold display-6 mt-2 mb-1">{{ $statuses['En coupe'] + $statuses['En couture'] }}</h2>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-primary" style="width: {{ $totalOrders > 0 ? (($statuses['En coupe'] + $statuses['En couture']) / $totalOrders) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0 border-start border-4 border-success h-100">
                <div class="card-body p-4">
                    <span class="text-muted text-uppercase small fw-bold">👗 Prêt pour Essayage</span>
                    <h2 class="fw-bold display-6 mt-2 mb-1">{{ $statuses['Prêt'] }}</h2>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-success" style="width: {{ $totalOrders > 0 ? ($statuses['Prêt'] / $totalOrders) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title fw-bold mb-0">🏆 Top Tendances Morphologiques</h5>
                </div>
                <div class="card-body p-4">
                    @if($morphologyCounts->isEmpty())
                        <p class="text-muted text-center my-4">Aucune donnée morphologique disponible.</p>
                    @else
                        @foreach($morphologyCounts as $item)
                            @php
                                $percent = $totalOrders > 0 ? round(($item->total / $totalOrders) * 100) : 0;
                            @endphp
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center small mb-1">
                                    <span class="fw-bold fs-6">Silhouette en {{ $item->dominant_morphology }}</span>
                                    <span class="text-muted fw-semibold">{{ $item->total }} cliente(s) ({{ $percent }}%)</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $percent }}%;" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title fw-bold mb-0">🪡 Charge de l'Atelier par Artisan</h5>
                </div>
                <div class="card-body p-4">
                    @if($artisanLoads->isEmpty())
                        <p class="text-muted text-center my-4">Aucun artisan enregistré dans le système.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Artisan</th>
                                        <th class="text-center">Rôle</th>
                                        <th class="text-center">Encours (Actifs)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($artisanLoads as $artisan)
                                        <tr>
                                            <td class="fw-bold text-dark">{{ $artisan->name }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark text-capitalize px-2 py-1">{{ $artisan->role }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge {{ $artisan->orders_count > 3 ? 'bg-danger' : ($artisan->orders_count > 0 ? 'bg-info' : 'bg-secondary') }} rounded-pill px-3">
                                                    {{ $artisan->orders_count }} fiches
                                                </span>
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
</div>
@endsection