@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <div class="row">
        <div class="col-12">

            <div class="d-flex justify-content-between align-items-center mb-5 pb-3 border-bottom">
                <div>
                    <h2 class="mb-1" style="font-family: 'Playfair Display', serif; font-weight: 700; color: var(--color-dark);">Mon Atelier de Confection</h2>
                    <p class="text-muted small mb-0">Retrouvez ci-dessous la liste de vos tâches et vêtements assignés.</p>
                </div>
            </div>

            <div class="mb-3">
                <h4 class="h6 text-muted text-uppercase fw-semibold" style="letter-spacing: 0.5px;">Mes Confections en Cours</h4>
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
                .measurement-tag {
                    font-size: 0.8rem;
                    color: var(--color-dark);
                    background-color: var(--color-bg-light);
                    padding: 0.2rem 0.5rem;
                    border-radius: 4px;
                    border: 1px solid rgba(0,0,0,0.03);
                }
                .status-badge {
                    padding: 0.4rem 0.8rem;
                    border-radius: 30px;
                    font-size: 0.75rem;
                    font-weight: 500;
                    display: inline-block;
                    border: 1px solid rgba(0,0,0,0.08);
                }
                .status-waiting { background-color: #FFF9E6; color: #8A6D1C; border-color: rgba(138,109,28,0.1); }
                .status-processing { background-color: #EBF3F5; color: #2B5861; border-color: rgba(43,88,97,0.1); }
            </style>

            @if($my_orders->isEmpty())
                <div class="card text-center p-5 border-0 shadow-sm">
                    <div class="card-body py-4">
                        <h5 class="fw-medium text-dark mb-1" style="font-size: 1rem;">Aucune pièce ne vous est attribuée pour le moment.</h5>
                        <p class="text-muted small mb-0 fw-light">Dès que l'administration vous assignera un projet, il apparaîtra ici.</p>
                    </div>
                </div>
            @else
                <table class="atelier-table">
                    <tbody>
                        @foreach($my_orders as $order)
                        <tr>
                            <td style="width: 25%;">
                                <small class="text-muted d-block" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Cliente</small>
                                <span class="fw-semibold text-dark" style="font-size: 0.95rem;">{{ $order->client_name }}</span>
                            </td>

                            <td style="width: 35%;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Anatomie (Ép. / Poit. / Tail. / Hanch.)</small>
                                <div class="d-flex gap-1 font-monospace">
                                    <span class="measurement-tag">{{ $order->shoulder_measurement }}</span>
                                    <span class="measurement-tag">{{ $order->chest_measurement }}</span>
                                    <span class="measurement-tag">{{ $order->waist_measurement }}</span>
                                    <span class="measurement-tag">{{ $order->hip_measurement }}</span>
                                </div>
                            </td>

                            <td style="width: 25%;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Longueurs (Manches | Totale)</small>
                                <span class="small text-secondary font-monospace">
                                    M : {{ $order->arm_length ?? '--' }} cm &nbsp;|&nbsp; T : {{ $order->total_length ?? '--' }} cm
                                </span>
                            </td>

                            <td style="width: 15%; text-align: right;">
                                <small class="text-muted d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; text-align: left;">État</small>
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