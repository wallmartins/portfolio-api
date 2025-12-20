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

use App\Model\Experience;

/**
 * @extends JsonResource<Experience>
 */
class ExperiencePublicResource extends JsonResource
{
    public function toArray(): array
    {
        $translation = $this->resource->translations->first();

        return [
            'id' => $this->resource->id,
            'company_name' => $this->resource->company_name,
            'position' => $translation?->position,
            'description' => $translation?->description,
            'start_date' => $this->resource->start_date,
            'techs' => $this->resource->techs->map(function ($tech) {
                return [
                    'id' => $tech->id,
                    'slug' => $tech->slug,
                    'name' => $tech->name,
                    'category' => $tech->category,
                ];
            }),
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
