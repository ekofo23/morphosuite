<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche MorphoSuite - {{ $order->client_name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.4;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 10px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 5px;
        }
        .section-title {
            background-color: #f5f5f5;
            padding: 6px 10px;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 20px;
            margin-bottom: 10px;
            border-left: 4px solid #198754;
        }
        .section-title.danger { border-left-color: #dc3545; }
        .section-title.dark { border-left-color: #212529; background-color: #212529; color: #fff; }

        .row { width: 100%; margin-bottom: 15px; }
        .col { float: left; width: 50%; }
        .clear { clear: both; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f9f9f9; font-weight: bold; }

        .badge-dominant {
            font-size: 28px;
            font-weight: bold;
            color: #198754;
            text-align: center;
            border: 2px solid #198754;
            width: 60px;
            margin: 0 auto;
            padding: 5px;
            border-radius: 5px;
        }
        ul { margin: 0; padding-left: 20px; }
        li { margin-bottom: 5px; }
        
        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 5px;
        }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo">✨ MorphoSuite</div>
        <div class="subtitle">Haute Couture & Analyse Proportionnelle Individualisée</div>
    </div>

    <div class="row">
        <div class="col">
            <strong>Nom de la cliente :</strong> {{ $order->client_name }}<br>
            <strong>Téléphone :</strong> {{ $order->client_phone ?? 'Non renseigné' }}<br>
            <strong>Date de l'analyse :</strong> {{ $order->created_at->format('d/m/Y à H:i') }}
        </div>
        <div class="col" style="text-align: center;">
            <div class="subtitle">Silhouette Dominante</div>
            <div class="badge-dominant">{{ $order->dominant_morphology }}</div>
        </div>
    </div>
    <div class="clear"></div>

    <div class="section-title">📊 Synthèse MorphoCore (Degré de Similarité)</div>
    <table>
        <thead>
            <tr>
                <th>Type de Silhouette</th>
                <th style="text-align: right;">Correspondance</th>
            </tr>
        </thead>
        <tbody>
            @if($order->morphology_percentages)
                @foreach($order->morphology_percentages as $morpho => $percentage)
                <tr @if($morpho == $order->dominant_morphology) style="font-weight: bold; background-color: #f1fbf7;" @endif>
                    <td>Silhouette en {{ $morpho }}</td>
                    <td style="text-align: right;">{{ $percentage }}%</td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <div class="section-title">👗 Recommandations de Style Personnalisées</div>
    <p><em>Ces directives vestimentaires sont générées pour harmoniser les proportions naturelles de votre silhouette.</em></p>
    <ul>
        @foreach($conseils['coupes_recommandees'] as $coupe)
            <li>{{ $coupe }}</li>
        @endforeach
    </ul>

    <div class="section-title danger">⚠️ Pièces et Coupes à Éviter</div>
    <ul>
        @foreach($conseils['a_eviter'] as $eviter)
            <li>{{ $eviter }}</li>
        @endforeach
    </ul>

    <div class="footer">
        MorphoSuite Alpha v1.0 • Document destiné à la cliente • Atelier de Mode Connecté
    </div>

    <div class="page-break"></div>

    <div class="header">
        <div class="logo">🪡 Bon Technique d'Atelier</div>
        <div class="subtitle">Fiche de Coupe et de Confection Précise</div>
    </div>

    <div class="row">
        <div class="col">
            <strong>Dossier :</strong> #{{ $order->id }} - {{ $order->client_name }}<br>
            <strong>Artisan Assigné :</strong> {{ $order->user ? $order->user->name : 'Non assigné' }}
        </div>
        <div class="col" style="text-align: right;">
            <strong>Statut Actuel :</strong> {{ $order->status }}
        </div>
    </div>
    <div class="clear"></div>

    <div class="section-title dark">📐 Mensurations Anatomiques Brutes (cm)</div>
    <table style="margin-top: 10px;">
        <tr>
            <th>Carrure Épaules</th>
            <td>{{ $order->shoulder_measurement }} cm</td>
            <th>Tour de Poitrine</th>
            <td>{{ $order->chest_measurement }} cm</td>
        </tr>
        <tr>
            <th>Tour de Taille</th>
            <td>{{ $order->waist_measurement }} cm</td>
            <th>Tour de Hanches</th>
            <td>{{ $order->hip_measurement }} cm</td>
        </tr>
        <tr>
            <th>Longueur Manches</th>
            <td>{{ $order->arm_length ?? '--' }} cm</td>
            <th>Longueur Totale</th>
            <td>{{ $order->total_length ?? '--' }} cm</td>
        </tr>
        <tr>
            <th>Segment Buste</th>
            <td>Buste {{ $order->buste_length }}</td>
            <th>Profil Postural</th>
            <td>Posture {{ $order->posture_type }}</td>
        </tr>
    </table>

    <div class="section-title dark">🛠️ Directives de Coupe & Ajustements de Patrons</div>
    <p><em>Modifications obligatoires à appliquer sur la base de papier avant la découpe du tissu :</em></p>
    <ul style="background-color: #fff9e6; padding: 15px 15px 15px 35px; border-radius: 4px; list-style-type: square;">
        @if(empty($conseils['ajustements_atelier']))
            <li style="list-style-type: none; margin-left: -15px; color: #666;">Confection standard. Aucun ajustement structurel requis pour cette silhouette.</li>
        @else
            @foreach($conseils['ajustements_atelier'] as $ajustement)
                <li style="font-weight: bold; color: #000; margin-bottom: 8px;">{{ $ajustement }}</li>
            @endforeach
        @endif
    </ul>

    <div class="footer">
        MorphoSuite Technical Document • Réservé à l'usage interne de l'Atelier • Ne pas diffuser
    </div>

</body>
</html>