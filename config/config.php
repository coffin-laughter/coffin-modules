<?php
/**
 *  +-------------------------------------------------------------
 *  | Coffin [ 花开不同赏，花落不同悲。欲问相思处，花开花落时。 ]
 *  +-------------------------------------------------------------
 *  | Copyright (c) 2025~2025 All rights reserved.
 *  +-------------------------------------------------------------
 *  | @author: coffin's laughter | <chuanshuo_yongyuan@163.com>
 *  +-------------------------------------------------------------
 */
return [
    /*
    |--------------------------------------------------------------------------
    | 默认中间件
    |--------------------------------------------------------------------------
    */
    'middleware_group' => [

    ],
    'coffin_auth_middleware_alias' => [

    ],

    /*
    |--------------------------------------------------------------------------
    | 超级管理员 ID
    |--------------------------------------------------------------------------
    */
    'super_admin'     => 1,
    'request_allowed' => true,

    'module' => [
        'driver' => [
            'default'    => 'file',
            'table_name' => 'admin_modules'
        ],
        'routes' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | response
    |--------------------------------------------------------------------------
    */
    'response' => [
        'always_json'              => \Coffin\Middleware\JsonResponseMiddleware::class,
        'request_handled_listener' => \Coffin\Listeners\RequestHandledListener::class
    ],

    /*
   |--------------------------------------------------------------------------
   | database sql log
   |--------------------------------------------------------------------------
   */
    'listen_db_log' => true,

    /*
   |--------------------------------------------------------------------------
   | admin auth model
   |--------------------------------------------------------------------------
   */
    'auth_model' => \Modules\User\Models\User::class,

    /*
   |--------------------------------------------------------------------------
   | route config
   |--------------------------------------------------------------------------
   */
    'route' => [
        'prefix' => 'api',

        'middlewares' => [
            \Coffin\Middleware\AuthMiddleware::class,
            \Coffin\Middleware\JsonResponseMiddleware::class
        ],
    ],

    'excel' => [
        'export' => [
            'csv_limit' => 20000,

            'path' => 'excel/export/'
        ]
    ],
];
