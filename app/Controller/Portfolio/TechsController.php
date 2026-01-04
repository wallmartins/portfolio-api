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

use App\Resource\Tech\TechCollection;
use App\Services\Tech\TechService;
use App\Traits\RespondsWithResource;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Swagger\Annotation\HyperfServer;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

#[OA\Schema(
    schema: 'Tech',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'slug', type: 'string', example: 'php'),
        new OA\Property(property: 'name', type: 'string', example: 'PHP'),
        new OA\Property(property: 'start_date', type: 'string', format: 'date', example: '2018-01-01'),
        new OA\Property(property: 'category', type: 'string', enum: ['language', 'framework', 'tool'], example: 'language'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
#[HyperfServer('http')]
class TechsController
{
    use RespondsWithResource;

    #[Inject]
    protected ResponseInterface $response;

    public function __construct(
        private readonly TechService $techService
    ) {
    }

    #[OA\Get(
        path: '/portfolio/techs',
        summary: 'Get all technologies/skills',
        tags: ['Portfolio'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of technologies',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Tech')),
                    ]
                )
            ),
        ]
    )]
    public function index(): PsrResponseInterface
    {
        $tech = $this->techService->getAll();
        return $this->jsonResource(TechCollection::make($tech));
    }
}
