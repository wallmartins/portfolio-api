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

use App\Resource\Social\SocialCollection;
use App\Services\Social\SocialService;
use App\Traits\RespondsWithResource;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Swagger\Annotation\HyperfServer;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

#[OA\Schema(
    schema: 'Social',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'social_name', type: 'string', example: 'GitHub'),
        new OA\Property(property: 'social_url', type: 'string', example: 'https://github.com/username'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
#[HyperfServer('http')]
class SocialController
{
    use RespondsWithResource;

    #[Inject]
    protected ResponseInterface $response;

    public function __construct(
        private readonly SocialService $socialService
    ) {
    }

    /**
     * Get all social media links.
     */
    #[OA\Get(
        path: '/portfolio/social',
        summary: 'Get all social media links',
        tags: ['Portfolio'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of social media links',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Social')),
                    ]
                )
            ),
        ]
    )]
    public function index(): PsrResponseInterface
    {
        $socials = $this->socialService->getAll();
        return $this->jsonResource(SocialCollection::make($socials));
    }
}
