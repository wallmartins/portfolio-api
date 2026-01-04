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

use App\Request\Portfolio\Experience\GetExperienceRequest;
use App\Resource\Experience\ExperienceCollection;
use App\Services\Experience\ExperienceService;
use App\Traits\RespondsWithResource;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Swagger\Annotation\HyperfServer;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

#[OA\Schema(
    schema: 'Experience',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'company', type: 'string', example: 'Tech Company Inc'),
        new OA\Property(property: 'start_date', type: 'string', format: 'date', example: '2020-01-01'),
        new OA\Property(property: 'end_date', type: 'string', format: 'date', nullable: true, example: '2022-12-31'),
        new OA\Property(property: 'title', type: 'string', example: 'Senior Developer'),
        new OA\Property(property: 'description', type: 'string', example: 'Worked on various projects...'),
        new OA\Property(property: 'locale', type: 'string', example: 'pt-BR'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
#[HyperfServer('http')]
class ExperiencesController
{
    use RespondsWithResource;

    #[Inject]
    protected ResponseInterface $response;

    public function __construct(
        private readonly ExperienceService $experienceService,
    ) {
    }

    #[OA\Get(
        path: '/portfolio/experiences',
        summary: 'Get all professional experiences',
        tags: ['Portfolio'],
        parameters: [
            new OA\Parameter(
                name: 'locale',
                in: 'query',
                required: true,
                schema: new OA\Schema(type: 'string', enum: ['pt-BR', 'en-US'])
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of experiences',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Experience')),
                    ]
                )
            ),
        ]
    )]
    public function index(GetExperienceRequest $request): PsrResponseInterface
    {
        $locale = $request->validated()['locale'];
        $experience = $this->experienceService->getAll($locale);

        return $this->jsonResource(ExperienceCollection::make($experience));
    }
}
