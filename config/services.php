<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'bnc' => [
        'master_key' => env('BNC_MASTER_KEY'), // usada para realizar el logon y pedir la working key
        'rif_cliente' => env('BNC_RIF_CLIENTE'),
        'client_guid' => env('BNC_CLIENT_GUID','e5050d19-a6ef-4e13-8b29-6366c9c70c30'),
        'account_number' => env('BNC_ACCOUNT_NUMBER'),
        'api_url' => env('BNC_API_URL'),
    ],

    'the_factory_hka' => [
        'api_url' => env('THE_FACTORY_HKA_URL'),
        'TokenUsuario' => env('THE_FACTORY_HKA_TOKEN_USUARIO'),
        'TokenPassword' => env('THE_FACTORY_HKA_PASSWORD'),
    ],

];
