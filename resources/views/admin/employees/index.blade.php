@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">👥 Gestion des Employés</h5>
                    <span class="badge bg-primary">{{ $employees->count() }} au total</span>
                </div>

                <div class="card-body">
                    <!-- Notification flash de succès -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            ✨ {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th># ID</th>
                                    <th>Nom de l'employé</th>
                                    <th>Adresse Email</th>
                                    <th>Rôle / Métier</th>
                                    <th>Date d'inscription</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($employees as $employee)
                                <tr>
                                    <td><strong>#{{ $employee->id }}</strong></td>
                                    <td>{{ $employee->name }}</td>
                                    <td>{{ $employee->email }}</td>
                                    <td>
                                        @if($employee->role->name == 'Admin')
                                            <span class="badge bg-danger">👑 {{ $employee->role->name }}</span>
                                        @elseif($employee->role->name == 'Styliste')
                                            <span class="badge bg-info text-dark">🎨 {{ $employee->role->name }}</span>
                                        @elseif($employee->role->name == 'Couturier')
                                            <span class="badge bg-success">✂️ {{ $employee->role->name }}</span>
                                        @else
                                            <span class="badge bg-secondary">📁 {{ $employee->role->name }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $employee->created_at->format('d/m/Y à H:i') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.employees.edit', $employee->id) }}" class="btn btn-sm btn-warning">Modifier</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection