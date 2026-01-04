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

use App\Resource\Tech\TechCollection;
use App\Resource\Tech\TechResource;
use App\Services\Tech\TechService;
use App\Traits\RespondsWithResource;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Di\Exception\NotFoundException;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Swagger\Annotation\HyperfServer;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

#[HyperfServer('http')]
class TechsController
{
    use RespondsWithResource;

    #[Inject]
    protected ResponseInterface $response;

    public function __construct(private readonly TechService $techService)
    {
    }

    public function index(): PsrResponseInterface
    {
        $tech = $this->techService->getAll();
        return $this->jsonResource(TechCollection::make($tech));
    }

    /**
     * @throws NotFoundException
     */
    public function show(int $id): PsrResponseInterface
    {
        $tech = $this->techService->getById($id);
        return $this->jsonResource(TechResource::make($tech));
    }

    #[OA\Post(
        path: '/admin/techs',
        summary: 'Create a new technology/skill',
        security: [['BearerAuth' => []]],
        tags: ['Admin - Technologies'],
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        required: ['slug', 'name', 'start_date', 'category'],
                        properties: [
                            new OA\Property(property: 'slug', type: 'string', example: 'php'),
                            new OA\Property(property: 'name', type: 'string', example: 'PHP'),
                            new OA\Property(property: 'start_date', type: 'string', format: 'date', example: '2018-01-01'),
                            new OA\Property(property: 'category', type: 'string', enum: ['language', 'framework', 'tool'], example: 'language'),
                        ]
                    )
                ),
            ]
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Technology created successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Tech')
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(array $data): PsrResponseInterface
    {
        $tech = $this->techService->create($data);
        return $this->jsonResource(TechResource::make($tech));
    }

    /**
     * @throws NotFoundException
     */
    #[OA\Put(
        path: '/admin/techs/{id}',
        summary: 'Update a technology/skill',
        security: [['BearerAuth' => []]],
        tags: ['Admin - Technologies'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: 'slug', type: 'string'),
                            new OA\Property(property: 'name', type: 'string'),
                            new OA\Property(property: 'start_date', type: 'string', format: 'date'),
                            new OA\Property(property: 'category', type: 'string', enum: ['language', 'framework', 'tool']),
                        ]
                    )
                ),
            ]
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Technology updated successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Tech')
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Technology not found'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function update(int $id, array $data): PsrResponseInterface
    {
        $tech = $this->techService->update($id, $data);
        return $this->jsonResource(TechResource::make($tech));
    }

    /**
     * @throws NotFoundException
     */
    #[OA\Delete(
        path: '/admin/techs/{id}',
        summary: 'Delete a technology/skill',
        security: [['BearerAuth' => []]],
        tags: ['Admin - Technologies'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Technology deleted successfully'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Technology not found'),
        ]
    )]
    public function delete(int $id): PsrResponseInterface
    {
        $this->techService->delete($id);
        return $this->noContent('Tech entry deleted successfully');
    }
}
