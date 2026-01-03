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
use App\Services\Image\ImageUploadService;
use Exception;
use Hyperf\Contract\PaginatorInterface;
use Hyperf\HttpMessage\Exception\NotFoundHttpException;
use Hyperf\HttpMessage\Upload\UploadedFile;

class PostService
{
    public function __construct(
        protected PostRepository $postRepository,
        protected ImageUploadService $imageUploadService,
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
        $uploadedImageUrl = null;

        try {
            // Handle image upload if present
            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $result = $this->imageUploadService->upload($data['image'], 'posts');
                $uploadedImageUrl = $result->secureUrl;
                $data['image'] = $uploadedImageUrl;
            }

            $post = $this->postRepository->create($data);
            return $post;
        } catch (\Exception $e) {
            // Rollback: delete uploaded image if post creation failed
            if ($uploadedImageUrl) {
                $this->imageUploadService->delete($uploadedImageUrl);
            }
            throw $e;
        }
    }

    public function update(int $id, string $locale, array $data): Post
    {
        $post = $this->getById($id, $locale);
        $oldImageUrl = $post->image;
        $uploadedImageUrl = null;

        try {
            // Handle image upload if present
            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $result = $this->imageUploadService->upload($data['image'], 'posts');
                $uploadedImageUrl = $result->secureUrl;
                $data['image'] = $uploadedImageUrl;
            }

            $updatedPost = $this->postRepository->update($post, $data);

            // Delete old image if new one was uploaded successfully
            if ($uploadedImageUrl && $oldImageUrl) {
                $this->imageUploadService->delete($oldImageUrl);
            }

            return $updatedPost;
        } catch (\Exception $e) {
            // Rollback: delete uploaded image if update failed
            if ($uploadedImageUrl) {
                $this->imageUploadService->delete($uploadedImageUrl);
            }
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        $post = Post::find($id);

        // Delete image from Cloudinary if exists
        if ($post && $post->image) {
            $this->imageUploadService->delete($post->image);
        }

        return $this->postRepository->delete($post);
    }
}
