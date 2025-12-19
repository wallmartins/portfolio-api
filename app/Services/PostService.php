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

use App\Model\Post;
use App\Repository\PostRepository;
use Exception;
use Hyperf\Database\Model\Collection;
use Hyperf\HttpMessage\Exception\NotFoundHttpException;

class PostService
{
    public function __construct(
        protected PostRepository $postRepository,
    ) {
    }

    public function getAll(): Collection
    {
        return $this->postRepository->getAll();
    }

    public function getById(int $id): Post
    {
        $post = $this->postRepository->findById($id);

        if (! $post) {
            throw new NotFoundHttpException('Post not found.');
        }

        return $post;
    }

    public function getBySlugAndLocale(string $slug, string $locale): Post
    {
        $post = $this->postRepository->findBySlugAndLocale($slug, $locale);

        if (! $post) {
            throw new NotFoundHttpException('Post not found for locale: {$locale}.');
        }

        return $post;
    }

    public function create(array $data): Post
    {
        return $this->postRepository->create($data);
    }

    public function update(int $id, array $data): Post
    {
        $post = $this->getById($id);
        return $this->postRepository->update($post, $data);
    }

    /**
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        $post = $this->getById($id);
        return $this->postRepository->delete($post);
    }
}
