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

namespace App\Repository\User;

use App\Model\User\User;

class UserRepository
{
    public function findByGithubId(string $githubId): ?User
    {
        return User::query()
            ->where('github_id', $githubId)
            ->first();
    }

    public function createFromGithub(array $data): User
    {
        return User::create([
            'name' => $data['name'] ?? $data['nickname'],
            'email' => $data['email'],
            'github_id' => $data['id'],
        ]);
    }

    public function updateFromGithub(User $user, array $data): User
    {
        $user->update([
            'name' => $data['name'] ?? $data['nickname'],
            'email' => $data['email'],
        ]);

        return $user;
    }

    public function findOrCreateFromGithub(array $data): User
    {
        $user = $this->findByGithubId($data['id']);

        return $user
            ? $this->updateFromGithub($user, $data)
            : $this->createFromGithub($data);
    }
}
