@extends('layouts.app')

@section('content')
<div class="container-fluid px-0">
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="background-color: rgba(43, 84, 58, 0.2); color: #69DB93; border: 1px solid rgba(105, 219, 147, 0.2) !important;">
                    {{ session('success') }}
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="background-color: rgba(168, 50, 50, 0.2); color: #FF8E8E; border: 1px solid rgba(255, 142, 142, 0.2) !important;">
                    <ul class="mb-0 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-5 pb-3 border-bottom" style="border-color: rgba(255,255,255,0.1) !important;">
                <div>
                    <!-- Écriture du haut passée en couleur Or (var(--color-gold)) -->
                    <h2 class="mb-1" style="font-family: 'Playfair Display', serif; font-weight: 700; color: var(--color-gold);">Configuration Générale</h2>
                    <p class="text-white-50 small mb-0">Pilotez l'identité visuelle de l'atelier et gérez les habilitations de l'équipe.</p>
                </div>
            </div>

            <ul class="nav nav-tabs border-bottom-0 mb-4" id="configTabs" role="tablist" style="gap: 10px;">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active px-4 py-2.5 border rounded-top fw-medium text-secondary" id="design-tab" data-bs-toggle="tab" data-bs-target="#design-pane" type="button" role="tab" aria-controls="design-pane" aria-selected="true" style="font-size: 0.9rem;">
                        Design & Identité Visuelle
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-4 py-2.5 border rounded-top fw-medium text-secondary" id="team-tab" data-bs-toggle="tab" data-bs-target="#team-pane" type="button" role="tab" aria-controls="team-pane" aria-selected="false" style="font-size: 0.9rem;">
                        Recrutement & Collaborateurs
                    </button>
                </li>
            </ul>

            <!-- Le conteneur principal passe en arrière-plan noir (#141619) -->
            <div class="tab-content border rounded p-4 p-md-5 shadow-sm" id="configTabsContent" style="background-color: #141619 !important; border-color: rgba(255,255,255,0.08) !important;">
                
                <div class="tab-pane fade show active" id="design-pane" role="tabpanel" aria-labelledby="design-tab" tabindex="0">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="theme_sidebar_color" class="form-label small fw-semibold text-uppercase text-white-50" style="letter-spacing: 0.5px;">Couleur de la Sidebar</label>
                                <div class="d-flex gap-2">
                                    <input type="color" class="form-control form-control-color border-secondary" id="theme_sidebar_color" name="theme_sidebar_color" value="{{ $settings['design']->where('key', 'theme_sidebar_color')->first()->value ?? '#1a1a1a' }}" title="Choisir la couleur">
                                    <input type="text" class="form-control font-monospace text-white bg-dark border-secondary" value="{{ $settings['design']->where('key', 'theme_sidebar_color')->first()->value ?? '#1a1a1a' }}" readonly style="font-size: 0.9rem;">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="theme_gold_color" class="form-label small fw-semibold text-uppercase text-white-50" style="letter-spacing: 0.5px;">Couleur d'accentuation (Détails / Or)</label>
                                <div class="d-flex gap-2">
                                    <input type="color" class="form-control form-control-color border-secondary" id="theme_gold_color" name="theme_gold_color" value="{{ $settings['design']->where('key', 'theme_gold_color')->first()->value ?? '#D4AF37' }}" title="Choisir la couleur">
                                    <input type="text" class="form-control font-monospace text-white bg-dark border-secondary" value="{{ $settings['design']->where('key', 'theme_gold_color')->first()->value ?? '#D4AF37' }}" readonly style="font-size: 0.9rem;">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label for="theme_card_color" class="form-label small fw-semibold text-uppercase text-white-50" style="letter-spacing: 0.5px;">Couleur des Blocs / Cartes</label>
                                <div class="d-flex gap-2">
                                    <input type="color" class="form-control form-control-color border-secondary" id="theme_card_color" name="theme_card_color" value="{{ $settings['design']->where('key', 'theme_card_color')->first()->value ?? '#FFFFFF' }}" title="Choisir la couleur des blocs">
                                    <input type="text" class="form-control font-monospace text-white bg-dark border-secondary" value="{{ $settings['design']->where('key', 'theme_card_color')->first()->value ?? '#FFFFFF' }}" readonly style="font-size: 0.9rem;">
                                </div>
                            </div>

                            <div class="col-12"><hr class="my-3 text-white" style="opacity: 0.12;"></div>

                            <div class="col-md-6">
                                <label for="app_background_type" class="form-label small fw-semibold text-uppercase text-white-50" style="letter-spacing: 0.5px;">Type d'arrière-plan global</label>
                                @php $currentType = $settings['design']->where('key', 'app_background_type')->first()->value ?? 'none'; @endphp
                                <select class="form-select py-2 text-white bg-dark border-secondary" name="app_background_type" id="app_background_type">
                                    <option value="none" {{ $currentType == 'none' ? 'selected' : '' }}>Aucun (Fond épuré d'origine)</option>
                                    <option value="image" {{ $currentType == 'image' ? 'selected' : '' }}>Image personnalisée</option>
                                    <option value="video" {{ $currentType == 'video' ? 'selected' : '' }}>Vidéo d'ambiance (.mp4)</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="app_background_file" class="form-label small fw-semibold text-uppercase text-white-50" style="letter-spacing: 0.5px;">Téléverser le média (Photo ou Vidéo)</label>
                                <input type="file" class="form-control py-2 text-white bg-dark border-secondary" id="app_background_file" name="app_background_file" accept="image/*,video/mp4">
                                @php $filePath = $settings['design']->where('key', 'app_background_file')->first()->value ?? null; @endphp
                                @if($filePath)
                                    <div class="form-text text-success small d-flex align-items-center gap-1 mt-1">
                                        <i class="bi bi-check-circle-fill"></i> Un fichier personnalisé est actuellement actif.
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-5 pt-3 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
                            <button type="submit" class="btn btn-primary py-2 px-4 fw-semibold" style="font-size: 0.85rem; background-color: var(--color-gold) !important; border: none; color: #1A1818;">
                                Enregistrer les préférences graphiques
                            </button>
                        </div>
                    </form>
                </div>

                <div class="tab-pane fade" id="team-pane" role="tabpanel" aria-labelledby="team-tab" tabindex="0">
                    <div class="mb-4">
                        <h4 class="h6 text-white text-uppercase fw-semibold mb-1" style="letter-spacing: 0.5px;">Inscrire un nouveau collaborateur</h4>
                        <p class="text-white-50 small">Remplissez les informations d'authentification pour lui générer un accès direct à l'atelier.</p>
                    </div>

                    <form action="{{ route('admin.settings.storeEmployee') }}" method="POST">
                        @csrf
                        
                        <div class="row g-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label small fw-semibold text-white-50">Nom Complet</label>
                                <input type="text" class="form-control py-2 text-white bg-dark border-secondary" id="name" name="name" placeholder="Ex: Jean Dupont" required value="{{ old('name') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label small fw-semibold text-white-50">Adresse Électronique (Identifiant)</label>
                                <input type="email" class="form-control py-2 text-white bg-dark border-secondary" id="email" name="email" placeholder="Ex: collaborateur@atelier.com" required value="{{ old('email') }}">
                            </div>

                            <div class="col-md-12">
                                <label for="role_id" class="form-label small fw-semibold text-white-50">Habilitation métier</label>
                                <select class="form-select py-2 text-white bg-dark border-secondary" name="role_id" id="role_id" required>
                                    <option value="" disabled selected class="text-muted">Sélectionnez un rôle pour cet utilisateur...</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="password" class="form-label small fw-semibold text-white-50">Mot de passe provisoire</label>
                                <input type="password" class="form-control py-2 text-white bg-dark border-secondary" id="password" name="password" required placeholder="Minimum 8 caractères">
                            </div>

                            <div class="col-md-6">
                                <label for="password_confirmation" class="form-label small fw-semibold text-white-50">Confirmer le mot de passe</label>
                                <input type="password" class="form-control py-2 text-white bg-dark border-secondary" id="password_confirmation" name="password_confirmation" required placeholder="Répétez le mot de passe">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-5 pt-3 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
                            <button type="submit" class="btn btn-primary py-2 px-4 fw-semibold" style="font-size: 0.85rem; background-color: var(--color-gold) !important; border: none; color: #1A1818;">
                                Créer et habiliter le compte
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
</div>

<style>
    /* Onglets adaptés au thème sombre actif */
    #configTabs .nav-link.active {
        background-color: #141619 !important;
        color: #FFFFFF !important;
        border-color: rgba(255, 255, 255, 0.08) rgba(255, 255, 255, 0.08) #141619 !important;
        font-weight: 600 !important;
    }
    #configTabs .nav-link:not(.active) {
        background-color: rgba(255, 255, 255, 0.03) !important;
        color: rgba(255, 255, 255, 0.5) !important;
        border-color: rgba(255, 255, 255, 0.08) !important;
    }
    #configTabs .nav-link:hover:not(.active) {
        color: #FFFFFF !important;
        background-color: rgba(255, 255, 255, 0.08) !important;
    }
    /* placeholders d'inputs plus discrets en mode sombre */
    .form-control::placeholder {
        color: rgba(255,255,255,0.3) !important;
    }
</style>
@endsection