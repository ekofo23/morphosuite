<?php

namespace App\Http\Controllers;

use App\Models\Mannequin;
use Illuminate\Http\Request;

class MannequinController extends Controller
{
    public function index()
    {
        // Récupère tous les mannequins enregistrés avec les infos de la commande associée
        $mannequins = Mannequin::with('order')->latest()->get();

        return view('mannequins.index', compact('mannequins'));
    }
}