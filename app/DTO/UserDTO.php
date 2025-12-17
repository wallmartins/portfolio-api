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

use App\Traits\HasToArray;
use DateTime;

class UserDTO
{
    use HasToArray;

    public int $id;

    public string $uuid;

    public string $name;

    public string $email;

    public string $githubId;

    public DateTime $createdAt;

    public DateTime $updatedAt;

    public function __construct(array $data)
    {
        $this->id = (int) $data['id'];
        $this->uuid = $data['uuid'];
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->githubId = $data['github_id'];
        $this->createdAt = $data['created_at'] instanceof DateTime ? $data['created_at'] : new DateTime($data['created_at']);
        $this->updatedAt = $data['updated_at'] instanceof DateTime ? $data['updated_at'] : new DateTime($data['updated_at']);
    }
}
