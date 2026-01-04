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

namespace App\Controller\Admin;

use App\Request\Admin\Blog\CreatePostRequest;
use App\Request\Admin\Blog\UpdatePostRequest;
use App\Request\Portfolio\Blog\GetListPostRequest;
use App\Request\Portfolio\Project\GetProjectRequest;
use App\Resource\Project\ProjectCollection;
use App\Resource\Project\ProjectPublicResource;
use App\Services\Project\ProjectService;
use App\Traits\RespondsWithResource;
use Exception;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Swagger\Annotation\HyperfServer;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

#[HyperfServer('http')]
class ProjectsController
{
    use RespondsWithResource;

    #[Inject]
    protected ResponseInterface $response;

    public function __construct(private readonly ProjectService $projectService)
    {
    }

    public function index(GetListPostRequest $request): PsrResponseInterface
    {
        $locale = $request->validated()['locale'];
        $filters = $request->validated();
        $project = $this->projectService->paginate($filters, $locale);
        return $this->jsonResource(ProjectCollection::make($project));
    }

    public function show(GetProjectRequest $request, int $id): PsrResponseInterface
    {
        $locale = $request->validated()['locale'];
        $project = $this->projectService->getById($id, $locale);
        return $this->jsonResource(ProjectPublicResource::make($project));
    }

    #[OA\Post(
        path: '/admin/projects',
        summary: 'Create a new project',
        security: [['BearerAuth' => []]],
        tags: ['Admin - Projects'],
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: 'multipart/form-data',
                    schema: new OA\Schema(
                        required: ['name', 'slug', 'translations', 'techs'],
                        properties: [
                            new OA\Property(property: 'name', type: 'string', example: 'My Portfolio'),
                            new OA\Property(property: 'slug', type: 'string', example: 'my-portfolio'),
                            new OA\Property(property: 'image', type: 'string', format: 'binary', description: 'Image file or URL string'),
                            new OA\Property(
                                property: 'translations',
                                type: 'array',
                                items: new OA\Items(
                                    properties: [
                                        new OA\Property(property: 'locale', type: 'string', enum: ['pt-BR', 'en-US']),
                                        new OA\Property(property: 'title', type: 'string'),
                                        new OA\Property(property: 'content', type: 'string'),
                                    ]
                                )
                            ),
                            new OA\Property(property: 'techs', type: 'object', properties: [
                                new OA\Property(property: 'ids', type: 'array', items: new OA\Items(type: 'integer')),
                            ]),
                        ]
                    )
                ),
            ]
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Project created successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Project')
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function create(CreatePostRequest $request): PsrResponseInterface
    {
        $projectData = $request->validated();
        $project = $this->projectService->create($projectData);
        return $this->jsonResource(ProjectPublicResource::make($project));
    }

    #[OA\Put(
        path: '/admin/projects/{id}',
        summary: 'Update a project',
        security: [['BearerAuth' => []]],
        tags: ['Admin - Projects'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: 'multipart/form-data',
                    schema: new OA\Schema(
                        required: ['locale'],
                        properties: [
                            new OA\Property(property: 'locale', type: 'string', enum: ['pt-BR', 'en-US']),
                            new OA\Property(property: 'name', type: 'string'),
                            new OA\Property(property: 'slug', type: 'string'),
                            new OA\Property(property: 'image', type: 'string', format: 'binary'),
                            new OA\Property(property: 'translations', type: 'array', items: new OA\Items()),
                            new OA\Property(property: 'techs', type: 'object'),
                        ]
                    )
                ),
            ]
        ),
        responses: [
            new OA\Response(response: 200, description: 'Project updated successfully', content: new OA\JsonContent(ref: '#/components/schemas/Project')),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Project not found'),
        ]
    )]
    public function update(UpdatePostRequest $request, int $id): PsrResponseInterface
    {
        $locale = $request->validated()['locale'];
        $projectData = $request->validated();
        $project = $this->projectService->update($id, $locale, $projectData);
        return $this->jsonResource(ProjectPublicResource::make($project));
    }

    /**
     * @throws Exception
     */
    #[OA\Delete(
        path: '/admin/projects/{id}',
        summary: 'Delete a project',
        security: [['BearerAuth' => []]],
        tags: ['Admin - Projects'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Project deleted successfully'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Project not found'),
        ]
    )]
    public function delete(int $id): PsrResponseInterface
    {
        $this->projectService->delete($id);
        return $this->noContent('Peroject entry deleted successfully.');
    }
}
