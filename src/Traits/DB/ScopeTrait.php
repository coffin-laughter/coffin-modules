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

trait ScopeTrait
{
    /**
     * creator
     */
    public static function scopeCreator($query): void
    {
        $model = app(static::class);

        if (in_array($model->getCreatorIdColumn(), $model->getFillable())) {
            $userModel = app(getAuthUserModel());

            $query->addSelect([
                    'creator' => $userModel->whereColumn($userModel->getKeyName(), $model->getTable() . '.' . $model->getCreatorIdColumn())
                        ->select('username')->limit(1)
                ]);
        }
    }
}
