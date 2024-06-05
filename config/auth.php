<?php

return [
    'defaults'         => [
        'guard'     => 'web',
        'passwords' => 'users',
    ],

    'guards'           => [
        'web'    => [
            'driver'   => 'session',
            'provider' => 'users',
        ],

        'admin'  => [
            'driver'   => 'session',
            'provider' => 'admins',
        ],

        'member' => [
            'driver'   => 'session',
            'provider' => 'members',
        ],

        'api'    => [
            'driver'   => 'token',
            'provider' => 'members',
            'hash'     => false,
        ],
    ],

    'providers'        => [
        'users'   => [
            'driver' => 'eloquent',
            'model'  => App\Models\Users\User::class,
        ],

        'admins'  => [
            'driver' => 'eloquent',
            'model'  => App\Models\Users\Admin::class,
        ],

        'members' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Users\Member::class,
        ],
    ],

    'passwords'        => [
        'users'   => [
            'provider' => 'users',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],

        'admins'  => [
            'provider' => 'admins',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],

        'members' => [
            'provider' => 'members',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
