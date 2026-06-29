<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role; // 👈 Ne pas oublier d'importer le modèle Role

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // On crée nos rôles de base pour l'atelier
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Secrétaire']);
        Role::create(['name' => 'Couturier']);
        Role::create(['name' => 'Styliste']);
    }
}