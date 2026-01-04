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

use App\Resource\Auth\AuthResource;
use App\Services\Auth\AuthService;
use App\Traits\RespondsWithResource;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Swagger\Annotation\HyperfServer;
use OnixSystemsPHP\HyperfSocialite\Contracts\Factory as SocialiteFactory;
use OpenApi\Attributes as OA;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

#[HyperfServer('http')]
class AuthController
{
    use RespondsWithResource;

    #[Inject]
    protected ResponseInterface $response;

    public function __construct(
        private readonly SocialiteFactory $socialite,
        private readonly AuthService $authService
    ) {
    }

    /**
     * Redirect to GitHub OAuth.
     */
    #[OA\Get(
        path: '/auth/github/redirect',
        summary: 'Redirect to GitHub OAuth',
        tags: ['Authentication'],
        responses: [
            new OA\Response(
                response: 302,
                description: 'Redirect to GitHub OAuth authorization page'
            ),
        ]
    )]
    public function redirect(): PsrResponseInterface
    {
        return $this->socialite->driver('github')->redirect();
    }

    /**
     * Handle GitHub OAuth callback.
     */
    #[OA\Get(
        path: '/auth/github/callback',
        summary: 'Handle GitHub OAuth callback',
        tags: ['Authentication'],
        parameters: [
            new OA\Parameter(
                name: 'code',
                in: 'query',
                required: true,
                description: 'Authorization code from GitHub',
                schema: new OA\Schema(type: 'string')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User authenticated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
                        new OA\Property(property: 'accessToken', type: 'string', example: 'eyJ0eXAiOiJKV1QiLCJhbGc...'),
                        new OA\Property(property: 'avatar', type: 'string', format: 'uri', example: 'https://avatars.githubusercontent.com/u/123456'),
                    ]
                )
            ),
            new OA\Response(response: 401, description: 'Authentication failed'),
        ]
    )]
    public function callback(): PsrResponseInterface
    {
        $githubUser = $this->socialite->driver('github')->user();

        $userData = [
            'id' => $githubUser->getId(),
            'name' => $githubUser->getName(),
            'nickname' => $githubUser->getNickname(),
            'email' => $githubUser->getEmail(),
        ];

        $authData = $this->authService->findOrCreateUser($userData);

        return $this->jsonResource(
            AuthResource::make(
                name: $authData['user']->name,
                email: $authData['user']->email,
                accessToken: $authData['accessToken'],
                avatar: $githubUser->getAvatar()
            )
        );
    }
}
