@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col">
            <h1 class="h2">✂️ Mon Atelier de Confection</h1>
            <p class="text-muted">Retrouvez ci-dessous la liste de vos tâches et vêtements assignés.</p>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-dark text-white py-3">
            <h5 class="mb-0">📋 Mes Confections en Cours</h5>
        </div>
        <div class="card-body p-0">
            @if($my_orders->isEmpty())
                <div class="text-center py-5 text-muted">
                    <h5>Aucune pièce ne vous est attribuée pour le moment.</h5>
                    <p class="mb-0 small">Dès que l'administration vous assignera un projet, il apparaîtra ici.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Cliente</th>
                                <th>Épaules / Poitrine / Taille / Hanches</th>
                                <th>Manches / Totale</th>
                                <th>Statut de fabrication</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($my_orders as $order)
                            <tr>
                                <td><strong>{{ $order->client_name }}</strong></td>
                                <td class="small font-monospace">
                                    {{ $order->shoulder_measurement }}cm / {{ $order->chest_measurement }}cm / {{ $order->waist_measurement }}cm / {{ $order->hip_measurement }}cm
                                </td>
                                <td class="small">
                                    M: {{ $order->arm_length ?? '--' }}cm | T: {{ $order->total_length ?? '--' }}cm
                                </td>
                                <td>
                                    <span class="badge @if($order->status == 'En attente') bg-warning text-dark @else bg-info @endif">
                                        {{ $order->status }}
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
@endsection