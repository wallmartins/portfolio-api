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

use App\Services\AboutService;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class AboutController
{
    public function __construct(
        private readonly AboutService $aboutService,
    ) {
    }

    public function index(RequestInterface $request, ResponseInterface $response): PsrResponseInterface
    {
        $locate = $request->query('locale');
        $aboutDTO = $this->aboutService->getAbout($locate);
        return $response->json($aboutDTO->toArray());
    }
}
