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

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

abstract class ModuleServiceProvider extends ServiceProvider
{
    protected array $events = [];


    /**
     * register
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @return void
     */
    public function register(): void
    {
        foreach ($this->events as $event => $listener) {
            Event::listen($event, $listener);
        }
        $this->loadMiddlewares();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function loadMiddlewares()
    {
        if (!empty($middlewares = $this->middlewares())) {
            $route = $this->app['config']->get('coffin.route', [
                'middlewares' => []
            ]);

            $route['middlewares'] = array_merge($route['middlewares'], $middlewares);

            $this->app['config']->set('coffin.route', $route);
        }
    }

    /**
     *
     * @return array
     */
    protected function middlewares(): array
    {
        return [];
    }

}
