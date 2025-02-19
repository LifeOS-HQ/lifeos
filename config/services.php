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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => '/login/github/callback',
    ],

    'exist' => [
        'client_id' => env('EXIST_CLIENT_ID'),
        'client_secret' => env('EXIST_CLIENT_SECRET'),
        'redirect' => '/login/exist/callback',
    ],

    'habitica' => [
        'username' => env('HABITICA_USERNAME'),
        'password' => env('HABITICA_PASSWORD'),
        'developer_user_id' => env('HABITICA_DEVELOPER_USER_ID'),
    ],

    'rentablo' => [
        'username' => env('RENTABLO_USERNAME'),
        'password' => env('RENTABLO_PASSWORD'),
        'base_uri' => env('RENTABLO_URI'),
    ],

    'wahoo' => [
        'client_id' => env('WAHOO_CLIENT_ID'),
        'client_secret' => env('WAHOO_CLIENT_SECRET'),
        'redirect' => '/login/wahoo/callback',
    ],

];
