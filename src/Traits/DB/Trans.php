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

namespace Coffin\Traits\DB;

use Illuminate\Support\Facades\DB;

/**
 * transaction
 */
trait Trans
{
    /**
     * begin transaction
     */
    public function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    /**
     * commit
     */
    public function commit(): void
    {
        DB::commit();
    }

    /**
     * rollback
     */
    public function rollback(): void
    {
        DB::rollBack();
    }

    /**
     * transaction
     *
     * @param \Closure $closure
     */
    public function transaction(\Closure $closure): void
    {
        DB::transaction($closure);
    }
}
