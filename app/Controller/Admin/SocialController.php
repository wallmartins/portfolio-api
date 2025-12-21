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
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

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
     */
    public function show(int $id): PsrResponseInterface
    {
        $social = $this->socialService->getById($id);
        return $this->jsonResource(SocialResource::make($social));
    }

    /**
     * Create a new social media link.
     */
    public function store(CreateSocialRequest $request): PsrResponseInterface
    {
        $validated = $request->validated();
        $social = $this->socialService->create($validated);
        return $this->created(SocialResource::make($social));
    }

    /**
     * Update a social media link.
     */
    public function update(int $id, UpdateSocialRequest $request): PsrResponseInterface
    {
        $validated = $request->validated();
        $social = $this->socialService->update($id, $validated);
        return $this->jsonResource(SocialResource::make($social));
    }

    /**
     * Delete a social media link.
     */
    public function destroy(int $id): PsrResponseInterface
    {
        $this->socialService->delete($id);
        return $this->noContent('Social media link deleted successfully');
    }
}
