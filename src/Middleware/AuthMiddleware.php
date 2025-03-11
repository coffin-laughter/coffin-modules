<?php
/**
 *  +-------------------------------------------------------------
 *  | Coffin [ 花开不同赏，花落不同悲。欲问相思处，花开花落时。 ]
 *  +-------------------------------------------------------------
 *  | Copyright (c) 2025~2025 All rights reserved.
 *  +-------------------------------------------------------------
 *  | @author: coffin's laughter | <chuanshuo_yongyuan@163.com>
 *  +-------------------------------------------------------------
 */

namespace Coffin\Middleware;

use Coffin\Enums\Code;
use Coffin\Events\User as UserEvent;
use Coffin\Exceptions\FailedException;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Throwable;

class AuthMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        try {
            if (!$user = Auth::guard(getGuardName())->user()) {
                throw new AuthenticationException();
            }

            Event::dispatch(new UserEvent($user));

            return $next($request);
        } catch (Exception|Throwable $e) {
            throw new FailedException(Code::LOST_LOGIN->message() . ":{$e->getMessage()}", Code::LOST_LOGIN);
        }
    }
}
