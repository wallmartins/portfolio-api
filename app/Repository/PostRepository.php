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

namespace App\Repository;

use App\Interface\PostRepositoryInterface;
use App\Model\Post;
use Exception;
use Hyperf\Contract\PaginatorInterface;
use Hyperf\Database\Model\Builder;

class PostRepository implements PostRepositoryInterface
{
    public function paginate(array $filters, string $locale, int $perPage = 10): PaginatorInterface
    {
        $query = Post::query()
            ->with(['translations' => function ($query) use ($locale) {
                $query->where('locale', $locale);
            }])
            ->with('techs');

        $this->applyFilters($query, $filters);

        return $query->simplePaginate((int) $filters['per_page'] ?? $perPage);
    }

    public function getById(int $id, string $locale): ?Post
    {
        return Post::query()
            ->with(['translations' => function ($query) use ($locale, $id) {
                $query->where('locale', $locale)->where('post_id', $id);
            }])
            ->with('techs')
            ->find($id);
    }

    public function create(array $data): Post
    {
        $post = Post::create([
            'slug' => $data['slug'],
        ]);

        if (isset($data['translations'])) {
            foreach ($data['translations'] as $translation) {
                $post->translations()->create($translation);
            }
        }

        if (isset($data['techs'])) {
            $post->techs()->attach($data['techs']);
        }

        return $post->load(['translations', 'techs']);
    }

    public function update(Post $post, array $data): Post
    {
        if (isset($data['slug'])) {
            $post->update(['slug' => $data['slug']]);
        }

        if (isset($data['translations'])) {
            $post->translations()->delete();

            foreach ($data['translations'] as $translation) {
                $post->translations()->create($translation);
            }
        }

        if (isset($data['tech_ids'])) {
            $post->techs()->sync($data['tech_ids']);
        }

        return $post->load(['translations', 'techs']);
    }

    /**
     * @throws Exception
     */
    public function delete(Post $post): bool
    {
        return $post->delete();
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
