<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
{
    // Configurations initiales pour l'identité visuelle (Design)
    $designSettings = [
        [
            'key' => 'theme_sidebar_color',
            'value' => '#1a1a1a', // Noir atelier par défaut
            'type' => 'color',
            'group' => 'design'
        ],
        [
            'key' => 'theme_gold_color',
            'value' => '#D4AF37', // Or d'accentuation
            'type' => 'color',
            'group' => 'design'
        ],
        [
            'key' => 'app_background_type',
            'value' => 'none', // none, image, ou video
            'type' => 'select',
            'group' => 'design'
        ],
        [
            'key' => 'app_background_file',
            'value' => null,
            'type' => 'file',
            'group' => 'design'
        ],
    ];

    foreach ($designSettings as $setting) {
        \App\Models\Setting::firstOrCreate(['key' => $setting['key']], $setting);
    }
}
}
