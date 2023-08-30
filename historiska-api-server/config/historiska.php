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
    'token_length' => [
        'auth' => 256,
        'activation' => 8,
        'recovery' => 128,
        'share_code' => 16,
    ],
    'activation_base_link' => '/account/activate/',
    'recovery_base_link' => '/account/recovery/',
    'mail_link_url' => env('MAIL_LINK_URL'),
];
