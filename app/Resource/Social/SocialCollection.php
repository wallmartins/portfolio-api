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

namespace App\Resource\Social;

use App\Model\Social\Social;
use App\Resource\ResourceCollection;

/**
 * @extends ResourceCollection<Social>
 */
class SocialCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     */
    public function toArray(): array
    {
        return [
            'data' => $this->collection->map(function ($social) {
                return SocialResource::make($social)->toArray();
            })->toArray(),
            'meta' => [
                'total' => $this->collection->count(),
            ],
        ];
    }
}
