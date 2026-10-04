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

            <div class="d-flex justify-content-between align-items-center mb-5 pb-3 border-bottom" style="border-color: rgba(255,255,255,0.1) !important;">
                <div>
                    <h2 class="mb-1" style="font-family: 'Playfair Display', serif; font-weight: 700; color: #D4AF37;">Gestion des Employés</h2>
                    <p class="text-white-50 small mb-0">Pilotez les accès de l'équipe et assignez les rôles au sein de l'atelier.</p>
                </div>
                <span class="badge px-3 py-2 rounded-pill border fw-medium" style="background-color: rgba(255, 255, 255, 0.08) !important; color: #FFFFFF !important; font-size: 0.8rem; border-color: rgba(255,255,255,0.1) !important;">
                    {{ $employees->count() }} membres au total
                </span>
            </div>

            <style>
                .atelier-table {
                    width: 100%;
                    border-collapse: separate;
                    border-spacing: 0 12px;
                    background: transparent !important;
                    background-color: transparent !important;
                }
                /* Arrière-plan mis à jour avec le noir premium (#121316) */
                .atelier-table tr {
                    background-color: #121316 !important;
                    background: #121316 !important;
                    box-shadow: 0 4px 12px rgba(0,0,0,0.25) !important;
                    transition: transform 0.15s ease-in-out;
                }
                .atelier-table tr:hover {
                    transform: translateY(-2px);
                }
                /* Badges d'habilitation adaptés en translucide */
                .role-badge {
                    padding: 0.4rem 0.8rem;
                    border-radius: 30px;
                    font-size: 0.75rem;
                    font-weight: 500;
                    display: inline-block;
                }
                .role-admin { background-color: rgba(239, 68, 68, 0.2) !important; color: #FCA5A5 !important; border: 1px solid rgba(239, 68, 68, 0.25); }
                .role-stylist { background-color: rgba(59, 130, 246, 0.2) !important; color: #93C5FD !important; border: 1px solid rgba(59, 130, 246, 0.25); }
                .role-tailor { background-color: rgba(245, 158, 11, 0.2) !important; color: #FDE68A !important; border: 1px solid rgba(245, 158, 11, 0.25); }
                .role-default { background-color: rgba(255, 255, 255, 0.1) !important; color: #D1D5DB !important; border: 1px solid rgba(255, 255, 255, 0.15); }
            </style>

            <table class="atelier-table" style="background: transparent !important;">
                <tbody style="background: transparent !important;">
                    @foreach($employees as $employee)
                    <tr>
                        
                        <td style="width: 25%; padding: 1.25rem 1rem; vertical-align: middle; border-top: 1px solid rgba(255, 255, 255, 0.05) !important; border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important; border-left: 1px solid rgba(255, 255, 255, 0.05) !important; border-top-left-radius: 6px; border-bottom-left-radius: 6px; background: transparent !important;">
                            <div class="d-flex align-items-center gap-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center fw-semibold" style="width: 36px; height: 36px; background-color: rgba(255,255,255,0.06) !important; color: #9CA3AF !important; font-size: 0.8rem; border: 1px solid rgba(255,255,255,0.1);">
                                    #{{ $employee->id }}
                                </div>
                                <div class="fw-semibold" style="font-size: 0.95rem; color: #FFFFFF !important;">
                                    {{ $employee->name }}
                                </div>
                            </div>
                        </td>

                        <td style="width: 25%; padding: 1.25rem 1rem; vertical-align: middle; border-top: 1px solid rgba(255, 255, 255, 0.05) !important; border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important; background: transparent !important;">
                            <small class="text-white-50 d-block" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Contact</small>
                            <span class="small fw-medium" style="color: #E5E7EB !important;">{{ $employee->email }}</span>
                        </td>

                        <td style="width: 20%; padding: 1.25rem 1rem; vertical-align: middle; border-top: 1px solid rgba(255, 255, 255, 0.05) !important; border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important; background: transparent !important;">
                            <small class="text-white-50 d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Habilitation</small>
                            @if($employee->role && $employee->role->name == 'Admin')
                                <span class="role-badge role-admin">{{ $employee->role->name }}</span>
                            @elseif($employee->role && $employee->role->name == 'Styliste')
                                <span class="role-badge role-stylist">{{ $employee->role->name }}</span>
                            @elseif($employee->role && $employee->role->name == 'Couturier')
                                <span class="role-badge role-tailor">{{ $employee->role->name }}</span>
                            @else
                                <span class="role-badge role-default">{{ $employee->role ? $employee->role->name : 'Aucun' }}</span>
                            @endif
                        </td>

                        <td style="width: 20%; padding: 1.25rem 1rem; vertical-align: middle; border-top: 1px solid rgba(255, 255, 255, 0.05) !important; border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important; background: transparent !important;">
                            <small class="text-white-50 d-block" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">Inscription</small>
                            <span class="small text-white-50">{{ $employee->created_at->format('d/m/Y à H:i') }}</span>
                        </td>

                        <td style="width: 10%; text-align: right; padding: 1.25rem 1rem; vertical-align: middle; border-top: 1px solid rgba(255, 255, 255, 0.05) !important; border-bottom: 1px solid rgba(255, 255, 255, 0.05) !important; border-right: 1px solid rgba(255, 255, 255, 0.05) !important; border-top-right-radius: 6px; border-bottom-right-radius: 6px; background: transparent !important;">
                            <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-sm btn-outline-light py-1 px-3" style="font-size: 0.75rem; border-color: rgba(255,255,255,0.2) !important;">
                                <i class="bi bi-pencil-square me-1"></i> Modifier
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</div>
@endsection