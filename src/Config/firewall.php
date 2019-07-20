<?php

return [

    'enabled' => env('FIREWALL_ENABLED', true),

    'whitelist' => [],

    'all_middleware' => [
        'firewall.ip',
        'firewall.agent',
        'firewall.lfi',
        'firewall.php',
        'firewall.referrer',
        'firewall.rfi',
        'firewall.session',
        'firewall.sqli',
        'firewall.swear',
        'firewall.xss',
    ],

    'middleware' => [

        'ip' => [
            'methods' => ['all'],

            'routes' => [
                'only' => [], // i.e. 'contact'
                'except' => [], // i.e. 'admin/*'
            ],
        ],

        'agent' => [
            'methods' => ['all'],

            'routes' => [
                'only' => [], // i.e. 'contact'
                'except' => [], // i.e. 'admin/*'
            ],

            // https://github.com/jenssegers/agent
            'browsers' => [
                'allow' => [], // i.e. 'Chrome', 'Firefox'
                'block' => [], // i.e. 'IE'
            ],

            'platforms' => [
                'allow' => [], // i.e. 'Ubuntu', 'Windows'
                'block' => [], // i.e. 'OS X'
            ],

            'devices' => [
                'allow' => [], // i.e. 'Desktop', 'Mobile'
                'block' => [], // i.e. 'Tablet'
            ],

            'properties' => [
                'allow' => [], // i.e. 'Gecko', 'Version/5.1.7'
                'block' => [], // i.e. 'AppleWebKit'
            ],

            'allow_robots' => true,

            'auto_block' => [
                'attempts' => 5,
                'frequency' => 1 * 60, // 1 minute
                'period' => 30 * 60, // 30 minutes
            ],
        ],

        'lfi' => [
            'methods' => ['get', 'delete'],

            'routes' => [
                'only' => [], // i.e. 'contact'
                'except' => [], // i.e. 'admin/*'
            ],

            'auto_block' => [
                'attempts' => 3,
                'frequency' => 5 * 60, // 5 minutes
                'period' => 30 * 60, // 30 minutes
            ],
        ],

        'login' => [
            'enabled' => true,

            'auto_block' => [
                'attempts' => 5,
                'frequency' => 1 * 60, // 1 minute
                'period' => 30 * 60, // 30 minutes
            ],
        ],

        'php' => [
            'methods' => ['get', 'post', 'delete'],

            'routes' => [
                'only' => [], // i.e. 'contact'
                'except' => [], // i.e. 'admin/*'
            ],

            'auto_block' => [
                'attempts' => 3,
                'frequency' => 5 * 60, // 5 minutes
                'period' => 30 * 60, // 30 minutes
            ],
        ],

        'referrer' => [
            'methods' => ['all'],

            'routes' => [
                'only' => [], // i.e. 'contact'
                'except' => [], // i.e. 'admin/*'
            ],

            'blocked' => [],

            'auto_block' => [
                'attempts' => 3,
                'frequency' => 5 * 60, // 5 minutes
                'period' => 30 * 60, // 30 minutes
            ],
        ],

        'rfi' => [
            'methods' => ['get', 'post', 'delete'],

            'routes' => [
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
            'methods' => ['get', 'post', 'delete'],

            'routes' => [
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
            'methods' => ['get', 'delete'],

            'routes' => [
                'only' => [], // i.e. 'contact'
                'except' => [], // i.e. 'admin/*'
            ],

            'auto_block' => [
                'attempts' => 3,
                'frequency' => 5 * 60, // 5 minutes
                'period' => 30 * 60, // 30 minutes
            ],
        ],

        'swear' => [
            'methods' => ['post', 'put', 'patch'],

            'routes' => [
                'only' => [], // i.e. 'contact'
                'except' => [], // i.e. 'admin/*'
            ],

            'words' => [],

            'auto_block' => [
                'attempts' => 3,
                'frequency' => 5 * 60, // 5 minutes
                'period' => 30 * 60, // 30 minutes
            ],
        ],

        'url' => [
            'methods' => ['all'],

            'inspections' => [], // i.e. 'admin'

            'auto_block' => [
                'attempts' => 5,
                'frequency' => 1 * 60, // 1 minute
                'period' => 30 * 60, // 30 minutes
            ],
        ],

        'whitelist' => [
            'methods' => ['all'],

            'routes' => [
                'only' => [], // i.e. 'contact'
                'except' => [], // i.e. 'admin/*'
            ],
        ],

        'xss' => [
            'methods' => ['post', 'put', 'patch'],

            'routes' => [
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
            'code' => 403,
        ],

    ],

    'notifications' => [

        'mail' => [
            'enabled' => true,
            'name' => 'Laravel Firewall',
            'from' => 'firewall@mydomain.com',
            'to' => ['admin@mydomain.com'],
        ],

        'slack' => [
            'enabled' => false,
            'from' => 'Laravel Firewall',
            'to' => '#my-channel',
            'emoji' => ':fire:',
        ],

    ],

];
