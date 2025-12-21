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

namespace App\Resource\Experience;

use App\Model\Experience\Experience;
use App\Resource\ResourceCollection;

/**
 * @extends ResourceCollection<Experience>
 */
class ExperienceCollection extends ResourceCollection
{
    public function toArray(): array
    {
        return [
            'data' => $this->collection->map(function ($experience) {
                return ExperiencePublicResource::make($experience)->toArray();
            })->toArray(),
            'meta' => [
                'count' => $this->collection->count(),
            ],
        ];
    }
}
