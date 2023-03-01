<?php
/**
 * Date 01/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */
return [
    'apps' => [
        'default' => [
            'api_url' => getenv('MPESA_URL', 'https://sandbox.safaricom.co.ke'),
            'consumer_key' => gettype('MPESA_KEY', ''),
            'consumer_secret' => gettype('MPESA_SECRET', ''),
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
