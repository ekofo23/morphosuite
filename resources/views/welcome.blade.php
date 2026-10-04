<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>MorphoSuite - Bienvenue</title>

        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">

        <style>
            html, body {
                margin: 0;
                padding: 0;
                width: 100%;
                height: 100%;
                font-family: 'Inter', sans-serif;
                overflow: hidden;
                background-color: #141619;
            }

            /* Conteneur principal plein écran */
            .hero-container {
                position: relative;
                width: 100%;
                height: 100vh;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                text-align: center;
                color: #ffffff;
            }

            /* Vidéo d'arrière-plan en boucle */
            .video-background {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                z-index: 1;
                pointer-events: none;
                opacity: 0.45; /* Ajuste l'opacité pour que le texte reste bien lisible */
            }

            /* Superposition sombre pour améliorer le contraste du texte */
            .overlay {
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(180deg, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0.6) 100%);
                z-index: 2;
            }

            /* Contenu textuel et boutons */
            .content {
                position: relative;
                z-index: 3;
                max-width: 800px;
                padding: 0 20px;
            }

            .brand-logo {
                font-family: 'Playfair Display', serif;
                font-size: 4rem;
                font-weight: 700;
                color: #D4AF37; /* Couleur dorée signature */
                margin-bottom: 0.5rem;
                letter-spacing: 2px;
                line-height: 1;
            }

            .welcome-title {
                font-family: 'Playfair Display', serif;
                font-size: 2.5rem;
                font-weight: 400;
                margin-bottom: 1.5rem;
                letter-spacing: 1px;
            }

            .welcome-subtitle {
                font-size: 1.1rem;
                color: #e2e8f0;
                max-width: 600px;
                margin: 0 auto 3rem auto;
                line-height: 1.6;
                font-weight: 300;
            }

            /* Bouton de Connexion Style Atelier / Moderne */
            .btn-login {
                display: inline-block;
                padding: 12px 40px;
                font-size: 1rem;
                font-weight: 600;
                color: #ffffff;
                text-decoration: none;
                background-color: transparent;
                border: 2px solid #D4AF37;
                border-radius: 4px;
                transition: all 0.3s ease;
                letter-spacing: 1px;
                text-transform: uppercase;
            }

            .btn-login:hover {
                background-color: #D4AF37;
                color: #141619;
                box-shadow: 0px 4px 20px rgba(212, 175, 55, 0.4);
                transform: translateY(-2px);
            }

            /* Liens vers le tableau de bord si déjà connecté */
            .btn-dashboard {
                display: inline-block;
                padding: 12px 40px;
                font-size: 1rem;
                font-weight: 600;
                color: #141619;
                text-decoration: none;
                background-color: #ffffff;
                border: 2px solid #ffffff;
                border-radius: 4px;
                transition: all 0.3s ease;
                letter-spacing: 1px;
                text-transform: uppercase;
            }

            .btn-dashboard:hover {
                background-color: transparent;
                color: #ffffff;
                transform: translateY(-2px);
            }
        </style>
    </head>
    <body>

        <div class="hero-container">
       <video autoplay muted loop playsinline class="video-background">
    <source src="{{ asset('storage/uploads/watermarked_preview.mp4') }}" type="video/mp4">
</video>
            <div class="overlay"></div>

            <div class="content">
                <div class="brand-logo">Fashion-house</div>
                <h1 class="welcome-title">Bienvenue sur MorphoSuite</h1>
                <p class="welcome-subtitle">
                    Votre outil de gestion d'atelier sur-mesure. Planifiez vos commandes, gérez vos modèles et suivez les mesures de vos clients en toute simplicité.
                </p>

                @if (Route::has('login'))
                    <div class="auth-actions">
                        @auth
                            <a href="{{ url('/home') }}" class="btn-dashboard">Tableau de bord</a>
                        @else
                            <a href="{{ route('login') }}" class="btn-login">Espace Connexion</a>
                        @endauth
                    </div>
                @endif
            </div>
        </div>

    </body>
</html>