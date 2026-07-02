@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <div class="row">
        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center mb-5 pb-3 border-bottom">
                <div>
                    <h2 class="mb-1" style="font-family: 'Playfair Display', serif; font-weight: 700; color: var(--color-dark);">Espace Création & Style</h2>
                    <p class="text-muted small mb-0">Outils d'analyses de silhouettes et de recommandations vestimentaires.</p>
                </div>
                <a href="{{ route('orders.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                    <i class="bi bi-geo-alt me-1"></i> Prendre de Nouvelles Mesures
                </a>
            </div>

            <div class="mb-3">
                <h4 class="h6 text-muted text-uppercase fw-semibold" style="letter-spacing: 0.5px;">Dernières fiches de mesures clients</h4>
            </div>

            <style>
                .atelier-table {
                    width: 100%;
                    border-collapse: separate;
                    border-spacing: 0 12px;
                }
                .atelier-table tr {
                    background-color: #FFFFFF;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.015);
                    transition: transform 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
                }
                .atelier-table tr:hover {
                    transform: translateY(-1px);
                    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.03);
                }
                .atelier-table td {
                    padding: 1.25rem 1rem;
                    vertical-align: middle;
                    border-top: 1px solid rgba(0, 0, 0, 0.02);
                    border-bottom: 1px solid rgba(0, 0, 0, 0.02);
                    color: var(--color-dark);
                }
                .atelier-table td:first-child {
                    border-left: 1px solid rgba(0, 0, 0, 0.02);
                    border-top-left-radius: 6px;
                    border-bottom-left-radius: 6px;
                }
                .atelier-table td:last-child {
                    border-right: 1px solid rgba(0, 0, 0, 0.02);
                    border-top-right-radius: 6px;
                    border-bottom-right-radius: 6px;
                }
                .morpho-badge {
                    font-size: 0.85rem;
                    font-weight: 600;
                    padding: 0.25rem 0.6rem;
                    background-color: var(--color-bg-light);
                    color: var(--color-dark);
                    border-radius: 4px;
                    border: 1px solid rgba(0,0,0,0.05);
                }
                .status-badge {
                    padding: 0.4rem 0.8rem;
                    border-radius: 30px;
                    font-size: 0.75rem;
                    font-weight: 500;
                    display: inline-block;
                    border: 1px solid rgba(0,0,0,0.08);
                    background-color: #FAFAFA;
                }
            </style>

            @if($recent_orders->isEmpty())
                <div class="card text-center p-5 border-0 shadow-sm">
                    <div class="card-body py-4">
                        <p class="text-muted mb-0 fw-light">Aucune fiche de mesures disponible pour le moment.</p>
                    </div>
                </div>
            @else
                <table class="atelier-table">
                    <tbody>
                        @foreach($recent_orders as $order)
                        <tr>
                            <td style="width: 20%;">
                                <small class="text-muted d-block" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Date de saisie</small>
                                <span class="text-secondary small">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                            </td>

                            <td style="width: 25%;">
                                <small class="text-muted d-block" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Cliente</small>
                                <span class="fw-semibold text-dark" style="font-size: 0.95rem;">{{ $order->client_name }}</span>
                            </td>

                            <td style="width: 25%;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Silhouette Dominante</small>
                                <span class="morpho-badge">Silhouette {{ $order->dominant_morphology }}</span>
                            </td>

                            <td style="width: 15%;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">État</small>
                                <span class="status-badge">{{ $order->status }}</span>
                            </td>

                            <td style="width: 15%; text-align: right;">
                                <a href="{{ route('orders.index') }}" class="btn btn-sm btn-secondary py-1 px-3" style="font-size: 0.75rem; border-color: rgba(0,0,0,0.1) !important;">
                                    <i class="bi bi-search me-1"></i> Consulter
                                </a>
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