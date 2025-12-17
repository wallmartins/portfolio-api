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

namespace App\DTO;

class AuthDTO
{
    public int $id;

    public UserDTO $user;

    public string $accessToken;

    public string $refreshToken;

    public int $expiresIn;

    public function __construct(array $data)
    {
        $this->user = $data['user'] ?? new UserDTO($data['user']);
        $this->accessToken = $data['accessToken'];
        $this->refreshToken = $data['refreshToken'];
        $this->expiresIn = $data['expiresIn'];
    }

    public function toArray(): array
    {
        return [
            'user' => $this->user->toArray(),
            'accessToken' => $this->accessToken,
            'refreshToken' => $this->refreshToken,
            'expiresIn' => $this->expiresIn,
        ];
    }
}
