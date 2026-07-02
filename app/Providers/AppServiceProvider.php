<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View; // 👈 Ajouté pour le partage des vues
use App\Models\Setting;              // 👈 Ajouté pour charger notre modèle

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Ta ligne magique existante pour la longueur des chaînes
        Schema::defaultStringLength(191);

        // --- NOTRE AJOUT POUR LES PARAMÈTRES DYNAMIQUES ---
        // Sécurité : on vérifie que la table existe pour éviter les plantages lors des migrations initiales
        if (Schema::hasTable('settings')) {
            // On récupère tous les paramètres sous forme de clé => valeur
            $appSettings = Setting::pluck('value', 'key')->all();
            
            // On partage la variable $appSettings avec TOUTES les vues Blade de l'application
            View::share('appSettings', $appSettings);
        }
    }
}