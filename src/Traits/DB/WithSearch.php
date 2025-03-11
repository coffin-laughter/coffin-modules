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

/**
 * base operate
 */
trait WithSearch
{
    /**
     * @var array $searchable
     */
    public array $searchable = [];

    /**
     *
     * @param array $searchable
     * @return $this
     */
    public function setSearchable(array $searchable): static
    {
        $this->searchable = array_merge($this->searchable, $searchable);

        return $this;
    }
}
