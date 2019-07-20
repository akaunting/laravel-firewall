<?php

return [

    'enabled' => env('FIREWALL_ENABLED', true),

    'whitelist' => [],

    'all_middleware' => [
        'firewall.ip',
        'firewall.lfi',
        'firewall.php',
        'firewall.rfi',
        'firewall.session',
        'firewall.sqli',
        'firewall.xss',
    ],

    'middleware' => [

        'ip' => [
            'requests' => ['all'],

            'urls' => [
                'only' => [], // i.e. 'contact'
                'except' => [], // i.e. 'admin/*'
            ],
        ],

        'lfi' => [
            'requests' => ['get', 'delete'],

            'urls' => [
                'only' => [], // i.e. 'contact'
                'except' => [], // i.e. 'admin/*'
            ],

            'auto_block' => [
                'attempts' => 3,
                'frequency' => 5 * 60, // 5 minutes
                'period' => 30 * 60, // 30 minutes
            ],
        ],

        'php' => [
            'requests' => ['get', 'post', 'delete'],

            'urls' => [
                'only' => [], // i.e. 'contact'
                'except' => [], // i.e. 'admin/*'
            ],

            'auto_block' => [
                'attempts' => 3,
                'frequency' => 5 * 60, // 5 minutes
                'period' => 30 * 60, // 30 minutes
            ],
        ],

        'rfi' => [
            'requests' => ['get', 'post', 'delete'],

            'urls' => [
                'only' => [], // i.e. 'contact'
                'except' => [], // i.e. 'admin/*'
            ],

            'auto_block' => [
                'attempts' => 3,
                'frequency' => 5 * 60, // 5 minutes
                'period' => 30 * 60, // 30 minutes
            ],

            'exceptions' => [],
        ],

        'session' => [
            'requests' => ['get', 'post', 'delete'],

            'urls' => [
                'only' => [], // i.e. 'contact'
                'except' => [], // i.e. 'admin/*'
            ],

            'auto_block' => [
                'attempts' => 3,
                'frequency' => 5 * 60, // 5 minutes
                'period' => 30 * 60, // 30 minutes
            ],
        ],

        'sqli' => [
            'requests' => ['get', 'delete'],

            'urls' => [
                'only' => [], // i.e. 'contact'
                'except' => [], // i.e. 'admin/*'
            ],

            'auto_block' => [
                'attempts' => 3,
                'frequency' => 5 * 60, // 5 minutes
                'period' => 30 * 60, // 30 minutes
            ],
        ],

        'url' => [
            'requests' => ['all'],

            'auto_block' => [
                'attempts' => 5,
                'frequency' => 1 * 60, // 1 minute
                'period' => 30 * 60, // 30 minutes
            ],

            'inspections' => [], // i.e. 'admin'
        ],

        'whitelist' => [
            'requests' => ['all'],

            'urls' => [
                'only' => [], // i.e. 'contact'
                'except' => [], // i.e. 'admin/*'
            ],
        ],

        'xss' => [
            'requests' => ['post', 'put', 'patch'],

            'urls' => [
                'only' => [], // i.e. 'contact'
                'except' => [], // i.e. 'admin/*'
            ],

            'auto_block' => [
                'attempts' => 3,
                'frequency' => 5 * 60, // 5 minutes
                'period' => 30 * 60, // 30 minutes
            ],
        ],

    ],

    'models' => [
        'user' => '\App\User',
    ],
    
    'responses' => [

        'block' => [
            'view' => null,
            'redirect' => null,
            'abort' => false,
            'message' => 'Access Denied',
            'code' => 403,
        ],

    ],

    'notifications' => [

        'mail' => [
            'enabled' => true,
            'name' => 'Laravel Firewall',
            'from' => 'firewall@mydomain.com',
            'to' => ['admin@mydomain.com'],
            'subject' => ':fire: Possible attack on :domain',
            'message' => 'A possible :middleware attack on :domain has been detected from :ip address. The following URL has been affected:<br><br>:url<br><br>Regards',
        ],

        'slack' => [
            'enabled' => false,
            'from' => 'Laravel Firewall',
            'to' => '#my-channel',
            'emoji' => ':fire:',
            'message' => 'A possible attack on :domain has been detected.',
        ],

    ],

];
