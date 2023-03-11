<?php
/**
 * Date 01/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */
return [
    'api_url' => getenv('MPESA_URL', 'https://sandbox.safaricom.co.ke'),
    'apps' => [
        'default' => [
            'consumer_key' => getenv('MPESA_KEY', ''),
            'consumer_secret' => getenv('MPESA_SECRET', ''),
        ],
    ],
    'cache' => [
        'driver' => getenv('CACHE_DRIVER', 'file'),
        'file' => [
            'driver' => 'file',
            'path' => approot_path('cache'),
        ],
        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
        ],
    ]
];
