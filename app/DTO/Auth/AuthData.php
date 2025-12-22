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

namespace App\DTO\Auth;

use App\Model\User\User;

class AuthData
{
    public function __construct(
        public readonly User $user,
        public readonly string $accessToken,
        public readonly string $refreshToken,
        public readonly int $expiresIn
    ) {
    }
}
