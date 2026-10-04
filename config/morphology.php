<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Idéaux Anatomiques (Ratios cibles pour chaque silhouette)
    |--------------------------------------------------------------------------
    */
    'ideals' => [
        'X' => ['sh_hp' => 1.00, 'ch_hp' => 1.00, 'w_hp' => 0.68],
        '8' => ['sh_hp' => 1.00, 'ch_hp' => 1.06, 'w_hp' => 0.68],
        'A' => ['sh_hp' => 0.84, 'ch_hp' => 0.84, 'w_hp' => 0.70],
        'V' => ['sh_hp' => 1.16, 'ch_hp' => 0.98, 'w_sh' => 0.70],
        'H' => ['sh_hp' => 1.00, 'ch_hp' => 1.00, 'w_hp' => 0.88],
        'O' => ['w_hp' => 1.02, 'w_ch' => 1.02, 'sh_hp' => 1.00],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tolérances (Écart maximum accepté avant que le score ne tombe à 0)
    |--------------------------------------------------------------------------
    */
    'tolerances' => [
        'sh_hp' => 0.15,
        'ch_hp' => 0.15,
        'w_hp'  => 0.15,
        'w_sh'  => 0.15,
        'w_ch'  => 0.15,
    ],

    /*
    |--------------------------------------------------------------------------
    | Poids / Points attribués à chaque critère (Total sur 100)
    |--------------------------------------------------------------------------
    */
    'weights' => [
        'X' => ['sh_hp' => 35, 'ch_hp' => 25, 'w_hp' => 40],
        '8' => ['sh_hp' => 30, 'ch_hp' => 30, 'w_hp' => 40],
        'A' => ['sh_hp' => 45, 'ch_hp' => 20, 'w_hp' => 35],
        'V' => ['sh_hp' => 45, 'ch_hp' => 25, 'w_sh' => 30],
        'H' => ['sh_hp' => 40, 'ch_hp' => 20, 'w_hp' => 40],
        'O' => ['w_hp' => 40, 'w_ch' => 30, 'sh_hp' => 30],
    ],

    /*
    |--------------------------------------------------------------------------
    | Libellés naturels pour les explications en atelier
    |--------------------------------------------------------------------------
    */
    'labels' => [
        'sh_hp' => "Les épaules sont très proches de la largeur des hanches",
        'ch_hp' => "La poitrine est harmonieusement proportionnée aux hanches",
        'w_hp'  => "La taille est fortement marquée par rapport aux hanches",
        'w_sh'  => "La taille est bien cintrée par rapport à la carrure",
        'w_ch'  => "La taille est fine sous la poitrine",
    ]
];