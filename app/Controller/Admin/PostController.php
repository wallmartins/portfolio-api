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
use App\Request\Portfolio\Blog\GetPostRequest;
use App\Resource\Blog\PostCollection;
use App\Resource\Blog\PostPublicResource;
use App\Services\Blog\PostService;
use App\Traits\RespondsWithResource;
use Exception;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class PostController
{
    use RespondsWithResource;

    #[Inject]
    protected ResponseInterface $response;

    public function __construct(private readonly PostService $postService)
    {
    }

    public function index(GetListPostRequest $request): PsrResponseInterface
    {
        $locale = $request->validated()['locale'];
        $filters = $request->validated();
        $post = $this->postService->paginate($filters, $locale);
        return $this->jsonResource(PostCollection::make($post));
    }

    public function show(GetPostRequest $request, int $id): PsrResponseInterface
    {
        $locale = $request->validated()['locale'];
        $post = $this->postService->getById($id, $locale);
        return $this->jsonResource(PostPublicResource::make($post));
    }

    public function store(CreatePostRequest $request): PsrResponseInterface
    {
        $postData = $request->validated();
        $post = $this->postService->create($postData);
        return $this->jsonResource(PostPublicResource::make($post));
    }

    public function update(UpdatePostRequest $request, int $id): PsrResponseInterface
    {
        $locale = $request->validated()['locale'];
        $postData = $request->validated();
        $post = $this->postService->update($id, $locale, $postData);
        return $this->jsonResource(PostPublicResource::make($post));
    }

    /**
     * @throws Exception
     */
    public function delete(int $id): PsrResponseInterface
    {
        $this->postService->delete($id);
        return $this->noContent('Post entry deleted successfully.');
    }
}
