<?php

namespace App\Services;

class MorphologyEngine
{
    /**
     * Courbe en cloche Gaussienne pure (Calcul de score continu et fluide)
     */
    private static function calculateGauss(float $current, float $ideal, float $maxPoints, float $tolerance): float
    {
        $distance = abs($current - $ideal);
        
        // Si l'écart dépasse deux fois la tolérance, le critère ne correspond pas du tout
        if ($distance >= ($tolerance * 2)) {
            return 0.0;
        }

        $sigma = $tolerance / 1.5;
        return $maxPoints * exp(-($distance ** 2) / (2 * ($sigma ** 2)));
    }

    /**
     * Calcule la morphologie à partir des mesures brutes (épaules, poitrine, taille, hanches)
     */
    public static function calculate(float $sh, float $ch, float $w, float $hp): MorphologyResult
    {
        // Sécurité de base
        if ($sh <= 0 || $ch <= 0 || $w <= 0 || $hp <= 0) {
            return new MorphologyResult('Données invalides', 'Aucune', 0, [], []);
        }

        // Les 5 Ratios clés du corps humain
        $ratios = [
            'sh_hp' => $sh / $hp,
            'ch_hp' => $ch / $hp,
            'w_hp'  => $w / $hp,
            'w_sh'  => $w / $sh,
            'w_ch'  => $w / $ch,
        ];

        $cfg = config('morphology');
        $scores = ['X' => 0, '8' => 0, 'A' => 0, 'V' => 0, 'H' => 0, 'O' => 0];
        $explications = [];

        // Application directe du scoring Gaussien par morphotype
        foreach ($scores as $m => $currentScore) {
            $explications[$m] = [];
            
            foreach ($cfg['weights'][$m] as $critere => $poidsMax) {
                $ideal = $cfg['ideals'][$m][$critere];
                $tolerance = $cfg['tolerances'][$critere] ?? 0.15;
                $label = $cfg['labels'][$critere] ?? $critere;

                $points = self::calculateGauss($ratios[$critere], $ideal, $poidsMax, $tolerance);
                $scores[$m] += $points;

                // Si le critère apporte une vraie valeur, on l'ajoute en langage naturel
                if (round($points, 1) > ($poidsMax * 0.4)) {
                    $explications[$m][] = $label;
                }
            }
            $scores[$m] = round($scores[$m], 1);
        }

        // Tri des résultats (du plus grand au plus petit score)
        arsort($scores);
        $tri = array_keys($scores);
        $dominante = $tri[0];
        $secondaire = $tri[1];

        // CALCUL DE CONFIANCE STABLE : score1 / (score1 + score2)
        $score1 = $scores[$dominante];
        $score2 = $scores[$secondaire];
        
        if (($score1 + $score2) > 0) {
            $confiance = intval(($score1 / ($score1 + $score2)) * 100);
        } else {
            $confiance = 0;
            $dominante = "Indéterminée";
        }

        return new MorphologyResult($dominante, $secondaire, $confiance, $scores, $explications[$dominante] ?? []);
    }
}