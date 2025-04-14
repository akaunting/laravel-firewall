<?php

return [

    'mail' => [

        'subject' => '🔥 Attaque possible sur :domain',
        'message' => 'Une possible attaque :middleware sur :domain a été détectée depuis l\'adresse :ip. L\'URL suivante est concernée : :url',

    ],

    'slack' => [

        'message' => 'Une possible attaque sur :domain a été détectée.',

    ],

];
