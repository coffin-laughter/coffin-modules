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

namespace Coffin\Providers;

use Coffin\Exceptions\Handler;
use Coffin\Support\DB\Query;
use Coffin\Support\Macros\MacrosRegister;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class CoffinModulesServiceProvider extends ServiceProvider
{
    /**
     * boot
     *
     * @return void
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function boot(): void
    {
        $this->registerEvents();
        $this->listenDBLog();
        $this->app->make(MacrosRegister::class)->boot();
    }

    /**
     * register
     *
     * @return void
     * @throws ReflectionException
     */
    public function register(): void
    {
        $this->registerCommands();
        $this->registerExceptionHandler();
        $this->publishConfig();
        $this->publishModuleMigration();
    }
    /**
     * listen db log
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @return void
     */
    protected function listenDBLog(): void
    {
        if ($this->app['config']->get('coffin.listen_db_log')) {
            Query::listen();

            $this->app->terminating(function () {
                Query::log();
            });
        }
    }

    /**
     * publish config
     *
     * @return void
     */
    protected function publishConfig(): void
    {
        if ($this->app->runningInConsole()) {
            $from = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

            $to = config_path('coffin.php');

            $this->publishes([$from => $to], 'coffin-config');
        }
    }


    /**
     * publish module migration
     *
     * @return void
     */
    protected function publishModuleMigration(): void
    {
        if ($this->app->runningInConsole()) {
            $form = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . '2025_03_10_034127_module.php';

            $to = database_path('migrations') . DIRECTORY_SEPARATOR . '2025_03_10_034127_module.php';

            $this->publishes([$form => $to], 'coffin-module');
        }
    }


    /**
     * register commands
     *
     * @return void
     * @throws ReflectionException
     */
    protected function registerCommands(): void
    {
        loadCommands(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Commands', 'Coffin\\');
    }


    /**
     * register events
     *
     * @return void
     */
    protected function registerEvents(): void
    {
        Event::listen(RequestHandled::class, config('coffin.response.request_handled_listener'));
    }

    /**
     * register exception handler
     *
     * @return void
     */
    protected function registerExceptionHandler(): void
    {
        if (isRequestFromAjax()) {
            $this->app->singleton(ExceptionHandler::class, Handler::class);
        }
    }
}
