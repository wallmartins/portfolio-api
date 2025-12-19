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

use App\Model\Post;

/**
 * @extends JsonResource<Post>
 */
class PostPublicResource extends JsonResource
{
    public function toArray(): array
    {
        $translation = $this->resource->translations->first();

        return [
            'id' => $this->resource->id,
            'slug' => $this->resource->slug,
            'title' => $translation?->title,
            'subtitle' => $translation?->subtitle,
            'content' => $translation?->content,
            'image' => $this->resource->image,
            'techs' => $this->resource->techs->map(function ($tech) {
                return [
                    'id' => $tech->id,
                    'slug' => $tech->slug,
                    'name' => $tech->name,
                    'category' => $tech->category,
                ];
            })->toArray(),
        ];
    }
}
