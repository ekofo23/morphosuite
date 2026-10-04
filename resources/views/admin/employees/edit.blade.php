@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">

            <div class="mb-5 pb-3 border-bottom" style="border-color: rgba(255,255,255,0.1) !important;">
                <h2 class="mb-1" style="font-family: 'Playfair Display', serif; font-weight: 700; color: #D4AF37;">Modifier l'habilitation</h2>
                <p class="text-white-50 small mb-0">Attribuez un nouveau rôle ou métier au sein de l'organisation.</p>
            </div>

            <div class="p-4 mb-4 rounded border" style="background-color: #121316 !important; background: #121316 !important; border-color: rgba(255,255,255,0.05) !important; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);">
                <div class="row">
                    <div class="col-sm-6 mb-3 mb-sm-0">
                        <small class="text-white-50 d-block text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Collaborateur</small>
                        <span class="fw-semibold" style="font-size: 0.95rem; color: #FFFFFF !important;">{{ $employee->name }}</span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-white-50 d-block text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Adresse de contact</small>
                        <span class="small" style="color: #E5E7EB !important;">{{ $employee->email }}</span>
                    </div>
                </div>
            </div>

            <form action="{{ route('admin.employees.update', $employee->id) }}" method="POST" class="mt-4">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="role_id" class="form-label small fw-semibold text-uppercase text-white-50" style="letter-spacing: 0.5px;">Attribuer un nouveau métier</label>
                    <select class="form-select py-2.5 px-3" name="role_id" id="role_id" required style="background-color: rgba(255, 255, 255, 0.08) !important; border: 1px solid rgba(255, 255, 255, 0.15) !important; color: #FFFFFF !important; font-size: 0.9rem; border-radius: 4px;">
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" {{ $employee->role_id == $role->id ? 'selected' : '' }} style="background-color: #121316; color: #fff;">
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-5 pt-3 border-top" style="border-top: 1px solid rgba(255,255,255,0.1) !important;">
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary py-2 px-4" style="font-size: 0.85rem; border-color: rgba(255,255,255,0.1) !important;">
                        Annuler
                    </a>
                    <button type="submit" class="btn btn-primary py-2 px-4" style="font-size: 0.85rem; background-color: #D4AF37 !important; color: #1A1818 !important; border: none; font-weight: 600;">
                        Enregistrer les modifications
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection