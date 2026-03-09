<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    |
    | This option controls the default authentication "guard" and password
    | reset options for your application. You may change these defaults
    | as required, but they're a perfect start for most applications.
    |
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'members',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'members',
        ],

        'admin' => [
            'driver' => 'session',
            'provider' => 'operators',
        ],
    ],

    'providers' => [
        'members' => [
            'driver' => 'eloquent',
            'model' => App\Models\Member::class,
        ],

        'operators' => [
            'driver' => 'eloquent',
            'model' => App\Models\Operator::class,
        ],
    ],

    'passwords' => [
        'members' => [
            'provider' => 'members',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    |
    | Here you may define the amount of seconds before a password confirmation
    | times out and the user is prompted to re-enter their password via the
    | confirmation screen. By default, the timeout lasts for three hours.
    |
    */

    'password_timeout' => 10800,

];
