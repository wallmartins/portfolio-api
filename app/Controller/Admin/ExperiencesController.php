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
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

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

    public function store(CreateExperienceRequest $request): PsrResponseInterface
    {
        $experienceData = $request->validated();
        $experience = $this->experienceService->create($experienceData);
        return $this->jsonResource(ExperiencePublicResource::make($experience));
    }

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
    public function destroy(int $id): PsrResponseInterface
    {
        $this->experienceService->delete($id);
        return $this->noContent('Experience entry deleted successfully');
    }
}
