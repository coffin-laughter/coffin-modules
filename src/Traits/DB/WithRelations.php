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

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * base operate
 */
trait WithRelations
{
    /**
     *
     * @param Model $model
     */
    public function deleteRelations(Model $model): void
    {
        $relations = $this->getRelations();
        foreach ($relations as $relation) {
            $isRelation = $model->{$relation}();
            // BelongsToMany
            if ($isRelation instanceof BelongsToMany) {
                $isRelation->detach();
            }
        }
    }


    /**
     * when updated
     *
     * @param Model $model
     * @param array $data
     */
    public function updateRelations(Model $model, array $data): void
    {
        foreach ($this->getRelationsData($data) as $relation => $relationData) {
            $isRelation = $model->{$relation}();

            // BelongsToMany
            if ($isRelation instanceof BelongsToMany) {
                $isRelation->sync($relationData);
            }
        }
    }
    /**
     * when create
     *
     * @param array $data
     */
    protected function createRelations(array $data): void
    {
        foreach ($this->getRelationsData($data) as $relation => $relationData) {
            $isRelation = $this->{$relation}();
            if (!count($relationData)) {
                continue;
            }

            // BelongsToMany
            if ($isRelation instanceof BelongsToMany) {
                $isRelation->attach($relationData);
            }

            if ($isRelation instanceof HasMany || $isRelation instanceof HasOne) {
                $isRelation->create($relationData);
            }
        }
    }


    /**
     * get relations data
     *
     * @param array $data
     * @return array
     */
    protected function getRelationsData(array $data): array
    {
        $relations = $this->getFormRelations();

        if (empty($relations)) {
            return [];
        }

        $relationsData = [];

        foreach ($relations as $relation) {
            if (!isset($data[$relation]) || !$this->isRelation($relation)) {
                continue;
            }

            $relationData = $data[$relation];

            $relationsData[$relation] = $relationData;
        }

        return $relationsData;
    }
}
