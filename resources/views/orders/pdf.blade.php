<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Fiche Atelier - {{ $order->client_name }}</title>
    <style>
        @page { margin: 40px; }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #2B2B2B;
            font-size: 13px;
            line-height: 1.5;
        }
        .header {
            border-bottom: 2px solid #E5E5E5;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .brand {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 1px;
            color: #111111;
        }
        .title {
            font-size: 14px;
            text-transform: uppercase;
            color: #666666;
            margin-top: 5px;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 30px;
        }
        .meta-td {
            width: 50%;
            vertical-align: top;
        }
        .section-title {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #111111;
            padding-bottom: 5px;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .mesures-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .mesures-table td {
            padding: 10px;
            background: #F9F9F9;
            border: 1px solid #EEEEEE;
            width: 25%;
            text-align: center;
        }
        .mesures-label {
            font-size: 10px;
            color: #666666;
            text-transform: uppercase;
            display: block;
            margin-bottom: 4px;
        }
        .mesures-value {
            font-size: 16px;
            font-weight: bold;
        }
        .bullet-list {
            margin: 0;
            padding-left: 15px;
        }
        .bullet-list li {
            margin-bottom: 8px;
            color: #444444;
        }
        .highlight-box {
            background-color: #F5F5F5;
            padding: 15px;
            border-radius: 4px;
            margin-top: 15px;
        }
    </style>
</head>
<body>

    <!-- EN-TÊTE -->
    <div class="header">
        <table style="width: 100%;">
            <tr>
                <td>
                    <div class="brand">MORPHOSUITE</div>
                    <div class="title">Fiche de Coupe & Directives Atelier</div>
                </td>
                <td style="text-align: right; vertical-align: bottom; color: #666666; font-size: 11px;">
                    Date d'édition : {{ now()->format('d/m/Y') }}
                </td>
            </tr>
        </table>
    </div>

    <!-- INFOS CLIENT & SUIVI -->
    <table class="meta-table">
        <tr>
            <td class="meta-td">
                <strong style="font-size: 14px;">Client : {{ $order->client_name }}</strong><br>
                Téléphone : {{ $order->client_phone ?? 'Non renseigné' }}<br>
                Création de la fiche : {{ $order->created_at->format('d/m/Y') }}
            </td>
            <td class="meta-td" style="text-align: right;">
                <strong>Artisan en charge :</strong> {{ $order->user ? $order->user->name : 'Non assigné' }}<br>
                <strong>Statut actuel :</strong> {{ $order->status }}<br>
                <strong>Silhouette dominante :</strong> {{ $order->dominant_morphology }}
            </td>
        </tr>
    </table>

    <!-- MENSURATIONS -->
    <div class="section-title">Mensurations Anatomiques</div>
    <table class="mesures-table">
        <tr>
            <td>
                <span class="mesures-label">Épaules</span>
                <span class="mesures-value">{{ $order->shoulder_measurement }} cm</span>
            </td>
            <td>
                <span class="mesures-label">Poitrine</span>
                <span class="mesures-value">{{ $order->chest_measurement }} cm</span>
            </td>
            <td>
                <span class="mesures-label">Taille</span>
                <span class="mesures-value">{{ $order->waist_measurement }} cm</span>
            </td>
            <td>
                <span class="mesures-label">Hanches</span>
                <span class="mesures-value">{{ $order->hip_measurement }} cm</span>
            </td>
        </tr>
    </table>

    <table style="width: 100%; margin-bottom: 30px;">
        <tr>
            <td style="width: 50%;"><strong>Longueur Buste :</strong> <span style="text-transform: capitalize;">{{ $order->buste_length }}</span></td>
            <td style="width: 50%;"><strong>Type de Posture :</strong> <span style="text-transform: capitalize;">{{ $order->posture_type }}</span></td>
        </tr>
    </table>

    <!-- DIRECTIVES ATELIER -->
    @php
        $conseils = \App\Services\StyleAdvisorService::generateAdvisor($order);
    @endphp

    <div class="section-title">Directives de Coupe et Modélisme</div>
    
    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td style="width: 50%; vertical-align: top; padding-right: 15px;">
                <strong style="display:block; margin-bottom: 10px; font-size: 12px; color: #2B5861;">✔ Coupes Recommandées</strong>
                <ul class="bullet-list">
                    @foreach($conseils['coupes_recommandees'] as $coupe)
                        <li>{{ $coupe }}</li>
                    @endforeach
                </ul>
            </td>
            <td style="width: 50%; vertical-align: top; padding-left: 15px;">
                <strong style="display:block; margin-bottom: 10px; font-size: 12px; color: #A24444;">✘ Configurations à Éviter</strong>
                <ul class="bullet-list">
                    @foreach($conseils['a_eviter'] as $eviter)
                        <li>{{ $eviter }}</li>
                    @endforeach
                </ul>
            </td>
        </tr>
    </table>

    <!-- AJUSTEMENTS TECHNIQUES -->
    <div class="highlight-box">
        <strong style="display:block; margin-bottom: 8px;">Ajustements Techniques (Structure de Coupe) :</strong>
        <ul class="bullet-list" style="margin: 0; padding-left: 15px;">
            @if(empty($conseils['ajustements_atelier']))
                <li style="list-style-type: none; padding-left: 0; color: #666666;">Aucun ajustement structurel requis pour cette configuration.</li>
            @else
                @foreach($conseils['ajustements_atelier'] as $ajustement)
                    <li style="font-weight: bold;">{{ $ajustement }}</li>
                @endforeach
            @endif
        </ul>
    </div>

</body>
</html>