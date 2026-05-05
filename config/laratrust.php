<?php

return [
    'use_morph_map' => false,

    'checkers' => [
        'user' => 'default',
        'role' => 'default',
    ],

    'cache' => [
        'enabled'         => env('LARATRUST_ENABLE_CACHE', env('APP_ENV') === 'production'),
        'expiration_time' => 3600,
    ],

    'user_models' => [
        'admins' => \App\Modules\Auth\Models\Admin::class,
    ],

    'models' => [
        'role'       => \App\Modules\Roles\Models\Role::class,
        'permission' => \App\Modules\Roles\Models\Permission::class,
        'team'       => null,
    ],

    'tables' => [
        'roles'              => 'roles',
        'permissions'        => 'permissions',
        'role_user'          => 'role_user',
        'permission_user'    => 'permission_user',
        'permission_role'    => 'permission_role',
        'teams'              => 'teams',
    ],

    'foreign_keys' => [
        'role'       => 'role_id',
        'permission' => 'permission_id',
        'team'       => 'team_id',
    ],

    'teams' => [
        'enabled' => false,
    ],

    'middleware' => [
        'register' => true,
        'handling' => 'abort',
        'handlers' => [
            'abort' => [
                'code'    => 403,
                'message' => 'User does not have any of the necessary access rights.',
            ],
            'redirect' => [
                'url'    => '/login',
                'message' => ['key' => 'error', 'content' => ''],
            ],
        ],
    ],

    'panel' => [
        'register' => false,
    ],
];
