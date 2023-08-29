<?php

return [
    'token_lifetime' => [
        'auth' => 120,
        'activation' => 1440,
        'recovery' => 15
    ],
    'email_cooldown' => 2,
    'reward' =>
        [
            'time' => [
                'h' => 0,
                'm' => 0,
            ],
            'count' => 5,
            'gold_rarity_percent' => 1,
        ],
];
