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

use App\Services\AuthService;
use Hyperf\HttpServer\Contract\RequestInterface;
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

    public function redirect(ResponseInterface $response): PsrResponseInterface
    {
        return $this->socialite->driver('github')->redirect();
    }

    public function callback(RequestInterface $request, ResponseInterface $response): PsrResponseInterface
    {
        $githubUser = $this->socialite->driver('github')->user();

        $userData = [
            'id' => $githubUser->getId(),
            'name' => $githubUser->getName(),
            'nickname' => $githubUser->getNickname(),
            'email' => $githubUser->getEmail(),
        ];

        $authDTO = $this->authService->findOrCreateUser($userData);

        return $response->json($authDTO->toArray());
    }
}
