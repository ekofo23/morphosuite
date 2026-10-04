@extends('layouts.app')

@section('content')
<div class="container-fluid px-0" style="max-width: 900px;">
    <div class="row justify-content-center">
        <div class="col-12">
            
            @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4" role="alert" style="background-color: var(--status-waiting-bg); color: var(--status-waiting-color); font-size: 0.9rem;">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-5 pb-3 border-bottom">
                <div>
                    <!-- Écritures du haut passées en Or -->
                    <h2 class="mb-1" style="font-family: 'Playfair Display', serif; font-weight: 700; color: #D4AF37;">Nouvelle Fiche Mesures</h2>
                    <!-- Phrase sous le titre passée en blanc (text-white) -->
                    <p class="text-white small mb-0">Enregistrement des mensurations anatomiques et lancement de l'analyse morphologique.</p>
                </div>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm d-flex align-items-center gap-2">
                    <i class="bi bi-arrow-left"></i> Retour au carnet
                </a>
            </div>

            <form action="{{ route('orders.store') }}" method="POST">
                @csrf

                <style>
                    .atelier-section-title {
                        font-family: 'Inter', sans-serif;
                        font-weight: 600;
                        font-size: 1.1rem;
                        color: var(--color-dark);
                        letter-spacing: 0.3px;
                    }
                    .form-control, .form-select {
                        border: 1px solid var(--color-lin);
                        border-radius: 4px;
                        padding: 0.6rem 0.75rem;
                        font-size: 0.9rem;
                        color: var(--color-dark);
                        background-color: #FFFFFF;
                        transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
                    }
                    .form-control:focus, .form-select:focus {
                        border-color: var(--color-emeraude);
                        box-shadow: 0 0 0 0.25rem rgba(10, 58, 47, 0.08);
                        outline: 0;
                    }
                    .form-label {
                        font-size: 0.8rem;
                        font-weight: 500;
                        color: #555555;
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                        margin-bottom: 0.4rem;
                    }
                    /* Partie "cm" modifiée en sombre léger avec texte blanc */
                    .measurement-addon {
                        background-color: #1D1E22 !important;
                        border: 1px solid #1D1E22 !important;
                        color: #FFFFFF !important;
                        font-size: 0.85rem;
                    }
                </style>

                <div class="card border-0 shadow-sm p-4 mb-4">
                    <h4 class="atelier-section-title border-bottom pb-2 mb-4">Profil de la Cliente</h4>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="client_name" class="form-label">Nom Complet de la Cliente *</label>
                            <input type="text" name="client_name" id="client_name" class="form-control @error('client_name') is-invalid @enderror" value="{{ old('client_name') }}" required placeholder="Ex: Élise Martin">
                            @error('client_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="client_phone" class="form-label">Numéro de Téléphone</label>
                            <input type="text" name="client_phone" id="client_phone" class="form-control" value="{{ old('client_phone') }}" placeholder="Ex: +33 6 00 00 00 00">
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm p-4 mb-4">
                    <h4 class="atelier-section-title border-bottom pb-2 mb-4">Mensurations Anatomiques (MorphoCore)</h4>
                    
                    <div class="row mb-4">
                        <div class="col-md-3 col-6 mb-3">
                            <label for="shoulder_measurement" class="form-label">Largeur Épaules *</label>
                            <div class="input-group">
                                <input type="number" name="shoulder_measurement" id="shoulder_measurement" class="form-control @error('shoulder_measurement') is-invalid @enderror" value="{{ old('shoulder_measurement') }}" placeholder="92" required>
                                <span class="input-group-text measurement-addon">cm</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <label for="chest_measurement" class="form-label">Tour de Poitrine *</label>
                            <div class="input-group">
                                <input type="number" name="chest_measurement" id="chest_measurement" class="form-control @error('chest_measurement') is-invalid @enderror" value="{{ old('chest_measurement') }}" placeholder="95" required>
                                <span class="input-group-text measurement-addon">cm</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <label for="waist_measurement" class="form-label">Tour de Taille *</label>
                            <div class="input-group">
                                <input type="number" name="waist_measurement" id="waist_measurement" class="form-control @error('waist_measurement') is-invalid @enderror" value="{{ old('waist_measurement') }}" placeholder="70" required>
                                <span class="input-group-text measurement-addon">cm</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-6 mb-3">
                            <label for="hip_measurement" class="form-label">Tour de Hanches *</label>
                            <div class="input-group">
                                <input type="number" name="hip_measurement" id="hip_measurement" class="form-control @error('hip_measurement') is-invalid @enderror" value="{{ old('hip_measurement') }}" placeholder="108" required>
                                <span class="input-group-text measurement-addon">cm</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
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
                </div>

                <div class="card border-0 shadow-sm p-4 mb-5">
                    <h4 class="atelier-section-title border-bottom pb-2 mb-4">Spécifications de Confection</h4>
                    <div class="row {{ Auth::user()->role === 'admin' ? 'mb-3' : '' }}">
                        <div class="col-md-6 mb-3">
                            <label for="arm_length" class="form-label">Longueur des Manches</label>
                            <div class="input-group">
                                <input type="number" name="arm_length" id="arm_length" class="form-control" value="{{ old('arm_length') }}" placeholder="Optionnel">
                                <span class="input-group-text measurement-addon">cm</span>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="total_length" class="form-label">Longueur Totale du Vêtement</label>
                            <div class="input-group">
                                <input type="number" name="total_length" id="total_length" class="form-control" value="{{ old('total_length') }}" placeholder="Optionnel">
                                <span class="input-group-text measurement-addon">cm</span>
                            </div>
                        </div>
                    </div>

                    @if(Auth::user()->role === 'admin')
                    <div class="mb-2">
                        <label for="user_id" class="form-label">Assignation Artisan</label>
                        <select name="user_id" id="user_id" class="form-select">
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}" {{ Auth::id() == $emp->id ? 'selected' : '' }}>{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif
                </div>

                <div class="d-flex justify-content-end mb-5">
                    <!-- Bouton d'enregistrement Or avec texte sombre -->
                    <button type="submit" class="btn btn-lg px-5 fw-bold" style="font-size: 0.95rem; letter-spacing: 0.3px; background-color: #D4AF37; color: #121316; border: none; border-radius: 4px;">
                        Enregistrer et lancer l'analyse MorphoCore
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection