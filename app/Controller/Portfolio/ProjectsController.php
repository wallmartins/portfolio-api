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

use App\Request\Portfolio\Project\GetListProjectRequest;
use App\Request\Portfolio\Project\GetProjectRequest;
use App\Resource\Project\ProjectCollection;
use App\Resource\Project\ProjectPublicResource;
use App\Services\Project\ProjectService;
use App\Traits\RespondsWithResource;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Swagger\Annotation\HyperfServer;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

#[OA\Schema(
    schema: 'Project',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'My Portfolio'),
        new OA\Property(property: 'slug', type: 'string', example: 'my-portfolio'),
        new OA\Property(property: 'image', type: 'string', nullable: true, example: 'https://example.com/project.jpg'),
        new OA\Property(property: 'title', type: 'string', example: 'My Portfolio Project'),
        new OA\Property(property: 'content', type: 'string', example: 'Project description and details...'),
        new OA\Property(property: 'locale', type: 'string', example: 'pt-BR'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
#[HyperfServer('http')]
class ProjectsController
{
    use RespondsWithResource;

    #[Inject]
    protected ResponseInterface $response;

    public function __construct(protected readonly ProjectService $projectService)
    {
    }

    #[OA\Get(
        path: '/portfolio/projects',
        summary: 'Get list of projects',
        tags: ['Portfolio'],
        parameters: [
            new OA\Parameter(name: 'locale', in: 'query', required: true, schema: new OA\Schema(type: 'string', enum: ['pt-BR', 'en-US'])),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 1)),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 10)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of projects',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Project')),
                        new OA\Property(property: 'meta', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function index(GetListProjectRequest $request, ResponseInterface $response): PsrResponseInterface
    {
        $locale = $request->validated()['locale'];
        $filters = $request->validated();
        $project = $this->projectService->paginate($filters, $locale);
        return $this->jsonResource(ProjectCollection::make($project));
    }

    #[OA\Get(
        path: '/portfolio/projects/{id}',
        summary: 'Get a specific project',
        tags: ['Portfolio'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'locale', in: 'query', required: true, schema: new OA\Schema(type: 'string', enum: ['pt-BR', 'en-US'])),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Project details',
                content: new OA\JsonContent(ref: '#/components/schemas/Project')
            ),
            new OA\Response(response: 404, description: 'Project not found'),
        ]
    )]
    public function show(int $id, GetProjectRequest $request): PsrResponseInterface
    {
        $locale = $request->validated()['locale'];
        $project = $this->projectService->getById($id, $locale);
        return $this->jsonResource(ProjectPublicResource::make($project));
    }
}
