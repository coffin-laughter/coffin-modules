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

namespace Coffin\Listeners;

use Coffin\Enums\Code;
use Coffin\Support\ResponseBuilder;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class RequestHandledListener
{
    /**
     * Handle the event.
     *
     * @param RequestHandled $event
     * @return void
     */
    public function handle(RequestHandled $event): void
    {
        if (isRequestFromAjax()) {
            $response = $event->response;

            // 自定义响应内容
            if ($response instanceof ResponseBuilder) {
                $event->response = $response();
            } else {
                // 标准响应
                if ($response instanceof JsonResponse) {
                    $exception = $response->exception;

                    if ($response->getStatusCode() == SymfonyResponse::HTTP_OK && !$exception) {
                        $response->setData($this->formatData($response->getData()));
                    }
                }
            }
        }
    }

    /**
     * @param mixed $data
     * @return array
     */
    protected function formatData(mixed $data): array
    {
        return array_merge(
            [
                'code'    => Code::SUCCESS->value(),
                'message' => Code::SUCCESS->message(),
            ],
            format_response_data($data)
        );
    }
}
