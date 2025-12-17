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

use App\Model\RefreshToken;
use App\Model\User;
use Firebase\JWT\JWT;
use Hyperf\Stringable\Str;

use function Hyperf\Config\config;
use function Hyperf\Support\env;

class TokenService
{
    public function generateAccessToken(User $user): string
    {
        $payload = [
            'iss' => config('APP_NAME', 'portfolio-api'),
            'sub' => $user->id,
            'uid' => $user->id,
            'email' => $user->email,
            'iat' => time(),
            'exp' => time() + 3600,
        ];

        return JWT::encode($payload, env('JWT_SECRET_KEY'), 'HS256');
    }

    public function generateRefreshToken(User $user): string
    {
        $token = (string) Str::uuid();

        RefreshToken::create([
            'user_id' => $user->id,
            'token' => $token,
            'refresh_token' => $token,
            'expires_at' => time() + 3600 * 7,
        ]);

        return $token;
    }
}
