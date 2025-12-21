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

namespace App\Repository\Project;

use App\Interface\ProjectRepositoryInterface;
use App\Model\Project\Project;
use Exception;
use Hyperf\Contract\PaginatorInterface;
use Hyperf\Database\Model\Builder;

class ProjectRepository implements ProjectRepositoryInterface
{
    public function paginate(array $filters, string $locale, int $perPage = 10): PaginatorInterface
    {
        $query = Project::query()
            ->with(['translations' => function ($query) use ($locale) {
                $query->where('locale', $locale);
            }])
            ->with('techs');

        $this->applyFilters($query, $filters);

        return $query->simplePaginate($perPage);
    }

    public function getById(int $id, string $locale): ?Project
    {
        return Project::query()
            ->with(['translations' => function ($query) use ($locale) {
                $query->where('locale', $locale);
            }])
            ->where('id', $id)
            ->find($id);
    }

    public function create(array $data): Project
    {
        $project = Project::create([
            'slug' => $data['slug'],
        ]);

        if (isset($data['translations'])) {
            foreach ($data['translations'] as $translation) {
                $project->translations()->create($translation);
            }
        }

        if (isset($data['techs'])) {
            $project->techs()->attach($data['techs']);
        }

        return $project->load(['translations', 'techs']);
    }

    public function update(Project $project, array $data): Project
    {
        if (isset($data['slug'])) {
            $project->update(['slug' => $data['slug']]);
        }

        if (isset($data['translations'])) {
            $project->translations()->delete();

            foreach ($data['translations'] as $translation) {
                $project->translations()->create($translation);
            }
        }

        if (isset($data['techs'])) {
            $project->techs()->sync($data['techs']);
        }

        return $project->load(['translations', 'techs']);
    }

    /**
     * @throws Exception
     */
    public function delete(Project $project): bool
    {
        return $project->delete();
    }

    protected function applyFilters(Builder $query, array $filters): void
    {
        $this->applySearch($query, $filters);
        $this->applyOrdering($query, $filters);
    }

    protected function applySearch(Builder $query, array $filters): void
    {
        if (empty($filters['search'])) {
            return;
        }

        $search = $filters['search'];

        $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
                ->orWhere('content', 'like', "%{$search}%");
        });
    }

    protected function applyOrdering(Builder $query, array $filters): void
    {
        $query->orderBy(
            $filters['order_by'] ?? 'created_at',
            $filters['order'] ?? 'desc'
        );
    }
}
