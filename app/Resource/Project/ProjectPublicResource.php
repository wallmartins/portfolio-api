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

namespace App\Resource\Project;

use App\Model\Project\Project;
use App\Resource\JsonResource;

/**
 * @extends JsonResource<Project>
 */
class ProjectPublicResource extends JsonResource
{
    public function toArray(): array
    {
        $translations = $this->resource->translations->first();

        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'slug' => $this->resource->slug,
            'image' => $this->resource?->image,
            'title' => $translations?->title,
            'content' => $translations?->content,
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
