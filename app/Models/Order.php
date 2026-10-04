<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\MorphologyEngine;
use App\Services\MorphologyResult;

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
     * 🧠 LE NOUVEAU MOTEUR MORPHOCORE V4
     * Cette fonction extrait l'analyse complète (avec Gauss, explications et confiance stable)
     * sans impacter ou modifier directement la base de données lors d'une simple lecture.
     * * @return MorphologyResult
     */
    public function getMorphologyAnalysis(): MorphologyResult
    {
        return MorphologyEngine::calculate(
            (float) $this->shoulder_measurement,
            (float) $this->chest_measurement,
            (float) $this->waist_measurement,
            (float) $this->hip_measurement
        );
    }

    /**
     * 💾 MÉTHODE DE SAUVEGARDE AUTOMATIQUE
     * Cette fonction est appelée automatiquement à l'enregistrement pour stocker les scores bruts 
     * et la dominante en base de données.
     */
   /**
 * 💾 MÉTHODE DE SAUVEGARDE AUTOMATIQUE
 */
public function calculateMorphology()
{
    $analysis = $this->getMorphologyAnalysis();

    // Sécurité : Si le moteur renvoie une erreur ou une valeur indéterminée
    if (!$analysis || $analysis->dominante === 'Données invalides' || $analysis->dominante === 'Indéterminée') {
        $this->morphology_percentages = ['X' => 0, '8' => 0, 'A' => 0, 'V' => 0, 'H' => 0, 'O' => 0];
        $this->dominant_morphology = 'Indéterminée';
        return;
    }

    // On sauvegarde les scores globaux et la dominante calculée par le nouveau moteur
    $this->morphology_percentages = $analysis->scores;
    $this->dominant_morphology = $analysis->dominante;
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