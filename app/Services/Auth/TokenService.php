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

namespace App\Services\Auth;

use App\Model\Auth\RefreshToken;
use App\Model\User\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Hyperf\Stringable\Str;

use function Hyperf\Config\config;
use function Hyperf\Support\env;

class TokenService
{
    public function generateAccessToken(User $user): string
    {
        $payload = [
            'iss' => env('APP_NAME', 'portfolio-api'),
            'sub' => $user->id,
            'uid' => $user->id,
            'email' => $user->email,
            'github_id' => $user->github_id,
            'iat' => time(),
            'exp' => time() + 3600,
        ];

        return JWT::encode($payload, env('JWT_SECRET_KEY'), 'HS256');
    }

    public function generateRefreshToken(User $user): string
    {
        $existingToken = $this->findValidRefreshToken($user);

        if ($existingToken) {
            return $existingToken->token;
        }

        $token = (string) Str::uuid();

        RefreshToken::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => Carbon::now()->addDays(7),
        ]);

        return $token;
    }

    private function findValidRefreshToken(User $user): ?RefreshToken
    {
        return RefreshToken::query()
            ->where('user_id', $user->id)
            ->where('expires_at', '>', Carbon::now())
            ->whereNull('revoked_at')
            ->first();
    }
}
