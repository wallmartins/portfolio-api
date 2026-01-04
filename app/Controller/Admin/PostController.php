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
use Hyperf\Swagger\Annotation\HyperfServer;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

#[HyperfServer('http')]
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

    #[OA\Post(
        path: '/admin/posts',
        summary: 'Create a new blog post',
        security: [['BearerAuth' => []]],
        tags: ['Admin - Blog'],
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: 'multipart/form-data',
                    schema: new OA\Schema(
                        required: ['slug', 'translations', 'tech_ids'],
                        properties: [
                            new OA\Property(property: 'slug', type: 'string', example: 'my-first-post'),
                            new OA\Property(property: 'image', type: 'string', format: 'binary', description: 'Image file (jpg, png, webp, gif) max 5MB or URL string'),
                            new OA\Property(
                                property: 'translations',
                                type: 'array',
                                items: new OA\Items(
                                    properties: [
                                        new OA\Property(property: 'locale', type: 'string', enum: ['pt-BR', 'en-US']),
                                        new OA\Property(property: 'title', type: 'string'),
                                        new OA\Property(property: 'subtitle', type: 'string', nullable: true),
                                        new OA\Property(property: 'content', type: 'string'),
                                    ]
                                )
                            ),
                            new OA\Property(property: 'tech_ids', type: 'array', items: new OA\Items(type: 'integer')),
                        ]
                    )
                ),
            ]
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Post created successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Post')
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
    public function store(CreatePostRequest $request): PsrResponseInterface
    {
        $postData = $request->validated();
        $post = $this->postService->create($postData);
        return $this->jsonResource(PostPublicResource::make($post));
    }

    #[OA\Put(
        path: '/admin/posts/{id}',
        summary: 'Update a blog post',
        security: [['BearerAuth' => []]],
        tags: ['Admin - Blog'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: [
                new OA\MediaType(
                    mediaType: 'multipart/form-data',
                    schema: new OA\Schema(
                        required: ['locale'],
                        properties: [
                            new OA\Property(property: 'locale', type: 'string', enum: ['pt-BR', 'en-US']),
                            new OA\Property(property: 'slug', type: 'string', example: 'my-updated-post'),
                            new OA\Property(property: 'image', type: 'string', format: 'binary', description: 'Image file or URL string'),
                            new OA\Property(
                                property: 'translations',
                                type: 'array',
                                items: new OA\Items(
                                    properties: [
                                        new OA\Property(property: 'locale', type: 'string', enum: ['pt-BR', 'en-US']),
                                        new OA\Property(property: 'title', type: 'string'),
                                        new OA\Property(property: 'subtitle', type: 'string', nullable: true),
                                        new OA\Property(property: 'content', type: 'string'),
                                    ]
                                )
                            ),
                            new OA\Property(property: 'tech_ids', type: 'array', items: new OA\Items(type: 'integer')),
                        ]
                    )
                ),
            ]
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Post updated successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/Post')
            ),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Post not found'),
            new OA\Response(response: 422, description: 'Validation error'),
        ]
    )]
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
    #[OA\Delete(
        path: '/admin/posts/{id}',
        summary: 'Delete a blog post',
        security: [['BearerAuth' => []]],
        tags: ['Admin - Blog'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Post deleted successfully'),
            new OA\Response(response: 401, description: 'Unauthorized'),
            new OA\Response(response: 404, description: 'Post not found'),
        ]
    )]
    public function delete(int $id): PsrResponseInterface
    {
        $this->postService->delete($id);
        return $this->noContent('Post entry deleted successfully.');
    }
}
