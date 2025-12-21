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

namespace App\Services\Blog;

use App\Model\Blog\Post;
use App\Repository\Blog\PostRepository;
use Exception;
use Hyperf\Contract\PaginatorInterface;
use Hyperf\HttpMessage\Exception\NotFoundHttpException;

class PostService
{
    public function __construct(
        protected PostRepository $postRepository,
    ) {
    }

    public function paginate(array $filters, string $locale): PaginatorInterface
    {
        return $this->postRepository->paginate($filters, $locale);
    }

    public function getById(int $id, string $locale): Post
    {
        $post = $this->postRepository->getById($id, $locale);

        if (! $post) {
            throw new NotFoundHttpException('Post not found.');
        }

        return $post;
    }

    public function create(array $data): Post
    {
        return $this->postRepository->create($data);
    }

    public function update(int $id, string $locale, array $data): Post
    {
        $post = $this->getById($id, $locale);
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
