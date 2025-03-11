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

namespace Coffin\Exceptions;

use Coffin\Enums\Code;
use Coffin\Enums\Enum;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class CoffinException extends HttpException
{
    protected $code = 0;

    /**
     * @param string $message
     * @param int|Code $code
     */
    public function __construct(string $message = '', int|Code $code = 0)
    {
        if ($code instanceof Enum) {
            $code = $code->value();
        }

        if ($this->code instanceof Enum && !$code) {
            $code = $this->code->value();
        }

        parent::__construct($this->statusCode(), $message ?: $this->message, null, [], $code);
    }

    /**
     * render
     *
     * @return array
     */
    public function render(): array
    {
        return [
            'code' => $this->code,

            'message' => $this->message
        ];
    }

    /**
     * status code
     *
     * @return int
     */
    public function statusCode(): int
    {
        return 500;
    }
}
