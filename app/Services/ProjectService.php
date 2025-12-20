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

namespace App\Services;

use App\Model\Project;
use App\Repository\ProjectRepository;
use Exception;
use Hyperf\Contract\PaginatorInterface;
use Hyperf\HttpMessage\Exception\NotFoundHttpException;

class ProjectService
{
    public function __construct(protected ProjectRepository $projectRepository)
    {
    }

    public function paginate(array $filters, string $locale, int $perPage = 10): PaginatorInterface
    {
        return $this->projectRepository->paginate($filters, $locale, $perPage);
    }

    public function getById(int $id, string $locale): ?Project
    {
        $project = $this->projectRepository->getById($id, $locale);

        if (! $project) {
            throw new NotFoundHttpException('Project not found');
        }

        return $project;
    }

    public function create(array $data): Project
    {
        return $this->projectRepository->create($data);
    }

    public function update(int $id, string $locale, array $data): Project
    {
        $project = $this->projectRepository->getById($id, $locale);
        return $this->projectRepository->update($project, $data);
    }

    /**
     * @throws Exception
     */
    public function delete(Project $project): bool
    {
        return $this->projectRepository->delete($project);
    }
}
