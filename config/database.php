<?php

use Illuminate\Support\Str;

return [

    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
//            'url' => env('DATABASE_URL'),
//            'host' => env('DB_HOST', '127.0.0.1'),
//            'port' => env('DB_PORT', '3306'),
//            'database' => env('DB_DATABASE', 'default'),
//            'username' => env('DB_USERNAME', 'default'),
//            'password' => env('DB_PASSWORD', 'secret'),
//            'unix_socket' => env('DB_SOCKET', ''),
            'host' => 'mysql',
            'port' => '3306',
            'database' =>  'default',
            'username' =>  'default',
            'password' =>  'secret',
//            'unix_socket' => env('DB_SOCKET', ''),
//            'prefix' => '',?
        ],

    ],

    'migrations' => 'migrations',

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];
