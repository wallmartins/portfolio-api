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

namespace App\Controller\Portfolio;

use App\Model\Post;
use App\Request\Portfolio\GetPostRequest;
use App\Resource\PostPublicResource;
use App\Services\PostService;
use App\Traits\RespondsWithResource;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class PostController
{
    use RespondsWithResource;

    public function __construct(ResponseInterface $response, private readonly PostService $postService)
    {
    }

    public function index(GetPostRequest $request, ResponseInterface $response): PsrResponseInterface
    {
        $locale = $request->validated()['locale'];
        $posts = $this->postService->getAll();

        return $this->jsonResource(PostPublicResource::make($posts));
    }

    public function show(string $slug, GetPostRequest $request, ResponseInterface $response, Post $post): PsrResponseInterface
    {
        $locale = $request->validated()['locale'];
        $post = $this->postService->getBySlugAndLocale($slug, $locale);

        return $this->jsonResource(PostPublicResource::make($post));
    }
}
