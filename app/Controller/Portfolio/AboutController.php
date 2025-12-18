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

use App\Request\Portfolio\GetAboutRequest;
use App\Resource\AboutResource;
use App\Services\AboutService;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class AboutController
{
    public function __construct(
        private readonly AboutService $aboutService,
    ) {
    }

    /**
     * Get about information by locale.
     */
    public function index(GetAboutRequest $request, ResponseInterface $response): PsrResponseInterface
    {
        $validated = $request->validated();
        $about = $this->aboutService->getByLocale($validated['locale']);
        $resource = AboutResource::make($about);

        return $response->json($resource->toArray());
    }
}
