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

namespace App\Controller\Portfolio;

use App\Services\SocialService;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

class SocialController
{

    public function __construct(
        private readonly SocialService $socialService
    ) {}

    public function index(RequestInterface $request, ResponseInterface $response)
    {
        $socialDTO = $this->socialService->getSocials();
        return $response->json($socialDTO->toArray());
    }
}
