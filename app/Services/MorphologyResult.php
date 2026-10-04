<?php

namespace App\Services;

class MorphologyResult
{
    public string $dominante;
    public string $secondaire;
    public int $confiance;
    public array $scores;
    public array $explications;

    public function __construct(string $dominante, string $secondaire, int $confiance, array $scores, array $explications)
    {
        $this->dominante = $dominante;
        $this->secondaire = $secondaire;
        $this->confiance = $confiance;
        $this->scores = $scores;
        $this->explications = $explications;
    }

    /**
     * Génère un libellé complet et élégant pour l'affichage principal
     */
    public function getLabelAttribute(): string
    {
        if ($this->confiance < 40) {
            return "Morphologie Atypique / Mixte";
        }
        
        return $this->confiance < 65 
            ? "Silhouette en {$this->dominante} (Nuancée par le {$this->secondaire})"
            : "Silhouette en {$this->dominante}";
    }
}