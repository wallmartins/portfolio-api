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

use App\Request\Admin\CreateAboutRequest;
use App\Request\Admin\UpdateAboutRequest;
use App\Resource\AboutResource;
use App\Services\AboutService;
use Hyperf\Database\Model\Collection;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class AboutController
{
    public function __construct(
        private readonly AboutService $aboutService
    ) {
    }

    /**
     * List all about entries.
     */
    public function index(ResponseInterface $response): PsrResponseInterface
    {
        $abouts = $this->aboutService->getAll();

        $data = $abouts->map(function ($about) {
            return AboutResource::make($about)->toArray();
        })->toArray();

        return $response->json([
            'data' => $data,
            'meta' => [
                'total' => $abouts->count(),
            ],
        ]);
    }

    /**
     * Get a specific about entry.
     */
    public function show(int $id, ResponseInterface $response): PsrResponseInterface
    {
        $about = $this->aboutService->getById($id);
        $resource = AboutResource::make($about);

        return $response->json($resource->toArray());
    }

    /**
     * Create a new about entry.
     */
    public function store(CreateAboutRequest $request, ResponseInterface $response): PsrResponseInterface
    {
        $validated = $request->validated();
        $about = $this->aboutService->create($validated);
        $resource = AboutResource::make($about);

        return $response->json($resource->toArray())->withStatus(201);
    }

    /**
     * Update an about entry.
     */
    public function update(int $id, UpdateAboutRequest $request, ResponseInterface $response): PsrResponseInterface
    {
        $validated = $request->validated();
        $about = $this->aboutService->update($id, $validated);
        $resource = AboutResource::make($about);

        return $response->json($resource->toArray());
    }

    /**
     * Delete an about entry.
     */
    public function destroy(int $id, ResponseInterface $response): PsrResponseInterface
    {
        $this->aboutService->delete($id);

        return $response->json([
            'message' => 'About entry deleted successfully',
        ])->withStatus(204);
    }
}
