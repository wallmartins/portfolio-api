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

use App\Resource\AuthResource;
use App\Services\AuthService;
use Hyperf\HttpServer\Contract\ResponseInterface;
use OnixSystemsPHP\HyperfSocialite\Contracts\Factory as SocialiteFactory;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class AuthController
{
    public function __construct(
        private readonly SocialiteFactory $socialite,
        private readonly AuthService $authService
    ) {
    }

    /**
     * Redirect to GitHub OAuth.
     */
    public function redirect(ResponseInterface $response): PsrResponseInterface
    {
        return $this->socialite->driver('github')->redirect();
    }

    /**
     * Handle GitHub OAuth callback.
     */
    public function callback(ResponseInterface $response): PsrResponseInterface
    {
        $githubUser = $this->socialite->driver('github')->user();

        $userData = [
            'id' => $githubUser->getId(),
            'name' => $githubUser->getName(),
            'nickname' => $githubUser->getNickname(),
            'email' => $githubUser->getEmail(),
        ];

        $authData = $this->authService->findOrCreateUser($userData);

        $resource = AuthResource::make(
            $authData['user'],
            $authData['accessToken'],
            $authData['refreshToken'],
            $authData['expiresIn']
        );

        return $response->json($resource->toArray());
    }
}
