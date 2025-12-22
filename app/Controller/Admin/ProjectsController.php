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

use App\Request\Admin\Blog\CreatePostRequest;
use App\Request\Admin\Blog\UpdatePostRequest;
use App\Request\Portfolio\Blog\GetListPostRequest;
use App\Request\Portfolio\Project\GetProjectRequest;
use App\Resource\Project\ProjectCollection;
use App\Resource\Project\ProjectPublicResource;
use App\Services\Project\ProjectService;
use App\Traits\RespondsWithResource;
use Exception;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class ProjectsController
{
    use RespondsWithResource;

    #[Inject]
    protected ResponseInterface $response;

    public function __construct(private readonly ProjectService $projectService)
    {
    }

    public function index(GetListPostRequest $request): PsrResponseInterface
    {
        $locale = $request->validated()['locale'];
        $filters = $request->validated();
        $project = $this->projectService->paginate($filters, $locale);
        return $this->jsonResource(ProjectCollection::make($project));
    }

    public function show(GetProjectRequest $request, int $id): PsrResponseInterface
    {
        $locale = $request->validated()['locale'];
        $project = $this->projectService->getById($id, $locale);
        return $this->jsonResource(ProjectPublicResource::make($project));
    }

    public function create(CreatePostRequest $request): PsrResponseInterface
    {
        $projectData = $request->validated();
        $project = $this->projectService->create($projectData);
        return $this->jsonResource(ProjectPublicResource::make($project));
    }

    public function update(UpdatePostRequest $request, int $id): PsrResponseInterface
    {
        $locale = $request->validated()['locale'];
        $projectData = $request->validated();
        $project = $this->projectService->update($id, $locale, $projectData);
        return $this->jsonResource(ProjectPublicResource::make($project));
    }

    /**
     * @throws Exception
     */
    public function delete(int $id): PsrResponseInterface
    {
        $this->projectService->delete($id);
        return $this->noContent('Peroject entry deleted successfully.');
    }
}
