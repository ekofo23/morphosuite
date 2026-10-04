<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mannequin extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'profile_name',
        'shoulder_measurement',
        'chest_measurement',
        'waist_measurement',
        'hip_measurement',
        'dominant_morphology'
    ];

    // Relation inverse : un mannequin appartient à une fiche commande/client
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}