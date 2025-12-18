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

use App\Resource\SocialCollection;
use App\Services\SocialService;
use Hyperf\HttpServer\Contract\ResponseInterface;

class SocialController
{
    public function __construct(
        private readonly SocialService $socialService
    ) {
    }

    /**
     * Get all social media links.
     */
    public function index(ResponseInterface $response)
    {
        $socials = $this->socialService->getAll();
        $resource = SocialCollection::make($socials);

        return $response->json($resource->toArray());
    }
}
