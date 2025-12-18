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

use App\Model\User;
use App\Repository\UserRepository;

class AuthService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly TokenService $tokenService,
    ) {
    }

    /**
     * Find or create user from GitHub data and generate auth tokens.
     *
     * @return array{user: User, accessToken: string, refreshToken: string, expiresIn: int}
     */
    public function findOrCreateUser(array $githubUserData): array
    {
        $user = $this->userRepository->findOrCreateFromGithub($githubUserData);

        return $this->generateAuthTokens($user);
    }

    /**
     * Generate authentication tokens for a user.
     *
     * @return array{user: User, accessToken: string, refreshToken: string, expiresIn: int}
     */
    public function generateAuthTokens(User $user): array
    {
        $accessToken = $this->tokenService->generateAccessToken($user);
        $refreshToken = $this->tokenService->generateRefreshToken($user);

        return [
            'user' => $user,
            'accessToken' => $accessToken,
            'refreshToken' => $refreshToken,
            'expiresIn' => 3600,
        ];
    }
}
