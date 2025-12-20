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

use App\Request\Portfolio\GetListProjectRequest;
use App\Request\Portfolio\GetProjectRequest;
use App\Resource\ProjectCollection;
use App\Resource\ProjectPublicResource;
use App\Services\ProjectService;
use App\Traits\RespondsWithResource;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class ProjectsController
{
    use RespondsWithResource;

    #[Inject]
    protected ResponseInterface $response;

    public function __construct(protected readonly ProjectService $projectService)
    {
    }

    public function index(GetListProjectRequest $request, ResponseInterface $response): PsrResponseInterface
    {
        $locale = $request->validated()['locale'];
        $filters = $request->validated();
        $project = $this->projectService->paginate($filters, $locale);
        return $this->jsonResource(ProjectCollection::make($project));
    }

    public function show(int $id, GetProjectRequest $request): PsrResponseInterface
    {
        $locale = $request->validated()['locale'];
        $project = $this->projectService->getById($id, $locale);
        return $this->jsonResource(ProjectPublicResource::make($project));
    }
}
