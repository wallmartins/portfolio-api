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

use App\Request\Admin\CreateSocialRequest;
use App\Request\Admin\UpdateSocialRequest;
use App\Resource\SocialCollection;
use App\Resource\SocialResource;
use App\Services\SocialService;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class SocialController
{
    public function __construct(
        private readonly SocialService $socialService
    ) {
    }

    /**
     * List all social media links.
     */
    public function index(ResponseInterface $response): PsrResponseInterface
    {
        $socials = $this->socialService->getAll();
        $resource = SocialCollection::make($socials);

        return $response->json($resource->toArray());
    }

    /**
     * Get a specific social media link.
     */
    public function show(int $id, ResponseInterface $response): PsrResponseInterface
    {
        $social = $this->socialService->getById($id);
        $resource = SocialResource::make($social);

        return $response->json($resource->toArray());
    }

    /**
     * Create a new social media link.
     */
    public function store(CreateSocialRequest $request, ResponseInterface $response): PsrResponseInterface
    {
        $validated = $request->validated();
        $social = $this->socialService->create($validated);
        $resource = SocialResource::make($social);

        return $response->json($resource->toArray())->withStatus(201);
    }

    /**
     * Update a social media link.
     */
    public function update(int $id, UpdateSocialRequest $request, ResponseInterface $response): PsrResponseInterface
    {
        $validated = $request->validated();
        $social = $this->socialService->update($id, $validated);
        $resource = SocialResource::make($social);

        return $response->json($resource->toArray());
    }

    /**
     * Delete a social media link.
     */
    public function destroy(int $id, ResponseInterface $response): PsrResponseInterface
    {
        $this->socialService->delete($id);

        return $response->json([
            'message' => 'Social media link deleted successfully',
        ])->withStatus(204);
    }
}
