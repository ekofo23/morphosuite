<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ConfigurationController extends Controller
{
    /**
     * Afficher le panneau de configuration global.
     */
    public function index()
    {
        // On récupère tous les paramètres organisés par leur groupe
        $settings = Setting::all()->groupBy('group');
        
        // On récupère les rôles pour le formulaire de création d'employé
        $roles = Role::all();

        return view('admin.config.index', compact('settings', 'roles'));
    }

    /**
     * Sauvegarder ou mettre à jour les paramètres de personnalisation.
     */
    public function update(Request $request)
    {
        // On boucle sur toutes les données envoyées par le formulaire sauf le token et les fichiers
        foreach ($request->except(['_token', 'app_background_file']) as $key => $value) {
            
            // On met à jour ou on crée la configuration si elle n'existe pas encore
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'group' => 'design', // Attribue automatiquement au groupe design par défaut
                    'type'  => 'text'    // Type texte/couleur standard
                ]
            );
        }

        // Gestion spécifique et isolée du fichier multimédia (Arrière-plan)
        if ($request->hasFile('app_background_file')) {
            $setting = Setting::where('key', 'app_background_file')->first();

            if ($setting) {
                // Supprimer l'ancien fichier s'il existe pour éviter de surcharger le disque
                if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                    Storage::disk('public')->delete($setting->value);
                }
            }

            // Stocker le nouveau média (photo ou vidéo) dans le dossier public 'uploads'
            $path = $request->file('app_background_file')->store('uploads', 'public');
            
            Setting::updateOrCreate(
                ['key' => 'app_background_file'],
                [
                    'value' => $path,
                    'group' => 'design',
                    'type'  => 'file'
                ]
            );
        }

        return redirect()->back()->with('success', 'Les préférences de l\'application ont été mises à jour avec succès.');
    }

    /**
     * Créer un nouvel employé et lui attribuer un rôle instantanément.
     */
    public function storeEmployee(Request $request)
    {
        // Validation stricte des données reçues
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Création de l'utilisateur avec mot de passe sécurisé
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
        ]);

        return redirect()->back()->with('success', 'Le nouveau collaborateur a été enregistré avec succès.');
    }
}