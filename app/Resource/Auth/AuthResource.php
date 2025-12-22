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

namespace App\Resource\Auth;

use App\Contracts\Arrayable;

class AuthResource implements Arrayable
{
    public function __construct(
        private readonly string $name,
        private readonly string $email,
        private readonly string $accessToken,
        private readonly ?string $avatar = null
    ) {
    }

    /**
     * Create a new auth resource instance.
     */
    public static function make(string $name, string $email, string $accessToken, ?string $avatar = null): self
    {
        return new self($name, $email, $accessToken, $avatar);
    }

    /**
     * Transform the resource into an array.
     */
    public function toArray(): array
    {
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'access_token' => $this->accessToken,
        ];

        if ($this->avatar !== null) {
            $data['avatar'] = $this->avatar;
        }

        return $data;
    }
}
