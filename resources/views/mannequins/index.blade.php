@extends('layouts.app')

@section('content')
<div class="container-fluid min-vh-100 py-4" style="background-color: #0B0F19; color: #F8FAFC; font-family: 'Inter', sans-serif;">
    <div class="row g-4">
        
        <div class="col-xl-4 col-lg-5 col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold text-white mb-1">Centre de Données</h5>
                    <p class="text-muted small mb-0">Historique chronologique</p>
                </div>
                <button class="btn btn-sm btn-outline-primary rounded-3 px-3" style="border-color: #3B82F6; color: #3B82F6;">
                  <a class="nav-link {{ Request::is('orders/create') ? 'active' : '' }}" href="{{ route('orders.create') }}">
                      <i class="bi bi-plus-lg me-1"></i> Nouveau 
                  </a>
                </button>
            </div>

            <div class="mb-3 position-relative">
                <span class="position-absolute top-50 start-0 translate-middle-y ps-3 text-muted">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" 
                       id="searchMannequin" 
                       class="form-control border-0 py-2.5 ps-5 rounded-3 text-white" 
                       style="background-color: #171C28; border: 1px solid #242B3D !important;" 
                       placeholder="Rechercher un profil par nom...">
            </div>

            <div class="pe-2" id="mannequinsList" style="max-height: 650px; overflow-y: auto; scrollbar-width: thin;">
                @forelse($mannequins->sortByDesc('created_at') as $mannequin)
                    <div class="card mb-3 border-0 rounded-4 mannequin-card transition-all cursor-pointer"
                         style="background-color: #171C28; border: 1px solid #242B3D !important; transition: all 0.25s ease;"
                         onclick="selectionnerProfil(this)"
                         data-id="{{ $mannequin->id }}"
                         data-nom="{{ strtolower($mannequin->profile_name) }}" {{-- Pour la recherche --}}
                         data-profile-name="{{ $mannequin->profile_name }}"
                         data-date="{{ $mannequin->created_at->format('d/m/Y') }}"
                         data-morpho="{{ $mannequin->dominant_morphology }}"
                         data-epaules="{{ $mannequin->shoulder_measurement }}"
                         data-poitrine="{{ $mannequin->chest_measurement }}"
                         data-taille="{{ $mannequin->waist_measurement }}"
                         data-hanches="{{ $mannequin->hip_measurement }}">
                        
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h6 class="text-white fw-bold mb-1 card-profile-name">{{ $mannequin->profile_name }}</h6>
                                    <span class="text-muted" style="font-size: 0.8rem;">
                                        <i class="bi bi-calendar3 me-1"></i> {{ $mannequin->created_at->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                                <span class="badge rounded-pill px-3 py-1.5 fw-medium" 
                                      style="background-color: rgba(59, 130, 246, 0.1); color: #3B82F6; border: 1px solid rgba(59, 130, 246, 0.2);">
                                    {{ $mannequin->dominant_morphology }}
                                </span>
                            </div>

                            <div class="row g-2 text-center bg-dark-subtle rounded-3 p-2 mb-3" style="background-color: #1F2937 !important;">
                                <div class="col-3 border-end border-secondary border-opacity-25">
                                    <div class="text-white small fw-bold">{{ $mannequin->shoulder_measurement }}</div>
                                    <div class="text-muted" style="font-size: 0.65rem; text-transform: uppercase;">Épa.</div>
                                </div>
                                <div class="col-3 border-end border-secondary border-opacity-25">
                                    <div class="text-white small fw-bold">{{ $mannequin->chest_measurement }}</div>
                                    <div class="text-muted" style="font-size: 0.65rem; text-transform: uppercase;">Poi.</div>
                                </div>
                                <div class="col-3 border-end border-secondary border-opacity-25">
                                    <div class="text-white small fw-bold">{{ $mannequin->waist_measurement }}</div>
                                    <div class="text-muted" style="font-size: 0.65rem; text-transform: uppercase;">Tai.</div>
                                </div>
                                <div class="col-3">
                                    <div class="text-white small fw-bold">{{ $mannequin->hip_measurement }}</div>
                                    <div class="text-muted" style="font-size: 0.65rem; text-transform: uppercase;">Han.</div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center pt-1">
                                <span class="text-muted small" style="font-size: 0.75rem;">Fiche #{{ $mannequin->order_id }}</span>
                                <button class="btn btn-primary btn-sm rounded-3 px-3 fw-medium text-white" style="background-color: #3B82F6; border: none;">
                                    Visualiser <i class="bi bi-cpu ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 rounded-4" style="background-color: #171C28; border: 1px dashed #242B3D;">
                        <i class="bi bi-person-bounding-box text-muted display-6 mb-3 d-block"></i>
                        <p class="text-muted mb-0">Aucun profil enregistré dans l'historique.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="col-xl-8 col-lg-7 col-md-12">
            <div class="card border-0 rounded-4 overflow-hidden mb-4 position-relative" style="background-color: #171C28; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);">
                <div class="card-header border-0 py-3 d-flex align-items-center justify-content-between" style="background-color: #1F2937;">
                    <div class="d-flex align-items-center gap-2">
                        <div class="spinner-grow spinner-grow-sm text-primary" role="status" id="renderPulse" style="display: none;"></div>
                        <h6 class="m-0 text-white fw-bold tracking-wide"><i class="bi bi-display me-2 text-primary"></i>MONITEUR DE RENDU 3D</h6>
                    </div>
                    <span class="badge bg-dark text-muted font-monospace small px-2 py-1" style="font-size: 0.7rem;">CAD-MODE v2.4</span>
                </div>

                <div class="position-relative" style="height: 520px; background: linear-gradient(180deg, #131824 0%, #0B0F19 100%);">
                    <div id="container-3d" class="w-100 h-100"></div>

                    <div id="info-overlay" class="position-absolute top-50 start-50 translate-middle text-center w-75 p-5 rounded-4" 
                         style="background: rgba(11, 15, 25, 0.85); border: 1px dashed #242B3D; backdrop-filter: blur(8px); z-index: 100; transition: opacity 0.4s ease;">
                        <i class="bi bi-shield-shaded text-primary display-4 mb-3 d-block"></i>
                        <h5 class="text-white fw-bold mb-2">Centre de Visualisation Morphologique</h5>
                        <p class="text-muted small mx-auto mb-0" style="max-width: 420px;">
                            Sélectionnez un mannequin dans l'historique de gauche afin de lancer la visualisation 3D.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-4 col-6">
                    <div class="card border-0 rounded-4 p-3 text-center" style="background-color: #171C28;">
                        <div class="text-muted small text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.05em;">Morphologie</div>
                        <h5 class="text-primary fw-bold mb-0" id="stat-morpho">--</h5>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="card border-0 rounded-4 p-3 text-center" style="background-color: #171C28;">
                        <div class="text-muted small text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.05em;">Épaules</div>
                        <h4 class="text-white fw-bold mb-0 font-monospace" id="stat-epaules">-- <span class="fs-6 text-muted">cm</span></h4>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="card border-0 rounded-4 p-3 text-center" style="background-color: #171C28;">
                        <div class="text-muted small text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.05em;">Poitrine</div>
                        <h4 class="text-white fw-bold mb-0 font-monospace" id="stat-poitrine">-- <span class="fs-6 text-muted">cm</span></h4>
                    </div>
                </div>
                <div class="col-md-2 col-6">
                    <div class="card border-0 rounded-4 p-3 text-center" style="background-color: #171C28;">
                        <div class="text-muted small text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.05em;">Taille</div>
                        <h4 class="text-white fw-bold mb-0 font-monospace" id="stat-taille">-- <span class="fs-6 text-muted">cm</span></h4>
                    </div>
                </div>
                <div class="col-md-2 col-12">
                    <div class="card border-0 rounded-4 p-3 text-center" style="background-color: #171C28;">
                        <div class="text-muted small text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.05em;">Hanches</div>
                        <h4 class="text-white fw-bold mb-0 font-monospace" id="stat-hanches">-- <span class="fs-6 text-muted">cm</span></h4>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .mannequin-card:hover {
        background-color: #1F2637 !important;
        transform: translateY(-2px);
    }
    .mannequin-card.active-dark-card {
        border-color: #3B82F6 !important;
        background-color: #1E293B !important;
        box-shadow: 0 0 12px rgba(59, 130, 246, 0.25);
    }
    .cursor-pointer { cursor: pointer; }
    /* Style pour cacher proprement un élément filtré */
    .d-none-filter { display: none !important; }

    
    
</style>
@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

<script>
    let scene, camera, renderer, mannequinMesh;
    
    document.addEventListener('DOMContentLoaded', () => {
        // 1. DISPARITION AUTOMATIQUE FORCEE DE LA SIDEBAR DE TON DESIGN DE BASE
        // Cette logique cherche et clique sur ton bouton de bascule ou applique la classe appropriée au body
        const btnToggleSidebar = document.querySelector('[data-bs-toggle="sidebar"]') || 
                                 document.querySelector('.sidebar-toggle') || 
                                 document.getElementById('sidebarToggle');
        
        const bodyElement = document.body;
        const sidebarContainer = document.querySelector('.sidebar') || document.getElementById('sidebar');

        // Si ton design utilise une classe globale sur le body (ex: sidebar-toggled, sidebar-enable)
        if (bodyElement && !bodyElement.classList.contains('sidebar-toggled')) {
            bodyElement.classList.add('sidebar-mini', 'sidebar-collapse'); 
        }

        // Si un bouton de menu existe, on simule un clic s'il est resté ouvert
        if (btnToggleSidebar && !sidebarContainer?.classList.contains('collapsed')) {
            btnToggleSidebar.click();
        }

        // Si la sidebar a directement une classe d'état
        if (sidebarContainer) {
            sidebarContainer.classList.add('collapsed', 'd-none');
            const mainContent = document.querySelector('.main-content') || document.getElementById('main-content');
            if (mainContent) mainContent.classList.add('w-100', 'm-0', 'p-0');
        }

        // Forcer le recalcul des dimensions du canvas
        setTimeout(() => { window.dispatchEvent(new Event('resize')); }, 350);

        // 3. LOGIQUE FILTRANTE DE LA BARRE DE RECHERCHE EN TEMPS RÉEL (JS)
        const searchInput = document.getElementById('searchMannequin');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase().trim();
                const cards = document.querySelectorAll('.mannequin-card');
                
                cards.forEach(card => {
                    const nomProfil = card.getAttribute('data-nom');
                    if (nomProfil.includes(query)) {
                        card.classList.remove('d-none-filter');
                    } else {
                        card.classList.add('d-none-filter');
                    }
                });
            });
        }

        init3DViewport();
    });

    function init3DViewport() {
        const container = document.getElementById('container-3d');
        if (!container) return;
        
        scene = new THREE.Scene();
        scene.background = null; 

        camera = new THREE.PerspectiveCamera(35, container.clientWidth / container.clientHeight, 0.1, 100);
        camera.position.set(0, 0.1, 3.8);

        renderer = new THREE.WebGLRenderer({ antialias: true, alpha: true });
        renderer.setPixelRatio(window.devicePixelRatio);
        renderer.setSize(container.clientWidth, container.clientHeight);
        
        container.innerHTML = ''; 
        container.appendChild(renderer.domElement);

        const ambientLight = new THREE.AmbientLight(0xffffff, 0.4);
        scene.add(ambientLight);

        const studioLumiereFace = new THREE.DirectionalLight(0xffffff, 0.8);
        studioLumiereFace.position.set(2, 4, 5);
        scene.add(studioLumiereFace);

        const lumContreJour = new THREE.DirectionalLight(0x93C5FD, 0.55); 
        lumContreJour.position.set(-4, 2, -3);
        scene.add(lumContreJour);

        creerStructureMannequinPure();
        animate();
    }

    function creerStructureMannequinPure() {
        mannequinMesh = new THREE.Group();

        const materialMat = new THREE.MeshStandardMaterial({ 
            color: '#E2E8F0', 
            roughness: 0.45,
            metalness: 0.02
        });

        const points = [
            new THREE.Vector2(0.0, 1.40),   
            new THREE.Vector2(0.045, 1.38), 
            new THREE.Vector2(0.05, 1.22),  
            new THREE.Vector2(0.26, 1.14),  
            new THREE.Vector2(0.21, 0.98),  
            new THREE.Vector2(0.27, 0.82),  
            new THREE.Vector2(0.19, 0.62),  
            new THREE.Vector2(0.13, 0.36),  
            new THREE.Vector2(0.18, 0.12),  
            new THREE.Vector2(0.28, -0.15), 
            new THREE.Vector2(0.20, -0.48), 
            new THREE.Vector2(0.13, -0.80), 
            new THREE.Vector2(0.07, -1.15), 
            new THREE.Vector2(0.0, -1.20)   
        ];

        const bodyGeo = new THREE.LatheGeometry(points, 64);
        const bodyMesh = new THREE.Mesh(bodyGeo, materialMat);
        bodyMesh.name = "buste_principal";
        mannequinMesh.add(bodyMesh);

        const teteGeo = new THREE.SphereGeometry(0.135, 32, 32);
        teteGeo.scale(1, 1.35, 1); 
        const tete = new THREE.Mesh(teteGeo, materialMat);
        tete.position.y = 1.56;
        mannequinMesh.add(tete);

        mannequinMesh.position.y = -0.1;
        scene.add(mannequinMesh);
    }

    function selectionnerProfil(card) {
        const overlay = document.getElementById('info-overlay');
        if (overlay) overlay.style.opacity = '0';
        setTimeout(() => { if(overlay) overlay.style.display = 'none'; }, 400);

        document.querySelectorAll('.mannequin-card').forEach(c => c.classList.remove('active-dark-card'));
        card.classList.add('active-dark-card');

        const pulse = document.getElementById('renderPulse');
        if(pulse) pulse.style.display = 'inline-block';

        const epaules = parseFloat(card.getAttribute('data-epaules'));
        const poitrine = parseFloat(card.getAttribute('data-poitrine'));
        const taille = parseFloat(card.getAttribute('data-taille'));
        const hanches = parseFloat(card.getAttribute('data-hanches'));
        const morpho = card.getAttribute('data-morpho');

        document.getElementById('stat-morpho').innerText = "Silhouette " + morpho;
        document.getElementById('stat-epaules').innerHTML = epaules + ' <span class="fs-6 text-muted">cm</span>';
        document.getElementById('stat-poitrine').innerHTML = poitrine + ' <span class="fs-6 text-muted">cm</span>';
        document.getElementById('stat-taille').innerHTML = taille + ' <span class="fs-6 text-muted">cm</span>';
        document.getElementById('stat-hanches').innerHTML = hanches + ' <span class="fs-6 text-muted">cm</span>';

        modifierMorphologie3D(epaules, poitrine, taille, hanches);
    }

    function modifierMorphologie3D(epaules, poitrine, taille, hanches) {
        if (!mannequinMesh) return;
        const bodyMesh = mannequinMesh.getObjectByName("buste_principal");
        if (!bodyMesh) return;

        const scaleEpaules = epaules / 95;
        const scalePoitrine = poitrine / 90;
        const scaleTaille = taille / 70;
        const scaleHanches = hanches / 95;

        const positionAttribute = bodyMesh.geometry.attributes.position;
        
        if (!bodyMesh.geometry.userData.initialPositions) {
            bodyMesh.geometry.userData.initialPositions = positionAttribute.clone();
        }
        const initialPos = bodyMesh.geometry.userData.initialPositions;

        for (let i = 0; i < positionAttribute.count; i++) {
            const initY = initialPos.getY(i);
            let currentScale = 1.0;

            if (initY >= 1.14) { 
                currentScale = scaleEpaules;
            } else if (initY >= 0.82 && initY < 1.14) { 
                const t = (initY - 0.82) / (1.14 - 0.82);
                currentScale = THREE.MathUtils.lerp(scalePoitrine, scaleEpaules, t);
            } else if (initY >= 0.36 && initY < 0.82) { 
                const t = (initY - 0.36) / (0.82 - 0.36);
                currentScale = THREE.MathUtils.lerp(scaleTaille, scalePoitrine, t);
            } else if (initY >= -0.15 && initY < 0.36) { 
                const t = (initY - (-0.15)) / (0.36 - (-0.15));
                currentScale = THREE.MathUtils.lerp(scaleHanches, scaleTaille, t);
            } else {
                currentScale = scaleHanches;
            }

            positionAttribute.setX(i, initialPos.getX(i) * currentScale);
            positionAttribute.setZ(i, initialPos.getZ(i) * currentScale);
        }

        mannequinMesh.scale.set(0.96, 0.96, 0.96);
        let t = 0;
        function transitionBuste() {
            t += 0.1;
            if (t < 1) {
                let currentS = THREE.MathUtils.lerp(0.96, 1.0, t);
                mannequinMesh.scale.set(currentS, currentS, currentS);
                requestAnimationFrame(transitionBuste);
            } else {
                mannequinMesh.scale.set(1, 1, 1);
                const pulse = document.getElementById('renderPulse');
                if(pulse) pulse.style.display = 'none';
            }
        }
        transitionBuste();

        positionAttribute.needsUpdate = true;
        bodyMesh.geometry.computeVertexNormals();
    }

    function animate() {
        requestAnimationFrame(animate);
        if (mannequinMesh) {
            mannequinMesh.rotation.y += 0.007; 
        }
        renderer.render(scene, camera);
    }

    window.addEventListener('resize', () => {
        const container = document.getElementById('container-3d');
        if (!camera || !renderer || !container) return;
        camera.aspect = container.clientWidth / container.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(container.clientWidth, container.clientHeight);
    });
</script>