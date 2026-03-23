<?php

return [
    'paths' => [
        resource_path('views'),
    ],

    'compiled' => env('APP_ENV') === 'local' ? sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'laravel-views' : env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views'))
    ),
];
