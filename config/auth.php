<?php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'empleados',
        ],
        // Cambiamos a Sanctum y dejamos empleados como default provider para la API
        'api' => [
            'driver' => 'sanctum',
            'provider' => 'empleados',
        ],
    ],

    'providers' => [
        'empleados' => [
            'driver' => 'eloquent',
            'model' => App\Models\Empleado::class,
        ],
        'clientes' => [
            'driver' => 'eloquent',
            'model' => App\Models\Cliente::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'empleados',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];