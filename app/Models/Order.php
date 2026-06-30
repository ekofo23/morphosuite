<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Les champs que l'on autorise à être remplis massivement
    protected $fillable = [
        'client_name',
        'client_phone',
        'shoulder_measurement',
        'chest_measurement',
        'waist_measurement',
        'hip_measurement',
        'buste_length',
        'posture_type',
        'arm_length',
        'total_length',
        'dominant_morphology',
        'morphology_percentages',
        'status',
        'user_id'
    ];

    // 🔒 Très important : On dit à Laravel que ce champ est du JSON.
    // Laravel va automatiquement le transformer en tableau PHP quand on le lit, et en JSON quand on l'enregistre !
    protected $casts = [
        'morphology_percentages' => 'array',
    ];

    /**
     * Liaison : Une commande appartient à un utilisateur (Couturier/Styliste)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 🧠 LE MOTEUR MORPHOCORE
     * Cette fonction calculera automatiquement les scores dès qu'on créera ou modifiera une commande.
     */
    public function calculateMorphology()
    {
        $sh = $this->shoulder_measurement;
        $ch = $this->chest_measurement;
        $w  = $this->waist_measurement;
        $hp = $this->hip_measurement;

        // Éviter une division par zéro si les mesures ne sont pas encore saisies
        if (!$sh || !$ch || !$w || !$hp) {
            return;
        }

        // 1️⃣ Calcul des Ratios Physiques Réels de la cliente
        $ratio_sh_hp = $sh / $hp; // Épaules / Hanches
        $ratio_w_hp  = $w / $hp;  // Taille / Hanches
        $ratio_w_sh  = $w / $sh;  // Taille / Épaules

        $scores = [];

        // 2️⃣ Évaluation de la Morphologie A (Pyramide)
        // Idéal théorique : Hanches plus larges que les épaules, taille marquée.
        // Plus le ratio Épaules/Hanches descend en dessous de 0.95, plus le score est haut.
        $diff_A = abs($ratio_sh_hp - 0.88); 
        $scores['A'] = round(max(0, (1 - $diff_A * 3)) * 100);

        // 3️⃣ Évaluation de la Morphologie V (Pyramide Inversée)
        // Idéal théorique : Épaules nettement plus larges que les hanches.
        $diff_V = abs($ratio_sh_hp - 1.15);
        $scores['V'] = round(max(0, (1 - $diff_V * 3)) * 100);

        // 4️⃣ Évaluation de la Morphologie H (Rectangle)
        // Idéal théorique : Épaules et hanches alignées (ratio proche de 1), taille peu marquée (ratio > 0.8)
        $diff_H_shape = abs($ratio_sh_hp - 1.0);
        $diff_H_waist = abs($ratio_w_hp - 0.85);
        $scores['H'] = round(max(0, (1 - ($diff_H_shape + $diff_H_waist) * 2)) * 100);

        // 5️⃣ Évaluation de la Morphologie X (Sablier - Osseux/Fin)
        // Idéal théorique : Épaules et hanches alignées (ratio proche de 1), taille très fine (ratio < 0.7)
        $diff_X_shape = abs($ratio_sh_hp - 1.0);
        $diff_X_waist = abs($ratio_w_hp - 0.68);
        $scores['X'] = round(max(0, (1 - ($diff_X_shape + $diff_X_waist) * 2)) * 100);

        // 6️⃣ Évaluation de la Morphologie 8 (Huit - Courbes Voluptueuses)
        // Proche du X, mais évalué en croisant le ratio poitrine/taille pour marquer les formes pleines
        $ratio_w_ch = $w / $ch;
        $diff_8_shape = abs($ratio_sh_hp - 1.0);
        $diff_8_waist = abs($ratio_w_ch - 0.70);
        $scores['8'] = round(max(0, (1 - ($diff_8_shape + $diff_8_waist) * 2)) * 100);

        // 7️⃣ Évaluation de la Morphologie O (Ronde)
        // Idéal théorique : La taille est plus large ou égale aux épaules/hanches.
        $diff_O = abs($ratio_w_hp - 1.05);
        $scores['O'] = round(max(0, (1 - $diff_O * 2)) * 100);

        // 3️⃣ Sauvegarde des résultats calculés dans l'objet
        $this->morphology_percentages = $scores;

        // Déterminer la morphologie dominante (celle qui a le score maximal)
        arsort($scores); // Trie le tableau du plus grand au plus petit
        $this->dominant_morphology = key($scores); // Récupère la clé du premier élément (ex: 'A')
    }

    /**
     * Événement de cycle de vie de Laravel Eloquent
     * On intercepte la sauvegarde pour s'assurer que le calcul est TOUJOURS exécuté à jour.
     */
    protected static function booted()
    {
        static::saving(function ($order) {
            $order->calculateMorphology();
        });
    }
}