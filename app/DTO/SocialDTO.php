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
use Carbon\Carbon;

class SocialDTO
{
    use HasToArray;

    public int $id;

    public string $social_name;

    public string $social_url;

    public Carbon $created_at;

    public Carbon $updated_at;

    public function __construct(array $social)
    {
        $this->id = $social['id'];
        $this->social_name = $social['social_name'];
        $this->social_url = $social['social_url'];
        $this->created_at = $social['created_at'];
        $this->updated_at = $social['updated_at'];
    }
}
