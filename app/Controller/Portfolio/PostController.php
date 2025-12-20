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

use App\Request\Portfolio\GetListPostRequest;
use App\Request\Portfolio\GetPostRequest;
use App\Resource\PostCollection;
use App\Resource\PostPublicResource;
use App\Services\PostService;
use App\Traits\RespondsWithResource;
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
        $posts = $this->postService->paginate($filters, $locale);

        return $this->jsonResource(PostCollection::make($posts));
    }

    public function show(int $id, GetPostRequest $request): PsrResponseInterface
    {
        $locale = $request->validated()['locale'];
        $post = $this->postService->getById($id, $locale);

        return $this->jsonResource(PostPublicResource::make($post));
    }
}
