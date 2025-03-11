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

namespace Coffin\Facade;

use Coffin\Support\Zip\Zipper as Zip;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Zip make(string $pathToFile)
 * @method static Zip zip(string $pathToFile)
 * @method static Zip phar(string $pathToFile)
 *
 * @see Zipper
 * Class Module
 */
class Zipper extends Facade
{
    public static function getFacadeAccessor(): string
    {
        return Zip::class;
    }
}
