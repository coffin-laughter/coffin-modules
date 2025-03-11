<?php

declare(strict_types=1);
/**
 *  +-------------------------------------------------------------
 *  | Coffin [ 花开不同赏，花落不同悲。欲问相思处，花开花落时。 ]
 *  +-------------------------------------------------------------
 *  | Copyright (c) 2025~2025 All rights reserved.
 *  +-------------------------------------------------------------
 *  | @author: coffin's laughter | <chuanshuo_yongyuan@163.com>
 *  +-------------------------------------------------------------
 */
use Coffin\Base\CoffinModel;
use Illuminate\Console\Application as Artisan;
use Illuminate\Console\Command;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Symfony\Component\Finder\Finder;

if (!function_exists('loadCommands')) {
    /**
     * @throws ReflectionException
     */
    function loadCommands($paths, $namespace, $searchPath = null): void
    {
        if (!$searchPath) {
            $searchPath = dirname($paths) . DIRECTORY_SEPARATOR;
        }

        $paths = Collection::make(Arr::wrap($paths))->unique()->filter(function ($path) {
            return is_dir($path);
        });

        if ($paths->isEmpty()) {
            return;
        }

        foreach ((new Finder())->in($paths->toArray())->files() as $command) {
            $command = $namespace . str_replace(['/', '.php'], ['\\', ''], Str::after($command->getRealPath(), $searchPath));

            if (is_subclass_of($command, Command::class) &&
                !(new ReflectionClass($command))->isAbstract()) {
                Artisan::starting(function ($artisan) use ($command) {
                    $artisan->resolve($command);
                });
            }
        }
    }
}


/**
 * table prefix
 */
if (!function_exists('withTablePrefix')) {
    function withTablePrefix(string $table): string
    {
        return DB::connection()->getTablePrefix() . $table;
    }
}

/**
 * get guard name
 */
if (!function_exists('getGuardName')) {
    function getGuardName(): string
    {
        $guardKeys = array_keys(config('coffin.auth.guards', []));

        if (count($guardKeys)) {
            return $guardKeys[0];
        }

        return 'sanctum';
    }
}

/**
 * get table columns
 */
if (!function_exists('getTableColumns')) {
    function getTableColumns(string $table): array
    {
        $SQL = 'desc ' . withTablePrefix($table);

        $columns = [];

        foreach (DB::select($SQL) as $column) {
            $columns[] = $column->Field;
        }

        return $columns;
    }
}

if (!function_exists('dd_')) {
    /**
     * @param mixed ...$vars
     * @return void
     */
    function dd_(...$vars): void
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Allow-Headers: *');

        dd(...$vars);
    }
}

if (!function_exists('getAuthUserModel')) {
    /**
     * get user model
     *
     * @return mixed
     */
    function getAuthUserModel(): mixed
    {
        return config('coffin.auth_model');
    }
}

if (!function_exists('importTreeData')) {
    /**
     * import tree data
     *
     * @param array $data
     * @param string $table
     * @param string $pid
     * @param string $primaryKey
     */
    function importTreeData(array $data, string $table, string $pid = 'parent_id', string $primaryKey = 'id'): void
    {
        foreach ($data as $value) {
            if (isset($value[$primaryKey])) {
                unset($value[$primaryKey]);
            }

            $children = $value['children'] ?? false;
            if ($children) {
                unset($value['children']);
            }

            // 首先查询是否存在
            $model = new class () extends CoffinModel {};

            $menu = $model->setTable($table)->where('permission_name', $value['permission_name'])
                ->where('module', $value['module'])
                ->where('permission_mark', $value['permission_mark'])
                ->first();

            if ($menu) {
                $id = $menu->id;
            } else {
                $id = DB::table($table)->insertGetId($value);
            }
            if ($children) {
                foreach ($children as &$v) {
                    $v[$pid] = $id;
                }

                importTreeData($children, $table, $pid);
            }
        }
    }
}

if (!function_exists('isRequestFromAjax')) {
    /**
     * @return bool
     *
     * @author: coffin's laughter | <chuanshuo_yongyuan@163.com>
     * @time  : 2024-05-29 下午3:03
     */
    function isRequestFromAjax(): bool
    {
        return Request::ajax();
    }
}

/**
 * 格式化返回
 */
if (!function_exists('format_response_data')) {
    function format_response_data(mixed $data): array
    {
        $responseData = [];

        if ($data instanceof LengthAwarePaginator) {
            $responseData['data'] = $data->items();
            $responseData['total'] = $data->total();
            $responseData['limit'] = $data->perPage();
            $responseData['page'] = $data->currentPage();

            return $responseData;
        } else {
            if (is_object($data)
                && property_exists($data, 'per_page')
                && property_exists($data, 'total')
                && property_exists($data, 'current_page')) {
                $responseData['data'] = $data->data;
                $responseData['total'] = $data->total;
                $responseData['limit'] = $data->per_page;
                $responseData['page'] = $data->current_page;

                return $responseData;
            }
        }

        $responseData['data'] = $data;

        return $responseData;
    }
}
