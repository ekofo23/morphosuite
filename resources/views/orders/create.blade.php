@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">📏 Nouvelle Fiche de Mesures & Commande</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf

                        <h4 class="text-secondary border-bottom pb-2 mb-3">👤 Informations Client</h4>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="client_name" class="form-label">Nom Complet de la Cliente *</label>
                                <input type="text" name="client_name" id="client_name" class="form-control @error('client_name') is-invalid @enderror" value="{{ old('client_name') }}" required>
                                @error('client_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="client_phone" class="form-label">Numéro de Téléphone</label>
                                <input type="text" name="client_phone" id="client_phone" class="form-control" value="{{ old('client_phone') }}">
                            </div>
                        </div>

                        <h4 class="text-secondary border-bottom pb-2 mb-3">🧠 Analyse MorphoCore (Mensurations en cm)</h4>
                        <div class="row mb-3">
                            <div class="col-md-3 col-6 mb-3">
                                <label for="shoulder_measurement" class="form-label">Largeur Épaules *</label>
                                <input type="number" name="shoulder_measurement" id="shoulder_measurement" class="form-control @error('shoulder_measurement') is-invalid @enderror" value="{{ old('shoulder_measurement') }}" placeholder="ex: 92" required>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <label for="chest_measurement" class="form-label">Tour de Poitrine *</label>
                                <input type="number" name="chest_measurement" id="chest_measurement" class="form-control @error('chest_measurement') is-invalid @enderror" value="{{ old('chest_measurement') }}" placeholder="ex: 95" required>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <label for="waist_measurement" class="form-label">Tour de Taille *</label>
                                <input type="number" name="waist_measurement" id="waist_measurement" class="form-control @error('waist_measurement') is-invalid @enderror" value="{{ old('waist_measurement') }}" placeholder="ex: 70" required>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <label for="hip_measurement" class="form-label">Tour de Hanches *</label>
                                <input type="number" name="hip_measurement" id="hip_measurement" class="form-control @error('hip_measurement') is-invalid @enderror" value="{{ old('hip_measurement') }}" placeholder="ex: 108" required>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="buste_length" class="form-label">Longueur du Buste *</label>
                                <select name="buste_length" id="buste_length" class="form-select" required>
                                    <option value="normal" {{ old('buste_length') == 'normal' ? 'selected' : '' }}>Normal</option>
                                    <option value="court" {{ old('buste_length') == 'court' ? 'selected' : '' }}>Court</option>
                                    <option value="long" {{ old('buste_length') == 'long' ? 'selected' : '' }}>Long</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="posture_type" class="form-label">Type de Posture *</label>
                                <select name="posture_type" id="posture_type" class="form-select" required>
                                    <option value="standard" {{ old('posture_type') == 'standard' ? 'selected' : '' }}>Standard / Alignée</option>
                                    <option value="cambrée" {{ old('posture_type') == 'cambrée' ? 'selected' : '' }}>Cambrée</option>
                                    <option value="voûtée" {{ old('posture_type') == 'voûtée' ? 'selected' : '' }}>Voûtée</option>
                                </select>
                            </div>
                        </div>

                        <h4 class="text-secondary border-bottom pb-2 mb-3">✂️ Confection (Optionnel)</h4>
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="arm_length" class="form-label">Longueur des Manches (cm)</label>
                                <input type="number" name="arm_length" id="arm_length" class="form-control" value="{{ old('arm_length') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="total_length" class="form-label">Longueur Totale du Vêtement (cm)</label>
                                <input type="number" name="total_length" id="total_length" class="form-control" value="{{ old('total_length') }}">
                            </div>
                        </div>

                        @if(Auth::user()->role->name === 'Admin')
                        <div class="mb-4">
                            <label for="user_id" class="form-label">Assigner à un Couturier / Styliste</label>
                            <select name="user_id" id="user_id" class="form-select">
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ Auth::id() == $emp->id ? 'selected' : '' }}>{{ $emp->name }} ({{ $emp->role->name }})</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">🚀 Lancer l'analyse MorphoCore & Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection