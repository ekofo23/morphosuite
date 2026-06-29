@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">✏️ Modifier le rôle de l'employé</h5>
                </div>

                <div class="card-body">
                    <div class="mb-4 p-3 bg-light rounded">
                        <p class="mb-1"><strong>Nom :</strong> {{ $employee->name }}</p>
                        <p class="mb-0"><strong>Email :</strong> {{ $employee->email }}</p>
                    </div>

                    <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST">
                        @csrf
                        @method('PUT') <div class="mb-3">
                            <label for="role_id" class="form-label font-weight-bold">Attribuer un nouveau métier :</label>
                            <select class="form-select" name="role_id" id="role_id" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ $employee->role_id == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection