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
    // Paypal payments
    'paypal' => [
        'base_uri' => env('PAYPAL_BASE_URI'),
        'secret' => env('PAYPAL_SECRET'),
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'class' => App\Services\PaypalService::class,
        'plans' => [
            'plan_mensual' => env('PAYPAL_MONTHLY_PLAN'),
            'plan_anual' => env('PAYPAL_YEARLY_PLAN'),
            'plan_semestral' => env('PAYPAL_SEMESTER_PLAN'),
            'plan_x' => env('PAYPAL_X_PLAN'),
            'plan_x_nt' => env('PAYPAL_X_PLAN_NO_TRIAL'),
            'plan_mensual_nt' => env('PAYPAL_MONTHLY_PLAN_NO_TRIAL'),
            'plan_anual_nt' => env('PAYPAL_YEARLY_PLAN_NO_TRIAL'),
            'plan_semestral_nt' => env('PAYPAL_SEMESTER_PLAN_NO_TRIAL'),
        ]
    ],
    // Stripe payments
    'stripe' => [
        'base_uri' => env('STRIPE_BASE_URI'),
        'secret' => env('STRIPE_SECRET'),
        'key' => env('STRIPE_KEY'),
        'class' => App\Services\StripeService::class,
        'plans' => [
            'plan_mensual' => env('STRIPE_MONTHLY_PLAN'),
            'plan_anual' => env('STRIPE_YEARLY_PLAN'),
            'plan_semestral' => env('STRIPE_SEMESTER_PLAN'),
            'plan_x' => env('STRIPE_X_PLAN'),
            'plan_x_nt' => env('STRIPE_X_PLAN_NO_TRIAL'),
            'plan_mensual_nt' => env('STRIPE_MONTHLY_PLAN_NO_TRIAL'),
            'plan_anual_nt' => env('STRIPE_YEARLY_PLAN_NO_TRIAL'),
            'plan_semestral_nt' => env('STRIPE_SEMESTER_PLAN_NO_TRIAL'),
        ]
    ],
    // Projobi payments
    'projobi' => [
        'base_uri' => env('PROJOBI_BASE_URI', 'https://projobi.com/'),
        'secret' => env('PROJOBI_SECRET', 'secret'),
    ],
];
