<?php

declare(strict_types=1);

namespace App\Exception\Handler;

use Hyperf\Di\Exception\NotFoundException;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class NotFoundExceptionHandler extends ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        // Log the exception
        $this->stopPropagation();

        // Return 404 response
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(404)
            ->withBody(new SwooleStream(json_encode([
                'message' => $throwable->getMessage(),
            ])));
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof NotFoundException;
    }
}
