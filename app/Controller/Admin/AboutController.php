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

use App\Request\Admin\About\CreateAboutRequest;
use App\Request\Admin\About\UpdateAboutRequest;
use App\Resource\About\AboutResource;
use App\Services\About\AboutService;
use App\Traits\RespondsWithResource;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Di\Exception\NotFoundException;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Swagger\Annotation\HyperfServer;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

#[HyperfServer('http')]
class AboutController
{
    use RespondsWithResource;

    #[Inject]
    protected ResponseInterface $response;

    public function __construct(
        private readonly AboutService $aboutService
    ) {
    }

    /**
     * List all about entries.
     */
    public function index(): PsrResponseInterface
    {
        $abouts = $this->aboutService->getAll();

        $data = $abouts->map(function ($about) {
            return AboutResource::make($about)->toArray();
        })->toArray();

        return $this->response->json([
            'data' => $data,
            'meta' => [
                'total' => $abouts->count(),
            ],
        ]);
    }

    /**
     * Get a specific about entry.
     * @throws NotFoundException
     */
    public function show(int $id): PsrResponseInterface
    {
        $about = $this->aboutService->getById($id);
        return $this->jsonResource(AboutResource::make($about));
    }

    /**
     * Create a new about entry.
     */
    #[OA\Post(
        path: '/admin/about',
        summary: 'Create about information',
        security: [['BearerAuth' => []]],
        tags: ['Admin - About'],
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: 'multipart/form-data',
                    schema: new OA\Schema(
                        required: ['title', 'description', 'locale'],
                        properties: [
                            new OA\Property(property: 'title', type: 'string'),
                            new OA\Property(property: 'description', type: 'string'),
                            new OA\Property(property: 'image', type: 'string', format: 'binary'),
                            new OA\Property(property: 'locale', type: 'string', enum: ['pt-BR', 'en-US']),
                        ]
                    )
                ),
            ]
        ),
        responses: [
            new OA\Response(response: 201, description: 'About created successfully', content: new OA\JsonContent(ref: '#/components/schemas/About')),
            new OA\Response(response: 401, description: 'Unauthorized'),
        ]
    )]
    public function store(CreateAboutRequest $request): PsrResponseInterface
    {
        $validated = $request->validated();
        $about = $this->aboutService->create($validated);
        return $this->created(AboutResource::make($about));
    }

    /**
     * Update an about entry.
     * @throws NotFoundException
     */
    #[OA\Put(
        path: '/admin/about/{id}',
        summary: 'Update about information',
        security: [['BearerAuth' => []]],
        tags: ['Admin - About'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        requestBody: new OA\RequestBody(
            content: [
                new OA\MediaType(
                    mediaType: 'multipart/form-data',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: 'title', type: 'string'),
                            new OA\Property(property: 'description', type: 'string'),
                            new OA\Property(property: 'image', type: 'string', format: 'binary'),
                            new OA\Property(property: 'locale', type: 'string', enum: ['pt-BR', 'en-US']),
                        ]
                    )
                ),
            ]
        ),
        responses: [
            new OA\Response(response: 200, description: 'About updated successfully', content: new OA\JsonContent(ref: '#/components/schemas/About')),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function update(int $id, UpdateAboutRequest $request): PsrResponseInterface
    {
        $validated = $request->validated();
        $about = $this->aboutService->update($id, $validated);
        return $this->jsonResource(AboutResource::make($about));
    }

    /**
     * Delete an about entry.
     * @throws NotFoundException
     */
    #[OA\Delete(
        path: '/admin/about/{id}',
        summary: 'Delete about information',
        security: [['BearerAuth' => []]],
        tags: ['Admin - About'],
        parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))],
        responses: [
            new OA\Response(response: 204, description: 'About deleted successfully'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Not found'),
        ]
    )]
    public function destroy(int $id): PsrResponseInterface
    {
        $this->aboutService->delete($id);
        return $this->noContent('About entry deleted successfully');
    }
}
