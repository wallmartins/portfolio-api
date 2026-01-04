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

use App\Request\Admin\Experience\CreateExperienceRequest;
use App\Request\Admin\Experience\UpdateExperienceRequest;
use App\Request\Portfolio\Experience\GetExperienceRequest;
use App\Resource\Experience\ExperienceCollection;
use App\Resource\Experience\ExperiencePublicResource;
use App\Services\Experience\ExperienceService;
use App\Traits\RespondsWithResource;
use Exception;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Swagger\Annotation\HyperfServer;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

#[HyperfServer('http')]
class ExperiencesController
{
    use RespondsWithResource;

    #[Inject]
    protected ResponseInterface $response;

    public function __construct(private readonly ExperienceService $experienceService)
    {
    }

    public function index(GetExperienceRequest $request): PsrResponseInterface
    {
        $locale = $request->validated()['locale'];
        $experience = $this->experienceService->getAll($locale);
        return $this->jsonResource(ExperienceCollection::make($experience));
    }

    public function show(GetExperienceRequest $request, int $id): PsrResponseInterface
    {
        $locale = $request->validated()['locale'];
        $experience = $this->experienceService->getById($id, $locale);
        return $this->jsonResource(ExperiencePublicResource::make($experience));
    }

    #[OA\Post(
        path: '/admin/experiences',
        summary: 'Create a new experience',
        security: [['BearerAuth' => []]],
        tags: ['Admin - Experiences'],
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        required: ['company', 'role', 'start_date', 'translations'],
                        properties: [
                            new OA\Property(property: 'company', type: 'string', example: 'Tech Company'),
                            new OA\Property(property: 'role', type: 'string', example: 'Senior Developer'),
                            new OA\Property(property: 'start_date', type: 'string', format: 'date', example: '2020-01-01'),
                            new OA\Property(property: 'end_date', type: 'string', format: 'date', nullable: true, example: '2022-12-31'),
                            new OA\Property(
                                property: 'translations',
                                type: 'array',
                                items: new OA\Items(
                                    properties: [
                                        new OA\Property(property: 'locale', type: 'string', enum: ['pt-BR', 'en-US']),
                                        new OA\Property(property: 'description', type: 'string'),
                                    ]
                                )
                            ),
                        ]
                    )
                ),
            ]
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Experience created successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Experience')
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(CreateExperienceRequest $request): PsrResponseInterface
    {
        $experienceData = $request->validated();
        $experience = $this->experienceService->create($experienceData);
        return $this->jsonResource(ExperiencePublicResource::make($experience));
    }

    #[OA\Put(
        path: '/admin/experiences/{id}',
        summary: 'Update an experience',
        security: [['BearerAuth' => []]],
        tags: ['Admin - Experiences'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: 'application/json',
                    schema: new OA\Schema(
                        required: ['locale'],
                        properties: [
                            new OA\Property(property: 'locale', type: 'string', enum: ['pt-BR', 'en-US']),
                            new OA\Property(property: 'company', type: 'string'),
                            new OA\Property(property: 'role', type: 'string'),
                            new OA\Property(property: 'start_date', type: 'string', format: 'date'),
                            new OA\Property(property: 'end_date', type: 'string', format: 'date', nullable: true),
                            new OA\Property(
                                property: 'translations',
                                type: 'array',
                                items: new OA\Items(
                                    properties: [
                                        new OA\Property(property: 'locale', type: 'string', enum: ['pt-BR', 'en-US']),
                                        new OA\Property(property: 'description', type: 'string'),
                                    ]
                                )
                            ),
                        ]
                    )
                ),
            ]
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Experience updated successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Experience')
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Experience not found'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function update(UpdateExperienceRequest $request, int $id): PsrResponseInterface
    {
        $locale = $request->validated()['locale'];
        $experienceData = $request->validated();
        $experience = $this->experienceService->update($id, $locale, $experienceData);
        return $this->jsonResource(ExperiencePublicResource::make($experience));
    }

    /**
     * @throws Exception
     */
    #[OA\Delete(
        path: '/admin/experiences/{id}',
        summary: 'Delete an experience',
        security: [['BearerAuth' => []]],
        tags: ['Admin - Experiences'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Experience deleted successfully'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Experience not found'),
        ]
    )]
    public function destroy(int $id): PsrResponseInterface
    {
        $this->experienceService->delete($id);
        return $this->noContent('Experience entry deleted successfully');
    }
}
