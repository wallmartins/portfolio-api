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

namespace App\Services;

use App\DTO\AuthDTO;
use App\Model\User;
use App\Repository\UserRepository;

class AuthService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly TokenService $tokenService,
    ) {
    }

    public function findOrCreateUser(array $githubUserData)
    {
        $user = $this->userRepository->findOrCreateFromGithub($githubUserData);

        return $this->generateAuthTokens($user);
    }

    public function generateAuthTokens(User $user): AuthDTO
    {
        $accessToken = $this->tokenService->generateAccessToken($user);
        $refreshToken = $this->tokenService->generateRefreshToken($user);

        return new AuthDTO([
            'user' => $user->toDTO(),
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken,
            'expiresIn' => 900,
        ]);
    }
}
