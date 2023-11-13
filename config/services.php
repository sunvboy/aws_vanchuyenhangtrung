<?php
return [
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
    'google' => [
        'client_id' => env('client_id_google'),
        'client_secret' => env('client_secret_google'),
        'redirect' => env('redirect_google'),
    ],
    'facebook' => [
        'client_id' => env('client_id_facebook'),
        'client_secret' => env('client_secret_facebook'),
        'redirect' => env('redirect_facebook'),
    ],
    'firebase' => [
        'database_url' => env('FIREBASE_DATABASE_URL', ''),
        'project_id' => env('FIREBASE_PROJECT_ID', ''),
        'private_key_id' => env('FIREBASE_PRIVATE_KEY_ID', 'your-key'),
        'private_key' => str_replace("\\n", "\n", env('FIREBASE_PRIVATE_KEY', '')),
        'client_email' => env('FIREBASE_CLIENT_EMAIL', 'e@email.com'),
        'client_id' => env('FIREBASE_CLIENT_ID', ''),
        'client_x509_cert_url' => env('FIREBASE_CLIENT_x509_CERT_URL', ''),
    ]
];
