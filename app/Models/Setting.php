<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    // Colonnes autorisées à la modification
    protected $fillable = ['key', 'value', 'type', 'group'];
}