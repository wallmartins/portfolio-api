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
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

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
     */
    public function show(int $id): PsrResponseInterface
    {
        $about = $this->aboutService->getById($id);
        return $this->jsonResource(AboutResource::make($about));
    }

    /**
     * Create a new about entry.
     */
    public function store(CreateAboutRequest $request): PsrResponseInterface
    {
        $validated = $request->validated();
        $about = $this->aboutService->create($validated);
        return $this->created(AboutResource::make($about));
    }

    /**
     * Update an about entry.
     */
    public function update(int $id, UpdateAboutRequest $request): PsrResponseInterface
    {
        $validated = $request->validated();
        $about = $this->aboutService->update($id, $validated);
        return $this->jsonResource(AboutResource::make($about));
    }

    /**
     * Delete an about entry.
     */
    public function destroy(int $id): PsrResponseInterface
    {
        $this->aboutService->delete($id);
        return $this->noContent('About entry deleted successfully');
    }
}
