<?php

use App\Models\Users\Admin;
use App\Models\Users\Employee;
use App\Models\Users\Member;

return [
    'superadmin' => [
        'model'       => Admin::class,
        'permissions' => [
            'media'         => 'crud',
            'mesesege'      => 'crud',
            'superadmin'    => 'cruda',
            'admin'         => 'cruda',
            'master'        => 'cruda',
            'member'        => 'cruda',
            'role'          => 'crud',
            'permission'    => 'cr',
            'log'           => 'r',
            'setting'       => 'crud',
            'profile'       => 'ru',
            'health'        => 'crud',
            'company'       => 'cruda',
            'task'          => 'crud',
            'task_category' => 'crud',
            'task_tag'      => 'crud',
        ],
    ],
    'admin'      => [
        'model'       => Employee::class,
        'permissions' => [
            'media'         => 'crud',
            'mesesege'      => 'crud',
            'admin'         => 'cruda',
            'supervisor'    => 'cruda',
            'master'        => 'cruda',
            'member'        => 'cruda',
            'log'           => 'r',
            'profile'       => 'ru',
            'health'        => 'cruda',
            'task'          => 'crud',
            'task_category' => 'crud',
            'task_tag'      => 'crud',
        ],
    ],
    'master'     => [
        'model'       => Employee::class,
        'permissions' => [
            'media'    => 'crud',
            'mesesege' => 'crud',
            'member'   => 'r',
            'profile'  => 'ru',
            'health'   => 'cruda',
        ],
    ],
    'supervisor' => [
        'model'       => Employee::class,
        'permissions' => [
            'mesesege' => 'crud',
            'member'   => 'r',
            'profile'  => 'ru',
            'health'   => 'cruda',
        ],
    ],
    'member'     => [
        'model'       => Member::class,
        'permissions' => [
            'health' => 'cruda',
        ],
    ],
];
