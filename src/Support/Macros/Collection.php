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

use Coffin\Support\Excel\Export;
use Coffin\Support\Tree;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection as LaravelCollection;

class Collection
{
    /**
     * boot
     */
    public function boot(): void
    {
        $this->toOptions();

        $this->toTree();

        $this->export();

        $this->download();
    }

    /**
     * @return void
     */
    public function download(): void
    {
        LaravelCollection::macro(__FUNCTION__, function (array $header) {
            $items = $this->toArray();
            $export = new class ($items, $header) extends Export {
                protected array $items;
                public function __construct(array $items, array $header)
                {
                    $this->items = $items;

                    $this->header = $header;
                }
                public function array(): array
                {
                    // TODO: Implement array() method.
                    return $this->items;
                }
            };

            return $export->download();
        });
    }

    /**
     * @return void
     */
    public function export(): void
    {
        LaravelCollection::macro(__FUNCTION__, function (array $header) {
            $items = $this->toArray();
            $export = new class ($items, $header) extends Export {
                protected array $items;
                public function __construct(array $items, array $header)
                {
                    $this->items = $items;

                    $this->header = $header;
                }
                public function array(): array
                {
                    // TODO: Implement array() method.
                    return $this->items;
                }
            };

            return $export->export();
        });
    }

    /**
     * toOptions
     *
     * @return void
     */
    public function toOptions(): void
    {
        LaravelCollection::macro(__FUNCTION__, function () {
            return $this->transform(function ($item, $key) use (&$options) {
                if ($item instanceof Arrayable) {
                    $item = $item->toArray();
                }

                if (is_array($item)) {
                    $item = array_values($item);

                    return [
                        'value' => $item[0],
                        'label' => $item[1]
                    ];
                } else {
                    return [
                        'value' => $key,
                        'label' => $item
                    ];
                }
            })->values();
        });
    }

    /**
     * collection to tree
     *
     * @return void
     */
    public function toTree(): void
    {
        LaravelCollection::macro(__FUNCTION__, function (int $pid = 0, string $pidField = 'parent_id', string $child = 'children') {
            return LaravelCollection::make(Tree::done($this->all(), $pid, $pidField, $child));
        });
    }
}
