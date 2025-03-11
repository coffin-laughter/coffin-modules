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

namespace Coffin\Support\Macros;

/**
 * boot
 */
class MacrosRegister
{
    public function __construct(
        protected Blueprint $blueprint,
        protected Collection $collection,
        protected Builder $builder
    ) {
    }

    /**
     * macros boot
     */
    public function boot(): void
    {
        $this->blueprint->boot();
        $this->collection->boot();
        $this->builder->boot();
    }
}
