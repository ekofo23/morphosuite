@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2">✨ Espace Création & Style</h1>
            <p class="text-muted">Outils d'analyses de silhouettes et de recommandations vestimentaires.</p>
        </div>
        <a href="{{ route('orders.create') }}" class="btn btn-primary btn-lg">📐 Prendre de Nouvelles Mesures</a>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-bold">📑 Dernières fiches de mesures clients créées</h5>
        </div>
        <div class="card-body p-0">
            @if($recent_orders->isEmpty())
                <p class="text-muted text-center py-4 mb-0">Aucune fiche de mesures disponible pour le moment.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Date/Heure</th>
                                <th>Cliente</th>
                                <th>Dominante</th>
                                <th>Statut</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recent_orders as $order)
                            <tr>
                                <td class="small text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td><strong>{{ $order->client_name }}</strong></td>
                                <td><span class="badge bg-success">Type {{ $order->dominant_morphology }}</span></td>
                                <td><span class="badge bg-light text-dark border">{{ $order->status }}</span></td>
                                <td class="text-end"><a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-secondary">Consulter l'analyse</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection