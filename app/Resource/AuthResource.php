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

namespace App\Resource;

use App\Model\User;

class AuthResource
{
    public function __construct(
        private readonly User $user,
        private readonly string $accessToken,
        private readonly string $refreshToken,
        private readonly int $expiresIn = 3600
    ) {
    }

    /**
     * Create a new auth resource instance.
     */
    public static function make(User $user, string $accessToken, string $refreshToken, int $expiresIn = 3600): self
    {
        return new self($user, $accessToken, $refreshToken, $expiresIn);
    }

    /**
     * Transform the resource into an array.
     */
    public function toArray(): array
    {
        return [
            'user' => UserResource::make($this->user)->toArray(),
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refreshToken,
            'expires_in' => $this->expiresIn,
            'token_type' => 'Bearer',
        ];
    }
}
