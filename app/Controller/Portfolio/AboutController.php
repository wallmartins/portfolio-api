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

use App\Request\Portfolio\About\GetAboutRequest;
use App\Resource\About\AboutResource;
use App\Services\About\AboutService;
use App\Traits\RespondsWithResource;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Di\Exception\NotFoundException;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Swagger\Annotation\HyperfServer;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

#[OA\Schema(
    schema: 'About',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'title', type: 'string', example: 'About Me'),
        new OA\Property(property: 'description', type: 'string', example: 'I am a software developer...'),
        new OA\Property(property: 'image', type: 'string', nullable: true, example: 'https://example.com/profile.jpg'),
        new OA\Property(property: 'locale', type: 'string', example: 'pt-BR'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
#[HyperfServer('http')]
class AboutController
{
    use RespondsWithResource;

    #[Inject]
    protected ResponseInterface $response;

    public function __construct(
        private readonly AboutService $aboutService,
    ) {
    }

    /**
     * Get about information by locale.
     * @throws NotFoundException
     */
    #[OA\Get(
        path: '/portfolio/about',
        summary: 'Get about information',
        tags: ['Portfolio'],
        parameters: [
            new OA\Parameter(
                name: 'locale',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'string', enum: ['pt-BR', 'en-US'])
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'About information',
                content: new OA\JsonContent(ref: '#/components/schemas/About')
            ),
            new OA\Response(response: 404, description: 'About information not found for locale'),
        ]
    )]
    public function index(GetAboutRequest $request): PsrResponseInterface
    {
        $validated = $request->validated();
        $about = $this->aboutService->getByLocale($validated['locale']);
        return $this->jsonResource(AboutResource::make($about));
    }
}
