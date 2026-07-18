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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'brevo' => [
        'api_key' => env('nestora_api_key', env('BREVO_API_KEY')),
        'from_email' => env('BREVO_FROM_EMAIL'),
        'from_name' => env('BREVO_FROM_NAME', env('APP_NAME', 'Nestora')),
    ],

    'geoapify' => [
        'api_key' => env('GEOAPIFY_API_KEY') ?: env('geoapify_api_key'),
        'apartment_lat' => env('NESTORA_APARTMENT_LAT', 23.7465),
        'apartment_lon' => env('NESTORA_APARTMENT_LON', 90.3760),
        'apartment_address' => env('NESTORA_APARTMENT_ADDRESS', '12/A, Road 5, Dhanmondi, Dhaka 1209, Bangladesh'),
    ],

    'openweather' => [
        'api_key' => env('OPENWEATHER_API_KEY') ?: env('OPEN_WEATHER_API_KEY') ?: env('open_weather_api_key'),
        'default_lat' => env('NESTORA_WEATHER_LAT', 23.7465),
        'default_lon' => env('NESTORA_WEATHER_LON', 90.3760),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
