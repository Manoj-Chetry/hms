<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    // 👇 All guards go here
    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // ✅ Add this for admin
        'admin' => [
            'driver' => 'session',
            'provider' => 'admins',
        ],

        'student' => [
            'driver' => 'session',
            'provider' =>'students',
        ],
        'staff' => [
            'driver' => 'session',
            'provider' => 'staffs',
        ],
    ],

    // 👇 All user providers go here
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class, // Will dynamically resolve based on user type
        ],

        // ✅ Add this for admin
        'admins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],

        'students' => [
            'driver' => 'eloquent',
            'model' => App\Models\Student::class,
        ],
        'staffs' => [
            'driver' => 'eloquent',
            'model' => App\Models\Staff::class,
        ],
    ],

    // 👇 Password reset settings
    'passwords' => [
        'students' => [
            'provider' => 'students',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
        'staffs' => [
            'provider' => 'staffs',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
        'admins' => [
            'provider' => 'admins',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],



    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
