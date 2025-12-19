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

use App\Model\Tech;

/**
 * @extends ResourceCollection<Tech>
 */
class TechCollection extends ResourceCollection
{
    public function toArray(): array
    {
        return [
            'data' => $this->collection->map(function ($tech) {
                return TechResource::make($tech)->toArray();
            })->toArray(),
            'meta' => [
                'total' => $this->collection->count(),
            ],
        ];
    }
}
