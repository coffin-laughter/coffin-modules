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

namespace Coffin\Support\DB;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Query
{
    /**
     * @var string|null
     */
    protected static string|null $log = null;

    /**
     * @return void
     */
    public static function listen(): void
    {
        DB::listen(function ($query) {
            $sql = str_replace(
                '?',
                '%s',
                sprintf('[%s] ' . $query->sql . ' | %s ms' . PHP_EOL, date('Y-m-d H:i'), $query->time)
            );

            static::$log .= vsprintf($sql, $query->bindings);
        });
    }


    /**
     * @return void
     */
    public static function log(): void
    {
        if (static::$log) {
            $sqlLogPath = storage_path('logs' . DIRECTORY_SEPARATOR . 'query' . DIRECTORY_SEPARATOR);

            if (!File::isDirectory($sqlLogPath)) {
                File::makeDirectory($sqlLogPath, 0777, true);
            }

            $logFile = $sqlLogPath . date('Y-m-d') . '.log';

            if (!File::exists($logFile)) {
                File::put($logFile, '', true);
            }

            file_put_contents($logFile, static::$log . PHP_EOL, LOCK_EX | FILE_APPEND);

            static::$log = null;
        }
    }
}
