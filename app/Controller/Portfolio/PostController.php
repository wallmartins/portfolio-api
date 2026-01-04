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

use App\Request\Portfolio\Blog\GetListPostRequest;
use App\Request\Portfolio\Blog\GetPostRequest;
use App\Resource\Blog\PostCollection;
use App\Resource\Blog\PostPublicResource;
use App\Services\Blog\PostService;
use App\Traits\RespondsWithResource;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Swagger\Annotation\HyperfServer;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

#[OA\Schema(
    schema: 'Post',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'slug', type: 'string', example: 'my-first-post'),
        new OA\Property(property: 'image', type: 'string', nullable: true, example: 'https://example.com/image.jpg'),
        new OA\Property(property: 'title', type: 'string', example: 'My First Post'),
        new OA\Property(property: 'subtitle', type: 'string', nullable: true, example: 'An introduction to the blog'),
        new OA\Property(property: 'content', type: 'string', example: 'This is the post content...'),
        new OA\Property(property: 'locale', type: 'string', example: 'pt-BR'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time'),
    ]
)]
#[HyperfServer('http')]
class PostController
{
    use RespondsWithResource;

    #[Inject]
    protected ResponseInterface $response;

    public function __construct(private readonly PostService $postService)
    {
    }

    #[OA\Get(
        path: '/portfolio/blog',
        summary: 'Get list of blog posts',
        tags: ['Portfolio'],
        parameters: [
            new OA\Parameter(name: 'locale', in: 'query', required: true, schema: new OA\Schema(type: 'string', enum: ['pt-BR', 'en-US'])),
            new OA\Parameter(name: 'page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 1)),
            new OA\Parameter(name: 'per_page', in: 'query', required: false, schema: new OA\Schema(type: 'integer', default: 15)),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of blog posts',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(ref: '#/components/schemas/Post')),
                        new OA\Property(property: 'meta', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function index(GetListPostRequest $request): PsrResponseInterface
    {
        $locale = $request->validated()['locale'];
        $filters = $request->validated();
        $posts = $this->postService->paginate($filters, $locale);

        return $this->jsonResource(PostCollection::make($posts));
    }

    #[OA\Get(
        path: '/portfolio/blog/{id}',
        summary: 'Get a specific blog post',
        tags: ['Portfolio'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
            new OA\Parameter(name: 'locale', in: 'query', required: true, schema: new OA\Schema(type: 'string', enum: ['pt-BR', 'en-US'])),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Blog post details',
                content: new OA\JsonContent(ref: '#/components/schemas/Post')
            ),
            new OA\Response(response: 404, description: 'Post not found'),
        ]
    )]
    public function show(int $id, GetPostRequest $request): PsrResponseInterface
    {
        $locale = $request->validated()['locale'];
        $post = $this->postService->getById($id, $locale);

        return $this->jsonResource(PostPublicResource::make($post));
    }
}
