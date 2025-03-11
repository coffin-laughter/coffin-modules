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

namespace Coffin\Base;

use Coffin\Support\DB\SoftDelete;
use Coffin\Traits\DB\BaseOperate;
use Coffin\Traits\DB\DateformatTrait;
use Coffin\Traits\DB\ScopeTrait;
use Coffin\Traits\DB\Trans;
use Coffin\Traits\DB\WithAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;

/**
 *
 * @mixin Builder
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
abstract class CoffinModel extends Model
{
    use BaseOperate;
    use DateformatTrait;
    use ScopeTrait;
    use SoftDeletes;
    use Trans;
    use WithAttributes;

    /**
     * unix timestamp
     *
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * @var string[]
     */
    protected array $defaultCasts = [
        'created_at' => 'datetime:Y-m-d H:i',

        'updated_at' => 'datetime:Y-m-d H:i',
    ];

    protected array $defaultHidden = ['deleted_at'];

    /**
     * paginate limit
     */
    protected $perPage = 10;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->init();
    }

    /**
     * soft delete
     *
     * @time 2021年08月09日
     * @return void
     */
    public static function bootSoftDeletes(): void
    {
        static::addGlobalScope(new SoftDelete());
    }

    /**
     * init
     */
    protected function init()
    {
        $this->makeHidden($this->defaultHidden);

        $this->mergeCasts($this->defaultCasts);

        // auto use data range
        foreach (class_uses_recursive(static::class) as $trait) {
            if (str_contains($trait, 'DataRange')) {
                $this->setDataRange();
            }
        }
    }
}
