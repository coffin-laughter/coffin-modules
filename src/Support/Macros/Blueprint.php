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

use Illuminate\Database\Schema\Blueprint as LaravelBlueprint;

class Blueprint
{
    /**
     * boot;
     */
    public function boot(): void
    {
        $this->createdAt();

        $this->updatedAt();

        $this->deletedAt();

        $this->status();

        $this->creatorId();

        $this->unixTimestamp();

        $this->parentId();

        $this->sort();
    }

    /**
     * created unix timestamp
     *
     * @return void
     */
    public function createdAt(): void
    {
        LaravelBlueprint::macro(__FUNCTION__, function () {
            $this->unsignedInteger('created_at')->default(0)->comment('created time');
        });
    }

    /**
     * creator id
     *
     * @return void
     */
    public function creatorId(): void
    {
        LaravelBlueprint::macro(__FUNCTION__, function () {
            $this->unsignedInteger('creator_id')->default(0)->comment('creator id');
        });
    }

    /**
     * soft delete
     *
     * @return void
     */
    public function deletedAt(): void
    {
        LaravelBlueprint::macro(__FUNCTION__, function () {
            $this->unsignedInteger('deleted_at')->default(0)->comment('delete time');
        });
    }


    /**
     * parent ID
     *
     * @return void
     */
    public function parentId(): void
    {
        LaravelBlueprint::macro(__FUNCTION__, function () {
            $this->unsignedInteger('parent_id')->default(0)->comment('parent id');
        });
    }

    /**
     * sort
     *
     * @param int $default
     * @return void
     */
    public function sort(int $default = 1): void
    {
        LaravelBlueprint::macro(__FUNCTION__, function () use ($default) {
            $this->integer('sort')->comment('sort')->default($default);
        });
    }


    /**
     * status
     *
     * @return void
     */
    public function status(): void
    {
        LaravelBlueprint::macro(__FUNCTION__, function ($default = 1) {
            $this->tinyInteger('status')->default($default)->comment('1:normal 2: forbidden');
        });
    }


    /**
     * unix timestamp
     *
     * @param bool $softDeleted
     * @return void
     */
    public function unixTimestamp(bool $softDeleted = true): void
    {
        LaravelBlueprint::macro(__FUNCTION__, function () use ($softDeleted) {
            $this->createdAt();
            $this->updatedAt();

            if ($softDeleted) {
                $this->deletedAt();
            }
        });
    }

    /**
     * update unix timestamp
     *
     * @return void
     */
    public function updatedAt(): void
    {
        LaravelBlueprint::macro(__FUNCTION__, function () {
            $this->unsignedInteger('updated_at')->default(0)->comment('updated time');
        });
    }
}
