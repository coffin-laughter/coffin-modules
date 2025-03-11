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
trait WithAttributes
{
    /**
     * as tress which is show in list as tree data
     *
     * @var bool
     */
    protected bool $asTree = false;

    /**
     * null to empty string
     *
     * @var bool
     */
    protected bool $autoNull2EmptyString = true;

    /**
     * @var bool
     */
    protected bool $dataRange = false;

    /**
     * columns which show in list
     *
     * @var array
     */
    protected array $fields = ['*'];


    /**
     * @var array
     */
    protected array $formRelations = [];

    /**
     * 是否填充创建人
     *
     * @var bool
     */
    protected bool $isFillCreatorId = true;


    /**
     * @var bool
     */
    protected bool $isPaginate = true;
    /**
     * @var string
     */
    protected string $parentIdColumn = 'parent_id';

    /**
     * @var bool
     */
    protected bool $sortDesc = true;

    /**
     * @var string
     */
    protected string $sortField = '';
}
