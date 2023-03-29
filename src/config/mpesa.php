<?php
/**
 * Date 01/03/2023
 *
 * @author   Simon Siva <simonsiva13@gmail.com>
 */
return [
    'api_url' => getenv('MPESA_URL', 'https://sandbox.safaricom.co.ke'),
    'max_txn' => getenv('MPESA_MAX_TXN', 125000),
    'min_txn' => getenv('MPESA_MIN_TXN', 100),
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
    ],
    
    
    /*
    |--------------------------------------------------------------------------
    | Account API Online Config
    |--------------------------------------------------------------------------
    |
    */
    'account' => [
        'short_code' => getenv('MPESA_SHORTCODE'),
        'result_url' => getenv('MPESA_RESULT_URL'),
        'timeout_url' => getenv('MPESA_TIMEOUT_URL'),
        'initiator_name' => getenv('MPESA_INITIATOR_NAME'),
        'security_credential' => getenv('MPESA_SECURITY_CREDENTIAL'),
        'security_cert' => getenv('MPESA_SECURITY_CERT'),
        'identifier_type' => getenv('MPESA_ACCOUNT_IDENTIFIER'),
        
        'balance' => [
            'default_command_id' => 'AccountBalance',
        ],
        'reversal' => [
            'default_command_id' => 'TransactionReversal',
        ],
        'transaction' => [
            'default_command_id' => 'TransactionStatusQuery',
        ],
        
    ],
    /*
    |--------------------------------------------------------------------------
    | LipaNaMpesa API Online Config
    |--------------------------------------------------------------------------
    |
    */
    'mpesa_online' => [
        /*
        |--------------------------------------------------------------------------
        | Paybill Number
        |--------------------------------------------------------------------------
        |
        | This is a registered Paybill Number that will be used as the Merchant ID
        | on every transaction. This is also the account to be debited.
        |
        |
        |
        */
        'short_code' => getenv('MPESA_SHORTCODE'),
    
        /*
        | STK Push callback URL
        |--------------------------------------------------------------------------
        |
        | This is a fully qualified endpoint queried by Safaricom's
        | API on completion or failure of a push transaction.
        |
        */
        'callback' => getenv('MPESA_CALLBACK_URL'),
    
        /*
        |--------------------------------------------------------------------------
        | SAG Passkey generated by Safaricom on registration of the Merchant's Paybill Number.
        |--------------------------------------------------------------------------
        */
        'passkey' => getenv('MPESA_PASSKEY'),
    
        /*
        |--------------------------------------------------------------------------
        | Default Transaction Type set on every STK Push request
        |--------------------------------------------------------------------------
        */
        'default_transaction_type' => getenv('MPESA_ONLINE_TRANSACTION_TYPE')

    ],
    /*
    |--------------------------------------------------------------------------
    | B2C API Config
    |--------------------------------------------------------------------------
    |
    */
    'b2c' => [
        'short_code' => getenv('MPESA_SHORTCODE'),
        'result_url' => getenv('MPESA_RESULT_URL'),
        'timeout_url' => getenv('MPESA_TIMEOUT_URL'),
        'initiator_name' => getenv('MPESA_INITIATOR_NAME'),
        'security_credential' => getenv('MPESA_SECURITY_CREDENTIAL'),
        'security_cert' => getenv('MPESA_SECURITY_CERT'),
        'identifier_type' => getenv('MPESA_ACCOUNT_IDENTIFIER'),
        'default_command_id' => getenv('MPESA_B2C_COMMAND'),
    ],
    /*
    |--------------------------------------------------------------------------
    | B2C API Config
    |--------------------------------------------------------------------------
    |
    */
    'b2b' => [
        'short_code' => getenv('MPESA_SHORTCODE'),
        'result_url' => getenv('MPESA_RESULT_URL'),
        'timeout_url' => getenv('MPESA_TIMEOUT_URL'),
        'initiator_name' => getenv('MPESA_INITIATOR_NAME'),
        'security_credential' => getenv('MPESA_SECURITY_CREDENTIAL'),
        'security_cert' => getenv('MPESA_SECURITY_CERT'),
        'identifier_type' => getenv('MPESA_ACCOUNT_IDENTIFIER'),
        'default_command_id' => getenv('MPESA_B2B_COMMAND'),
        'sender_identifier' => getenv('MPESA_B2B_SENDER_ID'),
        'receiver_identifier' => getenv('MPESA_B2B_RECEIVER_ID'),
    ],
    /*
       |--------------------------------------------------------------------------
       | C2B API Config
       |--------------------------------------------------------------------------
       |
       */
    'c2b' => [
        'short_code' => getenv('MPESA_SHORTCODE'),
        'default_command_id' => getenv('MPESA_C2B_COMMAND'),
        'confirmation_url' => getenv('MPESA_C2B_CONFIRMATION_URL'),
        'validation_url' => getenv('MPESA_C2B_VALIDATION_URL'),
        'response_type' => getenv('MPESA_C2B_RESPONSE_TYPE'),
    ],
    
];
