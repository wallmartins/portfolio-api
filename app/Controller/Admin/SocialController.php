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

use App\Request\Admin\Social\CreateSocialRequest;
use App\Request\Admin\Social\UpdateSocialRequest;
use App\Resource\Social\SocialCollection;
use App\Resource\Social\SocialResource;
use App\Services\Social\SocialService;
use App\Traits\RespondsWithResource;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Di\Exception\NotFoundException;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Swagger\Annotation\HyperfServer;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

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
     * List all social media links.
     */
    public function index(): PsrResponseInterface
    {
        $socials = $this->socialService->getAll();
        return $this->jsonResource(SocialCollection::make($socials));
    }

    /**
     * Get a specific social media link.
     * @throws NotFoundException
     */
    public function show(int $id): PsrResponseInterface
    {
        $social = $this->socialService->getById($id);
        return $this->jsonResource(SocialResource::make($social));
    }

    /**
     * Create a new social media link.
     */
    #[OA\Post(
        path: '/admin/social',
        summary: 'Create a new social media link',
        security: [['BearerAuth' => []]],
        tags: ['Admin - Social Media'],
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        required: ['social_name', 'social_url'],
                        properties: [
                            new OA\Property(property: 'social_name', type: 'string', example: 'GitHub'),
                            new OA\Property(property: 'social_url', type: 'string', format: 'uri', example: 'https://github.com/username'),
                        ]
                    )
                ),
            ]
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Social media link created successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Social')
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(CreateSocialRequest $request): PsrResponseInterface
    {
        $validated = $request->validated();
        $social = $this->socialService->create($validated);
        return $this->created(SocialResource::make($social));
    }

    /**
     * Update a social media link.
     * @throws NotFoundException
     */
    #[OA\Put(
        path: '/admin/social/{id}',
        summary: 'Update a social media link',
        security: [['BearerAuth' => []]],
        tags: ['Admin - Social Media'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: 'social_name', type: 'string', example: 'GitHub'),
                            new OA\Property(property: 'social_url', type: 'string', format: 'uri', example: 'https://github.com/username'),
                        ]
                    )
                ),
            ]
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Social media link updated successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Social')
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Social media link not found'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function update(int $id, UpdateSocialRequest $request): PsrResponseInterface
    {
        $validated = $request->validated();
        $social = $this->socialService->update($id, $validated);
        return $this->jsonResource(SocialResource::make($social));
    }

    /**
     * Delete a social media link.
     * @throws NotFoundException
     */
    #[OA\Delete(
        path: '/admin/social/{id}',
        summary: 'Delete a social media link',
        security: [['BearerAuth' => []]],
        tags: ['Admin - Social Media'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Social media link deleted successfully'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Social media link not found'),
        ]
    )]
    public function destroy(int $id): PsrResponseInterface
    {
        $this->socialService->delete($id);
        return $this->noContent('Social media link deleted successfully');
    }
}
