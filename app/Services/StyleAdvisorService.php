<?php

namespace App\Services;

class StyleAdvisorService
{
    /**
     * Génère des propositions de style uniques en croisant l'intégralité du profil.
     */
    public static function generateAdvisor($order)
    {
        $dominant = $order->dominant_morphology;
        $percentages = $order->morphology_percentages ?? [];
        $buste = $order->buste_length;
        $posture = $order->posture_type;

        $conseilsCoupes = [];
        $conseilsAEviter = [];
        $ajustementsAtelier = [];

        // 1️⃣ ANALYSE DE LA SILHOUETTE DOMINANTE & NUANCES
        switch ($dominant) {
            case 'A':
                $conseilsCoupes[] = "Mettre l'accent sur les épaules pour équilibrer la silhouette : privilégier les cols larges (bateau, bardot), les épaulettes structurales ou les manches bouffantes.";
                $conseilsCoupes[] = "Pour le bas, opter pour des coupes fluides, trapèze (ligne A) ou des jupes évasées qui effleurent les hanches sans les mouler.";
                $conseilsAEviter[] = "Éviter les bas à motifs horizontaux imposants, les jupes boules ou les pantalons à poches latérales volumineuses au niveau des hanches.";
                break;
            case 'V':
                $conseilsCoupes[] = "Adoucir la carrure en choisissant des cols en V profonds, des emmanchures raglan ou des hauts fluides sans détails sur les épaules.";
                $conseilsCoupes[] = "Apporter du volume sur le bas pour créer l'équilibre : jupes plissées, péplums, pantalons cargo ou coupes évasées.";
                $conseilsAEviter[] = "Éviter les cols bateau, les bustiers stricts et les vestes à grosses épaulettes qui accentuent la ligne supérieure.";
                break;
            case 'H':
                $conseilsCoupes[] = "Créer une illusion de taille en privilégiant les coupes droites fluides, les robes empires ou les vestes portées ouvertes qui verticalisent la silhouette.";
                $conseilsCoupes[] = "Utiliser des ceintures portées lâchement sur les hanches plutôt que serrées à la taille.";
                $conseilsAEviter[] = "Éviter les vêtements ultra-moulants, les ceintures contrastantes serrées au centre et les coupes trop géométriques.";
                break;
            case 'X':
            case '8':
                $conseilsCoupes[] = "Sublimer la taille marquée (votre point fort à " . ($percentages[$dominant] ?? 100) . "%) : coupes cintrées, robes portefeuilles, tailles hautes et ceintures ajustées.";
                $conseilsCoupes[] = "Privilégier les matières fluides qui épousent naturellement les courbes sans ajouter de volume superflu.";
                $conseilsAEviter[] = "Éviter les coupes amples, informes ou 'oversize' qui camouflent vos proportions naturelles et épaississent la silhouette.";
                break;
            case 'O':
                $conseilsCoupes[] = "Valoriser le décolleté et créer de la verticalité : cols en V, coupes monochromes, tissus fluides et vestes mi-longues structurées.";
                $conseilsCoupes[] = "Privilégier les coupes empires qui mettent en valeur le dessous de la poitrine.";
                $conseilsAEviter[] = "Éviter les tissus rigides (gros tweed, satin épais), les gros imprimés circulaires et les superpositions excessives.";
                break;
        }

        // 2️⃣ CROISEMENT INTELLIGENT AVEC LA LONGUEUR DU BUSTE
        if ($buste === 'court') {
            $ajustementsAtelier[] = " Buste court détecté : Il est conseillé d'allonger visuellement le buste. Descendre légèrement la ligne de taille naturelle sur le patron ou privilégier des hauts à porter longs/par-dessus le bas.";
        } elseif ($buste === 'long') {
            $ajustementsAtelier[] = " Buste long détecté : Raccourcir visuellement le haut du corps. Remonter la ligne de taille sur le vêtement (tailles hautes impératives) pour allonger la ligne des jambes.";
        }

        // 3️⃣ CROISEMENT INTELLIGENT AVEC LA POSTURE
        if ($posture === 'cambrée') {
            $ajustementsAtelier[] = " Posture Cambrée : Attention au tombé du tissu à l'arrière. Prévoir un ajustement de cambrure sur le patron dos (pince de dos plus profonde ou cambrure ajustée) pour éviter que le vêtement ne plisse ou ne remonte fâcheusement sur les fesses.";
        } elseif ($posture === 'voûtée') {
            $ajustementsAtelier[] = " Posture Voûtée : Allonger légèrement la longueur de la carrure dos sur le patron et basculer légèrement les coutures d'épaules vers l'avant pour accompagner le mouvement naturel du corps.";
        }

        return [
            'coupes_recommandees' => $conseilsCoupes,
            'a_eviter' => $conseilsAEviter,
            'ajustements_atelier' => $ajustementsAtelier
        ];
    }
}