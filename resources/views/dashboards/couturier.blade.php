@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <div class="row">
        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center mb-5 pb-3 border-bottom" style="border-color: rgba(255,255,255,0.1) !important;">
                <div>
                    <h2 class="mb-1" style="font-family: 'Playfair Display', serif; font-weight: 700; color: var(--color-gold);">Mon Atelier de Confection</h2>
                    <p class="text-white-50 small mb-0">Retrouvez ci-dessous la liste de vos tâches et vêtements assignés.</p>
                </div>
            </div>

            @php
                $current_period = request('period', 'all');
            @endphp
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
                <h3 style="font-family: 'Playfair Display', serif; color: var(--color-gold); font-weight: 600; margin-bottom: 0;">Liste de mes travaux</h3>
                
                <div class="d-flex rounded-3 p-1" style="background-color: #262322; border: 1px solid rgba(255,255,255,0.08);">
                    <a href="{{ route('couturier.rapport', ['period' => 'all']) }}" class="btn btn-sm px-3 py-1.5 fw-semibold transition-all {{ $current_period == 'all' ? 'active-filter' : 'text-white-50' }}">Tous</a>
                    <a href="{{ route('couturier.rapport', ['period' => 'today']) }}" class="btn btn-sm px-3 py-1.5 fw-semibold transition-all {{ $current_period == 'today' ? 'active-filter' : 'text-white-50' }}">Aujourd'hui</a>
                    <a href="{{ route('couturier.rapport', ['period' => 'week']) }}" class="btn btn-sm px-3 py-1.5 fw-semibold transition-all {{ $current_period == 'week' ? 'active-filter' : 'text-white-50' }}">Semaine</a>
                    <a href="{{ route('couturier.rapport', ['period' => 'month']) }}" class="btn btn-sm px-3 py-1.5 fw-semibold transition-all {{ $current_period == 'month' ? 'active-filter' : 'text-white-50' }}">Mois</a>
                    <a href="{{ route('couturier.rapport', ['period' => 'quarter']) }}" class="btn btn-sm px-3 py-1.5 fw-semibold transition-all {{ $current_period == 'quarter' ? 'active-filter' : 'text-white-50' }}">Trimestre</a>
                    <a href="{{ route('couturier.rapport', ['period' => 'year']) }}" class="btn btn-sm px-3 py-1.5 fw-semibold transition-all {{ $current_period == 'year' ? 'active-filter' : 'text-white-50' }}">Annuel</a>
                </div>
            </div>

            <style>
                .active-filter {
                    background-color: var(--color-gold) !important;
                    color: #1A1818 !important;
                    border-radius: 6px;
                }
                .transition-all {
                    transition: all 0.2s ease-in-out;
                    border: none;
                    font-size: 0.8rem;
                }
                .atelier-table {
                    width: 100%;
                    border-collapse: separate;
                    border-spacing: 0 12px;
                }
                /* Couleur du bloc passée en Noir (#141619) */
                .atelier-table tr {
                    background-color: #141619 !important;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
                    transition: transform 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
                }
                .atelier-table tr:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.35);
                }
                /* Adaptation des écritures pour le fond noir */
                .atelier-table td {
                    padding: 1.25rem 1rem;
                    vertical-align: middle;
                    border-top: 1px solid rgba(255, 255, 255, 0.05);
                    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
                    color: #FFFFFF !important;
                }
                .atelier-table td:first-child {
                    border-left: 1px solid rgba(255, 255, 255, 0.05);
                    border-top-left-radius: 8px;
                    border-bottom-left-radius: 8px;
                }
                .atelier-table td:last-child {
                    border-right: 1px solid rgba(255, 255, 255, 0.05);
                    border-top-right-radius: 8px;
                    border-bottom-right-radius: 8px;
                }
                .measurement-tag {
                    font-size: 0.8rem;
                    color: #FFFFFF;
                    background-color: rgba(255, 255, 255, 0.07);
                    padding: 0.2rem 0.5rem;
                    border-radius: 4px;
                    border: 1px solid rgba(255,255,255,0.1);
                }
                .status-badge {
                    padding: 0.4rem 0.8rem;
                    border-radius: 30px;
                    font-size: 0.75rem;
                    font-weight: 500;
                    display: inline-block;
                }
                .status-waiting { 
                    background-color: rgba(255, 193, 7, 0.15); 
                    color: #FFC107; 
                    border: 1px solid rgba(255, 193, 7, 0.3); 
                }
                .status-processing { 
                    background-color: rgba(13, 110, 253, 0.15); 
                    color: #0D6EFD; 
                    border: 1px solid rgba(13, 110, 253, 0.3); 
                }
            </style>

            <div class="mb-3">
                <h4 class="h6 text-white-50 text-uppercase fw-semibold" style="letter-spacing: 0.5px;">Mes Confections en Cours</h4>
            </div>

            @if($my_orders->isEmpty())
                <div class="card text-center p-5 border-0 shadow-sm" style="background-color: #141619 !important;">
                    <div class="card-body py-4">
                        <h5 class="fw-medium text-white mb-1" style="font-size: 1rem;">Aucune pièce ne vous est attribuée pour le moment.</h5>
                        <p class="text-white-50 small mb-0 fw-light">Dès que l'administration vous assignera un projet, il apparaîtra ici.</p>
                    </div>
                </div>
            @else
                <table class="atelier-table">
                    <tbody>
                        @foreach($my_orders as $order)
                        <tr>
                            <td style="width: 25%;">
                                <small class="text-white-50 d-block" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Cliente</small>
                                <span class="fw-semibold text-white" style="font-size: 0.95rem;">{{ $order->client_name }}</span>
                            </td>

                            <td style="width: 35%;">
                                <small class="text-white-50 d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Anatomie (Ép. / Poit. / Tail. / Hanch.)</small>
                                <div class="d-flex gap-1 font-monospace">
                                    <span class="measurement-tag">{{ $order->shoulder_measurement }}</span>
                                    <span class="measurement-tag">{{ $order->chest_measurement }}</span>
                                    <span class="measurement-tag">{{ $order->waist_measurement }}</span>
                                    <span class="measurement-tag">{{ $order->hip_measurement }}</span>
                                </div>
                            </td>

                            <td style="width: 25%;">
                                <small class="text-white-50 d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Longueurs (Manches | Totale)</small>
                                <span class="small text-white font-monospace" style="opacity: 0.85;">
                                    M : {{ $order->arm_length ?? '--' }} cm &nbsp;|&nbsp; T : {{ $order->total_length ?? '--' }} cm
                                </span>
                            </td>

                            <td style="width: 15%; text-align: right;">
                                <small class="text-white-50 d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; text-align: left;">État</small>
                                <div style="text-align: left;">
                                    <span class="status-badge @if($order->status == 'En attente') status-waiting @else status-processing @endif">
                                        {{ $order->status }}
                                    </span>
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