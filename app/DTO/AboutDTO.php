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

use Carbon\Carbon;

class AboutDTO
{
    public int $id;

    public string $title;

    public string $description;

    public ?string $image = null;

    public string $locale;

    public Carbon $createdAt;

    public Carbon $updatedAt;

    public function __construct(array $about)
    {
        $this->id = $about['id'];
        $this->title = $about['title'];
        $this->description = $about['description'];
        $this->image = $about['image'] ?? null;
        $this->locale = $about['locale'];
        $this->createdAt = Carbon::now();
        $this->updatedAt = Carbon::now();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'image' => $this->image ?? null,
            'locale' => $this->locale,
            'createdAt' => $this->createdAt,
            'updatedAt' => $this->updatedAt,
        ];
    }
}
