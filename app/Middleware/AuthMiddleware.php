<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Middleware;

use Firebase\JWT\JWT;
use Hyperf\HttpServer\Contract\ResponseInterface as Response;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

use function Hyperf\Support\env;

class AuthMiddleware implements MiddlewareInterface
{
    private Response $response;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): PsrResponseInterface
    {
        $authHeader = $request->getHeaderLine('Authorization');

        if (empty($authHeader) || ! str_starts_with($authHeader, 'Bearer ')) {
            return $this->unauthorized('Missing Token on Header Authorization');
        }

        $token = substr($authHeader, 7);

        try {
            $headers = env('JWT_METHOD');
            $payload = JWT::decode($token, env('JWT_SECRET_KEY'), $headers);
        } catch (Throwable $th) {
            return $this->unauthorized('Invalid Token on Header Authorization');
        }

        if (($payload->iss ?? null) !== env('APP_NAME')) {
            return $this->unauthorized('Invalid API issuer');
        }

        if ((string) $payload->github_id !== env('ADMIN_ID')) {
            return $this->forbidden('Access denied, invalid github user');
        }

        $request = $request->withAttribute('auth', [
            'user_id' => $payload->sub,
            'github_id' => $payload->github_id,
        ]);

        return $handler->handle($request);
    }

    private function unauthorized(string $message): PsrResponseInterface
    {
        return $this->response
            ->json(['message' => $message])
            ->withStatus(401);
    }

    private function forbidden(string $message): PsrResponseInterface
    {
        return $this->response
            ->json(['message' => $message])
            ->withStatus(403);
    }
}
