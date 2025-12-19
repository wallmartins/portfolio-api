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

use App\Model\Post;
use Exception;
use Hyperf\Collection\Collection;

class PostRepository
{
    public function getAll(): Collection
    {
        return Post::query()
            ->with(['translations', 'techs'])
            ->get();
    }

    public function findById(int $id): ?Post
    {
        return Post::query()
            ->with(['translations', 'techs'])
            ->find($id);
    }

    public function findBySlugAndLocale(string $slug, string $locale): ?Post
    {
        return Post::query()
            ->where('slug', $slug)
            ->with('translations', function ($query) use ($locale) {
                $query->where('locale', $locale);
            })
            ->with('techs')
            ->first();
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
            foreach ($data['techs'] as $tech) {
                $post->techs()->create($tech);
            }
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
}
